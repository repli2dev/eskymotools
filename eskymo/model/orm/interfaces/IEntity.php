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
interface IEntity
{

	/**
	 * It returns the entity ID
	 * @return int
	 */
	function getId();

	/**
	 * It sets the entity ID
	 */
	function setId($id);

	/**
	 * It checks if the entity can be inserted.
	 * @return bool
	 */
	function isReadyToInsert();

	/**
	 * It checks if the entity can be updated
	 * @return bool
	 */
	function isReadyToUpdate();

	/**
	 * It loads the data from DibiRow
	 *
	 * WARNING: It deletes old data!
	 * @param array Source data
	 * @return IEntity This method is fluent.
	 */
	function loadDataFromArray(array $resource);

}

