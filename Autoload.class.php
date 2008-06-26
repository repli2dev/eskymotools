<?php
/**
* @package eskymoFW
* @author Eskymaci
*/
/**
* Automaticky nahrava soubory s deklarovanymi tridami.
* @package eskymoFW
* @example doc_example/Autoload.phps
*/
class Autoload {

	/**
	* @var string Znak, kterym se oddeluji balicky pri volani trid - MOMENTALNE NEFUNGUJE!!!
	*/
	protected static $pattern = "_";

	/**
	* @var array_string Cesty k deklarovanym tridam.
	*/	
	protected static $directory = array();

	/**
	* @var string Koncovka nahravanych souboru.
	*/
	protected static $ending = ".class.php";


	/**
	* Prida cestu do pole cest Autoload::$directory.
	* @param string Cesta k deklarovanym tridam (nesmi zacinat ani koncit lomitkem, resp. teckou)
	* @return void
	*/
	public static function add($dir) {
		self::$directory[] = "./" . $dir . "/";
	}

	/**
	* Metoda, ktera je volana pri hledani souboru, kde je trida deklarovana.
	* @param string Nazev tridy.
	* @return boolean
	*/
	public static function load($className) {
		//$path = str_replace(self::$pattern,"/",$className);
		$path = $className . self::$ending;
		foreach(self::$directory AS $dir) {
			if (file_exists($dir . $path)) {
				require_once($dir . $path);
				return TRUE;
			}
		}
		return FALSE;
	}
}
// Zaregistruje autoload
spl_autoload_register(array("Autoload","load"));

?>
