<?php

class IframeController extends DefaultController {
	
	public function __construct() {
		parent::__construct();
		$this->getHttpResponse()->setLayout('iframe_layout.php');
	}
	
}

?>