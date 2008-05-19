<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro uchovani dat typu string.
*/
class String extends Object {
	
	/**
	* Vytiskne retezec na obrazovku.
	* @return void
	*/
	public function view() {
		echo $this->getValue();
	}
}
?>