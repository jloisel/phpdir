<?php

class AddBannedIpForm extends AddBannedItemForm {
	
	public function __construct() {
		parent::__construct('BannedIp',BannedIp::TYPE);
	}
	
	protected function getSpecificFieldValidator() {
		return new IpValidator();
	}
	
	public function configure() {
		parent::configure();
		$this->widgetSchema->setLabels(array(
			'value' => 'Ip'
		));
	}
}

?>