<?php

/**
 * Abstract Composite algorithm.
 */
abstract class AbstractCompositeLoggerAlgorithm extends AbstractLoggerAlgorithm {
	/**
	 * AbstractLoggerAlgorithm
	 *
	 * @var array of Object {@link AbstractLoggerAlgorithm}
	 */
	protected $_loggerAlgorithms = array();
	
	/**
	 * Default constructor
	 *
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Returns an instance of AbstractLoggerAlgorithm.
	 * @param	Integer index
	 * @return	Object {@link AbstractLoggerAlgorithm}, else FALSE if failure
	 */
	public function getChild($index) {
		if(is_int($index)) {
			if(isset($this->_loggerAlgorithms[$index])) {
				return $this->_loggerAlgorithms[$index];
			}
		}
		return false;
	}
	
	/**
	 * Adds an algorithm to the composite algorithm.
	 * @param	Object {@link AbstractLoggerAlgorithm}
	 */
	public function addChild($loggerAlgorithm) {
		if(	is_object($loggerAlgorithm) && 
			is_subclass_of($loggerAlgorithm,'AbstractLoggerAlgorithm')) {
			$this->_loggerAlgorithms[] = $loggerAlgorithm;
		}
	}
	
	/**
	 * Removes an algorithm of the composite.
	 * @param	Integer index
	 */
	public function removeChild($index) {
		if(is_int($index)) {
			if(isset($this->_loggerAlgorithms[$index])) {
				unset($this->_loggerAlgorithms[$index]);
			}
		}
	}
	
	/**
	 * Checks if one of the logger algorithms has logged something
	 * about the specified objectType, objectID and IP.
	 *
	 * @param String $objectType (Ex: 'vote')
	 * @param Integer $objectId (Unique ID)
	 * @param String $ip (IP address)
	 */
	public function isLogged($objectType,$objectId,$ip) {
		$isLogged = false;
		if(count($this->_loggerAlgorithms) > 0) {
			foreach($this->_loggerAlgorithms as $logAlgorithm) {
				if($logAlgorithm->isLogged($objectType,$objectId,$ip)) {
					$isLogged = true;
					break;
				}
			}
		}
		return $isLogged;
	}
	
	/**
	 * Returns the expiration delay.
	 * @return Integer expiration delay (in seconds)
	 */
	public function getExpirationTime() {
		return $this->_logExpirationTime;
	}
	
	/**
	 * Defines the expiration delay. (default: 86400 sec)
	 * @param	Integer expiration delay
	 */
	public function setExpirationTime($expirationTime) {
		if(is_int($expirationTime) && $expirationTime >= 0) {
			if(count($this->_loggerAlgorithms) > 0) {
				foreach($this->_loggerAlgorithms as $logAlgorithm) {
					$logAlgorithm->setExpirationTime($expirationTime);
				}
			}
			$this->_logExpirationTime = $expirationTime;
		}
	}
	
	/**
	 * Logs an entry in each algorithm.
	 * @param	String $objectType
	 * @param	Integer $objectID
	 * @param	String	$ip (IP address)
	 */
	public function log($objectType,$objectId,$ip) {
		if(count($this->_loggerAlgorithms) > 0) {
			foreach($this->_loggerAlgorithms as $logAlgorithm) {
				$logAlgorithm->log($objectType,$objectId,$ip);
			}
		}
	}
	
	/**
	 * Garbage collector.
	 * @return Integer cleaned items count
	 */
	public function gc() {
		$cleanCount = 0;
		if(count($this->_loggerAlgorithms) > 0) {
			foreach($this->_loggerAlgorithms as $logAlgorithm) {
				$cleanCount += $logAlgorithm->gc();
			}
		}
		return $cleanCount;
	}
}

?>