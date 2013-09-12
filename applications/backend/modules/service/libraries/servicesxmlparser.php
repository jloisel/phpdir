<?php

/**
 * The services XML parser parses the 
 * online XML retrieved from Repository.
 *
 * @author Jerome Loisel
 */
class ServicesXMLParser {
	
	/**
	 * The URL of the services XML remote file.
	 *
	 * @var string
	 */
	protected $url = null;
	
	/**
	 * Default constructor.
	 */
	public function __construct($url) {
		$this->url = $url;
	}
	
	/**
	 * Retrieves and parses the 
	 * remote XML describing the services 
	 * available online.
	 *
	 * Returns:
	 * array(
	 * 		'type' => array(
	 * 			OnlineService(), ...
	 * 		)
	 * )
	 *
	 * @return array
	 */
	public function parse() {
		$cm = new FeedCacheManager();
		$servicesXML = $cm->getFeed($this->url);
		$services = array();
		if(is_object($servicesXML)) {
			foreach($servicesXML->children() as $type) {
				$typeName = (string)$type['name'];
				foreach($type->children() as $service) {
					$serviceName = (string)$service['name'];
					$service = new OnlineService(
						(string)$typeName,
						$serviceName, 
						(string)$service['description'],
						(string)$service['version']
					);
					$services[$typeName][$serviceName] = $service;
				}
			}
		}
		return $services;
	}
}

?>