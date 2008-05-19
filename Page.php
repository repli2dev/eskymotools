<?php
require_once("Object.php");
require_once("Tag.php");
require_once("String.php");
require_once("Script.php");
require_once("Link.php");
require_once("A.php");
require_once("Ul.php");
require_once("Li.php");



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
	* @var array Pole externich javascriptovych fci.
	*/
	protected static $externJS = array();

	/**
	* @var int Pocet souboru s JS fcemi v poli externich souboru Page::$externJS.
	*/
	protected static $numJS = 0;

	/**
	* @var array Pole externich CSS souboru.
	*/
	protected $styleSheet = array();	
		
	/**
	* @var int Pocet externich CSS souboru v Page::$styleSheet.
	*/	
	protected $numCSS;

	/**
	* @var string Adresar, kde se nachazi CSS soubory.
	*/
	public static $dirCSS = "css/";
	
	/**
	* @var string Adresar, kde se nachazi JS soubory.
	*/	
	public static $dirJS = "js/";
	
	/**
	* @var int Pocet objektu v poli hodnot Page::$value.
	*/
	private $numVal = 0;

	/**
	* @var string Titulek stranky.
	*/
	private $title;

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
	public function addStyleSheet($fn) {
		$help = FALSE;
		foreach ($this->$styleSheet AS $item) {
			if ($item == $fn) {
				$help = TRUE;
			}
		}
		if ($help) {
			$this->numCSS++;
			$this->styleSheet[$this->numCSS] = $fn;
		}
		return $this->numCSS;
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
  		foreach($this->styleSheet AS $item) {
			$link = new Link("stylesheet", "text/css", self::$dirCSS . $item);
			$link->view();
  		}
  		unset($link);
  		foreach(Page :: $externJS AS $item) {
  			$script = new Script("text/javascript", Page::$dirJS . $item . ".js");
  			$script->view();
  		}
  		unset($script);
		$title = new Tag(new String($title));
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
$a = new A(new String("odkaz1"),"aaa");
$a->addValue(new String("Odkaz2"));
$a->view();

$ul = new Ul;
$li = new String("polozka");
$ul->addValue($li);
$ul->addValue($li);
$ul->view();

?>
