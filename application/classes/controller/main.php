<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Template {

	public function action_index()
	{
	}
	
	public function action_get_started()
	{
		if (! $_POST)
			return;
			
		 /* Create a new wedding list */
		 // Validate alias
		 $alias = Arr::get($_POST, 'alias', '');
		 if ( ! $this->_valid_alias($alias)) 
		 {
			 $this->flash_err_msg('Invalid wedding code. Allowed characters: alphabets, digits, hyphen, underscores.');
			 return;
		 }
		 
		 // Check is alias is used
		 $wedding = ORM::factory('wedding')->load_alias($alias); 
		 if ($wedding->loaded())
		 {
			 $this->flash_err_msg('This wedding code is already in used. Please try another code.');
			 return;
		 }
		 
		 // Create new wedding list and 1 table
		 $wedding->clear();
		 $wedding->new_wedding($alias, 1);
		 $this->auto_render = FALSE;
		 $this->request->redirect('wedding/index/'.$alias);
	}
	
	/**
	 * Validation check.
	 * Allow: [A-Za-z0-9_-]
	 */
	private function _valid_alias($alias)
	{
		if ($alias == '')
			return FALSE;
		elseif (preg_match('/[^A-Za-z0-9_-]/', $alias))
			return FALSE;
			
			return TRUE;
	}
} // End Main
