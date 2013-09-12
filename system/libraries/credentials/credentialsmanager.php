<?php

/**
 * The Credentials manager loads the application 
 * and module credential configuration.
 * 
 * @author Jerome Loisel
 */
class CredentialsManager extends Singleton implements Credentials {
	
	/**
	 * Is the Controller secure ?
	 *
	 * @var bool
	 */
	protected $_isSecure = false;
	
	/**
	 * Controller to execute when user is 
	 * not authenticated.
	 *
	 * @var String
	 */
	protected $_loginController = null;
	
	/**
	 * Controller action to execute when user 
	 * is not authenticated. 
	 *
	 * @var String
	 */
	protected $_loginAction = null;
	
	/**
	 * Controller loaded when user is authenticated but 
	 * has not the right credentials.
	 *
	 * @var String
	 */
	protected $_secureController = null;
	
	/**
	 * Action to execute when user is authenticated 
	 * but has not the right credentials.
	 *
	 * @var String
	 */
	protected $_secureAction = null;
	
	/**
	 * Credentials defined in credentials config.
	 *
	 * @var array
	 */
	protected $_credentials = null;
	
	/**
	 * Default constructor.
	 *
	 */
	protected function __construct() {
		parent::__construct();
		$this->init();
	}
	
	/**
	 * Initialize Credentials manager.
	 *
	 */
	protected function init() {
		$this->loadApplicationCredentials();
		$this->loadModuleCredentials();
	}
	
	/**
	 * Loads application credentials and checks if they are well defined.
	 *
	 */
	protected function loadApplicationCredentials() {
		$appCredentials = AppConfigLoader::getInstance()->getConfig(ConfigLoader::CREDENTIALS,self::APP_CREDENTIALS_REQUIRED);
		if($this->isValidCredentialsConfig($appCredentials,self::APP_CREDENTIALS_REQUIRED)) {
			$this->assignCredentials($appCredentials);
		}
	}
	
	/**
	 * Loads the module credentials configuration 
	 * and checks that credentials are well defined.
	 *
	 */
	protected function loadModuleCredentials() {
		$moduleCredentials = ModuleConfigLoader::getInstance()->getConfig(ConfigLoader::CREDENTIALS,self::MODULE_CREDENTIALS_REQUIRED);
		if(is_array($moduleCredentials)) {
			$this->assignCredentials($moduleCredentials);
		}		
	}
	
	/**
	 * Checks that the credentials defined in the config array 
	 * passed to method is valid.
	 *
	 * @param array $credentialsConfig
	 */
	protected function isValidCredentialsConfig($credentialsConfig, $isMandatory=false) {
		if(is_array($credentialsConfig)) {
			if(!isset($credentialsConfig[self::IS_SECURE])) {
				throw new SecurityException(
					"Credentials item '".self::IS_SECURE."' is not defined",SecurityException::UNDEFINED_CRED_CONFIG_ITEM);
			}
			
			$isSecure = $credentialsConfig[self::IS_SECURE];
			if($isSecure) {
				if(!isset($credentialsConfig[self::LOGIN_CONTROLLER])) {
					throw new SecurityException(
						"Credentials item '".self::LOGIN_CONTROLLER."' is not defined",SecurityException::UNDEFINED_CRED_CONFIG_ITEM);
				}
				if(!isset($credentialsConfig[self::LOGIN_ACTION])) {
					throw new SecurityException(
						"Credentials item '".self::LOGIN_ACTION."' is not defined",SecurityException::UNDEFINED_CRED_CONFIG_ITEM);
				}
				if(!isset($credentialsConfig[self::SECURE_CONTROLLER])) {
					throw new SecurityException(
						"Credentials item '".self::SECURE_CONTROLLER."' is not defined",SecurityException::UNDEFINED_CRED_CONFIG_ITEM);
				}
				if(!isset($credentialsConfig[self::SECURE_ACTION])) {
					throw new SecurityException(
						"Credentials item '".self::SECURE_ACTION."' is not defined",SecurityException::UNDEFINED_CRED_CONFIG_ITEM);
				}
				if(!isset($credentialsConfig[self::CREDENTIALS])) {
					throw new SecurityException(
						"Credentials item '".self::CREDENTIALS."' is not defined",SecurityException::UNDEFINED_CRED_CONFIG_ITEM);
				}
			}
		} else {
			if($isMandatory) {
				throw new SecurityException("Credentials configuration array not found",SecurityException::MALFORMED_CREDENTIALS_CONFIG);
			}
		}
		return true;
	}
	
	/**
	 * Assigns the credentials to the credential manager.
	 *
	 * @param array $credentialsArray
	 */
	protected function assignCredentials($credentialsArray) {
		if(isset($credentialsArray[self::IS_SECURE])) {
			$this->_isSecure = $credentialsArray[self::IS_SECURE];
		}
		if(isset($credentialsArray[self::LOGIN_CONTROLLER])) {
			$this->_loginController = $credentialsArray[self::LOGIN_CONTROLLER];
		}
		if(isset($credentialsArray[self::LOGIN_ACTION])) {
			$this->_loginAction = $credentialsArray[self::LOGIN_ACTION];
		}
		if(isset($credentialsArray[self::SECURE_CONTROLLER])) {
			$this->_secureController = $credentialsArray[self::SECURE_CONTROLLER];
		}
		if(isset($credentialsArray[self::SECURE_ACTION])) {
			$this->_secureAction = $credentialsArray[self::SECURE_ACTION];
		}
		if(isset($credentialsArray[self::SESSION_TIMEOUT])) {
			Context::getSession()->setSessionTimeout($credentialsArray[self::SESSION_TIMEOUT]);
		}
		if(isset($credentialsArray[self::SESSION_NAME])) {
			Context::getSession()->setSessionName($credentialsArray[self::SESSION_NAME]);
		}
		if(isset($credentialsArray[self::CREDENTIALS])) {
			$this->_credentials = $credentialsArray[self::CREDENTIALS];
		}
	}
	
	/**
	 * Is the controller executed in secured environment ?
	 * This value is retrieved from credentials configuration file. 
	 * 
	 * @return bool
	 */
	public function isSecure() {
		return $this->_isSecure;
	}
	
	/**	 
	 * Name of the login action defined in application or 
	 * module configuration file.
	 * 
	 * @return String
	 */
	public function getLoginAction() {
		return $this->_loginAction;
	}
	
	/**
	 * Name of the login controller defined in application or 
	 * module configuration file.
	 * 
	 * @return String
	 */
	public function getLoginController() {
		return $this->_loginController;
	}
	
	/**
	 * Name of the secure action defined in application or 
	 * module configuration file.
	 * 
	 * @return String
	 */
	public function getSecureAction() {
		return $this->_secureAction;
	}
	
	/**
	 * Name of the secure controller defined in application of 
	 * module configuration file.
	 * 
	 * @return String
	 */
	public function getSecureController() {
		return $this->_secureController;
	}
	
	/**
	 * Returns the credentials defined for the application and/or 
	 * controller.
	 * 
	 * @return array
	 */
	public function getCredentials() {
		return $this->_credentials;
	}
	
	/**
	 * Returns the unique instance of the Credentials manager.
	 *
	 * @return CredentialsManager
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__); 
	}
}

?>