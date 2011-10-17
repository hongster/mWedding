<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Web template
 */
class Controller_Template extends Kohana_Controller_Template {
	public $view;
	public $err_msg; // Flash msg in webpage
	public $info_message; // Flash msg in webpage
	
	protected function flash_err_msg($err_msg)
	{
		Session::instance()->set('err_msg', $err_msg);
	}
	
	protected function flash_info_msg($info_msg)
	{
		Session::instance()->set('info_msg', $info_msg);
	}
	
	public function before() 
	{
		parent::before();
		
		if ($this->auto_render === TRUE)
		{
			// Render flash msg
			$session = Session::instance();
			if ($msg = $session->get_once('err_msg'))
			{
				$this->template->err_msg = $msg;
			}
			elseif ($msg = $session->get_once('info_msg'))
			{
				$this->template->info_msg = $msg;
			}
			
			// XXX Not efficient, need improvement
			$this->view = View::factory();
		}
	}
	
	public function after() 
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
