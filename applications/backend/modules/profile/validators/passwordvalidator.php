<?php

/**
 * Checks that the value is the valid password
 * for the passed customer.
 *
 * @author Jerome Loisel
 */
class PasswordValidator {
	
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
		$customer = isset($arguments[0]) ? $arguments[0] : null;
		if($customer != null && is_object($customer) && $customer->isValidPassword($value)) {
			return $value;
		}
		throw new sfValidatorError($validator,'invalid',$arguments);
	}
	
}

?>