<?php

/**
 * Manages the customer lost password.
 *
 * @author Jerome Loisel
 */
class LostAction extends DefaultController {
	
	/**
	 * Generate a random password.
	 *
	 * @param integer $length
	 * @return string
	 */
	private static function generatePassword($length = 8) {
		$password = '';
		$possible = '0123456789bcdfghjkmnpqrstvwxyz';
		$i = 0;
		while ( $i < $length ) {
			$char = substr ( $possible, mt_rand ( 0, strlen ( $possible ) - 1 ), 1 );
			if (! strstr ( $password, $char )) {
				$password .= $char;
				$i ++;
			}
		}
		return $password;
	
	}
	
	/**
	 * Sends an email to the customer with its new password.
	 *
	 * @param LostPasswordForm $form
	 */
	private function recoverPassword(LostPasswordForm $form) {
		$email = $form->getValue ( 'email' );
		$customer = Doctrine::getTable('Customer')->findByEmail($email);
		if(is_object($customer)) {
			$newPassword = self::generatePassword();
			$customer->password = md5($newPassword);
			$customer->save();
		}
	}
	
	/**
	 * Action entry point.
	 *
	 * @return integer
	 */
	public function executeAction() {
		$this->form = new LostPasswordForm ( );
		if ($this->getRequestMethod () == RequestMethod::POST) {
			$this->form->bind ( $this->getParameter ( 'lost' ) );
			if ($this->form->isValid ()) {
				$this->recoverPassword ( $this->form );
			}
		}
		return View::TPL;
	}
}

?>