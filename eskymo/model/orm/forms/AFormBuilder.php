<?php
abstract class AFormBuilder {

    /** @var bool */
    private $built;

    /** @var array */
    private $disabled = array();

    /* @var IEntity */
    private $entity;

    /** @var Form */
    private $form;

    /** @var array */
    private $resources = array();

    public function &buildForm() {
    // If the form has been already built. return it
	if ($this->isBuilt()) {
	    return $this->form;
	}
	// Build a new form
	switch($this->entity->getState()) {
	    case IEntity::STATE_NEW:
	    case IEntity::STATE_PERSISTED:
		$this->setForm($this->createForm());
		$this->built = true;
		return $this->form;
		break;
	    default:
		throw new InvalidStateException("The form can not be created because the entity is not in state [NEW] or [PERSISTED].");
	}
    }

    public function disable($attribute) {
	if (!in_array($attribute,$this->getEntity()->getAttributeNames("Form"))) {
	    throw new InvalidArgumentException("The attribute name [$name] is not compatible with the entity.");
	}
	$this->disabled[$attribute] = TRUE;
    }

    public function disableAll() {
	foreach($this->getEntity()->getAttributeNames("Form") AS $attribute) {
	    $this->disabled[$attribute] = TRUE;
	}
    }

    public function enable($attribute) {
	if (!in_array($attribute,$this->getEntity()->getAttributeNames("Form"))) {
	    throw new InvalidArgumentException("The attribute name [$name] is not compatible with the entity.");
	}
	unset($this->disabled[$attribute]);
    }

    /** @return IEntity */
    public final function getEntity() {
	return $this->entity;
    }

    public function getResource($name) {
	if (empty($name)) {
	    throw new NullPointerException("name");
	}
	if (isset($this->resources[$name])) {
	    return $this->resources[$name];
	}
	else {
	    return NULL;
	}
    }

    public function isBuilt() {
	return $this->built;
    }

    public function onSubmit(Form $form) {
    // Get form values
	$values = $form->getValues();
	// Fill the entity
	foreach ($this->getEntity()->getAttributeNames("Form") AS $attribute => $translated) {
	    if (isset($values[$translated])) {
		$this->getEntity()->$attribute = $values[$translated];
	    }
	}
	// TODO: Exceptions and transaction
	// Persist the entity
	$this->entity->persist();
    }

    public function setResource($name, $resource) {
	if (empty($name)) {
	    throw new NullPointerException("name");
	}
	if (empty($resource)) {
	    throw new NullPointerException("resource");
	}
	if (!in_array($name,$this->getEntity()->getAttributeNames("Form"))) {
	    throw new InvalidArgumentException("The resource name [$name] is not compatible with the entity.");
	}
	$attribute = ExtraArray::keyOf($this->getEntity()->getAttributeNames("Form"), $name);
	$type = $this->getEntity()->getAttributeType($attribute);
//	if (!empty($type) && !empty($type->name) && $type->name == "enum") {
//	    throw new InvalidStateException("The resource can not be set because the attribute type is declared as [ENUM].");
//	}
	$this->resources[$name] = $resource;
    }

    /* PROTECTED METHODS */

    /** @return Form */
    protected abstract function &createForm();

    /** @retrun Form */
    protected function getForm() {
	return empty($this->form) ? NULL : $this->form;
    }

    protected function getResources() {
	return $this->resources;
    }

    protected function isDisabled($attribute) {
	return !empty($this->disabled[$attribute]);
    }

    protected final function setEntity(IEntity $entity) {
	$this->entity = $entity;
    }

    protected function setForm(Form $form) {
	$this->form = $form;
    }

}