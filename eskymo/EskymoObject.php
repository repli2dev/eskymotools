<?php
/**
 * Description of EskymoObject
 *
 * @author papi
 */
class EskymoObject extends Object implements IEskymoObject
{

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

	public function getMethods() {
		return get_class_methods($this->getClass());
	}

	public function getVars() {
		return array_keys(get_object_vars($this));
	}

	public function equals(EskymoObject &$object) {
		return $this === $object;
	}

}