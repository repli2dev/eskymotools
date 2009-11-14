<?php
/**
 * Description of EskymoObject
 *
 * @author papi
 */
class EskymoObject extends Object
{

	/**
	 * It returns the class annotation value. If the attribute is set,
	 * the attribute annotation value will be returned.
	 *
	 * @param string $annotation
	 * @param string $attribute
	 */
	function getAnnotation($annotation, $attribute = NULL) {
		if (empty($annotation)) {
			throw new NullPointerException("annotation");
		}
		if (empty($attribute)) {
			$reflection = $this->getReflection();
		}
		else {
			$reflection = $this->getReflection()->getProperty($attribute);
		}
		if (!Annotations::has($reflection, $annotation)) {
			return NULL;
		}
		else {
			$result = Annotations::getAll($reflection, $annotation);
			if (sizeof($result) == 1) {
				return ExtraArray::firstValue($result);
			}
			else {
				return $result;
			}
		}
	}


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
		return array_keys(get_object_vars($this));
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