<?php defined('SYSPATH') or die('No direct script access.');

class Model_Guest extends ORM {
	protected $_belongs_to = array(
		'table' => array(),
	);
	
	public function has_arrived() {
		if (! $this->loaded())
			throw new Kohana_Exception('Cannot invoke has_arrived because model is not loaded.');
			
		return $this->has_arrived != 0;
	}
	
	public function search_by_name($name)
	{
		if ($this->loaded())
			throw new Kohana_Exception('Method all_guests() cannot be called on loaded objects');
			
		return $this->where('name', 'like', "%$name%")
			->order_by('name', 'ASC')
			->find_all();
	}
	
	/**
	 * Find other guests in the same table.
	 * 
	 * @params Model_Guest|int Either the loaded guest or guest ID.
	 */
	public function same_table_with($guest)
	{
		if ($this->loaded())
			throw new Kohana_Exception('Method same_table_guests() cannot be called on loaded objects');
		
		if ( ! is_object($guest))
		{
			$guest = ORM::factory('guest', $guest);
		}
			
		return $this->where('table_id', '=', $guest->table_id)
			->and_where('id', '<>', $guest->pk())
			->order_by('name', 'ASC')
			->find_all();
	}
	
	/**
	 * Remove guests from a table.
	 * 
	 * @param int $table_id
	 */
	public function remove_from_table($table_id)
	{
		DB::delete($this->_table_name)
			->where('table_id', '=', $table_id)
			->execute($this->_db);
		
		return $this->clear();
	}
}
