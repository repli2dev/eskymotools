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
	 * @param String Atribut action.
	 * @param String Atribut method.
	 * @param String Atribut enctype.
	 * @param String Attribut name.
	 * @param Pointer Atribut enctype.
	 * @return void
	 */	 
	// name je jméno formuláře - pokud je definováno, pokusí se vložit JS
	// page je ukazatel - řeší problém s Page::addStyleSheet
	public function __construct($action = NULL, $method = NULL, $enctype = NULL,$name = NULL) {
		$this->setTag("form");
		$this->setPair();
		if ($action) {
			$this->action($action);
		}
		if ($method) {
			$this->method($action);
		}
		if ($enctype) {
			$this->enctype($enctype);
		}
		//pridani generickeho stylopisu
		Page::addStyleSheet("form.css");
		//$page->addJsFile("form-".$name."");
		$this->addEvent("onsubmit","zkontroluj(this)");
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
	 * @param string Atribut action.
	 * @param string Atribut legend.
	 * @return void
	 */	 
	public function __construct($legend = NULL) {
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
	
	/**
	 * Přidá textove policko
	 * @param Object value
	 * @param String name
	 * @param Boolean disabled
	 * @param Boolean readonly
	 * @return void
	 */
	public function addTextInput($name = NULL, $value = "", $disabled = NULL, $readonly = NULL){
		$this->addValue(new Input($value, $name, "text", $disabled, $readonly));
	}
	
	/**
	 * Přidá policko pro heslo
	 * @param string name
	 * @param string value
	 * @param Boolean disabled
	 * @param Boolean readonly
	 * @return void
	 */
	public function addPasswordInput($name = NULL, $value = NULL, $disabled = NULL, $readonly = NULL){
		$this->addValue(new Input($value, $name, "password", $disabled, $readonly));
	}
	
	/* Přidá odesílací tlačítko
	 * @param string name
	 * @param string value
	 * @param Boolean disabled
	 * @param Boolean readonly
	 */
	public function addSubmitButton($name = NULL, $value = NULL, $disabled = NULL, $readonly = NULL){
		$this->addValue(new Input($value, $name, "submit", $disabled, $readonly));
	}
	
	/**
	 * Přidá radio button
	 * @param Object value
	 * @param String name
	 * @param Boolean checked
	 * @param Boolean disabled
	 * @param Boolean readonly
	 */
	public function addRadioInput($value = NULL, $name = NULL, $checked= NULL, $disabled = NULL, $readonly = NULL){
		$this->addValue(new Radio($value, $name, $checked, $disabled, $readonly));
	}
	
	/**
	 * Přidá checkbox button
	 * @param Object value
	 * @param String name
	 * @param Boolean checked
	 * @param Boolean disabled
	 * @param Boolean readonly
	 */
	public function addCheckboxInput($value = NULL, $name = NULL, $checked= NULL, $disabled = NULL, $readonly = NULL){
		$this->addValue(new Checkbox($value, $name, $checked, $disabled, $readonly));
	}
	
	/**
	 * Přidá textareu
	 * @param Object value
	 * @param String name
	 * @param Integer cols
	 * @param Integer rows
	 * @param Boolean disabled
	 * @param Boolean readonly
	 * @param String wrap
	 */
	public function addTextarea($value = NULL, $name = NULL, $cols = NULL, $rows = NULL,$disabled = NULL, $readonly = NULL, $wrap = NULL){
		$this->addValue(new Textarea($value, $name, $cols, $rows,$disabled, $readonly, $wrap));
	}

	/**
	 * Přidá select
	 * @param Object name
	 * @param Boolean multiple
	 * @param Integer size
	 * @param Boolean disabled
	 * @param mixed Pole hodnot (options), kde index oznacuje zobrazeny text a jeho hodnota hodnotu.
	 */
	public function addSelect($name = NULL, $options = array(), $multiple = NULL, $size = NULL,$disabled = NULL){
		$select = new Select($name, $multiple, $size,$disabled);
		foreach ($options AS $key => $item) {
			$select->addOption($key,$item);
		}
		$this->addValue($select);
		unset($select);
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
* Trida slouzici pro praci s input tagem <input /> konkrétně s radio inputem
* @example doc_example/Form.phps
*/
class Radio extends Input {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut Input.
	 * @param String Attribut name.
	 * @param Boolean Attribut checked
	 * @param Boolean Attribut disabled
	 * @param Boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($value = NULL, $name = NULL, $checked = NULL, $disabled = NULL, $readonly = NULL) {
		parent::__construct($value);
		$this->setTag("input");
		$this->addAtribut("type", "radio");
		if ($value) {
			$this->addAtribut("value", $value->getValue());
		}
		if ($checked){
			$this->addAtribut("checked", "checked");
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
* Trida slouzici pro praci s input tagem <input />
* @example doc_example/Form.phps
*/
class Checkbox extends Input {
	
	/**
	 * Konstruktor.
	 * @param Object Attribut Input.
	 * @param String Attribut name.
	 * @param Boolean Attribut checked
	 * @param Boolean Attribut disabled
	 * @param Boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($value = NULL, $name = NULL, $checked = NULL, $disabled = NULL, $readonly = NULL) {
		parent::__construct($value);
		$this->setTag("input");
		$this->addAtribut("type", "checkbox");
		if ($value) {
			$this->addAtribut("value", $value->getValue());
		}
		if ($checked){
			$this->addAtribut("checked", "checked");
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
	 * @param Object Attribut value.
	 * @param String Attribut name.
	 * @param Integer Attribut cols.
	 * @param Integer Attribut rows.
	 * @param Boolean Attribut disabled.
	 * @param Boolean Attribut readonly.
	 * @param String Attribut wrap.
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
	 * @param string text.
	 * @param string value.
	 * @param Boolean selected.
	 * @return void
	 */
	public function __construct($text = NULL, $value = NULL, $selected = NULL) {
		$this->setTag("option");
		$this->setPair();
		if ($text) {
			$this->addValue($text);
		}
		if ($value){
			$this->addAtribut("value",$value);
		}
		if ($selected){
			$this->addAtribut("selected", $selected);
		}
	}
}
?>

