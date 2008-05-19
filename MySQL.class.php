<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Tato trida slouzi pro komunikaci s MySQL databazi.
*/
class MySQL {
	
	/**
	* @var boolean Promenna, ktera urcuje, zda je trida v testovacim rezimu (TRUE) nebo ne (FALSE). 
	*/
	private static $test = TRUE;

	/**
	* @var string Server, na kterem bezi databaze.
	*/
	private static $server = "localhost";
	
	/**
	* @var string Nazev databaze, se kterou pracuji.
	*/
	private static $database = "";

	/**
	* @var string Jmeno uzivatele databaze.
	*/
	private static $user = "";

	/**
	* @var string Heslo k databazi.
	*/
	private static $password = "";

	/**
	* @var string Porovnavani spojeni.
	*/
	private static $character;

	/**
	* Konstruktor.
	* @return void
	*/
	public function __construct() {
	}

	/**
	* Pripojit se k databazi.
	* @return boolean
	*/
	public static function connect() {
		if (self :: getTest()) {		
			mysql_pconnect(self :: getServer(),self :: getUser(), self :: $password) or die(Language :: $error . " : " . __FILE__ . " : " . __LINE__ . Language :: $noDBServer);
			mysql_select_db(self :: getDatabase()) or die(Language :: $error . " : " . __FILE__ . " : " .__LINE__ . " : " . Language :: $noDatabase);
			mysql_query("SET CHARACTER SET ". self :: getCharacter()) or die(Language :: $error . " : " . __FILE__ . " : " . __LINE__ . " : " . Language :: $wrongCharset);
			return TRUE;
		}
		else {
			try {
				if (!(mysql_pconnect(self :: getServer(),self :: getUser(), self :: $password))) {
					throw new Exception;
				}
				if (!(mysql_select_db(self :: getDatabase()))) {
					throw new Exception;
				}
				if (!(mysql_query("SET CHARACTER SER ". self :: getCharacter()))) {
					throw new Exception;
				}
				return TRUE;
				
			}
			catch (Exception $e) {
				return FALSE;
			}
		}
	}
	
	
	/**
	* Vrati databazovy server.
	* @return string
	*/
	public static function getServer() {
 		return self :: $server;
	}
	
	/**
	* Vrati nazev databaze.
	* @return string
	*/	
	public static function getDatabase() {
		return self :: $database;
	}
	
	/**
	* Vrati jmeno uzivatele databaze.
	* @return string 
	*/
	public static function getUser() {
		return self :: $user;
	} 
	
	/**
	* Vrati pouzitou znakovou sadu pripojeni (napr. utf8).
	* @return string
	*/
	public static function getCharacter() {
		return self :: $character;
	}
	
	/**
	* Vrati TRUE, pokud je trida v testovacim rezimu.
	* @return boolean
	*/	
	public static function getTest() {
		return self :: $test;
	}
	
	/**
	* Nastavi databazovy server.
	* @param string
	* @return void
	*/
	public static function setServer($server) {
		self :: $server = $server;
	}
	
	/**
	* Nastavi nazev databaze.
	* @param string
	* @return void
	*/
	public static function setDatabase($database) {
		self :: $database = $database;
	}
	
	/**
	* Nastavi jmeno uzivatele.
	* @param string
	* @return void
	*/
	public static function setUser($user) {
		self :: $user = $user;
	}
	
	/**
	* Nastavi heslo.
	* @param string
	* @return void
	*/
	public static function setPassword($password) {
		self :: $password = $password;
	}

	/**
	* Nastavi znakovou sadu (porovnavani) pripojeni (napr. utf8).
	* @param string
	* @return void
	*/
	public static function setCharacter($character) {
		self :: $character = $character;
	}
	
	/**
	* Nastavi testovaci rezim.
	* @param boolean
	* @return void
	*/	
	public static function setTest($test) {
		self :: $test = $test;
	}
	
	/**
	* Polozi dotaz databazi.
	* @param string SQL dotaz.
	* @param string Soubor, ktery vola metodu.
	* @param int Radek, ze ktereho je metoda volana.
	* @return boolean
	*/
	public static function query($sql,$file = NULL, $line = NULL) {
		if (self :: $test) {
			mysql_query($sql) or die (Language :: $error . $file . " : " . $line . " : " . $sql);
			return TRUE;
		}
		else {
			try {
				if (!(mysql_query($sql))) {
					throw new Exception;
				}
				return TRUE;
			}
			catch (Exception $e) {
				return FALSE;
			}
		}
	}
}
?>
