<?php

/**
 * Application Configuration loader.
 * @author Jerome Loisel
 */
abstract class ConfigLoader extends Singleton {
	/**
	 * Default Controller config arrays.
	 */
	const ROUTES 		= '__ROUTES';
	const I18N			= '__I18N';
	const CREDENTIALS	= '__CREDENTIALS';
	const FILTERS		= '__FILTERS';
	const CACHING		= '__CACHING';
	const GENERAL		= '__GENERAL';
	
	/**
	 * Available application configurations.
	 *
	 * @var Array
	 */
	protected static $_availableConfigs = array(
		self::ROUTES		=>	'routes.php', 
		self::I18N 			=>	'i18n.php', 
		self::CREDENTIALS 	=>	'credentials.php', 
		self::FILTERS		=>	'filters.php', 
		self::CACHING		=>	'caching.php', 
		self::GENERAL		=>	'general.php'
	);
	
	/**
	 * Controller configuration
	 *
	 * @var Array
	 */
	protected $_config = array(
		self::ROUTES 		=> null, 
		self::I18N 			=> null, 
		self::CREDENTIALS 	=> null, 
		self::FILTERS		=> null,
		self::CACHING		=> null,
		self::GENERAL		=> null
	);
	
	/**
	 * Default constructor.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Returns the wanted configuration file content : the array containing
	 * all the config items.
	 *
	 * @param String $name (ConfigLoader::CONFIG, ..)
	 * @param Boolean $isMandatory (Is this configuration file mandatory ?)
	 * @return Array (null if none available)
	 */
	public function getConfig($name, $isMandatory=true) {
		if(array_key_exists($name,self::$_availableConfigs)) {
			if($this->_config[$name] == null) {
				$this->_config[$name] = array();
				$configFile = $this->getFilePath($name);
				if($this->isValidFile($configFile)) {
					require_once($configFile);
					if(isset($$name)) {
						$configArray =  $$name;
						if(	isset($configArray) && 
						is_array($configArray)) {
							$this->_config[$name] = $configArray;
						}
					}
				} else if($isMandatory) {
					throw new BaseException(
						'Configuration file "'.$configFile.'" does not exists or is not readable');
				}
			}
			return isset($this->_config[$name]) ? $this->_config[$name] : null;
		} else {
			throw new BaseException(
				'Configuration "'.$name.'" is not available');
		}
	}
	
	/**
	 * Returns a configuration item value.
	 *
	 * @param String $name (ConfigLoader::CONFIG, ..)
	 * @param String $item
	 * @param Boolean $isMandatory (Is this configuration file mandatory ?)
	 * @return String (null if none available)
	 */
	public function getItem($name,$item,$isMandatory=true) {
		if(array_key_exists($name,self::$_availableConfigs)) {
			if($this->_config[$name] == null) {
				$this->getConfig($name,$isMandatory);
			}

			if(	!empty($name) && 
				is_string($name)) {
				if(	!empty($item) && 
					is_string($item) && 
					isset($this->_config[$name][$item])) {
					return $this->_config[$name][$item];
				} else {
					if($isMandatory) {
						throw new BaseException(
						'In '.$name.' config, Configuration item "'.$item.'" does not exists or is not valid<br />(file: '.$this->getConfigPath().')');
					}
				}
			} else {
				if($isMandatory) {
					throw new BaseException(
						'In '.$name.' config, Configuration item "'.$item.'" is not defined or is not valid<br />(file: '.$this->getConfigPath().')');
				}
			}
		}
		return null;
	}
	
	/**
	 * Makes some check points on file existence, readability etc.
	 *
	 * @param String $fileFullPath
	 * @return Boolean
	 */
	protected function isValidFile($fileFullPath) {
		return (
			is_string($fileFullPath) && !empty($fileFullPath) && 
			is_file($fileFullPath) && file_exists($fileFullPath) && is_readable($fileFullPath)
		);
	}
	
	/**
	 * Application configuration file path.
	 *
	 * @param String $name
	 * @return String
	 */
	protected function getFilePath($name) {
		$filePath = '';
		$configPath = $this->getConfigPath();
		if(!is_dir($configPath) || !is_readable($configPath)) {
			throw new BaseException(
				'"'.$configPath.'" folder does not exists or is not readable');
		}
		if(	is_string($name) && !empty($name)) {
			if(isset(self::$_availableConfigs[$name])) {
				$filePath .= $configPath.DIRECTORY_SEPARATOR.self::$_availableConfigs[$name];
			} else {
				throw new BaseException(
				'Configuration "'.$name.'" is not available or not valid');
			}
		}
		return $filePath;
	}
	
	/**
	 * Returns the configuration file full path. (without configuration file name)
	 *
	 */
	protected abstract function getConfigPath();
	
	/**
	 * Cleans all the configuration arrays.
	 */
	public function clean() {
		$this->_config = array(
			self::ROUTES 		=> null, 
			self::I18N 			=> null, 
			self::CREDENTIALS 	=> null, 
			self::FILTERS		=> null,
			self::CACHING		=> null, 
			self::GENERAL		=> null
		);
	}
}