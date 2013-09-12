<?php

class User extends Singleton implements UserSecurity {

	/**
	 * User credentials.
	 *
	 * @var Array
	 */
	protected $credentials = null;

	/**
	 * Is the user authentificated.
	 *
	 * @var Boolean
	 */
	protected $authenticated = null;

	/**
	 * Is the user authentication timed out ?
	 *
	 * @var Boolean
	 */
	protected $timedout = false;

	/**
	 * Session timeout.
	 *
	 * @var Integer
	 */
	protected $timeout = null;

	/**
	 * Last user request detected.
	 *
	 * @var Integer
	 */
	protected $lastRequest = null;

	/**
	 * Default constructor.
	 */
	protected function __construct() {
		$this->initialize();
	}

	public function initialize() {
		$session = Session::getInstance();
		$session->initialize();

		$this->authenticated = $session->read('authentificated',false);
		$this->credentials = $session->read('credentials',array());
		$this->timedout = $session->read('timedout',false);
		$this->lastRequest = $session->read('lastRequest',time());
		$this->timeout = AppConfigLoader::getInstance()->getItem(ConfigLoader::CREDENTIALS,'session_timeout',true);

		// Automatic logout if no request for more than [session_timeout]
		if (null !== $this->lastRequest && (time() - $this->lastRequest) > $this->timeout) {
			$this->setTimedOut();
			$this->setAuthenticated(false);
			$session->removeAll();
		}


		$this->lastRequest = time();
	}

	/**
	* Adds a credential.
	*
	* @param  mixed credential
	*/
	public function addCredential($credentials=array()) {
		$this->addCredentials($credentials);
	}

	/**
	* Adds several credential at once.
	*
	* @param  mixed array or list of credentials
	*/
	protected function addCredentials($credentials=array())
	{
		if(!is_array($credentials) || count($credentials) == 0) {
			return;
		}
		foreach ($credentials as $aCredential) {
			if (!in_array($aCredential, $this->credentials)) {
				$this->credentials[] = $aCredential;
			}
		}
	}

	/**
	* Returns true if user has credential.
	*
	* @param  mixed credentials
	* @param  boolean useAnd specify the mode, either AND or OR
	* @return boolean
	*
	* @author Olivier Verdier <Olivier.Verdier@free.fr>
	*/
	public function hasCredential($credentials, $useAnd = true) {
		if (!is_array($credentials)) {
			return in_array($credentials, $this->credentials);
		}
		
		// now we assume that $credentials is an array
		$test = false;

		foreach ($credentials as $credential) {
			// recursively check the credential with a switched AND/OR mode
			$test = $this->hasCredential($credential, $useAnd ? false : true);

			if ($useAnd) {
				$test = $test ? false : true;
			}

			// either passed one in OR mode or failed one in AND mode
			if ($test) {
				break; // the matter is settled
			}
		}

		// in AND mode we succeed if $test is false
		if ($useAnd) {
			$test = $test ? false : true;
		}

		return $test;
	}

	/**
	* Returns true if user is authenticated.
	*
	* @return boolean
	*/
	public function isAuthenticated() {
		return $this->authenticated;
	}

	public function setTimedOut() {
		$this->timedout = true;
	}

	public function isTimedOut() {
		return $this->timedout;
	}

	public function setTimeout($timeout) {
		if(is_integer($timeout)) {
			$this->timeout = intval($timeout);
		} else {
			throw new BaseException(
				'Session time out set ("'.$timeout.'") is not an integer');
		}
	}

	public function getTimeout() {
		return $this->timeout;
	}

	/**
	* Sets authentication for user.
	*
	* @param  boolean
	*/
	public function setAuthenticated($authenticated) {
		if ($authenticated === true) {
			$this->authenticated = true;
		} else {
			$this->authenticated = false;
			$this->clearCredentials();
		}
	}

	/**
	 * Clears all the user credentials.
	 */
	public function clearCredentials() {
		$this->credentials = array();
	}

	/**
	* Removes a credential.
	*
	* @param  mixed credential
	*/
	public function removeCredential($credential) {
		if ($this->hasCredential($credential)) {
			foreach ($this->credentials as $key => $value) {
				if ($credential == $value) {

					unset($this->credentials[$key]);
					return;
				}
			}
		}
	}

	/**
	 * User credentials.
	 *
	 * @return Array
	 */
	public function getCredentials() {
		return $this->credentials;
	}

	/**
	 * Unique instance of the user.
	 *
	 * @return User
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}

	/**
	 * Sets a user attribute.
	 *
	 * @param String $name
	 * @param Mixed
	 */
	public function setAttribute($name,$value=null) {
		Session::getInstance()->write($name,$value);
	}

	/**
	 * Removes an attribute.
	 *
	 * @param String $name
	 */
	public function removeAttribute($name) {
		if(!empty($name)) {
			Session::getInstance()->remove($name);
		}
	}

	/**
	 * Is the attribute existing in the session.
	 *
	 * @param String $name
	 * @return Mixed
	 */
	public function hasAttribute($name) {
		return Session::getInstance()->read($name,null) == null;
	}

	/**
	 * Returns a user attribute.
	 *
	 * @param String $name
	 * @param Mixed $defaultValue
	 * @return Mixed
	 */
	public function getAttribute($name,$defaultValue=null) {
		if(!empty($name)) {
			return Session::getInstance()->read($name,$defaultValue);
		}
		return $defaultValue;
	}

	/**
	 * Write user authentification and credentials on shutdown.
	 */
	public function __destruct() {
		$session = Session::getInstance();
		$session->write('authentificated', $this->authenticated);
		$session->write('credentials', $this->credentials);
		$session->write('lastRequest', $this->lastRequest);
		$session->write('timedout', $this->timedout);
		$session->write('timeout', $this->timeout);
		$session->end();
	}
}
?>