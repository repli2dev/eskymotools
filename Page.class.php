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
	 * @var string Kodovani zobrazovanych stranek.
	 */
	const CHARSET = "utf-8";
	
	const DIR_JS = "js/";
	
	const DIR_CSS = "style/";
	
	/**
	* @var boolean Zapne (Vypne) sessions.
	*/	
	const SWITCHER_SESSION = FALSE;

	/**
	* @var boolean Zapne/Vypne praci s MySQL
	*/
	const SWITCHER_MYSQL = TRUE;

	/**
	* @var array Obraz superglobalniho pole $_SESSION[]
	*/
	private $session = array();

	/**
	* @var string Titulek stranky.
	*/
	private $title;

	private static $styleSheet = array();
	
	private static $numCSS;
	
	private static $numJS;
	
	private static $externJS = array();

	/**
	* Promenna nesouci prvky stranky.
	*/
	private $value = array();
	
	/**
	* Konstruktor - nacte superglobalni pole do atributu Page::$get, Page::$post, Page::$session (kontroluje se jejich obsah).
	* @see Page::$get
	* @see Page::$post
	* @see Page::$session
	*/
	public function __construct() {
		if (self::SWITCHER_SESSION) {
			session_start();
			$this->loadSession();
		}
		$this->loadGet();
		$this->loadPost();
		if (self::SWITCHER_MYSQL) {		
			MySQL::connect();
		}
		parent::__construct();
	}
	
	/**
	* Vrati danou polozku z atributu self :: $get.
	* @see Page::$get
	* @param string
	* @return array_item
	*/
	public function get($key) {
		return $this->get[$key];
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
	public function loadSession() {
		foreach ($_SESSION AS $key => $item) {
			$this->setSession($key,$item);
		}
		
	}
	
	/**
	* Znovu nahraje superglobalni promenne.
	* @return void
	*/
	public static function reload() {
		self::loadPost();
		self::loadGet();
		self::loadSession();
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
	* Zmeni titulek stranky Page::$title.
	* @param string
	* @return void
	*/
	public function setTitle($tile) {
		$this->title = $title;
	}

	/**
	* Vrati titulek stranky Page::$title.
	* @return string
	*/
	public function getTitle() {
		return $this->title;
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
	* Prida JS soubor do pole externich souboru Page::$externFile na zaklade hlavicky fce.
	* @param string Hlavicka JS fce.
	* @return int Pocet externich JS souboru. 
	*/
	public static function addJsFile($fun) {
		$fn = split("\(",$fun);
		$fn = $fn[0];
		$help = FALSE;
		foreach (Page::$externJS AS $item) {
			if ($item == $fn) {
				$help = TRUE;
			} 
		}
		if (!$help) {
			Page::$numJS++;
			Page::$externJS[self::$numJS] = $fn;	
		}
		return self::$numJS;
	}

	/**
	* Prida CSS soubor do pole externich CSS souboru Page::$styleSheet.
	* @param string Nazev CSS souboru.
	* @return int Pocet externich CSS souboru.
	*/
	public static function addStyleSheet($fn) {
		$help = FALSE;
		foreach (self::$styleSheet AS $item) {
			if ($item == $fn) {
				$help = TRUE;
			}
		}
		if (!$help) {
			self::$numCSS++;
			self::$styleSheet[self::$numCSS] = $fn;
		}
		return self::$numCSS;
	}
	
	/**
	* Vytiskne stranku (objekt Page).
	* @return void
	*/
	public function view() {
		echo "
		<?xml version=\"1.0\" encoding=\"UTF-8\"?><!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"cs\" lang=\"cs\">
  			<head>
  		";  		
		$tag = new HTMLTag;
		$tag->setTag("meta");
		$tag->addAtribut("http-equiv","Content-Type");
		$tag->addAtribut("content","text/html; charset=". self::CHARSET);
		$tag->view();
  		foreach(self::$styleSheet AS $item) {
			$link = new Link("stylesheet", "text/css", self::DIR_CSS . $item);
			$link->view();
  		}
  		unset($link);
  		foreach(Page :: $externJS AS $item) {
  			$script = new Script("text/javascript", self::DIR_JS . $item . ".js");
  			$script->view();
  		}
  		unset($script);
		$title = new HTMLTag(new String($this->title));
  		$title->setPair();
  		$title->setTag("title");
  		$title->view();
  		unset($title);
  		echo "</head>";
  		echo "<body>";
		foreach($this->value as $item) {
			$item->view();
		}
		echo "</body>";
		echo "</html>";
	} 
}
?>

