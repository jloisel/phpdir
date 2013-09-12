<?php

/**
 * Renders the output from controller's action.
 *
 */
class RenderingFilter extends AbstractFilter {
	
	
	public function __construct() {
		parent::__construct();
	}
	
	public function preExecute() {
		
	}
	
	public function postExecute() {
		Context::getHttpResponse()->send();
	}
}

?>