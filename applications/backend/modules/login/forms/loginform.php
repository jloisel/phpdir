<?php

/**
 * Login form.
 *
 * @author Jerome Loisel
 */
class LoginForm extends sfI18nForm {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Configures the fields, validators of the form.
	 *
	 */
	public function configure() {
		$this->setWidgets(array(
			'email' => new sfWidgetFormInput(), 
			'password' => new sfWidgetFormInputPassword()
		));
		
		$this->widgetSchema->setNameFormat('login[%s]');
		
		$this->setValidators(array(
			'email' => new sfValidatorString(
							array(), 
							array('required' => 'Email is required')
						),
			'password' => new sfValidatorString(
							array(),
							array('required' => 'Password is required'))
		));
		
		$this->validatorSchema->setPostValidator(
			new sfValidatorCallback(
	          array(
	            'callback' => array('LoginValidator', 'execute'),
	            'arguments' => array()
	          ),
	          array('invalid'  => 'The email/password combination is incorrect')
	        )
		);
	}
}

?>