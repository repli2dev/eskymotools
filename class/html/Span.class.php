<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s tagem Span.
*/
class Span extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setTag("span");
		$this->setPair();
	}
	
}
?>