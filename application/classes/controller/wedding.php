<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Wedding extends Controller_Template {
	protected $wedding = FALSE;

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
		// TODO
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
			$this->flash_err_msg("Please create a new wedding checklist.");
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
				'redirect' => Route::url('table_index', array('alias'=>$wedding->alias)),
			)));
		return;
	}
} // End Main
