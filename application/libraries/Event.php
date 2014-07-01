<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package CI Bootstrap
 * @author Chris Pynegar <chris@chrispynegar.co.uk>
 */
class Event {

	/**
	 * Stored events
	 *
	 * @var array
	 */
	protected $events = array();

	/**
	 * Listen for an event
	 */
	public function on($event, $class, $method)
	{
		// Create the event if it doesn't exist
		if ( ! isset($this->events[$event]))
		{
			$this->events[$event] = array();
		}

		// Store the event data
		array_push($this->events[$event, compact('class', 'method'));
	}

	/**
	 * Trigger an event
	 */
	public function trigger($event)
	{
		// Do nothing if the event doesn't exist
		if ( ! isset($this->events[$event])) {
			return;
		}

		// Fire the event callbacks
		foreach($this->events as $callback)
		{
			$class = $callback['class'];
			call_user_func_array(array($this->$class, $callback['method']), array());
		}
	}

}

/* End of file Event.php */
/* Location: ./application/libraries/Event.php */