<?php
class EntityManager
{

	private $entityClass;

	private static $managers = array();

	private $registered = array();

	private function  __construct($entityClass) {
		if (empty($entityClass)) {
			throw new NullPointerException("The parameter [entityClass] is empty.");
		}
		$this->entityClass = $entityClass;
	}

	/**
	 * It returns the entity with specified ID
	 *
	 * @return IEntity
	 * @throws InvalidStateException if entity with the ID is not registered.
	 */
	public function &get($id) {
		if (!$this->isRegistered($id)) {
			throw new InvalidStateException("The entity with id [$id] is not registered.");
		}
		return $this->registered[$id];
	}

	/**
	 * It return IDs of registered entities
	 *
	 * @return array
	 */
	public function getIds() {
		return array_keys($this->registered);
	}

	/**
	 * It returns manager by specified name
	 * 
	 * @return EntityManager
	 */
	public static function getManager($entityClass) {
		if (!isset(self::$managers[$entityClass])) {
			self::$managers[$entityClass] = new EntityManager($entityClass);
		}
		return self::$managers[$entityClass];
	}

	/**
	 * It checks whether an entity with the is registered.
	 *
	 * @param	mixed	$id
	 * @return	bool
	 */
	public function isRegistered($id) {
		return isset($this->registered[$id]);
	}

	/**
	 * It registers the entity
	 *
	 * @param IEnity $entity
	 * @throws InvalidArgumentException if the entity has no ID
	 * @throws InvalidArgumentException if the entity is not instance of specified class.
	 * @throws InvalidStateException if the entity is already registered.
	 */
	public function register(IEntity &$entity) {
		if ($entity->getId() == NULL) {
			throw new InvalidArgumentException("The entity has no ID, so it can not be registered.");
		}
		if ($this->isRegistered($entity->getId())) {
			throw new InvalidStateException("The entity with id [".$entity->getId()."] is already registered.");
		}
		$this->registered[$entity->getId()] = $entity;
	}

	/**
	 * It resets content of entity manager
	 */
	public function reset() {
		$this->registered = array();
	}

	/**
	 * It unregister the entity
	 *
	 * @param IEntity $entity
	 * @throws InvalidArgumentException if the entity has no ID
	 * @throws InvalidArgumentException if the entity is not instance of specified class.
	 * @throws InvalidStateException if the entity is not registered.
	 */
	public function unregister(IEntity &$entity) {
		if ($entity->getId() == NULL) {
			throw new InvalidStateException("The entity has no ID, so it can not be unregistered.");
		}
		if (!$this->isRegistered($entity->getId())) {
			throw new InvalidStateException("The entity with id [$id] is not registered.");
		}
		if (get_class($entity) != $this->entityClass) {
			throw new InvalidArgumentException("The entity has to instance of [$this->entityClass], [" . get_class($entity) . "] given.");
		}
		unset($this->registered[$entity->getId()]);
	}
	
}