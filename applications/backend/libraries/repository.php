<?php

/**
 * The repository is a mapping of the 
 * information available online like:
 * - services
 * - modules
 * - themes
 * - ...
 * 
 * @author Jerome Loisel
 */
class Repository extends Singleton {
	
	/**
	 * Repository location.
	 * Example: http://www.phpdir.org
	 *
	 * @var string
	 */
	protected $location = null;
	
	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct();
		$this->location = Config::get('repository');
	}
	
	/**
	 * Returns the services XML descriptor 
	 * URL on remote repository.
	 *
	 * @return unknown
	 */
	public function getServicesDescriptorURL() {
		return $this->location.'/services.xml';
	}
	
	/**
	 * Returns the URL from where the services can be 
	 * downloaded.
	 * The URL must be formatted using strtr() and the following parameters 
	 * must be provided: 
	 * - type: string. Type of the service
	 * - service: string. Name of the service
	 *
	 * @return string
	 */
	public function getServicesDownloadURL() {
		return $this->location.'/download.php?id=service&type=:type&service=:service';
	}
	
	/**
	 * Returns the URL of the remote XML describing 
	 * the available modules.
	 *
	 * @return string
	 */
	public function getModulesDescriptorURL() {
		return $this->location.'/modules.xml';
	}
	
	/**
	 * Returns the URL where the module can be downloaded.
	 * The URL must be formatted using strtr() and the following parameters 
	 * must be provided: 
	 * - module: string. Name of the module.
	 * 
	 * @return string
	 */
	public function getModulesDownloadURL() {
		return $this->location.'/download.php?id=module&module=:module';
	}
	
	/**
	 * Returns the URL of the XML file describing all 
	 * available themes online.
	 *
	 * @return string
	 */
	public function getThemesDescriptorURL() {
		return $this->location.'/themes.xml';
	}
	
	/**
	 * Returns the URL of the location where the themes can be 
	 * downloaded.
	 * The URL must be formatted using strtr() and the following parameters 
	 * must be provided: 
	 * - theme: string. Name of the theme.
	 * 
	 * @return string
	 */
	public function getThemesDownloadURL() {
		return $this->location.'/download.php?id=theme&theme=:theme';
	}
	
	/**
	 * Returns the unique instance of the Repository.
	 *
	 * @return Repository
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
	
}

?>