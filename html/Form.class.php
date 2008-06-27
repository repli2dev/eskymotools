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
			$this->setValue($value->getValue());
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
* @example doc_example/Form.phps
*/
class Fieldset extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object obsah fielsetu
	 * @param string Atribut action.
	 * @param string Atribut legend.
	 * @return void
	 */	 
	public function __construct($value = NULL, $legend = NULL) {
		parent::__construct($value);
		$this->setTag("fieldset");
		$this->setPair();
		if ($legend) {
			$this->legend($legend);
		}
		if ($value) {
			$this->setValue($value->getValue());
		}
	}
	/**
	 * Vytvori legendu fieldsetu
	 * @param string Atribut legend.
	 * @return void
	 */
	public function legend($legend){
		$this->addValue(new Legend($legend));
	}
}
/**
* @package eskymoFW
* Trida slouzici pro praci s Fieldset tagem <fieldset></fieldset>.
* @example doc_example/Form.phps
*/
class Legend extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut value.
	 * @return void
	 */	 
	public function __construct($value = NULL) {
		parent::__construct($value);
		$this->setTag("legend");
		$this->setPair();
		if ($value) {
			$this->setValue($value->getValue());
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci s input tagem <input />
* @example doc_example/Form.phps
*/
class Input extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut Input.
	 * @param String Attribut name.
	 * @param String Attribut type
	 * @param Boolean Attribut disabled
	 * @param Boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($value = NULL, $name = NULL, $type = NULL, $disabled = NULL, $readonly = NULL) {
		parent::__construct($value);
		$this->setTag("input");
		if ($value) {
			$this->addAtribut("value", $value->getValue());
		}
		if ($type){
			$this->addAtribut("type", $type);
		}
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
		if ($readonly){
			$this->addAtribut("readonly", $readonly);
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci s textareou, tagem <textarea></textarea>
* @example doc_example/Form.phps
*/
class Textarea extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut Input.
	 * @return void
	 */	 
	public function __construct($value = NULL, $name = NULL, $cols = NULL, $rows = NULL,$disabled = NULL, $readonly = NULL, $wrap = NULL) {
		parent::__construct($value);
		$this->setTag("textarea");
		$this->setPair();
		if ($value) {
			$this->setValue($value->getValue());
		}
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($cols){
			$this->addAtribut("cols", $cols);
		}
		if ($rows){
			$this->addAtribut("rows", $rows);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
		if ($readonly){
			$this->addAtribut("readonly", $readonly);
		}
		if ($wrap){
			$this->addAtribut("wrap", $wrap);
		}
	}
}

/*
* @package eskymoFW
* Trida slouzici pro praci se selectem
* @example doc_example/Form.phps
*/
class Select extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param String name
	 * @param Boolean multiple
	 * @param Integer size
	 * @param Boolean disabled
	 * @return void
	 */	 
	public function __construct($name = NULL, $multiple = NULL, $size = NULL,$disabled = NULL) {
		parent::__construct($value);
		$this->setTag("select");
		$this->setPair();
		if ($name){
			$this->addAtribut("name", $name);
		}
		if ($multiple){
			$this->addAtribut("multiple", $multiple);
		}
		if ($size){
			$this->addAtribut("size", $size);
		}
		if ($disabled){
			$this->addAtribut("disabled", $disabled);
		}
	}
	
	/**
	 * Přidá do selectu možnost (option)
	 * @param String text.
	 * @param Integer value.
	 * @param Boolean selected.
	 * @return void
	 */
	public function addOption($text = NULL,$value = NULL,$selected = NULL){
		$this->addValue(new Option($text,$value,$selected));
	}
}

class Option extends HTMLTag {
	
	/**
	 * Konstruktor.
	 * @param String text.
	 * @param Integer value.
	 * @param Boolean selected.
	 * @return void
	 */
	public function __construct($text = NULL, $value = NULL, $selected = NULL) {
		parent::__construct($text->getValue());
		$this->setTag("option");
		$this->setPair();
		if ($value){
			$this->addAtribut("value",$value);
		}
		if ($selected){
			$this->addAtribut("selected", $selected);
		}
	}
}
?>
