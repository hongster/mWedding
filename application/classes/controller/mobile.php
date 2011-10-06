<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Mobile webpages
 */
class Controller_Mobile extends Controller_Template {
	public $template = 'mobile';

	public function action_index()
	{
		$this->view->tables = ORM::factory('table')->all_tables();
	}
	
	public function action_table()
	{
		$id = $this->request->param('id');
		$table = ORM::factory('table', $id);
		if (! $table->loaded())
			throw new Kohana_Exception('Invalid table id ":id"', array(':id'=>$id));
		
		$this->template->title = $table->name;
		$this->view->guests = $table->all_guests();
	}
	
	public function action_checkin()
	{
		$guest_id = $this->request->param('guest_id');
		$checkin = (bool) $this->request->param('checkin');
		$guest = ORM::factory('guest', $guest_id);
		if (! $guest->loaded())
			throw new Kohana_Exception('Invalid guest id ":guest_id"', array(':guest_id'=>$guest_id));
		
		$guest->checkin($checkin);
		$this->request->redirect('mobile/table/'.$guest->table_id);
	}
	
	public function action_search()
	{
		$query = trim(Arr::get($_POST, 'query', ''));
		if ($query == '')
		{
			$this->view->guests = array();
			return;
		}
		
		$this->view->query = $query;
		$this->view->guests = ORM::factory('guest')->search_by_name($query);
	}

} // End Controller_Mobile
