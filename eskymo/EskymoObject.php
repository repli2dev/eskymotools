<?php
/**
 * Description of EskymoObject
 *
 * @author papi
 */
class EskymoObject extends Object
{

	public function  __construct() {}

	/**
	 * It returns object methods
	 *
	 * @return array Method names
	 */
	public function getMethods() {
		return get_class_methods($this->getClass());
	}

	/**
	 * It returns object variables
	 *
	 * @return array Variable names
	 */
	public function getVars() {
		return get_class_vars($this->getClass());
	}

	/**
	 * It checks if this instance is equals to another one.
	 *
	 * @param EskymoObject $object
	 * @return bool
	 * @throws NullPointerException if the $object is empty
	 */
	public function equals(EskymoObject &$object) {
		return $this === $object;
	}

}