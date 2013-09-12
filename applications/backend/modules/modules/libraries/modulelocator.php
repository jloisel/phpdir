<?php

/**
 * The module locator is responsible of 
 * find and loading module definitions.
 *
 * @author Jerome Loisel
 */
class ModuleLocator extends AbstractObjectLocator {
	
	/**
	 * Default constructor.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Fetches the module credentials array, and returns the 'credentials' 
	 * item.
	 *
	 * @param string $moduleName
	 * @return mixed
	 */
	protected function getModuleCredentials($moduleName) {
		$file = new File(
			$this->getLocalObjectsLocation()->getPath(),
			$moduleName.'/'.'config'.'/'.'credentials.php');
		if($file->isFile()) {
			$__CREDENTIALS = null;
			include_once ($file->getFullname());
			if(isset($__CREDENTIALS['credentials'])) {
				return $__CREDENTIALS['credentials'];
			} else {
				return self::getAppCredentials();
			}
		} else {
			return self::getAppCredentials();
		}
	}
	
	/**
	 * Returns the application credentials item.
	 *
	 * @return array
	 */
	protected static function getAppCredentials() {
		return AppConfigLoader::getInstance()->getItem(ConfigLoader::CREDENTIALS,'credentials');
	}
	
	/**
	 * Returns the local localizable object, 
	 * if any.
	 *
	 * @param File $object
	 * @return LocalizableObject
	 */
	protected function getLocalLocalizableObject(File $directory) {
		$localObject = parent::getLocalLocalizableObject($directory);
		if(is_object($localObject)) {
			$localObject->initialize(array(
				Module::CREDENTIALS => $this->getModuleCredentials($localObject->getLocalFolder())
			));
			return $localObject;
		}
		return null;
	}
	
	/**
	 * Returns the file mapping the folder containing 
	 * sub-folders which are each a localizable object.
	 *
	 * @return File
	 */
	protected function getLocalObjectsLocation() {
		return new File(SCRIPT_ROOT_PATH.'/'.APPS_FOLDER.'/'.APP_NAME.'/modules');
	}
	
	/**
	 * Returns the URL where the XML describing the remote 
	 * objects is located.
	 *
	 * @return string
	 */
	protected function getRemoteObjectsLocation() {
		return Context::getRepository()->getModulesDescriptorURL();
	}
	
	/**
	 * Returns the remote object XML parser.
	 *
	 * @return RemoteObjectXMLParser
	 */
	protected function getRemoteObjectXMLParser($url) {
		return new ThemesXMLParser($url);
	}
	
	/**
	 * @see AbstractObjectLocator::newLocalizableObject()
	 *
	 * @return Module
	 */
	protected function newLocalizableObject() {
		return new Module();
	}

	
	/**
	 * Returns the module locator unique instance.
	 *
	 * @return ModuleLocator
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>