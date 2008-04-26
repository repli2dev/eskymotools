<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro obsluhu stranky.
*/
class Page extends Object {

	/**
	* @var array Obraz superglobalniho pole $_GET[].
	*/
	private $get = array();
	
	/**
	* @var array Obraz superglobalniho pole $_POST[].
	*/
	private $post = array();

	/**
	* @var array Obraz superglobalniho pole $_SESSION[]
	*/
	private $session = array();

	/**
	* @var array Pole hodnot (objektu) stranky.
	*/
	private $value = array();

	/**
	* @var int Pocet objektu v poli hodnot.
	*/
	private $numVal = 0;

	/**
	* Konstruktor - nacte superglobalni pole do atributu Page::$get, Page::$post, Page::$session (kontroluje se jejich obsah).
	* @see Page::$get
	* @see Page::$post
	* @see Page::$session
	*/
	public function __construct() {
		session_start();
		$this->loadGet();
		$this->loadPost();
		$this->loadSession();
	}
	
	/**
	* Vrati danou polozku z atributu self :: $get.
	* @see Page::$get
	* @param string
	* @return array_item
	*/
	public function get($key) {
		return $this->get[$key];
		$this->loadGet();
		$this->loadPost();
		$this->loadSession();
	}
	
	/**
	* Kontroluje zda se v retezci nenachazi podezrele veci (nahradi je)
	* @param string
	* @return string
	*/	
	public static function control($s) {
		/* TOTO NENI HOTOVE --------------------------------------- */
		return $s;
	}
	
	
	/**
	* Nahraje pole $_GET[] do atributu Page::$get.
	* @see Page::$get
	* @return void
	*/	
	private function loadGet() {
		foreach ($_GET AS $key => $item) {
			$this->setGet($key,$item);
		}
	}
	
	/**
	* Nahraje pole $_POST[] do atributu Page::$get.
	* @see Page::$post
	* @return void
	*/
	private function loadPost() {
		foreach ($_POST AS $key => $item) {
			$this->setPost($key,$item);
		}
	}
	
	/**
	* Nahraje pole $_SESSION[] do atributu Page::$session.
	* @see Page::$session
	* @return void
	*/
	private function loadSession() {
		foreach ($_SESSION AS $key => $item) {
			$this->setSession($key,$item);
		}
		
	}
	
	/**
	* Nastavi danou polozku atributu Page::$get na danou hodnotu.
	* @see Page::$get
	* @param string Nazev polozky.
	* @param array_item Hodnota polozky.
	* @return void
	*/
	public function setGet($key,$value) {
		$this->get[$key] = self :: control($value);
	}

	/**
	* Vrati danou polozku z atributu Page::$post.
	* @see Page::$post
	* @param string
	* @return array_item
	*/
	public function post($key) {
		return $this->get[$key];
	}
	
	/**
	* Nastavi danou polozku atributu Page::$post na danou hodnotu.
	* @see Page::$post
	* @param string Nazev polozky.
	* @param array_item Hodnota polozky.
	* @return void
	*/
	public function setPost($key,$value) {
		$this->post[$key] = self :: control($value);
	}
	
	/**
	* Vrati danou polozku z atributu Page::$session.
	* @see Page::$session
	* @param string
	* @return array_item
	*/
	public function session($key) {
		return $this->session[$key];
	}	

	/**
	* Nastavi danou polozku atributu Page::$session na danou hodnotu.
	* @see Page::$session
	* @param string Nazev polozky
	* @param array_item Hodnota polozky
	* @return void
	*/		
	public function setSession($key,$value) {
		$this->get[$key] = self :: control($value);
	}
	
	/**
	* Prida objekt od pole hodnot Page::$value.
	* @see Page::$value
	* @param Object
	* @return int Poradi objektu v poli hodnot. 
	*/		
	public function addValue($object) {
		$this->numVal++;
		$this->value[$this->numVal] = $object;
		return $this->numVal;
	}
	
	/**
	* Vytiskne stranku (objekt Page).
	* @return void
	*/
	public function view() {
		foreach($this->value as $item) {
			$item->view();
		}
	} 
}

?>