<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro obsluhu stranky
*/

class Page extends Object {

	/**
	* @var array Obraz superglobalniho pole $_GET[].
	*/
	private $get = array;
	
	/**
	* @var array Obraz superglobalniho pole $_POST[].
	*/
	private $post = array;

	/**
	* @var array Pole hodnot (objektu) stranky.
	*/
	private $value = array;

	/**
	* @var int Pocet objektu v poli hodnot.
	*/
	private $numVal = 0;

	/**
	* Konstruktor - nacte superglobalni pole do atributu $get, $post (kontroluje se jejich obsah).
	*/
	public function __construct() {
	}
	
	/**
	* Vrati danou polozku z atributu $get.
	* @param string
	* @return array_item
	*/
	public function get($key) {
		return $this->get[$key];
	}
	
	/**
	* Nastavi danou polozku atributu $get na danou hodnotu.
	* @param string Nazev polozky.
	* @param array_item Hodnota polozky.
	* @return void
	*/
	public function setGet($key,$value) {
		$this->get[$key] = $value;
	}

	/**
	* Vrati danou polozku z atributu $post.
	* @param string
	* @return array_item
	*/
	public function post($key) {
		return $this->get[$key];
	}
	
	/**
	* Nastavi danou polozku atributu $post na danou hodnotu.
	* @param string Nazev polozky.
	* @param array_item Hodnota polozky.
	* @return void
	*/
	public function setPost($key,$value) {
		$this->post[$key] = $value;
	}
	
	/**
	* Prida objekt od pole hodnot.
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