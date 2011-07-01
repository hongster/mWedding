<?php defined('SYSPATH') or die('No direct script access.');

class Controller_API_Guest extends Controller_API {
	/**
	 * Search by name. Result sorted by name.
	 * 
	 * @param string $name
	 */
	public function action_search($name)
	{
		$this->view->guests = ORM::factory('guest')->search_by_name($name);
	}
	
	/**
	 * Show guest table name, and other guests in same table.
	 */
	public function action_info($guest_id)
	{
		$guest = ORM::factory('guest', $guest_id);
		
		$this->view->guest = $guest;
		$this->view->other_guests = ORM::factory('guest')->same_table_with($guest);
	}

} // End Controller_API_Guest
