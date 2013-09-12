<?php

/**
 * The service adapter allows to easily 
 * design a service sub-class without the need 
 * to implement all service interface methods.
 * 
 * @author Jerome
 *
 */
abstract class ServiceAdapter implements Service {
	
	/**
	 * Default service version.
	 * 
	 * @var string
	 */
	const DEFAULT_VERSION = '1.0';
	
	/**
	 * Type of the service. (Captcha, Thumbnail...)
	 * 
	 * @var string
	 */
	protected $type = null;
	
	/**
	 * Constructor.
	 * @return void
	 */
	protected function __construct() {
		
	}
	
	/**
	 * Sets the type of the service.
	 * 
	 * @param $type
	 */
	public function setType($type) {
		$this->type = strtolower($type);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#getType()
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#getName()
	 */
	public function getName() {
		return strtolower(get_class($this));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#getVersion()
	 */
	public function getVersion() {
		return self::DEFAULT_VERSION;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#install()
	 */
	public function install() {
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#uninstall()
	 */
	public function uninstall() {
		return true;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#getConfigurationForm()
	 */
	public function getConfigurationForm() {
		return null;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#onValidConfigurationForm()
	 */
	public function onValidConfigurationForm(sfI18nForm $form) {
		
	}
	
}

?>