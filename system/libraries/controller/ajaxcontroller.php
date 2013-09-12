<?php

class AjaxController extends DefaultController {
	
	public function __construct() {
		parent::__construct();
		$this->getHttpResponse()->setLayout(Layout::AJAX);
	}
}

?>