<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Tato trida slouzi jako predek pro tridy nesouci nejakou hodnotu.
*/
abstract class Object {

	/**
	* @var Object Polozka nesouci hodnotu objektu
	*/	
	private $value;

	/**
	* Konstruktor tridy. Nastavy atribut $value na pozadovanou hodnotu.
	* @param value Pozadovana hodnota.
	* @return void
	*/
	public function __construct($value = NULL) {
		$this->setValue($value);
		$this->main();
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
	* Metoda, ktera se provede po vytvoreni instance.
	* @return void
	*/
	public function main() {
	}

	/**
	* Vytiskne objekt.
	* @return void
	*/
	abstract public function view();
}
?>
