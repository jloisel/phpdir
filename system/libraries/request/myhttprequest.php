<?php

/**
 * An Http request encapsulates $_GET and $_POST parameters.
 * Http parameters should always be retrieved through this object.
 * Http Request is a proxy between code and Http parameters.
 * @author Jerome Loisel
 */
class MyHttpRequest extends Singleton {
	/**
	 * Internal character encoding (utf-8, iso-8859-1...)
	 */
	protected $_encoding = null;
	/**
	 * Request method (POST, GET and REQUEST)
	 */
	protected $_requestMethod = null;
	
	/**
	 * Controller HTTP request parameter.
	 *
	 */
	const CONTROLLER_PARAM = 'controller';

	/**
	 * Action HTTP request parameter.
	 *
	 */
	const ACTION_PARAM = 'action';
	
	/**
	 * This HTTP request parameter is the previous controller 
	 * who has made the forward to the current controller/action.  
	 *
	 */
	const FORWARD_CONTROLLER_PARAM = 'forwarded_from_controller';
	
	/**
	 * This HTTP request parameter is the previous action 
	 * who has made the forward to the current controller/action.
	 *
	 */
	const FORWARD_ACTION_PARAM = 'forwarded_from_action';
	
	protected static $_availableRequestMethods = array(
		RequestMethod::GET,
		RequestMethod::POST,
		RequestMethod::REQUEST
	);
	
	/**
	 * Request query string. Can be modified.
	 *
	 * @var string
	 */
	protected $queryString = null;

	/**
	 * Default constructor
	 */
	protected function __construct() {
		parent::__construct();
		$this->_encoding = 'utf-8';
		$this->initRequestMethod($_SERVER['REQUEST_METHOD']);
		if(!function_exists('mb_check_encoding')) {
			Config::setItem('encoding','ISO-8859-1');
		}
	}

	/**
	 * Initialize Request method.
	 *
	 */
	public function initRequestMethod($requestMethod) {
		if(	$requestMethod !=null && in_array(strtoupper($requestMethod),self::$_availableRequestMethods)) {
			$this->setRequestMethod($requestMethod);
		} else {
			$this->setRequestMethod(RequestMethod::GET);
		}
	}

	/**
	 * Returns defined encoding
	 * @return	String encoding
	 */
	public function getEncoding() {
		return $this->_encoding;
	}

	/**
	 * Sets encoding.
	 * @param	String encoding
	 */
	public function setEncoding($encoding) {
		if(!empty($encoding) && is_string($encoding)) {
			if(function_exists('mb_strtolower')) {
				$this->_encoding = mb_strtolower($encoding);
			} else {
				$this->_encoding = strtolower($encoding);
			}
		}
	}

	/**
	 * Returns the request method.
	 * @return	String request method
	 */
	public function getRequestMethod() {
		return $this->_requestMethod;
	}

	/**
	 * Sets request method.
	 * @param	String request method (GET or POST)
	 */
	public function setRequestMethod($requestMethod) {
		if(!empty($requestMethod) && is_string($requestMethod)) {
			if(function_exists('mb_strtoupper')) {
				$this->_requestMethod = mb_strtoupper($requestMethod);
			} else {
				$this->_requestMethod = strtoupper($requestMethod);
			}
		}
	}

	/**
	 * Returns a parameter value considering the request method.
	 */
	private function getParameterByMethod($paramName) {
		$paramValue = null;
		switch($this->_requestMethod) {
			case RequestMethod::GET:
				if(isset($_GET[$paramName])) {
					$paramValue = $_GET[$paramName];
				}
				break;

			case RequestMethod::POST:
				if(isset($_POST[$paramName])) {
					$paramValue = $_POST[$paramName];
				}
				break;

			default:
			case RequestMethod::REQUEST:
				if(isset($_REQUEST[$paramName])) {
					$paramValue = $_REQUEST[$paramName];
				}
				break;
		}
		return $paramValue;
	}

	/**
	 * Is the parameter defined in the request ?
	 *
	 * @param String $paramName
	 * @return Boolean
	 */
	public function hasParameter($paramName) {
		$paramValue = $this->getParameterByMethod($paramName);

		$hasParam = $paramValue !== null && !empty($paramValue) && $paramValue !== 0;

		return $hasParam;
	}

	/**
	 * Returns a parameter value considering the request method.
	 */
	private function setParameterByMethod($paramName,$paramValue) {
		switch($this->_requestMethod) {
			case RequestMethod::GET:
				$_GET[$paramName] = $paramValue;
				break;

			case RequestMethod::POST:
				$_POST[$paramName] = $paramValue;
				break;

			default:
			case RequestMethod::REQUEST:
				$_REQUEST[$paramName] = $paramValue;
				break;
		}
		return $paramValue;
	}

	/**
	 * Checks the string encoding and compares it to
	 * needed encoding. Encodes string if needed.
	 * @param	String string to check
	 * @return	String encoded string
	 */
	private function fitStringToEncoding($string) {
		if(function_exists('mb_check_encoding')) {
			if(!mb_check_encoding($string,$this->_encoding)) {
				if(function_exists('mb_check_encoding')) {
					$string = mb_convert_encoding($string,$this->_encoding);
				}
			}
		}
		return get_magic_quotes_gpc() ? stripslashes($string) : $string;
	}

