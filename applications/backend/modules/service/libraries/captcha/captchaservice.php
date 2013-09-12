<?php

/**
 * Base implementation of a captcha service.
 *
 * @author Jerome Loisel
 */
interface CaptchaService extends Service {
	
	/**
	 * Returns the HTML code to insert in the template.
	 * 
	 * @return string
	 */
	public function getHtml();
	
	/**
	 * Verifies that the captcha is valid.
	 * 
	 * @return boolean
	 */
	public function isValid();
}

?>