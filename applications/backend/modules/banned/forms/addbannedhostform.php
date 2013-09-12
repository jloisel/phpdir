<?php

class AddBannedHostForm extends AddBannedItemForm {
	
	public function __construct() {
		parent::__construct('BannedHost',BannedHost::TYPE);
	}
	
	protected function getSpecificFieldValidator() {
		return new sfValidatorUrl();
	}
	
	public function configure() {
		parent::configure();
		$this->widgetSchema->setLabels(array(
			'value' => 'Host'
		));
	}
}

?>