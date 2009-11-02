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
abstract class Worker extends EskymoObject
{

	/**
	 * It returns an array exctracted from entity
	 *
	 * @param AEntity $entity Entity which is extracted
	 * @param string $annotation Annotation which is used to replace the variable name to key name
	 * @return array
	 */
	protected function getArrayFromEntity(AEntity $entity, $annotation) {
		$result = array();
		foreach ($entity->getTranslatedAttributes($annotation) AS $key => $translated) {
			if (!isset($entity->$var)) {
				continue;
			}
			$result[$translated] = $entity->$var;
		}
		return $result;
	}

}
