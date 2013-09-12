<?php

/**
 * Checks into the database that the customer email 
 * does not already exists.
 *
 * @author Jerome Loisel
 */
class EmailNotExistsValidator extends EmailExistsValidator {
	
	/**
	 * execute validator
	 *
	 * @param sfValidatorBase Validator instance that calls  this method
	 * @param string Value of field that sfValidatorCallback  checks
	 * @param array Arguments for correct working
	 *
	 * @return value field when OK. Nothing if error (sfValidatorError  exception)
	 */
	protected function doClean($value) {
		try {
			parent::doClean($value);
		} catch(sfValidatorError $e) {
			return $value;
		}
		
		// Check if the new mail is different from the current one, 
		// if YES, that means the new mail is different from the old one 
		// but someone already uses it.
		if($value != Customer::getLogged()->email) {
			throw new sfValidatorError($validator,'invalid',$arguments);
		}
		return $value;
	}
}

?>