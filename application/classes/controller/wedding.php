<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Wedding extends Controller_Template {
	protected $wedding = FALSE;

	/**
	 * Search guest by name
	 */
	public function action_search_guest()
	{
		// Input validation
		$query = trim(Arr::get($_POST, 'query', ''));

		if ($query == '')
		{
			$this->auto_render = FALSE;
			$this->flash_err_msg('Please specify a guest name.');
			return $this->request->redirect('wedding/index');
		}

		$this->view->query = $query;
	}

	public function action_ajax_search_guest()
	{
		$this->auto_render = FALSE;
		$this->_init();

		$ret = array('guests'=>array());
		// Input validation
		$query = trim(Arr::get($_POST, 'query', ''));

		if ($query == '')
			return $this->response->body(json_encode($ret));

		$guests = $this->wedding->search_guests($query);

		foreach ($guests as $guest)
		{
			$ret['guests'][] = array(
				'guest_id' => $guest->id,
				'name' => $guest->name,
				'has_arrived' => $guest->has_arrived,
				'table_name' => $guest->table->name,
				'table_id' => $guest->table->id,
			);
		}

		return $this->response->body(json_encode($ret));
	}

	public function action_add_guest()
	{
		$this->auto_render = FALSE;

		$this->_init();
		$table_id = $this->request->param('id', '');
		// Verify table ID
		$table = $this->wedding->get_table($table_id);
		if ( ! $table->loaded())
		{
			$this->flash_err_msg("Invalid table ID, $table_id");
			return $this->request->redirect('wedding/index');
		}

		// Input validation
		$guest_name = trim(Arr::get($_POST, 'guest_name', ''));
		if ($guest_name == '')
		{
			$this->flash_err_msg('Please specify a guest name.');
			return $this->request->redirect($this->request->referrer());
		}

		$this->wedding->new_guest($guest_name, $table);
		return $this->request->redirect('wedding/table/'.$table_id);
	}

	public function action_guest_checkin()
	{
		$this->auto_render = FALSE;

		$this->_init();
		$guest_id = $this->request->param('id', FALSE);
		if ( ! $guest_id)
		{
			$this->flash_err_msg('Missing guest ID.');
			return $this->request->redirect('wedding/index');
		}

		$guest = $this->wedding->checkin_guest($guest_id);
		return $this->request->redirect('wedding/table/'.$guest->table_id);
	}

	public function action_guest_checkout()
	{
		$this->auto_render = FALSE;

		$this->_init();
		$guest_id = $this->request->param('id', FALSE);
		if ( ! $guest_id)
		{
			$this->flash_err_msg('Missing guest ID.');
			return $this->request->redirect('wedding/index');
		}

		$guest = $this->wedding->checkout_guest($guest_id);
		return $this->request->redirect('wedding/table/'.$guest->table_id);
	}

	/**
	 * Create new table
	 */
	public function action_new_table()
	{
		$this->_init();

		// Input validation
		$table_name = trim(Arr::get($_POST, 'table_name', ''));
		if ($table_name == '')
		{
			$this->flash_err_msg('Please specify a table name.');
			$this->auto_render = FALSE;
			return $this->request->redirect('wedding/index');
		}

		// Create table
		$table = ORM::factory('table');
		$table->wedding_id = $this->wedding->pk();
		$table->name = $table_name;
		$table->save();

		$this->auto_render = FALSE;
		return $this->request->redirect('wedding/table/'.$table->pk());
	}

	public function action_table()
	{
		$this->_init();

		// Verify table ID
		$table = $this->wedding->get_table($this->request->param('id', ''));
		if ( ! $table->loaded())
		{
			$this->flash_err_msg('You were trying to access an invalid Table page.');
			$this->auto_render = FALSE;
			return $this->request->redirect('wedding/index');
		}

		$this->view->table_id = $table->id;
	}

	/**
	 * @return JSON table information.
	 */
	public function action_ajax_table()
	{
		$this->_init();
		$table_id = $this->request->param('id', '');
		// Verify table ID
		$table = $this->wedding->get_table($table_id);
		if ( ! $table->loaded())
		{
			return json_encode(array('err' => "Invalid table ID, '$table_id'"));
		}

		// return table+guests info in JSON
		$array = array(
			'table_id' => $table->id,
			'name' => $table->name,
			'wedding_id' => $table->wedding_id,
			'num_guests' => $table->num_guests(),
			'num_checkins' => $table->num_checkins(),
			'guests' => array(),
		);
		foreach ($table->guests->find_all() as $guest)
		{
			$array['guests'][] = array(
				'guest_id' => $guest->id,
				'table_id' => $guest->table_id,
				'name' => $guest->name,
				'has_arrived' => $guest->has_arrived,
			);
		}

		$this->auto_render = FALSE;
		$this->response->body(json_encode($array));
	}

	public function action_index()
	{
		$this->_init();
		$this->view->wedding = $this->wedding;
		$this->view->tables = $this->wedding->tables->find_all();
	}

	/**
	 * Check if user has logged in. Load $this->wedding based on saved
	 * alias token. Redirect if user has not logged in.
	 */
	private function _init()
	{
		// Retrieve alias from session/cookie
		$alias = $this->_get_alias();
		if ($alias == FALSE)
		{
			$this->auto_render = FALSE;
			return $this->request->redirect('main');
		}

		// Verify alias
		$this->wedding = ORM::factory('wedding')->load_alias($alias);

		$session = Session::instance();
		if ( ! $this->wedding->loaded())
		{
			$this->_logout();
			$this->flash_err_msg("'$alias' is an invalid wedding alias ID.");
			$this->auto_render = FALSE;
			return $this->request->redirect('main');
		}

		// Continue login status
		$this->_login($alias);
	}

	/**
	 * After login, alias will be saved in session.
	 * @return string Saved alias.
	 * @return FALSE User has not logged in yet.
	 */
	private function _get_alias()
	{
		$alias = $this->request->param('alias', FALSE);
		if ($alias === FALSE) $alias = Cookie::get('alias', FALSE);

		return $alias;
	}

	private function _login($alias)
	{
		Session::instance()->set('alias', $alias);
		Cookie::set('alias', $alias, 31556926); // Expire in 1 year
	}

	private function _logout()
	{
		Session::instance()->delete('alias');
		Cookie::delete('alias');
	}

	public function action_ajax_new()
	{
		$this->auto_render = FALSE;

		if ( ! isset($_POST['num_tables'])) {
			$this->response->body(json_encode(array(
				'status' => 'ERROR',
				'err_msg' => 'Please indicate number of tables.'
			)));
			return;
		}

		$num_tables = (int)Arr::get($_POST, 'num_tables', 0);
		if ($num_tables < 1)
		{
			$this->response->body(json_encode(array(
				'status' => 'ERROR',
				'err_msg' => 'Please indicate number of tables.'
			)));
			return;
		}

		$wedding = ORM::factory('wedding')->new_wedding($num_tables);
		$this->response->body(json_encode(array(
				'status' => 'SUCCESS',
				'redirect' => Route::url('wedding_index', array('alias'=>$wedding->alias), TRUE),
			)));
		return;
	}
} // End Main
