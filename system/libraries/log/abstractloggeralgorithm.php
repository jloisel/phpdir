<?php

define('LOG_EXPIRATION_TIME',Config::get('stats_expiration_time'));

/**
 * Composite Logger Algorithm class.
 * Defines the implementations of algorithms common methods.
 * @author Jerome Loisel
 */
abstract class AbstractLoggerAlgorithm implements LoggerAlgorithm {
	/**
	 * Logs expiration time
	 *
	 * @var Integer
	 */
	protected $_logExpirationTime;
	
	/**
	 * Constructor
	 */
	protected function __construct() {
		$this->_logExpirationTime = 86400;
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
			$this->_logExpirationTime = $expirationTime;
		}
	}
	
	/**
	 * Says if the specified number is between specified min and max values.
	 * @param	Integer number
	 * @param	Integer min value
	 * @param	Integer max value
	 * @return	Boolean TRUE if min <= number <= max, else FALSE
	 */
	protected function isBetweenOrEqual($number,$min,$max) {
		return ($min <= $number && $number <= $max);
	}
	
	/**
	 * Says if the specified IP is a valid ip.
	 * @param	String ip
	 * @return	Boolean TRUE if success
	 */
	protected function isValidIp($ip) {
		if(is_string($ip) && strlen($ip) <= 15) {
			$ipNumbers = explode('.',$ip);
			if(count($ipNumbers) == 4) {
				$validNumbers = 0;
				foreach($ipNumbers as $ipNumber) {
					if($this->isBetweenOrEqual($ipNumber,0,255)) {
						$validNumbers++;
					}
				}
				return ($validNumbers == 4);
			}
		}
		return false;
	}
	
	/**
	 * Returns the current timestamp.
	 *
	 * @return Integer TimeStamp 
	 */
	protected function now() {
		return time();
	}
	
	/**
	 * Returns the expiration date. a date lower than this date is considerated
	 * as expired.
	 *
	 * @return Integer timestamp
	 */
	protected function getExpirationDate() {
		return $this->now() - $this->_logExpirationTime;
	}
}

?>