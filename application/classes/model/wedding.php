<?php defined('SYSPATH') or die('No direct script access.');

class Model_Wedding extends ORM {
	protected $_created_column = array('column'=>'created', 'format'=>TRUE);
	
	protected $_has_many = array(
		'tables' => array(),
	);
	
	public function total_guests()
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->total_guests() must be called on loaded object');
			
		return DB::select(array('count("guests.id")', 'count'))
			->from('weddings', 'tables', 'guests')
			->where('weddings.id', '=', 'tables.wedding_id')
			->and_where('tables.id', '=', 'guests.table_id')
			->and_where('weddings.alias', '=', $this->alias)
			->execute()
			->get('count');
	}
	
	public function total_checkins()
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->total_checkins() must be called on loaded object');
		
		return DB::select(array('count("guests.id")', 'count'))
			->from('weddings', 'tables', 'guests')
			->where('weddings.id', '=', 'tables.wedding_id')
			->and_where('tables.id', '=', 'guests.table_id')
			->and_where('guests.has_arrived', '=', 1)
			->and_where('weddings.alias', '=', $this->alias)
			->execute()
			->get('count');
	}
	
	/**
	 * Initialise thie model. Load model by alias.
	 * @return ORM
	 */
	public function load_alias($alias)
	{
		$this->where('alias', '=', $alias)
			->find();
			
		return $this;
	}
	
	public function new_wedding($num_tables) 
	{
		if ($this->loaded())
			throw new Kohana_Exception('Model_Wedding->new_wedding() cannot be called on loaded object');
		
		// Generate unique alias
		while (TRUE)
		{
			$alias = $this->_random_chars(5);
			$duplicate = DB::select('id')
				->from($this->table_name())
				->where('alias', '=', $alias)
				->limit(1)
				->execute()
				->count();
			
			if ( ! $duplicate)
				break;
		}
		
		// Create new wedding record, and tables
		$this->clear();
		$this->alias = $alias;
		if ( ! $this->save())
			throw new Kohana_Exception('Model_Wedding->new_wedding cannot create new wedding record.');
		
		// Create and attach tables
		$pk = $this->pk();
		$table = ORM::factory('table');
		try 
		{
			for ($i=1;$i<=$num_tables;$i++) 
			{
				$table->name = "Table $i";
				$table->wedding_id = $pk;
				if ( ! $table->save())
				{
					throw new Kohana_Exception('Model_Wedding->new_wedding unable to attach new table.');
				}
				$table->clear();
			}
		} 
		catch (Exception $e)
		{
			// Undo everything
			$this->delete();
			throw $e;
		}
		
		return $this;
	}
	
	private function _random_chars($length)
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$last_index = strlen($chars) - 1;
		$alias = '';
		while ($length)
		{
			$alias .= substr($chars, mt_rand(0, $last_index), 1);
			$length--;
		}
		
		return $alias;
	}
}
