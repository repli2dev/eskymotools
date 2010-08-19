<?php
class EntityManagerTest extends EskymoTestCase {

	/** @var IEntityFactory */
	private $factory;

	public function setUp() {
		$this->factory = new TestEntityFactory();
		EntityManager::getManager("TestEntity")->reset();
	}

	public function testRegisterLoaded() {
		$entity = $this->factory->createEmpty();
		$entity->loadDataFromArray(array(
			"translated_id" => 1,
			"name"			=> "sdkvhjdfsv"
		));

		$this->assertEquals(array(1), EntityManager::getManager("TestEntity")->getIds());
		$this->assertEquals($entity->name, EntityManager::getManager("TestEntity")->get(1)->name);
	}

	public function testRegisterPersisted() {
		$entity = $this->factory->createEmpty();
		$entity->name = "hatlapatla";
		$entity->persist();

		$this->assertEquals(array(1), EntityManager::getManager("TestEntity")->getIds());
		$this->assertEquals($entity->name, EntityManager::getManager("TestEntity")->get(1)->name);
	}

	public function testUnregisterDeleted() {
		$entity = $this->factory->createEmpty();
		$entity->persist();
		$entity->delete();

		$this->assertEquals(array(), EntityManager::getManager("TestEntity")->getIds());
	}

}