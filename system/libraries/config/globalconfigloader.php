<?php

/**
 * Core configuration loader.
 */
class GlobalConfigLoader extends ConfigLoader {
	
	/**
	 * Default constructor
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	protected function getConfigPath() {
		return SCRIPT_ROOT_PATH.DIRECTORY_SEPARATOR.'config';
	}
	
	/**
	 * Unique instance of this class.
	 *
	 * @return GlobalConfigLoader
	 */
	public static function getInstance() {
		return Singleton::getInstance(__CLASS__);
	}
}