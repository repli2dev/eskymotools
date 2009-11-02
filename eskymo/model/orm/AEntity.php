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
abstract class AEntity extends EskymoObject implements IEntity
{

	/**
	 * The entity ID
	 *
	 * @var int
	 */
	private $id;

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

	/* PUBLIC METHODS */
	
	public function __construct(array $array = NULL){
		if(!empty($array)){
			$this->loadDataFromArray($array);
		}
	}

	/**
	 * It returns translated attribute names by the specified annotation
	 *
	 * @param string $annotation
	 * @return array
	 * @throws NullPointerException if the $annotation is empty
	 */
	public function getTranslatedAttributes($annotation) {
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
	public function getTranslatedId() {
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

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function isReadyToUpdate() {
		return isset($this->id);
	}

	public function isReadyToInsert() {
		// Check if the id is not set.
		if (isset($this->id)) {
			return FALSE;
		}
		// Check if all required columns are set
		foreach ($this->getVars() AS $var) {
			$reflection = $this->getReflection()->getProperty($var);
			if (Annotations::has($reflection, "Required") && !isset($this->$var)) {
				return FALSE;
			}
		}
		// If all passed, the entity is ready to be inserted
		return TRUE;
	}

	public function  loadDataFromArray(array $source) {
		foreach ($this->getTranslatedAttributes("Load") AS $var => $translated) {
			if (isset($source[$translated])) {
				$this->$var = $source[$translated];
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

}
