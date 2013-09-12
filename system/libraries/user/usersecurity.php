<?php

interface UserSecurity {
	/**
	* Add a credential to this user.
	*
	* @param mixed Credential data.
	*
	* @return void
	*/
	public function addCredential();
	
	/**
	* Clear all credentials associated with this user.
	*
	* @return void
	*/
	public function clearCredentials();
	
	/**
	* Indicates whether or not this user has a credential.
	*
	* @param mixed Credential data.
	* @param boolean use AND or use OR
	*
	* @return bool true, if this user has the credential, otherwise false.
	*/
	public function hasCredential($credentials, $useAnd = true);
	
	/**
	* Indicates whether or not this user is authenticated.
	*
	* @return bool true, if this user is authenticated, otherwise false.
	*/
	public function isAuthenticated();
	
	/**
	* Remove a credential from this user.
	*
	* @param mixed Credential data.
	*
	* @return void
	*/
	public function removeCredential($credential);
	
	/**
	* Set the authenticated status of this user.
	*
	* @param bool A flag indicating the authenticated status of this user.
	*
	* @return void
	*/
	public function setAuthenticated($authenticated);
}