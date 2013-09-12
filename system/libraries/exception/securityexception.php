<?php

/**
 * Security exception.
 *
 * @author Jerome Loisel
 */
class SecurityException extends BaseException {

	const UNDEFINED_CRED_CONFIG_ITEM = 1001;
	const MALFORMED_CREDENTIALS_CONFIG = 1002;
	
	public function __construct($message,$code) {
		parent::__construct($message,$code);
	}
}

?>
