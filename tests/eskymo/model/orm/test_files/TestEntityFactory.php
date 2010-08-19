<?php
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
