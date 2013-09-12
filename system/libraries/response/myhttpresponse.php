<?php

/**
 * Response is the content sent/displayed to client.
 * @author Jerome Loisel
 */
class MyHttpResponse extends Singleton {
	/**
	 * View Mode {@link View}
	 *
	 * @var Integer
	 */
	protected $_viewMode = View::HEAD;
	
	/**
	 * HTTP Header status code.
	 *
	 * @var Integer
	 */
	protected $_statusCode = 200;
	
	/**
	 * HTTP header status text.
	 *
	 * @var String
	 */
	protected $_statusText = 'OK';
	
	/**
	 * HTTP code & Status text association.
	 *
	 * @var Array
	 */
	protected $_statusTexts = null;
	
	/**
	 * HTTP headers, added by user.
	 *
	 * @var Array
	 */
	protected $_httpHeaders = null;
	
	/**
	 * Cookies set by user.
	 *
	 * @var Array
	 */
	protected $_cookies = null;
	
	/**
	*	Template layout to use
	*/
	protected $_layout = 'layout.php';
	
	/**
	 * Has the output already been sent ?
	 *
	 * @var boolean
	 */
	protected $_sent = false;
	
	/**
	 * Default constructor
	 *
	 * @param Integer $viewMode
	 */
	protected function __construct() {
		parent::__construct();
		$this->initialize();
	}
	
	/**
	 * Http response initialization.
	 * Defines all the status code & text associations.
	 */
	protected function initialize() {
		$this->_statusTexts = array(
	      '100' => 'Continue',
	      '101' => 'Switching Protocols',
	      '200' => 'OK',
	      '201' => 'Created',
	      '202' => 'Accepted',
	      '203' => 'Non-Authoritative Information',
	      '204' => 'No Content',
	      '205' => 'Reset Content',
	      '206' => 'Partial Content',
	      '300' => 'Multiple Choices',
	      '301' => 'Moved Permanently',
	      '302' => 'Found',
	      '303' => 'See Other',
	      '304' => 'Not Modified',
	      '305' => 'Use Proxy',
	      '306' => '(Unused)',
	      '307' => 'Temporary Redirect',
	      '400' => 'Bad Request',
	      '401' => 'Unauthorized',
	      '402' => 'Payment Required',
	      '403' => 'Forbidden',
	      '404' => 'Not Found',
	      '405' => 'Method Not Allowed',
	      '406' => 'Not Acceptable',
	      '407' => 'Proxy Authentication Required',
	      '408' => 'Request Timeout',
	      '409' => 'Conflict',
	      '410' => 'Gone',
	      '411' => 'Length Required',
	      '412' => 'Precondition Failed',
	      '413' => 'Request Entity Too Large',
	      '414' => 'Request-URI Too Long',
	      '415' => 'Unsupported Media Type',
	      '416' => 'Requested Range Not Satisfiable',
	      '417' => 'Expectation Failed',
	      '500' => 'Internal Server Error',
	      '501' => 'Not Implemented',
	      '502' => 'Bad Gateway',
	      '503' => 'Service Unavailable',
	      '504' => 'Gateway Timeout',
	      '505' => 'HTTP Version Not Supported',
	    );
	    
	    $this->_httpHeaders = array();
	}
	
	/**
	 * Sets the HTTP status code.
	 *
	 * @param Integer $code
	 */
	public function setStatusCode($code) {
		if(is_int($code) && array_key_exists($code,$this->_statusTexts)) {
			$this->_statusCode = $code;
			$this->_statusText = $this->_statusTexts[$code];
		} else {
			throw new BaseException('HTTP status code must be an Integer and a valid HTTP status');
		}
	}
	
	/**
	 * Sets an Http Header. (Overwrites if already existing)
	 *
	 * @param String $name
	 * @param String $value
	 */
	public function setHttpHeader($name,$value) {
		if(!empty($name) && !empty($value)) {
			$this->_httpHeaders[$name] = $value;
		}
	}
	
	/**
	 * Sets the content type of the output response.
	 *
	 * @param String $contentType
	 */
	public function setContentType($contentType='text/html') {
		if(!empty($contentType)) {
			$this->_httpHeaders['content-type'] = $contentType;
		}
	}
	
	/**
	 * Set the view mode of the response.
	 *
	 * @param Integer $view
	 */
	public function setViewMode($view) {
		$view = intval($view);
		if(in_array($view,View::$availableViewModes)) {
			$this->_viewMode = $view;
		} else {
			if(!is_integer($view)) {
				throw new BaseException('"'.$view.'" is not a valid View mode (currently "'.$this->_viewMode.'")');
			}
		}
	}
	
	/**
	* Sets a cookie.
	*
	* @param String HTTP header name
	* @param String Value for the cookie
	* @param String Cookie expiration period
	* @param String Path
	* @param String Domain name
	* @param Boolean If secure
	* @param Boolean If uses only HTTP
	*
	* @throws Exception If fails to set the cookie
	*/
	public function setCookie($name, $value, $expire = null, $path = '/', $domain = '', $secure = false, $httpOnly = false)
	{
		if ($expire !== null) {
			if (is_numeric($expire)) {
				$expire = (int) $expire;
			} else {
				$expire = strtotime($expire);
				if ($expire === false || $expire == -1) {
					throw new BaseException('Your Cookie expire parameter is not valid.');
				}
			}
		}
	
		if(!empty($name)) {
			$this->_cookies[$name] = array(
			'name'     => $name,
			'value'    => $value,
			'expire'   => $expire,
			'path'     => $path,
			'domain'   => $domain,
			'secure'   => $secure ? true : false,
			'httpOnly' => $httpOnly,
			);
		}
	}
	
	/**
	* Retrieves a normalized Header.
	*
	* @param String Header name
	* @return String Normalized header
	*/
	protected function normalizeHeaderName($name) {
		return preg_replace('/\-(.)/e', "'-'.strtoupper('\\1')", strtr(ucfirst(strtolower($name)), '_', '-'));
	}
	
	/**
	 * Send everything to browser.
	 */
	public function send() {
		if(!$this->_sent) {
			if($this->_viewMode != View::NONE) {
				$this->sendHttpHeaders();
				$this->sendCookies();
				if($this->_viewMode == View::TPL) {
					$this->sendContent();
				}
			}
			$this->_sent = true;
		}
	}
	
	/**
	 * Sends HTTP headers to browser.
	 */
	protected function sendHttpHeaders() {
		$status = 'HTTP/1.0 '.$this->_statusCode.' '.$this->_statusText;
		header($status);
		if(is_array($this->_httpHeaders)) {
			foreach ($this->_httpHeaders as $name => $value) {
				header($this->normalizeHeaderName($name).': '.$value);
			}
		}
	}
	
	/**
	 * Sends the Cookies to browser.
	 */
	protected function sendCookies() {
		if(is_array($this->_cookies)) {
			$phpversion = phpversion();
			foreach ($this->_cookies as $cookie) {
				if (version_compare($phpversion, '5.2', '>='))	{
					setrawcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httpOnly']);
				} else {
					setrawcookie($cookie['name'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure']);
				}
			}
		}
	}
	
	/**
	 * Sends the response to the client.
	 */
	protected function sendContent() {
		Context::getTemplateEngine()->display($this->_layout);
	}
	
	public function setLayout($layout) {
		if(!empty($layout)) {
			$this->_layout = $layout;
		}
	}
	
	/**
	 * Singleton instance.
	 *
	 * @return MyHttpResponse
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>