<?php
class XMLFormBuilder extends AFormBuilder
{

    /** @var XMLBuilderFactory */
    private $factory;

    private $config;

    // ---- PUBLIC METHODS

    public function  __construct(XMLBuilderFactory $factory, $config, Form $form) {
	$this->factory	= $factory;
	$this->config	= $config;
	$this->setForm($form);
	$entityName = $config["entity"];
	$factory = SimpleEntityFactory::createEntityFactory(strtr($entityName, array("Entity" => "")));
	$this->setEntity($factory->createEmpty());
    }

    // ---- PROTECTED METHODS

    protected function &createForm() {
	$form = $this->getForm();
	$config = $this->getConfig();
	// TODO: Cycle detection
	if (isset($config["extends"])) {
	    $form = $this->getFactory()->createBuilder($name, $form)->buildForm();
	}
	foreach($config->attribute AS $attribute) {
	    $name = (string)$attribute["name"];
	    if ($this->isDisabled($name)) {
		continue;
	    }
	    if (isset($attribute->label)) {
		$label = (string)$attribute->label;
	    }
	    if ($this->getResource($name) == NULL) {
		if (isset($attribute->{'without-resource'}->label)) {
		    $label = $attribute->{'without-resource'}->label;
		}
		$type	    = isset($attribute->{'without-resource'}->type) ? $attribute->{'without-resource'}->type : IFormBuilder::TEXTINPUT;
		$resource   = NULL;
	    }
	    else {
		if (isset($attribute->{'with-resource'}->label)) {
		    $label = $attribute->{'with-resource'}->label;
		}
		$type	    = isset($attribute->{'with-resource'}->type) ? $attribute->{'with-resource'}->type : IFormBuilder::SELECTBOX;
		$resource   = $this->getResource($name);
	    }
	    $this->addItemToForm($form, $name, $label, $type, $resource);
	    foreach($attribute->rules->rule AS $rule) {
		$rType	    = (string)$rule->type;
		$message    = (string)$rule->message;
		$arg	    = (string)$rule->arg;
		$this->addFormRule($form, $name, $rType, $message, $arg);
	    }
	}
	return $form;
    }

    // ---- PRIVATE METHODS

    private function getConfig() {
	return $this->config;
    }

    /** @return XMLBuilderFactory */
    private function getFactory() {
	return $this->factory;
    }

}

