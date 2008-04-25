<?php
/**
* @package eskymoFrame
* @author Eskymaci
*/

/**
* @package eskymoFrame
* Tato trida slouzi jako predek pro tridy nesouci nejakou hodnotu.
*/
abstract class Object {

	/**
	* @var value Polozka nesouci hodnotu objektu
	*/	
	public $value;

	/**
	* Konstruktor tridy. Nastavy atribut $value na pozadovanou hodnotu.
	* @param value Pozadovana hodnota.
	* @return void
	*/
	public function __construct($value) {
		$this->setValue($value);
	}

	/**
	* Nastavi atribut $value na pozadovanou hodnotu.
	* @param value Pozadovana hodnota.
	* @return void
	*/
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	* Vrati hodnotu atributu $value
	* @return value 
	*/
	public function getValue() {
		return $this->value;
	}

	/**
	* Vytiskne objekt.
	* @return void
	*/
	abstract public function view();
}
?>
