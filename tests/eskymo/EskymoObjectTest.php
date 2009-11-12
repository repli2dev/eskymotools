<?php

class EskymoObjectTest extends EskymoTestCase
{

	public function testEquals() {
		$a = new TestObject();
		$b = new TestObject();
		$this->assertTrue($a->equals($a));
		$this->assertEquals(FALSE, $a->equals($b));
	}

	public function testGetAnnotation() {
		$a = new TestObject();

		$this->assertFalse($a->getAnnotation("Class") == NULL);
		$this->assertTrue(isset($a->getAnnotation("Class")->var));
		$this->assertEquals("value",isset($a->getAnnotation("Class")->var));

		$this->assertTrue($a->getAnnotation("Never") == NULL);

		$this->assertFalse($a->getAnnotation("Private", "private") == NULL);
		$this->assertTrue(isset($a->getAnnotation("Private", "private")->var));
		$this->assertEquals("value",isset($a->getAnnotation("Private", "private")->var));

		$this->assertTrue($a->getAnnotation("Never", "private") == NULL);

		$this->assertFalse($a->getAnnotation("Protected", "protected") == NULL);
		$this->assertTrue(isset($a->getAnnotation("Protected", "protected")->var));
		$this->assertEquals("value",isset($a->getAnnotation("Protected", "protected")->var));

		$this->assertTrue($a->getAnnotation("Never", "protected") == NULL);

		$this->assertFalse($a->getAnnotation("Public", "public") == NULL);
		$this->assertTrue(isset($a->getAnnotation("Public", "public")->var));
		$this->assertEquals("value",isset($a->getAnnotation("Public", "public")->var));

		$this->assertTrue($a->getAnnotation("Never", "protected") == NULL);
	}

	public function testGetMethods() {
		$a = new TestObject();
		$this->assertFalse(in_array("privateMethod", $a->getMethods()));
		$this->assertFalse(in_array("protectedMethod", $a->getMethods()));
		$this->assertTrue(in_array("publicMethod", $a->getMethods()));
	}

	public function testGetVars() {
		$a = new TestObject();
		$this->assertEquals(FALSE, in_array("private", $a->getVars()));
		//$this->assertEquals(FALSE, in_array("protected", $a->getVars()));
		$this->assertTrue(in_array("public", $a->getVars()));
	}

}

/**
 * @Class(var=value)
 */
class TestObject extends EskymoObject
{
	/**
	 * @Private(var=value)
	 */
	private		$private;

	/**
	 * @Protected(var=value)
	 */
	protected	$protected;
	/**
	 * @Public(var=value)
	 */
	public		$public;

	private function privateMethod() {}

	protected function protectedMethod() {}

	public function publicMethod() {}
}
