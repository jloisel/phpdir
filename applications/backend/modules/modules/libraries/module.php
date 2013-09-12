<?php

/**
 * A module.
 * 
 * @author Jerome Loisel
 *
 */
class Module extends AbstractLocalizableObject {
	
	/**
	 * Installation file.
	 *
	 */
	const INSTALL_FILE = '__install.php';
	
	/**
	 * Uninstallation file.
	 *
	 */
	const UNINSTALL_FILE = '__uninstall.php';
	
	/**
	 * Module credentials.
	 *
	 */
	const CREDENTIALS = 'credentials';
	
	/**
	 * Has the module a backend ?
	 *
	 */
	const HAS_BACKEND = 'has_backend';
	
	/**
	 * Has the module a frontend ?
	 */
	const HAS_FRONTEND = 'has_frontend';
	
	/**
	 * The module default action is the action 
	 * which will be linked from the dashboard.
	 *
	 */
	const DEFAULT_ACTION = 'default_action';
	
	/**
	 * The default "default action" of the module.
	 */
	const DEFAULT_DEFAULT_ACTION = 'index';
	
	/**
	 * Defines the weight of the module.
	 * The weight influences the display order 
	 * on the dashboard.
	 *
	 */
	const WEIGHT = 'weight';
	
	/**
	 * Default weight.
	 */
	const DEFAULT_WEIGHT = 0;
	
	/**
	 * Defines if the module is a system module.
	 * System modules cannot be uninstalled.
	 */
	const IS_SYSTEM = 'is_system';
	
	/**
	 * Returns the module credentials. NULL if none.
	 *
	 * @return array
	 */
	public function getCredentials() {
		return $this->getDefinition(self::CREDENTIALS,null);
	}
	
	/**
	 * Has the module a backend ?
	 * Default value is FALSE.
	 *
	 * @return boolean
	 */
	public function hasBackend() {
		return $this->getDefinition(self::HAS_BACKEND,false);
	}
	
	/**
	 * Has the module a frontend ?
	 * Default value is TRUE.
	 * 
	 * @return boolean
	 */
	public function hasFrontend() {
		return $this->getDefinition(self::HAS_FRONTEND,false);
	}
	
	/**
	 * Returns the module default action.
	 *
	 * @return string
	 */
	public function getDefaultAction() {
		return $this->getDefinition(self::DEFAULT_ACTION,self::DEFAULT_DEFAULT_ACTION);
	}
	
	/**
	 * Returns the module Weight. 
	 * Used by dashboard.
	 *
	 * @return integer
	 */
	public function getWeight() {
		return $this->getDefinition(self::WEIGHT,self::DEFAULT_WEIGHT);
	}
	
	/**
	 * Is the module a system module ?
	 * Default value is FALSE.
	 * 
	 * @return boolean
	 */
	public function isSystem() {
		return $this->getDefinition(self::IS_SYSTEM,false);
	}
	
	/**
	 * @return boolean
	 */
	public function isInstalled() {
		return DBModule::isInstalled($this->getLocalFolder());
	}
	
	/**
	 * Installs the module by registering it 
	 * into Database.
	 *
	 */
	public function install() {
		DBModule::install($this->getLocalFolder());
	}
	
	/**
	 * Uninstalls the module: unregisters the module 
	 * from database registry.
	 *
	 */
	public function uninstall() {
		DBModule::uninstall($this->getLocalFolder());
	}
	
	/**
	 * Returns the module Icon URL.
	 * (32x32 pixels)
	 *
	 * @return string
	 */
	public function getIcon() {
		return ImageHelper::image(
					Config::get('site_url')
						.'/'.APPS_FOLDER
						.'/'.APP_NAME.'/modules'
						.'/'.$this->getLocalFolder().'/view/images/icon-32x32.png');
	}
}

?>