<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Table extends Controller_Template {

	public function action_update($table_id)
	{
		$table = ORM::factory('table', $table_id);
		$this->view->table = $table;
		$this->view->guests = $table->all_guests();
	}

	public function action_update_name($table_id)
	{
		$table = ORM::factory('table', $table_id);
		if ( ! empty($_POST['table_name']))
		{
			$table->name = arr::get($_POST, 'table_name');
			$table->save();
		}
		
		$this->request->redirect('table/update/'.$table->id);
	}
	
	public function action_add_guest($table_id)
	{
		$table = ORM::factory('table', $table_id);
		if ( ! empty($_POST['guest_name']))
		{
			$guest = ORM::factory('guest');
			$guest->name = arr::get($_POST, 'guest_name');
			$table->add_guest($guest);
		}
		
		$this->request->redirect('table/update/'.$table->id);
	}
	
	public function action_delete_guest($guest_id)
	{
		$table = ORM::factory('table');
		$table->remove_guest($guest_id);
		$this->request->redirect($this->request->referrer());
	}
	
	public function action_add()
	{
		$table_name = '';
		
		if ($_POST)
		{
			$table_name = trim(Arr::get($_POST, 'table_name', ''));
			if ( ! empty($table_name))
			{
				$table = ORM::factory('table');
				$table->name = $table_name;
				$table->save();
				
				$this->request->redirect('table/update/'.$table->pk());
			}
		}
		
		$this->view->table_name = $table_name;
	}
	
	public function action_delete($table_id)
	{
		$table = ORM::factory('table', $table_id);
		if ( ! $table->loaded())
			$this->request->redirect($this->request->referrer());
		
		$table->delete();
		$this->request->redirect('/');
	}
} // End Table
