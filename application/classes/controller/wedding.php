<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Wedding extends Controller_Template {

	public function action_index()
	{
		// Login.
		$alias = $this->_get_alias();
		if ($alias == FALSE)
		{
			$session->set('err_msg', "Please create a new wedding checklist.");
			$this->auto_render = FALSE;
			$this->request->redirect('main');
		}
		
		$wedding = ORM::factory('wedding')->load_alias($alias);
		
		$session = Session::instance();
		if ( ! $wedding->loaded())
		{
			$this->_logout();
			$session->set('err_msg', "'$alias' is an invalid wedding alias ID.");
			$this->auto_render = FALSE;
			$this->request->redirect('main');
		}
		
		// Login session
		$this->_login($alias);
		$this->view->wedding = $wedding;
		$this->view->tables = $wedding->tables->find_all();
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
		Session::instance()->restart();
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
