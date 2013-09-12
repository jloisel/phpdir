<?php

/**
 * User session.
 *
 */
class Session extends Singleton {
	/**
	 * Name of the session.
	 *
	 * @var String
	 */
	protected $_sessionName = null;

	/**
	 * ID of the session.
	 *
	 * @var String
	 */
	protected $_sessionId = null;
	
	/**
	 * Session time out delay. (in seconds)
	 *
	 * @var integer
	 */
	protected $_sessionTimeout = null;
	
	/**
	 * Default session name.
	 *
	 */
	const DEFAULT_SESSION_NAME = 'user';
	
	/**
	 * Default constructor.
	 *
	 */
	protected function __construct() {
		parent::__construct();
	}

	/**
	 * Session initialization.
	 */
	public function initialize() {
		// set session name
	    session_name($this->getSessionName());

	    $use_cookies = (boolean) ini_get('session.use_cookies');
	    if (!$use_cookies) {
			$this->_sessionId = Context::getHttpRequest()->getParameter($this->_sessionName, null);

			if ($this->_sessionId != null) {
				session_id($this->_sessionId);
			} else {
				$this->setSessionId(session_id());
			}
	    }

	    $cookieDefaults = session_get_cookie_params();
	    $lifetime = $this->getParameter('session_cookie_lifetime', $cookieDefaults['lifetime']);
	    $path     = $this->getParameter('session_cookie_path',     $cookieDefaults['path']);
	    $domain   = $this->getParameter('session_cookie_domain',   $cookieDefaults['domain']);
	    $secure   = $this->getParameter('session_cookie_secure',   $cookieDefaults['secure']);
	    $httpOnly = $this->getParameter('session_cookie_httponly', isset($cookieDefaults['httponly']) ? $cookieDefaults['httponly'] : false);
	    if (version_compare(phpversion(), '5.2', '>=')) {
	    	session_set_cookie_params($lifetime, $path, $domain, $secure, $httpOnly);
	    } else {
	    	session_set_cookie_params($lifetime, $path, $domain, $secure);
	    }

		session_start();
	}

	/**
	 * Http request parameter retrieval.
	 *
	 * @param String $name
	 * @param Mixed $defaultValue
	 * @return Mixed
	 */
	protected function getParameter($name,$defaultValue=null) {
		return Context::getHttpRequest()->getParameter($name,$defaultValue);
	}

	/**
	* Reads data from this storage.
	*
	* The preferred format for a key is directory style so naming conflicts can be avoided.
	*
	* @param string A unique key identifying your data
	* @param Mixed $defaultValue
	*
	* @return mixed Data associated with the key
	*/
	public function & read($key,$defaultValue=null) {
		$retval = $defaultValue;
		if (isset($_SESSION[$key])) {
		  $retval =& $_SESSION[$key];
		}
		return $retval;
	}

	/**
	* Removes data from this storage.
	*
	* The preferred format for a key is directory style so naming conflicts can be avoided.
	*
	* @param string A unique key identifying your data
	*
	* @return mixed Data associated with the key
	*/
	public function & remove($key) {
		$retval = null;
		if (isset($_SESSION[$key])) {
		  $retval =& $_SESSION[$key];
		  unset($_SESSION[$key]);
		}
		return $retval;
	}

	/**
	* Writes data to this storage.
	*
	* The preferred format for a key is directory style so naming conflicts can be avoided.
	*
	* @param string A unique key identifying your data
	* @param mixed  Data associated with your key
	*
	*/
	public function write($key, &$data) {
		$_SESSION[$key] =& $data;
	}

	public function setSessionId($sessionId) {
		$this->_sessionId = $sessionId;
	}

	public function getSessionId() {
		return $this->_sessionId;
	}

	public function setSessionName($sessionName) {
		$this->_sessionName = $sessionName;
	}

	/**
	 * Name of the user session.
	 *
	 * @return string
	 */
	public function getSessionName() {
		if($this->_sessionName == null) {
			$this->_sessionName = self::DEFAULT_SESSION_NAME;
		}
		return $this->_sessionName;
	}
	
	/**
	 * @return integer
	 */
	public function getSessionTimeout() {
		return $this->_sessionTimeout;
	}
	
	/**
	 * @param integer $_sessionTimeout
	 */
	public function setSessionTimeout($_sessionTimeout) {
		$this->_sessionTimeout = intval($_sessionTimeout);
	}
	
	/**
	 * Removes all the session keys.
	 */
	public function removeAll() {
		global $_SESSION;
		if(is_array($_SESSION)) {
			foreach($_SESSION as $key => $value) {
				unset($_SESSION[$key]);
			}
		}
	}

	/**
	 * Close session
	 */
	public function end() {
		session_write_close();
	}

	/**
	 * Unique instance of the Session.
	 *
	 * @return Session
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}