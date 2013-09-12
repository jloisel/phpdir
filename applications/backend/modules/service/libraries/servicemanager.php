<?php 

/**
 * Installs new services on the directory.
 *
 * @author Jerome Loisel
 */
class ServiceManager extends Singleton {
	
	/**
	 * Default constructor.
	 *
	 * @param string $repository Example: http://www.phpdir.org/download.php
	 * @param string $type Example: Captcha
	 * @param string $service Example: Websnapr
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Installs a new service by downloading it from server and 
	 * then copies it on server disk.
	 *
	 * @return boolean TRUE if success, else FALSE
	 */
	public function install($type, $service) {
		$request = new RemoteRequest($this->getInstallUrl($type, $service));
		$file = $this->getServiceFile($type, $service);
		$content = $request->getContent();
		if($content != null) {
			$file->setContent($content);
			unset($content);
			return $file->write();
		}
		return false;
	}
	
	/**
	 * Returns the service file.
	 * 
	 * @param $type
	 * @param $service
	 * @return File
	 */
	private function getServiceFile($type,$service) {
		return new File(
			ServiceLocator::getServicesDiskLocation()
				.'/'.strtolower($type)
				.'/'.strtolower($service).'.php'
		);
	}
	
	/**
	 * Uninstalls a service.
	 * As a service is a single file, the 
	 * file is simply deleted.
	 *
	 */
	public function uninstall($type, $service) {
		$file = $this->getServiceFile($type, $service);
		if($file->exists()) {
			$file->delete();
		}
	}
	
	/**
	 * The service is installed if the file exists on disk.
	 * 
	 * @param $service
	 * @return boolean
	 */
	public function isInstalled(Service $service) {
		$file = $this->getServiceFile($service->getType(), $service->getName());
		return $file != null && $file->exists();
	}
	
	/**
	 * The service is up-to-date if the version of the local 
	 * service is equal to the version of the remote service.
	 * 
	 * @param Service $local
	 * @param OnlineService $online
	 * @return boolean
	 */
	public function isUptoDate(Service $local, $online) {
		if($local == null || $online == null) {
			return true;
		}
		return version_compare($local->getVersion(), $online->getVersion()) == 0;
	}
	
	/**
	 * Is the passed service selected ?
	 * @param $service
	 * @return boolean
	 */
	public function isSelected(Service $service) {
		return Config::get($service->getType().'_service') == $service->getName();
	}
	
	/**
	 * Sets the passed service as the selected one 
	 * for the given type. 
	 * 
	 * @param $type
	 * @param $service
	 * @return void
	 */
	public function setSelectedService($type,$service) {
		Config::setItem($type.'_service',$service);
	}
	
	/**
	 * Returns the default service for the 
	 * given type.
	 * 
	 * @param $type
	 * @return string
	 */
	public function getSelectedService($type) {
		return Config::get($type.'_service');
	}
	
	/**
	 * Returns the full service install URL.
	 *
	 * @return string
	 */
	private function getInstallUrl($type, $service) {
		return strtr(
			Context::getRepository()->getServicesDownloadURL(), 
			array(':type' => strtolower($type), ':service' => strtolower($service))
		);
	}
	
	/*
	 * Returns the unique instance of the service manager.
	 * 
	 * @return ServiceManager
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>