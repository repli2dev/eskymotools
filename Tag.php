<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici jako predek pro vsechny HTML tagy.
*/
class Tag extends Object {

	/**
	* @var boolean Parovy (TRUE) / Neparovy tag (FALSE).
	*/
	protected $pair;	

	/**
	* @var string Nazev tagu.
	*/
	protected $tag;
	
	/**
	* @var string Parametr class tagu.
	*/
	protected $class;
	
	/**
	* @var string Parametr ID tagu.
	*/
	protected $id;
	
	/**
	* @var array Pole parametru HTML tagu.
	*/	
	protected $atribut = array();
	
	/**
	* @var array Pole udalosti (javascript)
	*/
	protected $event = array();
	
	/**
	* Konstruktor.
	* @return void
	*/
	public function __construct($value) {
		parent :: __construct($value);
	}
	
	/**
	* Vytiskne tag.
	* @return void
	*/
	public function view() {
		$evt = "";
		foreach ($this->event AS $key => $value) {
			$evt .= " $key = "\"$value\"";
		}
		$atribut = "";
		foreach ($this->atribit AS $key => $value) {
			$atribut .= " $key = "\"$value\"";
		}
		if ($this->pair) {
			echo "<$this->tag class = \"$this->class\" id = \"$this->id\" $atribut $evt>";
			$this->value->view();
			echo "</$this->tag>";
		}
		else {
			echo "<$this->tag class = \"$this->class\" id = \"$this->id\" $atribut $evt />";
		}
	}
	
	
}
?>