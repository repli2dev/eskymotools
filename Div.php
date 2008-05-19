<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s tagem Div.
*/
class Div extends Tag {
	
	/**
	 * Konstruktor.
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setTag("div");
		$this->setPair();
	}
	
}
?>