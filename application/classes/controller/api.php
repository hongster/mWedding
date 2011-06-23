<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Abstract controller class for API templating.
 */
abstract class Controller_API extends Kohana_Controller_Template {

	/**
	 * @var  View  page template
	 */
	public $template = 'api';

} // End Controller_APIe
