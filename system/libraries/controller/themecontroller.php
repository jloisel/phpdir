<?php

/**
 * A theme controller has its view stored in 
 * /templates/My_Template folder.
 *
 * @author 
 */
class ThemeController extends AbstractController {

	public function __construct() {
		parent::__construct();
	}
	
	public function preExecute() {
		define('TEMPLATES_PATH',SCRIPT_ROOT_PATH.'/templates/'.Config::get('templates_folder'));
		// Main templates : layout, ajax_layout etc.
		$this->tpl->setPath(array(TEMPLATES_PATH, TEMPLATES_PATH.'/modules/'.$this->getHttpRequest()->getController()));
	}
}