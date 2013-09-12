<?php

/**
 * Module Configuration loader.
 * @author Jerome Loisel
 */
class ModuleConfigLoader extends ConfigLoader {
	
	/**
	 * Default constructor.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	protected function getConfigPath() {
		return Context::getParameterHolder()->getModulePath().'/config';
	}
	
	/**
	 * Singleton instance of this class.
	 *
	 * @return ModuleConfigLoader
	 */
	public static function getInstance() {
		return Singleton::getInstance(__CLASS__);
	}
}