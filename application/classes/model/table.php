<?php defined('SYSPATH') or die('No direct script access.');

class Model_Table extends ORM {
	protected $_has_many = array(
		'guests' => array(),
	);
	
	public function all_tables() 
	{
		return $this->find_all();
	}
	
	public function all_guests() 
	{
		if (! $this->loaded())
			throw new Kohana_Exception('Method all_guests() cannot be called on loaded objects');
			
		return $this->guests->find_all();
	}
	
	public function delete()
	{
		if (! $this->loaded())
			throw new Kohana_Exception('Method add_guest() cannot be called on loaded objects');
		
		ORM::factory('guest')->remove_from_table($this->pk());
		
		return parent::delete();
	}
	
	/**
	 * Add a guest to this table.
	 * 
	 * @param Model_Guest $guest
	 */
	public function add_guest(Model_Guest $guest)
	{
		if (! $this->loaded())
			throw new Kohana_Exception('Method add_guest() cannot be called on loaded objects');
		
		$guest->table_id = $this->pk();
		return $guest->save();
	}
	
	/**
	 * Remove a guest from this table.
	 * 
	 * @param int|Model_Guest If int is passed as agrument, it is assumed
	 *  to be Model_Guest's PK.
	 */
	public function remove_guest($guest)
	{
		if ( ! is_object($guest))
		{
			$guest = ORM::factory('guest', $guest);
		}
		
		if ( ! $guest->loaded())
			throw new Kohana_Exception('Invalid guest model.');
		
		return $guest->delete();
	}
}
