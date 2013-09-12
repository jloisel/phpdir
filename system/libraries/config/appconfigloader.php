<?php 

/**
 * Application configuration loader.
 */
class AppConfigLoader extends ConfigLoader {
	
	protected function __construct() {
		parent::__construct();
	}
	
	protected function getConfigPath() {
		return  Context::getParameterHolder()->getApplicationPath().DIRECTORY_SEPARATOR.'config';
	}
	
	/**
	 * Unique instance of this class.
	 *
	 * @return AppConfigLoader
	 */
	public static function getInstance() {
		return Singleton::getInstance(__CLASS__);
	}
}