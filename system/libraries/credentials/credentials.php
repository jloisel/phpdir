<?php

/**
 * All credentials configuration items are defined here.
 *
 */
interface Credentials {
	/**
	 * Defines the credentials required by the application/controller.
	 */
	const CREDENTIALS = 'credentials';
	
	/**
	 * Are the application credentials required ?
	 *
	 */
	const APP_CREDENTIALS_REQUIRED = true;
	
	/**
	 * Are the module credentials required ?
	 *
	 */
	const MODULE_CREDENTIALS_REQUIRED = false;
	
	/**
	 * Is application/controller secure config item.
	 * Is the application/controller secure ? 
	 * (need authentication and credentials)
	 */
	const IS_SECURE = 'is_secure';
	
	/**
	 * Login controller config item.
	 *
	 */
	const LOGIN_CONTROLLER = 'login_controller';
	
	/**
	 * Login controller action config item.
	 *
	 */
	const LOGIN_ACTION = 'login_action';
	
	/**
	 * Secure controller config item.
	 * Controller executed when user is authenticated 
	 * but hasn't the right credentials.
	 */
	const SECURE_CONTROLLER = 'secure_controller';
	
	/**
	 * Secure action config item.
	 * Controller action executed when user is authenticated 
	 * but hasn't the right credentials.
	 *
	 */
	const SECURE_ACTION = 'secure_action';
	
	/**
	 * Defines the authenticated session timeout.
	 *
	 */
	const SESSION_TIMEOUT = 'session_timeout';
	
	/**
	 * Name of the user session.
	 *
	 */
	const SESSION_NAME = 'session_name';
}

?>
