<?php

/**
 * The service locator scans the services 
 * folders and registers them.
 *
 * @author Jerome Loisel
 */
class ServiceLocator extends Singleton {
	
	/**
	 * All service types stored in system/Services 
	 * folder.
	 *
	 * @var array
	 */
	protected $types = null;
	
	/**
	 * Are the services of a certain type loaded ?
	 * 
	 * @var array
	 */
	protected $isServiceTypeLoaded = array();
	
	/**
	 * All the installed services.
	 *
	 * @var array
	 */
	protected $services = array();
	
	/**
	 * Remote services. (non-installed services)
	 * These services can be downloaded and installed.
	 *
	 * @var array
	 */
	protected $remoteServices = null;
	
	/**
	 * This array keeps in mind which 
	 * service has already been loaded.
	 *
	 * @var array
	 */
	protected $loaded = array();
	
	/**
	 * Default constructor.
	 *
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Retrieve a unique service instance.
	 *
	 * @param string $type
	 * @param string $service
	 * 
	 * @return Service
	 */
	public function getService($type,$name) {
		if(!isset($this->services[$type][$name])) {
			$service = new $name();
			$service->setType($type);
			$this->services[$type][$name] = $service;
		}
		return $this->services[$type][$name];
	}
	
	/**
	 * Returns an array of PHP files within the service 
	 * type directory.
	 * 
	 * Example:
	 * array(
	 * 		'type' => array(
	 * 			'service' => File
	 * 		)
	 * )
	 *
	 * @param string $type
	 * @return array
	 */
	public function getServices($type) {
		if(!isset($this->isServiceTypeLoaded[$type])) {
			$file = new File(self::getServicesDiskLocation().'/'.$type);
			if($file->isDirectory()) {
				$files = $file->getFiles(new PHPFileFilter());
				if(is_array($files)) {
					foreach($files as $file) {
						$name = strtolower(str_replace('.'.$file->getExtension(),'',$file->getName()));
						$this->getService($type,$name);
					}
				}
			}
			$this->isServiceTypeLoaded[$type] = true;
		}
		return $this->services[$type];
	}
	
	/**
	 * Returns all service types stored in system.
	 *
	 * @return array
	 */
	public function getServiceTypes() {
		if($this->types == null) {
			$this->types = array();
			$file = new File(self::getServicesDiskLocation());
			if($file->isDirectory()) {
				$directories = $file->getFiles(new DirectoryFilter());
				if(is_array($directories) && count($directories) > 0) {
					foreach($directories as $directory) {
						$this->types[] = strtolower($directory->getName());
					}
				}
			}
		}
		return $this->types;
	}
	
	/**
	 * Returns all the services available. (local and remote)
	 * 
	 * @return string
	 */
	public function getAllServices() {
		$all = array();
		$types = $this->getServiceTypes();
		if(is_array($types) && count($types) > 0) {
			$remote = $this->getRemoteServices();
			foreach($types as $type) {
				$local = $this->getServices($type);
				$all[$type] = array_merge($local,$remote[$type]);
			}
		}
		return $all;
	}
	
	/**
	 * Retrieves services from online repository.
	 * 
	 * Example:
	 * array(
	 * 		'type' => array(
	 * 			OnlineService(), ...
	 * 		)
	 * )
	 * 
	 * @return array
	 */
	public function getAllRemoteServices() {
		if($this->remoteServices == null) {
			$parser = new ServicesXMLParser(Context::getRepository()->getServicesDescriptorURL());
			$this->remoteServices = $parser->parse();
		}
		return $this->remoteServices;
	}
	
	/**
	 * Returns remote services of a certain type.
	 * 
	 * @param $type
	 * @return array NULL if none
	 */
	public function getRemoteServices($type) {
		$services = $this->getAllRemoteServices();
		return isset($services[$type]) ? $services[$type] : null;
	}
	
	/**
	 * Returns a certain remote service.
	 * 
	 * @param $type
	 * @param $name
	 * @return OnlineService
	 */
	public function getRemoteService($type,$name) {
		$services = $this->getRemoteServices($type);
		return isset($services[$name]) ? $services[$name] : null;
	}
	
	/**
	 * Returns the services disk location (on server disk).
	 *
	 * @return string
	 */
	public static function getServicesDiskLocation() {
		return Context::getParameterHolder()->get(Parameter::PUBLIC_PATH).'/services';
	}
	
	/**
	 * Service locator singleton instance.
	 *
	 * @return ServiceLocator
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>
