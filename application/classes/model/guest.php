<?php defined('SYSPATH') or die('No direct script access.');

class Model_Guest extends ORM {
	protected $_belongs_to = array(
		'table' => array(),
	);
	
	public function has_arrived() {
		if (! $this->loaded())
			throw new Kohana_Exception('Method all_guests() cannot be called on loaded objects');
			
		return $this->has_arrived != 0;
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
