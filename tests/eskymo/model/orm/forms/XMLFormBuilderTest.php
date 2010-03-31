<?php
class XMLFormBuilderTest extends EskymoTestCase
{

    /** @var XMLBuilderFactory */
    private $factory;

    /** @var XMLFormBuilder */
    private $builder;

    public function setUp() {
	$this->factory = new XMLFormBuilderFactory(new File(dirname(__FILE__) . "/xmls/forms.xml"));
	$form = new AppForm(Environment::getApplication()->getPresenter(), "testForm");
	$factory = SimpleEntityFactory::createEntityFactory("XMLFormBuilderTest");
	$this->builder = $this->factory->createBuilder("testForm", $form,$factory->createEmpty());
    }

    public function testBasic() {
	$this->builder->setResource("first", array("AaAA" => "AAA"));
	$this->builder->setResource("second", "hidden");
	$form = $this->builder->buildForm();
	$form->addSubmit("send", "Submit");
	$form->render();
    }

}

class XMLFormBuilderTestEntity extends AEntity
{
    protected $first;

    protected $second;

    protected $third;
}