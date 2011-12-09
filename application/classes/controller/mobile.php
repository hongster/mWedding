<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mobile webpages
 */
class Controller_Mobile extends Controller_Template {
	public $template = 'mobile';
	private $wedding; // Setup in init

	public function action_index()
	{
		// Precedence: url > post > cookie
		if ( ! ($alias = $this->request->param('id', FALSE)))
		{
			$alias = Arr::get($_POST, 'alias', FALSE);
		}
		
		if ($alias !== FALSE)
		{
			$this->auto_render = FALSE;
			$this->_login($alias);
			return $this->request->redirect('mobile/main');
		}
		
		$this->template->title = 'Login';
	}
	
	public function action_main()
	{
		$this->_init();
		$this->template->title = 'Overview';
		$this->view->total_guests = $this->wedding->total_guests();
		$this->view->total_checkins = $this->wedding->total_checkins();
		$this->view->tables = $this->wedding->tables->find_all();
	}
	
	public function action_table()
	{
		$this->_init();

		// Verify table ID
		$table = $this->wedding->get_table($this->request->param('id', ''));
		if ( ! $table->loaded())
		{
			$this->auto_render = FALSE;
			return $this->request->redirect('mobile/main');
		}
		
		$this->template->title = $table->name;
		$this->view->table = $table;
	}
	
	public function action_guest_checkin()
	{
		$this->auto_render = FALSE;

		$this->_init();
		$guest_id = $this->request->param('id', FALSE);
		if ( ! $guest_id)
		{
			return $this->request->redirect('mobile/main');
		}

		$guest = $this->wedding->checkin_guest($guest_id);
		return $this->request->redirect('mobile/table/'.$guest->table_id);
	}
	
	public function action_guest_checkout()
	{
		$this->auto_render = FALSE;

		$this->_init();
		$guest_id = $this->request->param('id', FALSE);
		if ( ! $guest_id)
		{
			return $this->request->redirect('mobile/main');
		}

		$guest = $this->wedding->checkout_guest($guest_id);
		return $this->request->redirect('mobile/table/'.$guest->table_id);
	}
	
	public function action_search()
	{
		$this->_init();
		
		$query = trim(Arr::get($_POST, 'query', ''));
		if ($query == '')
		{
			$this->view->guests = array();
			return;
		}
		
		$this->template->title = 'Overview';
		$this->view->query = $query;
		$this->view->guests = $this->wedding->search_guests($query);
	}
	
	private function _init()
	{
		// Retrieve alias from session/cookie
		if ( ! ($alias = Session::instance()->get('alias', FALSE))) 
		{
			$alias = Cookie::get('alias', FALSE);
		}
		
		if ($alias == FALSE)
		{
			$this->auto_render = FALSE;
			return $this->request->redirect('mobile/index');
		}

		// Verify alias
		$this->wedding = ORM::factory('wedding')->load_alias($alias);

		if ( ! $this->wedding->loaded())
		{
			$this->_logout();
			$this->auto_render = FALSE;
			return $this->request->redirect('mobile/index');
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
		Session::instance()->delete('alias');
		Cookie::delete('alias');
	}

} // End Controller_Mobile
