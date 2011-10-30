<?php defined('SYSPATH') or die('No direct script access.');

class Model_Wedding extends ORM {
	protected $_created_column = array('column'=>'created', 'format'=>TRUE);

	protected $_has_many = array(
		'tables' => array(),
	);

	/**
	 * Search guests by name.
	 * @param string $query Part of guest name.
	 * @return array Array of Model_Guest.
	 */
	public function search_guests($query)
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->search_guests() must be called on loaded object');

		return ORM::factory('guest')
			->join('tables', 'LEFT')
			->on('guests.table_id', '=', 'tables.id')
			->where('tables.wedding_id', '=', $this->id)
			->where('guests.name', 'like', "%$query%")
			->find_all();
	}

	/**
	 * Create new guest, attach to a specified table.
	 * @param String $guest_name
	 * @param Model_Table | int (table ID)
	 * @return Model_Guest
	 */
	public function new_guest($guest_name, $table)
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->new_guest() must be called on loaded object');

		// Check if table belongs to wedding
		if ( ! is_object($table))
		{
			$table = ORM::factory('table', $table);
		}
		if (( ! $table->loaded()) || ($table->wedding_id != $this->id))
			throw new Kohana_Exception('Model_Wedding->new_guest() invalid table parameter');

		$guest = ORM::factory('guest');
		$guest->name = $guest_name;
		$guest->has_arrived = 0;
		$guest->table_id = $table->id;
		return $guest->save();
	}

	/**
	 * @param Model_Guest | int (guest ID)
	 */
	public function checkin_guest($guest)
	{
		if ( ! is_object($guest))
		{
			$guest = ORM::factory('guest', $guest);
		}
		if ( ! $guest->loaded())
			throw new Kohana_Exception('Model_Wedding->checkin_guest() invalid guest parameter');

		if ($guest->table->wedding_id != $this->id)
			throw new Kohana_Exception('Model_Wedding->checkin_guest() guest does not belong to this wedding');

		$guest->has_arrived = 1;
		return $guest->save();
	}

	/**
	 * @param Model_Guest | int (guest ID)
	 */
	public function checkout_guest($guest)
	{
		if ( ! is_object($guest))
		{
			$guest = ORM::factory('guest', $guest);
		}
		if ( ! $guest->loaded())
			throw new Kohana_Exception('Model_Wedding->checkout_guest() invalid guest parameter');

		if ($guest->table->wedding_id != $this->id)
			throw new Kohana_Exception('Model_Wedding->checkout_guest() guest does not belong to this wedding');

		$guest->has_arrived = 0;
		return $guest->save();
	}

	/**
	 * Return table model based on $table_id.
	 * The method must be used on loaded Wedding model, it makes sure
	 * the loaded table belongs to this model.
	 *
	 * @prarm int $table_id
	 * @return Model_Table
	 */
	public function get_table($table_id)
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->get_table() must be called on loaded object');

		return $this->tables->where('id', '=', $table_id)->find();
	}

	public function total_guests()
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->total_guests() must be called on loaded object');

		return (String) DB::select(array('count("guests.id")', 'count'))
			->from('guests')
			->join('tables', 'LEFT')
			->on('guests.table_id', '=', 'tables.id')
			->where('tables.wedding_id', '=', $this->id)
			->execute()
			->get('count');
	}

	public function total_checkins()
	{
		if ( ! $this->loaded())
			throw new Kohana_Exception('Model_Wedding->total_checkins() must be called on loaded object');

		return (String) DB::select(array('count("guests.id")', 'count'))
			->from('guests')
			->join('tables', 'LEFT')
			->on('guests.table_id', '=', 'tables.id')
			->where('tables.wedding_id', '=', $this->id)
			->and_where('guests.has_arrived', '=', 1)
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
