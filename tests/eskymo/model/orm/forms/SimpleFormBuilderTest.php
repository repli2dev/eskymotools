<?php

class SimpleFormBuilderTest extends EskymoTestCase
{

	/** @var IFormBuilder */
	private $builder;

	/** @var IEntityFactory */
	private $factory;

	public function setUp() {
		$this->factory = new TestSimpleFormBuilderEntityFactory();
		$form = new AppForm(Environment::getApplication()->getPresenter(), "testForm");
		$this->builder = new SimpleFormBuilder($this->factory->createEmpty(),$form);
	}

	public function tearDown() {
		Environment::getApplication()->getPresenter()->removeComponent($this->builder->buildForm());
	}

	/** @TestThrow(InvalidArgumentException) */
	public function testSetInvalidResource() {
		$this->builder->setResource("skipped", "true");
	}

	public function testSetAndGetResource() {
		$this->assertNull($this->builder->getResource("translated_name"));
		$this->builder->setResource("translated_name", array("aa" => "aaa", "bbb" => "bbb"));
		$this->assertEquals(2, sizeof($this->builder->getResource("translated_name")));
		//$this->builder->buildForm()->render();
	}

	public function testFormComponentTypes() {
		$this->builder->setResource("translated_name", array("aa" => "aaa", "bbb" => "bbb"));
		$this->builder->setResource("description", array("aa" => "aaa", "bbb" => "bbb"));
		$this->assertEquals("SelectBox",$this->builder->buildForm()->getComponent("translated_name")->getReflection()->getName());
		$this->assertEquals("RadioList",$this->builder->buildForm()->getComponent("description")->getReflection()->getName());
		$this->assertEquals("TextArea",$this->builder->buildForm()->getComponent("age")->getReflection()->getName());
		$this->assertEquals("SelectBox",$this->builder->buildForm()->getComponent("sex")->getReflection()->getName());
		$this->assertEquals("TextInput",$this->builder->buildForm()->getComponent("city")->getReflection()->getName());
		$this->assertEquals("TextInput",$this->builder->buildForm()->getComponent("children")->getReflection()->getName());
		try {
			$this->builder->buildForm()->getComponent("skipped");
			$this->fail("The component [skipped] should not exist.");
		}
		catch(InvalidArgumentException $e) {}
	}

	public function testSubmitForm() {
		$this->builder->setResource("translated_name", 1);
		$form = $this->builder->buildForm();
		$form->render();
		$form->isSubmitted();
		$form->setDefaults(array());
		$this->builder->onSubmit($form);
		$this->assertEquals(IEntity::STATE_PERSISTED, $this->builder->getEntity()->getState());
		$this->assertEquals(1, $this->builder->getEntity()->name);
		$this->assertNull($this->builder->getEntity()->description);
		$this->assertNull($this->builder->getEntity()->skipped);
	}

}

class TestSimpleFormBuilderEntityFactory extends AEntityFactory
{

	public function createEmpty() {
		return new TestSimpleFormBuilderEntity($this);
	}

	public function insert(IEntity &$entity) {
		return 1;
	}

	public function delete($id) {}

	public function update(IEntity $entity) {}

	/* -------------------*/

	protected function createDeleter() {
		return $this;
	}

	protected function createUpdater() {
		return $this;
	}

	protected function createInserter() {
		return $this;
	}

	protected function createSelector() {
		throw new NotSupportedException();
	}
}
/** @Id(translate=translated_id) */
class TestSimpleFormBuilderEntity extends AEntity
{
	/**
	 * @Translate(translated_name)
	 * @Super(translate=super_name)
	 */
	protected $name;

	/**
	 * @Form(withoutResource = textarea, withResource = radiobox)
	 * @Rule(type=filled)
	 */
	protected $description;

	/** @Form(withoutResource=textarea) */
	protected $age;

	/** @Form(withoutResource=text) */
	protected $city;

	protected $children;

	/**
	 * @Type(name=enum, values=male:female)
	 */
	protected $sex;

	/** @Skip(Form) */
	protected $skipped;

}