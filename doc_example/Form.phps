<?php
require_once("../Autoload.class.php"); // Nacte tridu Autoload.

Autoload::add("../");
Autoload::add("../html/");

$page = new Page();
	$form = new Form();
		$fieldset = new Fieldset(NULL,"Legenda");
			$input = new Input(new String("Test"), "obsah" ,"text",TRUE,TRUE);
			$input2 = new Input(new String(""),"odeslano","submit");
			$textarea = new Textarea(new String("Obsah"),"textarea",50,10);
			$select = new Select("select");
			$select->addOption(new String("Option"),"1");
			$select->addOption(new String("Option2"),"2",TRUE);
		$fieldset->addValue($input);
		$fieldset->addValue($input2);
		$fieldset->addValue($textarea);
		$fieldset->addValue($select);
	$form->addValue($fieldset);
$page->addValue($form);
$page->view();

?>