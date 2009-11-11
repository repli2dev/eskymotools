<?php
class EntityTest extends EskymoTestCase {

	/** @var IEntityFactory */
	private $factory;

	public function setUp() {
		$this->factory = new TestEntityFactory();
	}

	public function testLifeCycle() {
		$entity = $this->factory->createEmpty();
		$this->assertEquals(IEntity::STATE_NEW, $entity->getState());
		$entity->persist();
		$this->assertEquals(IEntity::STATE_PERSISTED, $entity->getState());
		$entity->setName("aaa");
		$this->assertEquals(IEntity::STATE_MODIFIED, $entity->getState());
		$entity->persist();
		$this->assertEquals(IEntity::STATE_PERSISTED, $entity->getState());
		$entity->setName("aaa");
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

	public function testPersistId() {
		$entity = $this->factory->createEmpty()->persist();
		$this->assertEquals(1, $entity->getId());
	}

}



class TestEntityFactory extends AEntityFactory
{

	public function createEmpty() {
		return new TestEntity($this);
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
class TestEntity extends AEntity
{

	/**
	 * @Translate(translated_name)
	 * @Super(super_name)
	 */
	public $name;

}