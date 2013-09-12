<?php

/**
 * Security filter.
 *
 * @author Jerome Loisel
 */
class SecurityFilter extends AbstractFilter {
	
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Check user credentials.
	 * 
	 */
	public function preExecute() {
		if($this->isFirstCall()) {
			$credentialsManager = Context::getCredentialsManager();
			/**
			 * Is the controller secure ?
			 */
			if($credentialsManager->isSecure()) {
				/**
				 * Controller is secure.
				 */
				$user = Context::getUser();
				if(!$user->isAuthenticated()) {
					/**
					 * User is not authenticated.
					 * Set the controller and action to execute defined in the
					 * credentials configuration.
					 */
					Context::getHttpRequest()->setController($credentialsManager->getLoginController());
					Context::getHttpRequest()->setAction($credentialsManager->getLoginAction());
				} else if(!$user->hasCredential($credentialsManager->getCredentials())) {
					/**
					 * User is authenticated, but has not the right credentials.
					 */
					Context::getHttpRequest()->setController($credentialsManager->getSecureController());
					Context::getHttpRequest()->setAction($credentialsManager->getSecureAction());
				}
			}
		}
	}
	
	public function postExecute() {
		
	}
	
	/**
	 * Returns the unique instance of this class.
	 *
	 * @return SecurityFilter
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>