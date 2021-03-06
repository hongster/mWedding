<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Wedding extends Controller_Template {
	protected $wedding = FALSE;

	public function action_tag()
	{
		$this->_init();
		$tag = trim($this->request->param('tag', ''));
		if ($tag == '')
			return $this->request->redirect('wedding/index');
		
		$guests = $this->wedding->search_tag($tag);
		$this->view->tag = $tag;
		$this->view->guests = $guests;
	}
	
	public function action_delete_guest()
	{
		$this->auto_render = FALSE;
		$this->_init();
		$guest_id = $this->request->param('id', '');
		$guest = $this->wedding->get_guest($guest_id);
		if ($guest->loaded()) {
			$guest->delete();
		}
		
		return $this->request->redirect($this->request->referrer());
	}
	
	public function action_delete_table()
	{
		$this->auto_render = FALSE;
		$this->_init();
		$table_id = $this->request->param('id', '');
		$table = $this->wedding->get_table($table_id);
		if ($table->loaded()) {
			$table->delete();
		}
		
		return $this->request->redirect(URL::site('wedding/index', TRUE));
	}
	
	/**
	 * Change table name.
	 */
	public function action_ajax_update_table_name()
	{
		$this->auto_render = FALSE;
		
		$this->_init();
		$table_id = $this->request->param('id', '');
		// Verify table ID
		$table = $this->wedding->get_table($table_id);
		if ( ! $table->loaded())
			throw new Kohana_Exception('Invalid table ID:'.$table_id);

		$table_name = trim(Arr::get($_POST, 'table_name', ''));
		if ($table_name == '')
			return $this->response->body($table->name);

		$table->name = $table_name;
		$table->save();

		return $this->response->body($table_name);
	}
	
	/**
	 * Change guest name.
	 */
	public function action_ajax_update_guest_name()
	{
		$this->auto_render = FALSE;
		
		$this->_init();
		
		// Verify guest ID
		$guest_id = $this->request->param('id', '');
		$guest = $this->wedding->get_guest($guest_id);
		if ( ! $guest->loaded())
			throw new Kohana_Exception('Invalid guest ID:'.$guest_id);
		
		$guest_name = trim(Arr::get($_POST, 'guest_name', ''));
		if ($guest_name == '')
		{
			$return = View_Helper::tagalizer($guest->name, URL::site('wedding/tag/:tag', TRUE));
		}
		else {
			$guest->name = $guest_name;
			$guest->save();
			$return = View_Helper::tagalizer($guest_name, URL::site('wedding/tag/:tag', TRUE));
		}
		
		return $this->response->body($return);
	}

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

		$this->view->table = $table;
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

	/**
	 * Login using $alias (wedding code).
	 * $alias can be passed via $_POST or URL (/wedding/login/WED_CODE)
	 */
	public function action_login()
	{
		$this->auto_render = FALSE;
		
		// Try POST then URL
		$alias = isset($_POST['alias'])
			? $_POST['alias']
			: $this->request->param('alias', FALSE);
		
		// Missing alias
		if ( ! $alias)
		{
			$this->_logout();
			$this->flash_err_msg("The wedding code is invalid/missing.");
			return $this->request->redirect('main/get_started');
		}
		
		// Verify alias
		$this->wedding = ORM::factory('wedding')->load_alias($alias);
		if ( ! $this->wedding->loaded())
		{
			$this->_logout();
			$this->flash_err_msg("'$alias' is an invalid wedding code.");
			$this->auto_render = FALSE;
			return $this->request->redirect('main/get_started');
		}

		// Save login status
		$this->_login($alias);
		return $this->request->redirect('wedding');
	}
	
	public function action_logout()
	{
		$this->auto_render = FALSE;
		$this->_logout();
		return $this->request->redirect();
	}

	/**
	 * Show stats for all tables.
	 */
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
		// Retrieve alias from cookie
		if (($alias = Cookie::get('alias', FALSE)) == FALSE)
		{
			$this->auto_render = FALSE;
			return $this->request->redirect();
		}

		// Verify alias
		$this->wedding = ORM::factory('wedding')->load_alias($alias);
		if ( ! $this->wedding->loaded())
		{
			$this->_logout();
			$this->flash_err_msg("'$alias' is an invalid wedding code.");
			$this->auto_render = FALSE;
			return $this->request->redirect('main/get_started');
		}

		// Continue login status
		$this->_login($alias);
	}

	private function _login($alias)
	{
		Session::instance()->set('alias', $alias);
		Cookie::set('alias', $alias, time() + 31556926); // Expire in 1 year
	}

	private function _logout()
	{
		Cookie::delete('alias');
		Session::instance()->destroy();
	}

	// XXX still needed? anyone using?
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
