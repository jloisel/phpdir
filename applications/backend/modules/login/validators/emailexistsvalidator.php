<?php

/**
 * Validates that the email is a valid email and 
 * an administrator has this email.
 *
 * @author Jerome Loisel
 */
class EmailExistsValidator extends sfValidatorEmail {
	
	protected function configure($options = array(), $messages = array()) {
		parent::configure ( $options, $messages );
		if(is_array($messages)) {
			foreach($messages as $name => $value) {
				$this->addMessage($name, $value);
			}
		}
	}
	
	protected function doClean($value) {
		parent::doClean($value);
		$q = Doctrine::getTable('Customer')->createQuery();
		$q->where('email=?',$value);
		$q->limit(1);
		if($q->count() == 0) {
			throw new sfValidatorError($this,'email_not_exist',array('value' => $value));
		}
	}

}

?>