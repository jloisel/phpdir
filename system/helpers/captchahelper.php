<?php

/**
 * Simple helpers wrapping recaptcha service. 
 *
 * @author Jerome Loisel
 */
class CaptchaHelper extends AbstractHelper {
	
	/**
	 * Do we should use a captcha ?
	 *
	 * @return boolean
	 */
	public static function useCaptcha() {
		return Config::get ( 'use_captcha' ) == '1';
	}
	
	/**
	 * Displays the recaptcha antispam image.
	 *
	 * @return string
	 */
	public static function getHtml() {
		$captcha = ServiceLocator::getInstance()->newService(Service::CAPTCHA,Config::get(Service::CAPTCHA.'_service'));
		return $captcha->getHtml();
	}
	
	/**
	 * Checks if the entered captcha is valid or not.
	 *
	 * @return boolean
	 */
	public static function isValid() {
		$captcha = ServiceLocator::getInstance()->newService(Service::CAPTCHA,Config::get(Service::CAPTCHA.'_service'));
		return $captcha->isValid();
	}
}

?>
