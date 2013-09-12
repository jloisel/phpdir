<?php

/**
 * A Route is a rule defined by user which contains the following information :
 * - URL to match : regular expression to match with the query
 * 
 * - Parameters : Default Controller and Action to load
 * 
 * - Extracted Parameters : in case the URL to match contains parameters to extract,
 *   this array indicates the name of each extracted dynamic parameter
 * 
 * - Routed URL : generic url which may be filled with dynamic parameters in order 
 *   to obtain a routed url
 * 
 * - Matches : Contains the matches between URL to match and query when both are 
 *   matching.
 * 
 * @author Jerome Loisel
 */
class PatternRoute implements Route {
	
	/**
	 * Route name.
	 *
	 * @var string
	 */
	protected $name = null;
	
	/**
	 * The requested URL to match.
	 *
	 * @var string
	 */
	protected $url = null;
	/**
	 * Controller and Action to load.
	 * Ex: array(
     *   'controller' => 'blog',
     *   'action'     => 'view')
     * 
	 * @var array
	 */
	protected $parameters = null;
	
	/**
	 * requirements on parameters.
	 *
	 * @var array
	 */
	protected $requirements = null;
	
	/**
	 * Default constructor.
	 *
	 * @param String $url (the requested URL)
	 * @param Array $params Ex: array('controller' => 'blog', 'action' => 'index')
	 * @param Array $_extractedParams Ex: array(1 => 'id', 2 => 'description')
	 */
	public function __construct($name, $url, $params=null, $requirements=null) {
		$this->name = $name;
		$this->url = $url;
		$this->parameters = $params;
		$this->requirements = $requirements;
	}
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}
	
	/**
	 * @return array
	 */
	public function getRequirements() {
		return $this->requirements;
	}
	
	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
	
	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * @param array $parameters
	 */
	public function setParameters($parameters) {
		$this->parameters = $parameters;
	}
	
	/**
	 * @param array $requirements
	 */
	public function setRequirements($requirements) {
		$this->requirements = $requirements;
	}
	
	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}
}

?>