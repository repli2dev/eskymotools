<?php
class CallbackListenerTest extends EskymoTestCase
{

	/** @var IEvent */
	private $event;

	protected function setUp() {
		$this->event = new CallbeckListenereTestEvent();
	}

	public function testCallback() {
		$class = new CallbackListenerTestClass();
		$listener = new CallbackListener(array($class, "foo"));
		$this->assertTrue($listener->listen($this->event));
	}

	public function testStaticCallback() {
		$listener = new CallbackListener(array("CallbackListenerTestClass", "staticFoo"));
		$this->assertTrue($listener->listen($this->event));
	}

	/** @TestThrow(InvalidArgumentException) */
	public function testIllegalCallback() {
		$listener = new CallbackListener(array("NotExistingClass","notExistingMethod"));
	}

}

class CallbackListenerTestClass
{

	public static function staticFoo(IEvent $e) {
		return TRUE;
	}

	public function foo(IEvent $e) {
		return TRUE;
	}

}


class CallbeckListenereTestEvent extends EskymoObject implements IEvent
{

	

}