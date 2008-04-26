<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici jako predek pro vsehcny HTML tagy
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
	protected $id
	
	/**
	* @var string Odezva na udalost onClick
	*/
	protected $onClick;
	
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
		if ($this->pair) {
			echo "<$this->tag class = $this->class id = $this->id> onClick = $this->onClick";
			$this->value->print;
			echo "</$this->tag>";
		}
		else {
			echo "<$this->tag class = $this->class id = $this->id";
		}
	}
	
	
}
?>