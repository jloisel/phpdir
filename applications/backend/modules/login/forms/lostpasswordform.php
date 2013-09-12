<?php

/**
 * Lost password form.
 *
 * @author Jerome Loisel
 */
class LostPasswordForm extends sfI18nForm {
	
	public function __construct() {
		parent::__construct ();
	}

	/**
	 * Configures the fields, validators of the form.
	 *
	 */
	public function configure() {
		parent::configure();
		
		$this->setWidgets(array(
			'email' => new sfWidgetFormInput()
		));
		
		$this->widgetSchema->setNameFormat('lost[%s]');
		
		$this->setValidators(array(
			'email' => new EmailNotExistsValidator(
						array(), 
						array(
							'email_not_exist' => 'No administrator is registered with this email'
						)
					),
		));
	}	
}

?>