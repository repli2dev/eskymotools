<?php
class EntityTest extends EskymoTestCase {

	/** @var IEntityFactory */
	private $factory;

	public function setUp() {
		$this->factory = new TestEntityFactory();
	}

	public function testGetIdName() {
		$this->assertEquals("translated_id", $this->factory->createEmpty()->getIdName());
	}

	public function testGetAttributeNames() {
		$entity = $this->factory->createEmpty();
		$attributes = $entity->getAttributeNames();
		$this->assertEquals(1, sizeof($attributes));
		$this->assertEquals("name", ExtraArray::firstValue($attributes));

		$attributes = $entity->getAttributeNames("Super");
		$this->assertEquals(1, sizeof($attributes));
		$this->assertEquals("super_name", ExtraArray::firstValue($attributes));

		$attributes = $entity->getAttributeNames("Never");
		$this->assertEquals(1, sizeof($attributes));
		$this->assertEquals("translated_name", ExtraArray::firstValue($attributes));
	}

	public function testGetAttributeType() {
		$entity = $this->factory->createEmpty();
		$type = $entity->getAttributeType("name");
		$this->assertFalse(empty($type));
		$this->assertFalse(empty($type->name));
		$this->assertEquals("enum", $type->name);
		$this->assertFalse(empty($type->values));
		$this->assertEquals(2, sizeof($type->values));
	}

	public function testLifeCycle() {
		$entity = $this->factory->createEmpty();
		$this->assertEquals(IEntity::STATE_NEW, $entity->getState());
		$entity->persist();
		$this->assertEquals(IEntity::STATE_PERSISTED, $entity->getState());
		$entity->name = "aaa";
		$this->assertEquals(IEntity::STATE_MODIFIED, $entity->getState());
		$entity->persist();
		$this->assertEquals(IEntity::STATE_PERSISTED, $entity->getState());
		$entity->name = "aaa";
		$this->assertEquals(IEntity::STATE_PERSISTED, $entity->getState());
		$entity->delete();
		$this->assertEquals(IEntity::STATE_DELETED, $entity->getState());
	}

	public function testGetData() {
		$entity = $this->factory->createEmpty();
		$input = array(
			"translated_id"		=> 1,
			"super_name"		=> "super_name",
		);
		$entity->loadDataFromArray($input, "Super");
		// TODO
		$output = $entity->getData();
		$this->assertEquals(1,	sizeof($output));
		$this->assertTrue(isset($output["name"]));
		$this->assertEquals("super_name", $output["name"]);

		$output = $entity->getData("Super");
		$this->assertEquals(1,	sizeof($output));
		$this->assertTrue(isset($output["super_name"]));
		$this->assertEquals("super_name", $output["super_name"]);
	}

	/**
	 * @TestThrow(InvalidStateException)
	 */
	public function testLoadFromArrayTwoCalling() {
		$entity = $this->factory->createEmpty();
		$input = array(
			"translated_id"		=> 1
		);
		$entity->loadDataFromArray($input);
		$entity->loadDataFromArray($input);
	}

	public function testLoadFromArray() {
		$entity = $this->factory->createEmpty();
		$input = array(
			"translated_id"		=> 1,
			"super_name"		=> "super_name",
			"translated_name"	=> "translated_name"
		);
		$entity->loadDataFromArray($input);
		$this->assertEquals(1, $entity->getId());
		$this->assertNull($entity->name);
		$entity = $this->factory->createEmpty();
		$entity->loadDataFromArray($input, "Super");
		$this->assertEquals(1, $entity->getId());
		$this->assertEquals("super_name", $entity->name);
		$entity = $this->factory->createEmpty();
		$entity->loadDataFromArray($input, "Never");
		$this->assertEquals(1, $entity->getId());
		$this->assertEquals("translated_name", $entity->name);
	}

	public function testModifiedAfterLoadPersisted() {
		$input = array(
			"translated_id"	=> 1,
			"name"			=> "sdkvkdf",
		);
		$entity = $this->factory->createEmpty();
		$entity->loadDataFromArray($input);
		$this->assertEquals(array(), $entity->getData(NULL, IEntity::DATA_MODIFIED));
		$this->assertEquals(array("name" => $entity->name), $entity->getData(NULL, IEntity::DATA_NOT_MODIFIED));
	}

	public function testModifiedAfterChange() {
		$entity = $this->factory->createEmpty();
		$this->assertEquals(array(), $entity->getData(NULL, IEntity::DATA_MODIFIED));
		$entity->name = "sdvkhdbkv";
		$this->assertEquals(array("name" => $entity->name), $entity->getData(NULL, IEntity::DATA_MODIFIED));
		$this->assertEquals(array(), $entity->getData(NULL, IEntity::DATA_NOT_MODIFIED));
	}

	/** @TestThrow(MemberAccessException) */
	public function testNotDefinedVarRead() {
		$entity = $this->factory->createEmpty();
		$a = $entity->notdefined;
	}

	/** @TestThrow(MemberAccessException) */
	public function testNotDefinedVarWrite() {
		$entity = $this->factory->createEmpty();
		$entity->notdefined = "aaa";
	}

	public function testPersistId() {
		$entity = $this->factory->createEmpty()->persist();
		$this->assertEquals(1, $entity->getId());
	}

}

