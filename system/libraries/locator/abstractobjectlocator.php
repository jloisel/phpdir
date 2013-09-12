<?php

/**
 * Object locator base implementation.
 * An object locator is responsible of 
 * listing local and remote objects.
 * 
 * @author Jerome Loisel
 */
abstract class AbstractObjectLocator extends Singleton implements ObjectLocator {
	
	/**
	 * Objects installed locally.
	 *
	 * @var array
	 */
	protected $localObjects = null;
	
	/**
	 * Total remote objects count.
	 *
	 * @var integer
	 */
	protected $remoteObjectsCount = null;
	
	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Returns all the localizable objects found locally 
	 * on the server.
	 *
	 * @return array
	 */
	public function getAllLocalObjects() {
		if($this->localObjects == null) {
			$this->localObjects = array();
			$directory = $this->getLocalObjectsLocation();
			if($directory->exists()) {
				$subDirs = $directory->getSubdirectories();
				if(is_array($subDirs) && count($subDirs) > 0) {
					foreach($subDirs as $subDir) {
						$object = $this->getLocalLocalizableObject($subDir);
						if($object != null && is_object($object)) {
							$this->localObjects[$object->getLocalFolder()] = $object;
						}
					}
				}
			}
		}
		return $this->localObjects;
	}
	
	/**
	 * Returns the localizable object with the passed 
	 * name.
	 * If none available, returns null;
	 *
	 * @param string $name
	 */
	public function getLocalObject($name) {
		$objects = $this->getAllLocalObjects();
		return isset($objects[$name]) ? $objects[$name] : null;
	}
	
	/**
	 * Returns the total count of remote objects.
	 *
	 * @return integer
	 */
	public function getRemoteObjectsCount() {
		if($this->remoteObjectsCount == null) {
			$url = $this->getRemoteObjectsLocation().'&action=count';
			$parser = new RemoteObjectCountXMLParser($url);
			$this->remoteObjectsCount = $parser->parse();
		}
		return $this->remoteObjectsCount;
	}
	
	/**
	 * Returns all the remote objects found 
	 * when parsing the XML at the $url location.
	 *
	 * @param string $url
	 * @return array of LocalizableObject
	 */
	protected function getAllRemoteObjects($url) {
		return $this->getRemoteObjectXMLParser($url)->parse();
	}
	
	/**
	 * Returns remote objects available online.
	 *
	 * @param integer $start
	 * @param integer $limit
	 * @return array of LocalizableObject
	 */
	public function getRemoteObjects($start=0,$limit=10) {
		$url = $this->getRemoteObjectsLocation().'&start='.intval($start).'&limit='.intval($limit);
		return $this->getAllRemoteObjects($url);
	}
	
	/**
	 * Finds remote objects on the remote server.
	 *
	 * @param string $search
	 * @return array of LocalizableObject
	 */
	public function findRemoteObjects($search,$start=0,$limit=10) {
		$url = $this->getRemoteObjectsLocation()
				.'&start='.intval($start).'&limit='.intval($limit).'&search='.urlencode($search);
		return $this->getAllRemoteObjects($url);
	}
	
	/**
	 * Returns the local localizable object.
	 * (if any)
	 *
	 * @param File $object
	 * @return LocalizableObject
	 */
	protected function getLocalLocalizableObject(File $directory) {
		require_once $directory->getFullName().DIRECTORY_SEPARATOR.LocalizableObject::DEFINITION_FILE;
		if(is_array($__DEFINITION)) {
			$__DEFINITION[LocalizableObject::LOCAL_FOLDER] = $directory->getName();
			$object = $this->newLocalizableObject();
			if(is_object($object)) {
				$object->initialize($__DEFINITION);
				return $object;
			}
		}
		return null;
	}
	
	/**
	 * Finds an returns a local object by its name.
	 * Returns NULL if the local object has not been found.
	 * 
	 * @param string $name
	 */
	public function findLocalObjectByName($name) {
		$localObjects = $this->getAllLocalObjects();
		return isset($localObjects[$name]) ? $localObjects[$name] : null;
	}
	
	/**
	 * Returns a new instance of the localizable object 
	 * handled by the concrete parser.
	 *
	 * @return LocalizableObject
	 */
	protected abstract function newLocalizableObject();
	
	/**
	 * Returns the file mapping the folder containing 
	 * sub-folders which are each a localizable object.
	 *
	 * @return File
	 */
	protected abstract function getLocalObjectsLocation();
	
	/**
	 * Returns the URL where the XML describing the remote 
	 * objects is located.
	 *
	 * @return string
	 */
	protected abstract function getRemoteObjectsLocation();
	
	/**
	 * Returns the remote object XML parser.
	 *
	 * @return RemoteObjectXMLParser
	 */
	protected abstract function getRemoteObjectXMLParser($url);
}

?>