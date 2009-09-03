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
		// Foreach variable try to load data from a row
		foreach($entity->getVars() AS $var) {
			if (!isset($entity->$var)) {
				continue;
			}
			$reflection = $entity->getReflection()->getProperty($var);
			// The variables which has 'Skip' annotation will be skipped
			if (Annotations::has($reflection, "Skip")) {
				$toSkip = Annotations::get($reflection, $annotation);
				if (empty($toSkip) || in_array($annotation, $toSkip)) {
					continue;
				}
			}
			// Check if there is an annotation to change the column name
			// (Defaultly the column name is the same as variable name)
			if (Annotations::has($reflection, $annotation)) {
				$column = Annotations::get($reflection, $annotation);
			}
			else {
				$column = $var;
			}
			if (is_object($entity->$var)) {
				$result[$column] = $entity->$var;
			}
			else {
				$result[$column] = trim($entity->$var);
			}
		}
		return $result;
	}

}
