<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Web template
 */
class Controller_Template extends Kohana_Controller_Template {
	protected $view;
	
	function before() 
	{
		parent::before();
		
		if ($this->auto_render === TRUE)
		{
			$this->view = View::factory();
		}
	}
	
	function after() 
	{	
		if ($this->auto_render === TRUE)
		{
			$this->view->set_filename(
				$this->request->directory().DIRECTORY_SEPARATOR
				.$this->request->controller().DIRECTORY_SEPARATOR
				.$this->request->action()
			);
			$this->template->content = $this->view;
		}
		
		parent::after();
	}
} // End Controller_Template
