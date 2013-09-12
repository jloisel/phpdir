<?php

/**
 * IP validator.
 *
 * @author Jerome Loisel
 */
class IpValidator extends sfValidatorString {
	/**
	 * Constructor.
	 *
	 */
	public function __construct() {
		parent::__construct ( array (), array ('min_length' => 7, 'max_length' => 15 ) );
		$this->addMessage('invalid_ip','"%value%" is not a valid IP');
	}
	
	/**
	 * Validates that the IP is a valid ip address.
	 *
	 * @param string $value
	 * @return string
	 */
	protected function doClean($value) {
		$clean = parent::doClean($value);
		if (ip2long($clean) === false) {
			throw new sfValidatorError($this, 'invalid_ip', array('value' => $value));
		}
		return $clean;
	}
}

?>