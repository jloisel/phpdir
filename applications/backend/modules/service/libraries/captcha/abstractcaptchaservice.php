<?php

/**
 * Base captcha service implementation.
 * 
 * @author Jerome
 *
 */
abstract class AbstractCaptchaService extends ServiceAdapter implements CaptchaService {
	
	/**
	 * Constructor.
	 * @return void
	 */
	protected function __construct() {
		parent::__construct();
	}	
}

?>