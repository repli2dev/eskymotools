<?php

class SimpleFormBuilder implements IFormBuilder
{

	/** @var boolean	*/
	private $done;

	/** @var IEntity	*/
	private $entity;

	/** @var Form		*/
	private $form;

	/** @var boolean	*/
	private $built = FALSE;

	/** @var array		*/
	private $resources = array();

	/**
	 * The builder is set by the entity and the form. The implementation of AppForm
	 * is recommended
	 *
	 * @param IEntity $entity
	 * @param Form $form
	 */
	public function  __construct(IEntity $entity, Form $form) {
		$this->entity = $entity;
		$this->form = $form;
	}

	public function &buildForm() {
		// If the form has been already built. return it
		if ($this->isBuilt()) {
			return $this->form;
		}
		// Build a new form
		switch($this->entity->getState()) {
			case IEntity::STATE_NEW:
			case IEntity::STATE_PERSISTED:
				return $this->createForm();
				break;
			default:
				throw new InvalidStateException("The form can not be created becaus the entity is not in state [NEW] or [PERSISTED].");
		}
	}

	public function getEntity() {
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
		// Get translation of the entity attributes
		$translated = $this->entity->getAttributeNames("Form");
		// Fill the entity
		foreach ($this->entity->getAttributeNames() AS $attribute) {
			$this->entity->$attribute = $values[$translated[$attribute]];
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
		if (!in_array($name,$this->entity->getAttributeNames("Form"))) {
			throw new InvalidArgumentException("The resource name [$name] is not compatible with the entity.");
		}
		$this->resources[$name] = $resource;
	}

	/* PROTECTED METHODS */
	protected final function setBuilt() {
		$this->built = TRUE;
	}

	/* PRIVATE METHODS */

	private function addRules($attribute, FormControl $control) {
		$annotation = $this->getEntity()->getAnnotation("Rule",$attribute);
		// If the rule annotation is not set. Do nothing.
		if ($annotation == NULL) {
			return $control;
		}
		// Hack	- If there are more than one rule, $annotation is an array.
		//		  If there is only one rule, it is converted to the array.
		if (!is_array($annotation)) {
			$annotation = array($annotation);
		}
		// Add each rule.
		foreach ($annotation AS $rule) {
			$control->addRule(
				constant("Form::" . String::upper($rule->type)),
				$attribute . "_" . $rule->type . "_msg",
				isset($rule->arg) ? $rule->arg : NULL
			);
		}
	}

	private function addFormItem(Form &$form, $attribute) {
		// Get the Form annotation which specified the form element type
		$annotation = $this->entity->getAnnotation("Form", $attribute);
		// Translate the attribute by the Form annotation
		$translatedAttribute = Tools::arrayGet($this->entity->getAttributeNames("Form"), $attribute);
		// If the resource is set
		if (!empty($this->resources[$translatedAttribute])) {
			// If the resource is not an array - add hidden input
			if (!is_array($this->resources[$translatedAttribute])) {
				$form->addHidden($translatedAttribute);
			}
			// If the annotation does not specify the form element type, add a selectbox
			elseif(empty($annotation) || !isset($annotation->withResource) || $annotation->withResource == "selectbox") {
				$form->addSelect($translatedAttribute, $translatedAttribute, $this->resources[$translatedAttribute]);
			}
			// Otherwise add the specified element
			else {
				switch($annotation->withResource) {
					case "radiobox":
						$form->addRadioList($translatedAttribute, $translatedAttribute, $this->resources[$translatedAttribute]);
						break;
					case "checkbox":
					default:
						throw new NotSupportedException("The form element type [".$annotation->withResource."] is not supported");
				}
			}
		}
		// The resource is not set
		else {
			// If the annotation does not specify the form element type, add a text input
			if (empty($annotation) || !isset($annotation->withoutResource) || $annotation->withoutResource == "text") {
				$form->addText($translatedAttribute, $translatedAttribute);
			}
			// Otherwise add specified element.
			else {
				switch($annotation->withoutResource) {
					case "textarea":
						$form->addTextArea($translatedAttribute, $translatedAttribute);
						break;
					default:
						throw new NotSupportedException("The form element type [".$annotation->withoutResource."] is not supported");
						break;
				}
			}
		}
		// Add validation rules
		$this->addRules($attribute, $form->getComponent($translatedAttribute));
	}

	private function &createForm() {
		// Foreach attribute add a form element
		foreach($this->entity->getAttributeNames("Form") AS $attribute => $translatedAttribute) {
			$this->addFormItem($this->form, $attribute);
		}
		// Decide if the form action is 'insert' or 'update'
		if ($this->entity->getState() == IEntity::STATE_NEW) {
			$submit		= "insert";
			$defaults	= array();
		}
		else {
			$submit		= "update";
			$defaults	= $this->entity->getData("Form");
		}
		// Add submit button
		$this->form->addSubmit($submit, $submit);
		// Set default values
		foreach($this->resources AS $name => $value) {
			if (!is_array($value)) {
				$defaults[$name] = $value;
			}
		}
		$this->form->setDefaults($defaults);
		// Set the form as built
		$this->setBuilt();
		return $this->form;
	}

}