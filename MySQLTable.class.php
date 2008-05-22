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
	* Zmeni informace o polozce.
	* @param int ID polozky.
	* @param array_string Pole pozadovanych zmen, index predstavuji nazvy sloupcu tabulky v databazi, hodnoty pozadavane hodnoty.
	* @return void
	*/
	abstract public static function change($id,$change);

	/**
	* Vytvori polozku.
	* @return void
	*/
	abstract public static function create();

	/**
	* Odstrani polozku z databaze.
	* @param int ID polozky
	* @return void
	*/
	abstract public static function destroy($id);

	/**
	* Vrati informace o polozce.
	* @param int ID polozky.
	* @return record
	*/
	abstract public static function getInfo($id);
	
	/**
	* Vrati informace o polozce.
	* @param string Nazev sloupce.
	* @param value Hodnota.
	* @return record
	*/
	abstract public static function getInfoByItem($item,$value);

	/**
	* Vytvori tabulku v databazi.
	* @return void
	*/
	abstract public static function install();

}
?>
