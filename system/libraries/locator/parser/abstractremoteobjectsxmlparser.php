<?php

/**
 * Base implementation of the XML parser
 * which is able to parse and return 
 * localizable objects available online.
 *
 * @author Jerome Loisel
 */
abstract class AbstractRemoteObjectXMLParser implements RemoteObjectXMLParser {
	
	/**
	 * Online location of the remote 
	 * localizable objects XML definition file.
	 *
	 * @var string
	 */
	protected $url = null;
	
	/**
	 * Parser cache manager.
	 *
	 * @var FeedCacheManager
	 */
	protected $cacheManager = null;
	
	/**
	 * Constructor.
	 *
	 * @param string $url
	 */
	public function __construct($url) {
		$this->url = $url;
	}
	
	/**
	 * Returns the instance of the cache manager.
	 *
	 * @return FeedCacheManager
	 */
	protected function getCacheManager() {
		if($this->cacheManager == null) {
			$this->cacheManager = new FeedCacheManager();
		}
		return $this->getCacheManager();
	}
	
	/**
	 * Parses the XML defining the themes which 
	 * is located at the passed URL.
	 *
	 * Default XML format:
	 * <objects>
	 * 		<object name="name" description="..." author="Jerome Loisel" version="1.0" /> 
	 * </objects>
	 * 
	 * @return array of LocalizableObject
	 */
	public function parse() {
		$xml = $this->getXML();
		
		$modules = array();
		if(is_object($xml)) {
			foreach($xml->children() as $object) {
				$name = (string)$object[LocalizableObject::NAME];
				$object = $this->newLocalizableObject();
				$object->initialize(array(
					LocalizableObject::NAME => $name, 
					LocalizableObject::DESCRIPTION => (string)$object[LocalizableObject::DESCRIPTION], 
					LocalizableObject::AUTHOR => (string)$object[LocalizableObject::AUTHOR], 
					LocalizableObject::VERSION => (string)$object[LocalizableObject::VERSION], 
					LocalizableObject::IS_INSTALLED => false,
					LocalizableObject::IS_UPTODATE => true
				));
				$modules[$name] = $object;
			}
		}
		return $modules;
	}
	
	/**
	 * Returns the XML which is 
	 * located at the URL.
	 *
	 * @return SimpleXMLElement
	 */
	protected function getXML() {
		return $this->getCacheManager()->getFeed($this->url);
	}
	
	/**
	 * Returns a new instance of the localizable object 
	 * handled by the concrete parser.
	 *
	 * @return LocalizableObject
	 */
	protected abstract function newLocalizableObject();
	
}

?>