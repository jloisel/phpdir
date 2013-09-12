<?php

/**
 * Customer profile edition form.
 *
 * @author Jerome Loisel
 */
class ProfileEditForm extends sfI18nForm {
	
	
	public function configure() {
		$this->setWidgets(array(
			'created_on' => new sfWidgetFormInput(array(),array('readonly' => 'readonly')),
			'email' => new sfWidgetFormInput(), 
			'password' => new sfWidgetFormInputPassword(), 
			'new_password' => new sfWidgetFormInputPassword(), 
			'confirm_new_password' => new sfWidgetFormInputPassword(), 
			'firstname' => new sfWidgetFormInput(), 
			'lastname' => new sfWidgetFormInput()
		));
		
		$this->widgetSchema->setNameFormat('profile[%s]');
		
		$this->setValidators(array(
			'created_on' => new sfValidatorPass(),
			'email' => new sfValidatorAnd(array(
						new sfValidatorString(
							array(), 
							array('required' => 'Email is required')
						),
						new EmailNotExistsValidator(), 
					)
			),
			'password' => new sfValidatorCallback(array(
				'callback' => array('PasswordValidator','execute'), 
				'arguments' => array(Customer::getLogged())
			)),
			'firstname' => new sfValidatorString(
							array(),
							array('required' => 'Firstname is required')), 
			'lastname' => new sfValidatorString(
							array(),
							array('required' => 'Lastname is required')), 
			'new_password' => new sfValidatorPass(), 
			'confirm_new_password' => new sfValidatorPass(), 		
		));
		
		$this->validatorSchema->setPostValidator(
			new sfValidatorSchemaCompare('new_password', sfValidatorSchemaCompare::EQUAL, 'confirm_new_password')
		);
	}
}

?>