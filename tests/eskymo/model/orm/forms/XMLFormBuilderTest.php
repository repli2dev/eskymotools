<?php
class XMLFormBuilderTest extends EskymoTestCase
{

    /** @var XMLBuilderFactory */
    private $factory;

    /** @var XMLFormBuilder */
    private $builder;

    public function setUp() {
	$this->factory = new XMLBuilderFactory(new File(dirname(__FILE__) . "/xmls/forms.xml"));
	$form = new AppForm(Environment::getApplication()->getPresenter(), "testForm");
	$this->builder = $this->factory->createBuilder("testForm", $form);
    }

    public function testBasic() {
	$this->builder->setResource("first", array("aaa" => "aaa"));
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