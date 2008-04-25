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
	* @var boolean Promenna, ktera urcuje, zda se testuje (TRUE) nebo ne (FALSE). 
	*/
	private static $test;

	/**
	* @var string Server, na kterem bezi databaze.
	*/
	private static $server;
	
	/**
	* @var string Nazev databaze, se kterou pracuji.
	*/
	private static $database;

	/**
	* @var string Jmeno uzivatele databaze.
	*/
	private static $user;

	/**
	* @var string Heslo k databazi.
	*/
	private static $password;

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
		if (MySQL :: getTest()) {		
			mysql_pconnect(MySQL :: getServer(),MySQL :: getUser(), MySQL :: getPassword()) or die(__FILE__." : ".__LINE__." : Chyba v pripojeni k databazovemu serveru");
			mysql_select_db(MySQL :: getDatabase()) or die(__FILE__." : ".__LINE__." : Chyba v pripojeni k databazi")
			mysql_query("SET CHARACTER SER ". MySQL :: getCharacter()) or die(__FILE__." : ".__LINE__." : Chyba v nastaveni porovnavani");
			return TRUE;
		}
		else {
			try {
				if (!(mysql_pconnect(MySQL :: getServer(),MySQL :: getUser(), MySQL :: getPassword()))) {
					throw new Exception;
				}
				if (!(mysql_select_db(MySQL :: getDatabase()))) {
					mysql_select_db(MySQL :: getDatabase());
				}
				if (!(mysql_query("SET CHARACTER SER ". MySQL :: getCharacter()))) {
					throw new Exception;
				}
				
			}			
		}
	}
	
}
?>
