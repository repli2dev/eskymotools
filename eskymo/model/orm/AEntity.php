<?php
/**
 * This source file is subject to the "New BSD License".
 *
 * For more information please see http://code.google.com/p/eskymofw/
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://code.google.com/p/eskymofw/
 */

/**
 * @author		Jan Papousek
 * @author		Jan Drabek
 * @version		$Id$
 */
class AEntity extends EskymoObject implements IEntity{

	// TODO: State [MODIFIED] is not implemented correctly

	/* STATIC ATTRIBUTES */

	/**
	 * This array contains translated attributes for each subclass
	 * and foreach annotation name
	 *
	 * @var array
	 */
	private static $translatedAttributes = array();

	/**
	 * This array contains translated ID name for each subclass
	 *
	 * @var array
	 */
	private static $translatedIds = array();

	/* COMMON PRIVATE ATTRIBUTES */

	/** @var IEntityFactory */
	private $factory;

	/** @var int */
	private $id;

	/** @var array */
	private $modified = array();

	/** @var int */
	private $state;

	/* PUBLIC METHODS */

	public function  __construct(IEntityFactory &$factory) {
		$this->factory = $factory;
		$this->setState(IEntity::STATE_NEW);
	}

	public function & __get($name) {
		if (in_array($name, $this->getVars())) {
			return $this->$name;
		}
		else {
			return parent::__get($name);
		}
	}

	public function __set($name, $value) {
		if (in_array($name, $this->getVars())) {
			$this->setValue($name, $value);
		}
		else {
			parent::__set($name, $value);
		}
	}

	public final function delete() {
		if ($this->getId() == NULL) {
			throw new InvalidStateException("The entity can not be deleted.");
		}
		$this->factory->getDeleter()->delete($this->getId());
		$this->setState(IEntity::STATE_DELETED);
	}

	/**
	 * It returns data from the entity
	 *
	 * @param string $annotation
	 * @param string $modifier Only IEntity::DATA_ALL is implemented
	 * @return array
	 */
	public final function getData($annotation = NULL, $modifier = self::DATA_ALL) {
		if ($modifier != IEntity::DATA_ALL) {
			throw new NotSupportedException("Only the modifier [ALL] is supported.");
		}
		$result = array();
		if (!empty($annotation)) {
			foreach ($this->getTranslatedAttributes($annotation) AS $var => $translated) {
				if (!isset($this->$var)) {
					continue;
				}
				$result[$translated] = $this->$var;
			}
		}
		else {
			foreach($this->getVars() AS $var) {
				if (!isset($this->$var)) {
					continue;
				}
				$result[$var] = $this->$var;
			}
		}
		return $result;
	}

	public function getId() {
		if ($this->getState() != IEntity::STATE_NEW && empty($this->id)) {
			throw new InvalidStateException("The entity has no ID.");
		}
		return $this->id;
	}

	public final function getState() {
		return $this->state;
	}

	public final function persist() {
		switch($this->getState()) {
			case IEntity::STATE_NEW:
				$id = $this->factory->getInserter()->insert($this);
				$this->setId($id);
				break;
			case IEntity::STATE_MODIFIED:
				$this->factory->getUpdater()->update($this);
				break;
			case IEntity::STATE_PERSISTED:
				break;
			default:
				throw new InvalidStateException("The entity can not be persisted.");
		}
		$this->clearModifiedColumns();
		$this->setState(IEntity::STATE_PERSISTED);
		return $this;
	}

	public final function loadDataFromArray(array $source, $annotation = NULL) {
		if ($this->getState() != IEntity::STATE_NEW) {
			throw new InvalidStateException("The entity is not in state [NEW]. It can't be loaded from array.");
		}
		if (!empty($annotation)) {
			foreach ($this->getTranslatedAttributes($annotation) AS $var => $translated) {
				if (isset($source[$translated])) {
					$this->$var = $source[$translated];
				}
			}
		}
		else {
			foreach ($source AS $key => $value) {
				if (isset($this->$key)) {
					$this->$key = $value;
				}
			}
		}
		$this->loadId($source);
		return $this;
	}

	/* PROTECTED METHODS */

	/**
	 * It tries to load ID from the source
	 *
	 * @param array $source
	 */
	protected function loadId(array $source) {
		$key = $this->getTranslatedId();
		if (isset($source[$key])) {
			$this->setId($source[$key]);
		}
	}

	protected final function setId($id) {
		$this->id = $id;
		if ($this->state == IEntity::STATE_NEW) {
			$this->setState(IEntity::STATE_PERSISTED);
		}
	}

	protected final function setState($state) {
		if (empty($state)) {
			throw new NullPointerException("state");
		}
		$this->state = $state;
	}

	protected final function setValue($column, $value) {
		if ($this->$column != $value) {
			$this->$column = $value;
			$this->addModifiedColumn($column);
		}
	}

	/* PRIVATE METHODS */

	private function addModifiedColumn($column) {
		$this->modified[$column] = TRUE;
		if ($this->getState() == IEntity::STATE_PERSISTED) {
			$this->setState(IEntity::STATE_MODIFIED);
		}
	}

	private function clearModifiedColumns() {
		$this->modified = array();
	}

	/**
	 * It returns translated attribute names by the specified annotation
	 *
	 * @param string $annotation
	 * @return array
	 * @throws NullPointerException if the $annotation is empty
	 */
	private function getTranslatedAttributes($annotation) {
		if (empty($annotation)) {
			throw new NullPointerException("annotation");
		}
		if (!isset(self::$translatedAttributes[$this->getClass()])) {
			self::$translatedAttributes[$this->getClass()] = array();
		}
		if (!isset(self::$translatedAttributes[$this->getClass()][$annotation])) {
			$translated = array();
			foreach($this->getVars() AS $var) {
				$reflection = $this->getReflection()->getProperty($var);
				// The variables which has 'Skip' annotation will be skipped
				if (Annotations::has($reflection, "Skip")) {
					$toSkip = Annotations::get($reflection, "Skip");
					if (empty($toSkip) || (!is_array($toSkip) && $toSkip == $annotation) || (is_array($toSkip) && in_array($annotation, $toSkip))) {
						continue;
					}
				}
				// Check if there is an annotation to change the column name
				// (Defaultly the column name is the same as variable name)
				if (Annotations::has($reflection, $annotation)) {
					$translatedVar = Annotations::get($reflection, $annotation);
				}
				else if (Annotations::has($reflection, "Translate")) {
					$translatedVar = Annotations::get($reflection, "Translate");
				}
				else {
					$translatedVar = $var;
				}
				$translated[$var] = $translatedVar;
			}
			self::$translatedAttributes[$this->getClass()][$annotation] = $translated;
		}
		return self::$translatedAttributes[$this->getClass()][$annotation];
	}

	/**
	 * It returns the key name which is used to load ID
	 *
	 * @return string
	 * @throws InvalidStateException if the class has no annotation
	 * which translates the key
	 */
	private function getTranslatedId() {
		if (!isset(self::$translatedIds[$this->getClass()])) {
			if (!Annotations::has($this->getReflection(), "Id")) {
				throw new InvalidStateException("The annotation [Id] has to be set.");
			}
			$annotation = Annotations::get($this->getReflection(), "Id");
			if (!isset($annotation->translate)) {
				throw new InvalidStateException("The annotation [Id] has to contain parameter [translate]");
			}
			self::$translatedIds[$this->getClass()] = $annotation->translate;
		}
		return self::$translatedIds[$this->getClass()];
	}
}
