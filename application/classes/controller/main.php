<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Template {

	public function action_index()
	{
		$this->view->tables = ORM::factory('table')->all_tables();
	}
	
	public function action_search($query = NULL)
	{
		$query = $this->_determine_query($query);
		
		$this->view->query = $query;
		$this->view->quests = ORM::factory('guest')->search_by_name($query);	
	}
	
	/**
	 * POST['query'] overwrites the parameter in URL.
	 * 
	 * @param string $query Query parameter from URL.
	 */
	private function _determine_query($query = NULL)
	{
		// Using trim & empty to identify empty string
		$result = trim(Arr::get($_POST, 'query', ''));
		$result = empty($result) ? NULL : trim($query);
		
		// Just in case URL query parameter is empty string
		return empty($result) ? NULL : $result;
	}

} // End Main
