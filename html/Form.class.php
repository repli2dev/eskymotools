<?php
/**
* @package eskymoFW
* @author Eskymaci
*/

/**
* @package eskymoFW
* Trida slouzici pro praci s FORM tagem <form></form>.
* @example doc_example/Form.class.phps
*/
class Form extends HTMLTag {

	private $lastFildset;
	
	/**
	 * Konstruktor.
	 * @param string Atribut action.
	 * @param string Atribut method.
	 * @param string Atribut enctype.
	 * @param integer Attribut id.
	 * @param boolean Attribut load.
	 * @return void
	 */	 
	public function __construct($action, $method = NULL, $enctype = NULL,$id = NULL, $load = NULL) {
		$promene = $this->getData($id,$method);
		if($odeslano){
			//kontrola
				//uspech
				//neuspech
		} else {
			//zobrazeni formulare
			$this->renderForm($action,$method,$enctype);
		}
	}
	
	/**
	 * vykresli formular
	 * @param string Atribut action.
	 * @param string Atribut method.
	 * @param string Atribut enctype.
	 * @return void
	 */
	public function renderForm($action,$method,$enctype){
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
		$this->addEvent("onSubmit","zkontroluj(this)");
	}
		
	/**
	 * vykresli formular
	 * @param integer Atribut id.
	 * @param string Atribut method.
	 * @return array
	 */
	public function getData($id,$method){
		if(empty($id)){
			// nacte z post nebo get
			
		} else {
			// nacte z databaze
			
		}
		return array();
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
	
	/**
	 * Přidá fieldset a vrati jeho index v poli hodnot.
	 * @param string Popisek fieldsetu.
	 * @return int
	 */
	public function addFieldset($legend = NULL){
		$this->lastFildset = $this->addValue(new Fieldset($legend));
		return $this->lastFildset;
	}
	
	/**
	 * Prida polozku do naposledy pridaneho fieldsetu.
	 * @param Object Formularova polozka
	 */
	public function addToFieldset($value){
		$this->value[$this->lastFildset]->addValue($value);
	}
	
	/**
	 * Přidá textove policko
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 * @return void
	 */
	public function addTextInput($name = NULL, $label = NULL, $value = "", $disabled = NULL, $readonly = NULL){
		$p = new P();
		$p->addValue(new Input($name, $value, "text", $disabled, $readonly));
		$p->addValue(new Label($label));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá policko pro heslo
	 * @param string name
	 * @param string Popiska
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 * @return void
	 */
	public function addPasswordInput($name = NULL, $label = NULL, $value = NULL, $disabled = NULL, $readonly = NULL){
		$p = new P();
		$p->addValue(new Input($name, $value, "password", $disabled, $readonly));
		$p->addValue(new Label($label));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/* Přidá odesílací tlačítko
	 * @param string name
	 * @param string value
	 * @param boolean disabled
	 * @param boolean readonly
	 */
	public function addSubmitButton($name = NULL, $value = NULL, $disabled = NULL, $readonly = NULL){
		$this->addValue(new P(new Input($name, $value, "submit", $disabled, $readonly)));
	}
	
	/**
	 * Přidá radio button
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean checked
	 * @param boolean disabled
	 * @param boolean readonly
	 */
	public function addRadioInput($name = NULL, $label = NULL, $value = NULL, $checked= NULL, $disabled = NULL, $readonly = NULL){
		$p = new P();
		$p->addValue(new Radio($name, $value, $checked, $disabled, $readonly));
		$p->addValue(new Label($label));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá checkbox button
	 * @param string name
	 * @param string Popiska.
	 * @param string value
	 * @param boolean checked
	 * @param boolean disabled
	 * @param boolean readonly
	 */
	public function addCheckboxInput($name = NULL, $label = NULL, $value = NULL, $checked= NULL, $disabled = NULL, $readonly = NULL){
		$p = new P();
		$p->addValue(new Checkbox($name, $value, $checked, $disabled, $readonly));
		$p->addValue(new Label($label));
		$this->addToFieldset($p);
		unset($p);
	}
	
	/**
	 * Přidá textareu
	 * @param string name
	 * @param string Popiska.
	 * @param String Zobrazeny text.
	 * @param integer cols
	 * @param integer rows
	 * @param ioolean disabled
	 * @param boolean readonly
	 * @param string wrap
	 */
	public function addTextarea($name = NULL, $label = NULL, $text = NULL, $cols = NULL, $rows = NULL,$disabled = NULL, $readonly = NULL, $wrap = NULL){
		$p = new P();
		$p->addValue(new Textarea($name,$text,$cols,$rows,$disabled,$readonly,$wrap));
		$p->addValue(new Label($label));
		$this->addToFieldset($p);
		unset($p);
	}

	/**
	 * Přidá select
	 * @param string name
	 * @param mixed Pole hodnot (options), kde index oznacuje zobrazeny text a jeho hodnota hodnotu.
	 * @param string Popiska.
	 * @param boolean multiple
	 * @param integer size
	 * @param boolean disabled
	 */
	public function addSelect($name = NULL, $options = array(), $label = NULL, $multiple = NULL, $size = NULL,$disabled = NULL){
		$select = new Select($name, $multiple, $size,$disabled);
		foreach ($options AS $key => $item) {
			$select->addOption($key,$item);
		}
		$p = new P();
		$p->addValue($select);
		unset($select);
		$p->addValue(new Label($label));
		$this->addToFieldset($p);
		unset($p);
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
	public function __construct($name = NULL, $value = NULL, $type = NULL, $disabled = NULL, $readonly = NULL) {
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
	 * @param string Attribut Input.
	 * @param string Attribut name.
	 * @param boolean Attribut checked
	 * @param boolean Attribut disabled
	 * @param boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $checked = NULL, $disabled = NULL, $readonly = NULL) {
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
	 * @param string Attribut Input.
	 * @param string Attribut name.
	 * @param boolean Attribut checked
	 * @param boolean Attribut disabled
	 * @param boolean Attribut readonly
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $checked = NULL, $disabled = NULL, $readonly = NULL) {
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
	 * @param string Attribut value.
	 * @param string Attribut name.
	 * @param integer Attribut cols.
	 * @param integer Attribut rows.
	 * @param boolean Attribut disabled.
	 * @param boolean Attribut readonly.
	 * @param string Attribut wrap.
	 * @return void
	 */	 
	public function __construct($name = NULL, $value = NULL, $cols = NULL, $rows = NULL,$disabled = NULL, $readonly = NULL, $wrap = NULL) {
		parent::__construct($value);
		$this->setTag("textarea");
		$this->setPair();
		if ($value) {
			$this->addValue($value);
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

class Label extends HTMLTag {
	
	/**
	 * Konstruktor
	 * @param String Hodnota labelu.
	 */
	public function __construct($label) {
		$this->setTag("label");
		$this->setPair();
		$this->addValue($label);
	}
}
?>

