<?php

/**
 * The parameters holder has been designed to save and share global variables.
 *
 * @author Jerome Loisel
 */
class ParameterHolder extends Singleton {
	
	/**
	 * All the registered elements.
	 *
	 * @var array
	 */
	protected $registry = array();
	
	/**
	 * Default constructor.
	 *
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Register an object into the register at $name 
	 * index.
	 *
	 * @param Parameter $name
	 * @param mixed $value
	 */
	public function register($name, $value) {
		$this->registry[$name] = $value;
	}
	
	/**
	 * Unregister an object from registry.
	 *
	 * @param Parameter $name
	 */
	public function unregister($name) {
		if(isset($this->registry[$name])) {
			unset($this->registry[$name]);
		}
	}
	
	/**
	 * Returns a registry value if exists.
	 *
	 * @param Parameter
	 * @param $defaultValue
	 * @return mixed
	 */
	public function get($name,$defaultValue=null) {
		return isset($this->registry[$name]) ? $this->registry[$name] : $defaultValue;
	}
	
	/**
	 * Sets a name - value pair.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	protected function set($name,$value) {
		$this->registry[$name] = $value;
	}
	
	/**
	 * Returns the full server path to application files.
	 *
	 * @return string
	 */
	public function getApplicationPath() {
		return $this->get(Parameter::APP_PATH);
	}
	
	/**
	 * Returns the current application name.
	 *
	 * @return string
	 */
	public function getApplicationName() {
		return $this->get(Parameter::APP_NAME);
	}
	
	/**
	 * Returns the name of the folder containing all the applications.
	 *
	 * @return string
	 */
	public function getApplicationsFolder() {
		return $this->get(Parameter::APPS_FOLDER);
	}
	
	/**
	 * Sets the application folder.
	 *
	 * @param string $value
	 */
	public function setApplicationsFolder($value) {
		$this->set(Parameter::APPS_FOLDER,$value);
	}
	
	/**
	 * Sets the application name.
	 *
	 * @param string $value
	 */
	public function setApplicationName($value) {
		$this->set(Parameter::APP_NAME,$value);
	}
	
	/**
	 * Sets the application path.
	 *
	 * @param string $value
	 */
	public function setApplicationPath($value) {
		$this->set(Parameter::APP_PATH,$value);
	}
	
	/**
	 * Returns the full path to the current module.
	 *
	 * @return string
	 */
	public function getModulePath() {
		if($this->get(Parameter::MODULE_PATH) == null) {
			return 
				$this->getApplicationPath().DIRECTORY_SEPARATOR.
				'modules'.DIRECTORY_SEPARATOR.
				Context::getHttpRequest()->getController();
		}
		return $this->get(Parameter::MODULE_PATH);
	}
	
	/**
	 * Sets the full path to the module.
	 *
	 * @param string $value
	 */
	public function setModulePath($value) {
		$this->set(Parameter::MODULE_PATH,$value);
	}
	
	/**
	 * Returns the framework global registry.
	 *
	 * @return ParameterHolder
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>