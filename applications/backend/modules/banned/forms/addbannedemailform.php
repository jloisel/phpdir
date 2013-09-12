<?php

class AddBannedEmailForm extends AddBannedItemForm {
	
	public function __construct() {
		parent::__construct('BannedEmail',BannedEmail::TYPE);
	}
	
	protected function getSpecificFieldValidator() {
		return new sfValidatorEmail();
	}
	
	public function configure() {
		parent::configure();
		$this->widgetSchema->setLabels(array(
			'value' => 'Email'
		));
	}
}

?>