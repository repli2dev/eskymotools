<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s FORM tagem <form></form>.
* @example doc_example/Form.phps
*/
class Form extends HTMLTag {

	/**
	 * Konstruktor.
	 * @param Object obsah formulare
	 * @param string Atribut action.
	 * @param string Atribut method.
	 * @param string Atribut enctype.
	 * @param string Atribut legend.
	 * @return void
	 */	 
	public function __construct($value = NULL, $action = NULL, $method = NULL, $enctype = NULL) {
		parent::__construct($value);
		$this->setTag("form");
		$this->setPair();
		if ($action) {
			$this->action($action);
		}
		elseif ($value) {
			$this->content($value->getValue());
		}
		if ($method) {
			$this->method($action);
		}
		elseif ($enctype) {
			$this->enctype($enctype);
		}
	}
	
	/**
	 * Nastavi akci, ktera se provede po odeslani formulare
	 * @param string Atribut action.
	 * @return void
	 */
	public function action($action) {
		$this->addAtribut("action",$action);
	}
	
	/**
	 * Nastavi metodu odeslani POST nebo GET
	 * @param string Atribut method.
	 * @return void
	 */
	public function method($method) {
		$this->addAtribut("method",$method);
	}
	
	/**
	 * Zvoli zpusob prenosu dat (ascii vs. binarni vs. url encoded)
	 * @param string Atribut enctype.
	 * @return void
	 */
	public function enctype($enctype) {
		$this->addAtribut("enctype",$enctype);
	}
}

/**
* @package eskymoFW
* Trida slouzici pro praci s Fieldset tagem <fieldset></fieldset>.
* @example doc_example/Fieldset.phps
*/
class Fieldset extends HTMLTag {

}
// TODO: Fieldset tag
// TODO: Legend tag
// TODO: Input, select, tag
?>
