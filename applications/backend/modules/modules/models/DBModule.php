<?php

/**
 * A module database entry is created each time a 
 * module is installed.
 *
 * @author Jerome Loisel
 */
class DBModule extends BaseModule {
	
	/**
	 * Installed modules.
	 *
	 * @var array
	 */
	private static $installedModules = null;
	
	/**
	 * Before inserting a new installed module.
	 *
	 * @param unknown_type $event
	 */
	public function preInsert($event) {
		$this->installed_on = date('Y-m-d H:i:s');
	}
	
	/**
	 * Is the passed module installed ?
	 *
	 * @param string $moduleName
	 * @return boolean
	 */
	public static function isInstalled($moduleName) {
		$modules = self::getInstalledModules();
		return isset($modules[$moduleName]);
	}
	
	/**
	 * Returns all installed modules as an array. 
	 *
	 * @return array
	 */
	public static function getInstalledModules() {
		if(self::$installedModules == null) {
			$table = Doctrine::getTable('DBModule');
			$table->setAttribute(Doctrine::ATTR_COLL_KEY,'name');
			$modules = $table->findAll(Doctrine::HYDRATE_ARRAY);
			foreach($modules as $module) {
				self::$installedModules[$module['name']] = array(
					'id' => $module['id'], 
					'installed_on' => $module['installed_on']
				);
			}
		}
		return self::$installedModules;
	}
	
	/**
	 * returns the db entry of the module.
	 *
	 * @param string $moduleName
	 * @return array
	 */
	public static function getInstalledModule($moduleName) {
		if(self::isInstalled($moduleName)) {
			return self::$installedModules[$moduleName];
		}
		return null;
	}
	
	/**
	 * Installs a new module into database.
	 *
	 * @param string $moduleName
	 */
	public static function install($moduleName) {
		$module = new DBModule();
		$module->name = $moduleName;
		$module->save();
	}
	
	/**
	 * Uninstalls the module.
	 *
	 * @param string $moduleName
	 */
	public static function uninstall($moduleName) {
		$module = Doctrine::getTable('DBModule')->findByName($moduleName);
		if(is_object($module)) {
			$module->delete();
		}
	}
}

?>