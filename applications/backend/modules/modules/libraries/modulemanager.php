<?php

/**
 * The module manager is able to provide information about modules 
 * in the application.
 *
 * @author Jerome Loisel
 */
class ModuleManager extends Singleton {
	
	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Returns the File that maps the modules directory.
	 *
	 * @return File
	 */
	protected function getModulesDirectory() {
		return new File(SCRIPT_ROOT_PATH.'/'.APPS_FOLDER.'/'.APP_NAME.'/'.'modules');
	}
	
	/**
	 * Returns a module file.
	 *
	 * @param string $moduleName
	 * @param string $filename
	 * @return File
	 */
	protected function getModuleFile($moduleName, $filename) {
		return new File($this->getModulesDirectory().'/'.$moduleName,$filename);
	}
	
	/**
	 * Install a new module.
	 *
	 * @param Module $module
	 */
	public function install(Module $module) {
		$file = $this->getModuleFile($module->getLocalFolder(),self::INSTALL_FILE);
		if($file->exists()) {
			require_once ($file->getFullname());
		}
		$module->uninstall();
	}
	
	/**
	 * Uninstall an existing module.
	 *
	 * @param Module $module
	 */
	public function uninstall(Module $module) {
		$module->install();
		$file = $this->getModuleFile($module->getLocalFolder(),self::UNINSTALL_FILE);
		if($file->exists()) {
			require_once $file->getFullname();
		}
	}
	
	/**
	 * Is the passed module installed ?
	 *
	 * @param string $moduleName
	 * @return boolean
	 */
	public function isModuleInstalled($moduleName) {
		return Module::isInstalled($moduleName);
	}
	
	/**
	 * Returns the module manager.
	 *
	 * @return ModuleManager
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>