	/**
	* Checks the array values encoding and encodes string
	* if needed.
	* @param Array
	*/
	private function fitArrayToEncoding($array) {
		if(is_array($array) && count($array) > 0) {
			foreach($array as $key => $value) {
				if(is_string($value)) {
					$value = $this->fitStringToEncoding($value);
				} else if(is_array($value)) {
					$value = $this->fitArrayToEncoding($value);
				}
				$array[$key] = $value;
			}
		}
		return $array;
	}

	/**
	 * Returns the request parameter value.
	 * @param	String $paramName parameter name
	 * @param	String $paramDefaultValue parameter default value (if not set or empty)
	 * @return	String parameter value
	 */
	public function getParameter($paramName, $paramDefaultValue=null) {
		$paramValue = '';
		if(!empty($paramName)) {
			$paramValue = $this->getParameterByMethod($paramName);
			if($paramValue != null) {
				if(is_string($paramValue)) {
					$paramValue = $this->fitStringToEncoding($paramValue);
				} else if(is_array($paramValue)) {
					$paramValue = $this->fitArrayToEncoding($paramValue);
				}
			} else {
				$paramValue = $paramDefaultValue;
			}
		}
		return $paramValue;
	}

	/**
	 * Sets a parameter, used by router.
	 *
	 * @param String $name
	 * @param String $value
	 */
	public function setParameter($name,$value) {
		$this->setParameterByMethod($name,$value);
	}

	/**
	 * Is the request an Ajax request ?
	 *
	 * @return boolean
	 */
	public function isXmlHttpRequest() {
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			return (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
		}
		return false;
	}

	/**
	 * Returns all the request parameters.
	 *
	 * @return Array
	 */
	public function getParameters() {
		$parameters = null;
		switch($this->_requestMethod) {
			case RequestMethod::GET:
				$parameters = $_GET;
				break;

			case RequestMethod::POST:
				$parameters = $_POST;
				break;

			default:
			case RequestMethod::REQUEST:
				$parameters = $_REQUEST;
				break;
		}
		$parameters = $this->fitArrayToEncoding($parameters);
		return $parameters;
	}

	/**
	 * Removes a parameter from request.
	 *
	 * @param String $paramName
	 */
	public function removeParameter($paramName) {
		if(isset($_GET[$paramName])) {
			unset($_GET[$paramName]);
		} else if(isset($_REQUEST[$paramName])) {
			unset($_REQUEST[$paramName]);
		} else if(isset($_POST[$paramName])) {
			unset($_POST[$paramName]);
		}
	}

	/**
	 * Cleanes Routing parameters and initialize request method.
	 */
	public function clean() {
		$this->removeParameter('action');
		$this->removeParameter('controller');
		$this->initRequestMethod($_SERVER['REQUEST_METHOD']);
	}

	/**
	* Gets a cookie value.
	*
	* @return mixed
	*/
	public function getCookie($name, $defaultValue = null) {
		$retval = $defaultValue;
		if (isset($_COOKIE[$name])) {
			$retval = get_magic_quotes_gpc() ? stripslashes($_COOKIE[$name]) : $_COOKIE[$name];
		}
		return $retval;
	}
	
	/**
	 * Returns the server path info, if any.
	 *
	 * @return string
	 */
	protected function getServerPathInfo() {
		return isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	}
	
	/**
	 * Returns the query string.
	 *
	 * @return string
	 */
	public function getQueryString() {
		if($this->queryString == null) {
			$this->queryString = $this->getServerPathInfo();
		}
		return $this->queryString;
	}
	
	/**
	 * Sets the query string.
	 *
	 * @param string $queryString
	 */
	public function setQueryString($queryString) {
		if(is_string($queryString)) {
			$this->queryString = $queryString;
		}
	}
	
	/**
	 * Returns the controller name resolved by Router.
	 *
	 * @return string
	 */
	public function getController() {
		return $this->getParameter(self::CONTROLLER_PARAM);
	}
	
	/**
	 * Returns the action resolved by the router.
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->getParameter(self::ACTION_PARAM);
	}
	
	public function setController($controller) {
		$this->setParameter(self::CONTROLLER_PARAM,$controller);
	}
	
	public function setAction($action) {
		$this->setParameter(self::ACTION_PARAM,$action);
	}
	
	/**
	 * The controller who has forwarded the request to 
	 * the current controller.
	 * Returns the name of the controller.
	 *
	 * @return string
	 */
	public function getForwardedFromController() {
		return $this->getParameter(self::FORWARD_CONTROLLER_PARAM);
	}
	
	/**
	 * The action who has forwarded the request to the current 
	 * action.
	 * Returns the name of the action.
	 * 
	 * @return string
	 */
	public function getForwardedFromAction() {
		return $this->getParameter(self::FORWARD_ACTION_PARAM);
	}

	/**
	* Unique instance of HttpRequest.
	* @return HttpRequest
	*/
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>