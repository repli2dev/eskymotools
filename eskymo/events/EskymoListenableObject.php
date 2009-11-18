<?php

abstract class EskymoListenableObject extends EskymoObject implements IListenable
{

	/** @var array */
	private $listeners = array();

	/**
	 * It adds a new listner which listens to the specified event type
	 *
	 * @param string $type Event type
	 * @param IListener $listener
	 */
	protected final function addListener($type, IListener &$listener) {
		if (empty($type)) {
			throw new NullPointerException("type");
		}
		if (!isset($this->listeners[$type])) {
			$this->listeners[$type] = array();
		}
		$this->listeners[$listeners][] = $listener;
	}

	/**
	 * It calls all listeners which listen to the specified
	 */
	protected final function callListeners($type, IEvent &$event) {
		if (empty($type)) {
			throw new NullPointerException("type");
		}
		foreach($this->listeners AS $listener) {
			$listener->listern($event);
		}
	}

}
