<?php

/**
 * This class maps a single online service.
 * 
 * @author Jerome
 *
 */
class OnlineService extends ServiceAdapter {
	
	/**
	 * Service name.
	 * 
	 * @var string
	 */
	private $name = null;
	
	/**
	 * Service description.
	 * 
	 * @var string
	 */
	private $description = null;
	
	/**
	 * Service version.
	 * 
	 * @var string
	 */
	private $version = null;
	
	/**
	 * Default constructor.
	 * 
	 * @param $type
	 * @param $name
	 * @param $description
	 * @param $version
	 * @return unknown_type
	 */
	public function __construct($type, $name, $description, $version) {
		parent::__construct();
		$this->name = $name;
		$this->description = $description;
		$this->version = $version;
		$this->setType($type);
	}
	
	/**
	 * Returns the name of the service.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/Service#getDescription()
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see system/libraries/services/ServiceAdapter#getVersion()
	 */
	public function getVersion() {
		return $this->version;
	}
}

?>