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

	private $id;

	/* PUBLIC METHODS */
	
	public function __construct(array $array = NULL){
		if(!empty($array)){
			$this->loadDataFromArray($array);
		}
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
		// Foreach variable try to load data from a row
		foreach($this->getVars() AS $var) {
			$reflection = $this->getReflection()->getProperty($var);
			// The variables which has 'Skip' annotation will be skipped
			if (Annotations::has($reflection, "Skip")) {
				$toSkip = Annotations::get($reflection, "Skip");
				if (empty($toSkip) || $toSkip == "Load") {
					continue;
				}
			}
			// Check if there is an annotation to change the column name
			// (Defaultly the column name is the same as variable name)
			if (Annotations::has($reflection, "Load")) {
				$column = Annotations::get($reflection, "Load");
			}
			else {
				$column = $var;
			}
			if (isset($source[$column])) {
				$this->$var = $source[$column];
			}
		}
		$this->loadId($source);
		return $this;
	}

	/* PROTECTED METHODS */

	protected function loadId(array $source) {
		if (Annotations::has($this->getReflection(), "Id")) {
			$annotation = Annotations::get($this->getReflection(), "Id");
			if (!isset($annotation->translate)) {
				throw new InvalidStateException("The annotation [Id] has to contain parameter [translate]");
			}
			if (isset($source[$annotation->translate])) {
				$this->setId($so);
			}
		}
		else {
			throw new InvalidStateException("The annotation [Id] has to be set.");
		}
	}

}
