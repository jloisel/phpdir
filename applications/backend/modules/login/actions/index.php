<?php

class IndexAction extends DefaultController {
	
	public function preExecute() {
		$this->form = new LoginForm ( );
	}
	
	/**
	 * Gives the user access to administrator panel.
	 *
	 * @param Customer $customer
	 */
	protected function grantAccessToUser(Customer $customer) {
		if (is_object ( $customer )) {
			$user = $this->getUser ();
			$user->setAuthenticated ( true );
			$user->addCredential ( $customer->getCredentials () );
			$user->setAttribute('id',$customer->id);
		}
	}
	
	protected function redirectToSecureZone() {
		$this->redirect ( Route::CONTROLLER_ACTION, array ('controller' => 'dashboard', 'action' => 'index' ) );
	}
	
	/**
	 * Handle the login procedure when the login 
	 * form is submitted.
	 *
	 */
	protected function handleLogin() {
		$this->form->bind ( $this->getParameter ( 'login', array () ) );
		if ($this->form->isValid ()) {
			$email = $this->form->getValue ( 'email' );
			$customer = Doctrine::getTable ( 'Customer' )->findByEmail ( $email )->getFirst();
			$this->grantAccessToUser ( $customer );
			$this->redirectToSecureZone ();
		}
	}
	
	/**
	 * Login action entry point.
	 *
	 * @return integer
	 */
	public function executeAction() {
		if ($this->getUser ()->isAuthenticated ()) {
			$this->redirectToSecureZone ();
		}
		if ($this->getRequestMethod () == RequestMethod::POST) {
			$this->handleLogin ();
		}
		return View::TPL;
	}

}

?>