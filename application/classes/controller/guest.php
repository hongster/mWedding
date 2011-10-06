<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Guest extends Controller_Template {

	public function action_info($guest_id)
	{
		$guest = ORM::factory('guest', $guest_id);
		$this->view->guest = $guest;
	}
	
	public function action_checkin($guest_id)
	{
		$guest = ORM::factory('guest', $guest_id);
		$guest->checkin(TRUE);
		
		$this->request->redirect('guest/info/'.$guest_id);
	}
	
	public function action_checkout($guest_id)
	{
		$guest = ORM::factory('guest', $guest_id);
		$guest->checkin(FALSE);
		
		$this->request->redirect('guest/info/'.$guest_id);
	}
	
} // End Guest
