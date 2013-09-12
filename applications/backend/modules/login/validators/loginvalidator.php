<?php

/**
 * Validates the login of the user into the user administration panel.
 * 
 * @author Jerome Loisel
 *
 */
class LoginValidator {
	/**
	 * execute validator
	 *
	 * @param sfValidatorBase Validator instance that calls  this method
	 * @param string Value of field that sfValidatorCallback  checks
	 * @param array Arguments for correct working
	 *
	 * @return value field when OK. Nothing if error (sfValidatorError  exception)
	 */
	public static function execute($validator, $value, $arguments) {
		$request = Context::getHttpRequest();
		$form = $request->getParameter('login',array());
		$email = isset($form['email']) ? $form['email'] : '';
		$password = isset($form['password']) ? $form['password'] : '';
		
		$q = Doctrine::getTable('Customer')->createQuery();
		$q->where(
			'level=? and email=? and password=?',
			array(Customer::LEVEL_ADMINISTRATOR,$email,md5($password))
		);
		if($q->count() == 0) {
			throw new sfValidatorError($validator,'invalid',$arguments);
		}
		return $value;
	}
}

?>