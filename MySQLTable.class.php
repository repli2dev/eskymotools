<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici jako predek pro vsechny tridy, ktere obsluhuji tabulky v databazi.
* @example doc_example/MySQLTable.phps
*/
abstract class MySQLTable {

	/**
	* @var string Prefix tabulky v databazi.
	*/
	const prefix = "";


	/**
	* @var string Nazev tabulky v databazi
	*/
	abstract const tableName;

	/**
	* Vrati nazev tabulky (i s prefixem).
	* @return void
	*/
	public static function getTableName() {
		return (self::prefix . self::$tableName);

	}
	
	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/
	abstract public static function install();

}
?>
