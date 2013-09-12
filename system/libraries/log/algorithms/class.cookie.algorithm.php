<?php

/**
 * Cookies Logger algorithm.
 * @author Jerome Loisel
 */
class CookieLoggerAlgorithm extends AbstractLoggerAlgorithm {
	
	/**
	 * Default constructor.
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Is something logged for the specified information ?
	 *
	 * @param String $objectType
	 * @param Integer $objectId
	 * @param String $ip
	 * @return Boolean TRUE if success
	 */
	public function isLogged($objectType,$objectId,$ip) {
		return Context::getHttpRequest()->getCookie($objectType.$objectId);
	}
	
	/**
	 * Returns the expiration date starting from now.
	 *
	 * @return Integer TimeStamp 
	 */
	private function getNewExpirationDate() {
		return $this->now() + $this->_logExpirationTime;
	}
	
	/**
	 * Logs a event.
	 *
	 * @param String $objectType
	 * @param Integer $objectId
	 * @param String $ip
	 */
	public function log($objectType,$objectId,$ip) {
		Context::getHttpResponse()->setCookie(	$objectType.$objectId, 
												'1', 
												$this->getNewExpirationDate());
	}
	
	public function gc() {
		// Nothing to clean !
		return 0;
	}
}

?>