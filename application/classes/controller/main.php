<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_template {

	public function action_index()
	{
		$view = View::factory('main/index');
		
		$view->tables = ORM::factory('table')->all_tables();
		
		$this->template->content = $view;
	}

} // End Main
