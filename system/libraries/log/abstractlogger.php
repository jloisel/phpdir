<?php

/**
 * Abstract logger implementation.
 * @author Jerome Loisel
 */
abstract class AbstractLogger implements Logger {
		
	/**
	 * Object type logged.
	 * Ex: 1 = Link vote; 2 = Link clic...
	 *
	 * @var Integer
	 */
	protected $_objectType = -1;
	
	/**
	 * Unique ID of an object of type objectType.
	 *
	 * @var Integer ID
	 */
	protected $_objectId = -1;
	
	/**
	 * Logger Algorithm.
	 *
	 * @var AbstractLoggerAlgorithm
	 */
	protected $_loggerAlgorithm = null;
	
	protected $_loggerAlgorithmInstance = null;
	
	/**
	 * Constructor
	 * @param	String object type (Ex: 'vote', all available in LoggerFactory)
	 * @param	Integer object ID (Ex: ID of a link, user...) MUST be unique
	 */
	protected function __construct($objectType, $objectId = 0) {
		$this->_logExpirationTime = Config::get('stats_expiration_time');
		$this->_objectType = $objectType;
		if(intval($objectId) != 0) {
			$this->_objectId = intval($objectId);
		}
		$this->_loggerAlgorithm = LoggerAlgorithmFactory::IP_AND_COOKIE_ALGORITHM;
	}
	
	/**
	 * Sets a object type.
	 * @param	String Object type (Ex: 'vote', see LoggerFactory)
	 */
	public function setObjectType($objectType) {
		if(!empty($objectType) && is_int($objectType)) {
			$this->_objectType = $objectType;
		}
	}
	
	/**
	 * Returns the current object type.
	 * @return	String object type
	 */
	public function getObjectType() {
		return $this->_objectType;
	}
	
	/**
	 * Sets object ID.
	 * @param	Integer Object ID
	 */
	public function setObjectID($objectId) {
		if(is_int($objectId)) {
			$this->_objectId = $objectId;
		}
	}
	
	/**
	 * Returns an object ID.
	 * @return Integer object ID
	 */
	public function getObjectID() {
		return $this->_objectId;
	}
	
	/**
	 * Sets a logger algorithm.
	 * @param	String logger algorithm (Ex: 'ip', 'cookie' or 'composite')
	 */
	public function setAlgorithm($logAlgorithm) {
		if(is_string($logAlgorithm)) {
			$this->_loggerAlgorithm = $logAlgorithm;
		}
	}
	
	/**
	 * Returns the currently used Logger algorithm.
	 * @return	String logger algorithm
	 */
	public function getAlgorithm() {
		return $this->_loggerAlgorithm;
	}
	
	/**
	 * Returns an instance of an AbstractLoggerAlgorithm
	 *
	 * @return AbstractLoggerAlgorithm
	 */
	protected function getAlgorithmInstance() {
		if(!isset($this->_loggerAlgorithmInstance)) {
			$this->_loggerAlgorithmInstance = LoggerAlgorithmFactory::get($this->_loggerAlgorithm);
		}
		return $this->_loggerAlgorithmInstance;
	}
	
	/**
	 * 	Checks if the specified ip has been log, taking in consideration
	 * provided objectId and objectType.
	 * @param	String IP address
	 * @return	Boolean TRUE if already logged and not expired
	 */
	public function isLogged($ip) {
		$algorithm = $this->getAlgorithmInstance();
		if(	is_object($algorithm) && 
			is_subclass_of($algorithm,'AbstractLoggerAlgorithm')) {
			return $algorithm->isLogged($this->_objectType,$this->_objectId,$ip);
		}
		return false;
	}
	
	/**
	 * Logs an entry for this IP address.
	 *
	 * @param String $ip
	 * @return Boolean TRUE if success
	 */
	public function log($ip) {
		$algorithm = $this->getAlgorithmInstance();
		if(is_object($algorithm)) {
			return $algorithm->log($this->_objectType,$this->_objectId,$ip);
		}
		return false;
	}
	
	/**
	 * Logger garbage collector.
	 * @return	TRUE if success, else FALSE
	 */
	public function gc() {
		$algorithm = $this->getAlgorithmInstance();
		if(is_object($algorithm)) {
			return $algorithm->gc();
		}
		return false;
	}
}
?>