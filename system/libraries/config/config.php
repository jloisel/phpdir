<?php

/**
*	Configuration manager. Load/Edit/Save configuration easily.
*	@author Jerome Loisel
*/
class Config
{	
	/**
	 * Full path to configuration file.
	 * Must be set in order to load configuration on demand.
	 *
	 * @var String
	 */
	protected static $_configFilePath = null;
	/**
	 * Configuration from file is saved into this static field.
	 * 
	 * @var Array
	 */
	protected static $_config = null;
	
	/**
	 * Name of the configuration array.
	 *
	 * @var string
	 */
	protected static $_configName = null;
	
	/**
	 * Returns the value of associated config key.
	 * At the first config key retrieval, the configuration is loaded.
	 * (loading config only if necessary)
	 *
	 * @param String $index
	 * @return String Config value if success, index if failed.
	 */
	public static function get($index) {
		if(self::$_config == null) {
			self::load(self::$_configFilePath);
		}
		if(isset(self::$_config[$index])) {
			return self::$_config[$index];
		}
		return null;
	}
	
	/**
	 * Returns all configuration items.
	 *
	 * @return array
	 */
	public static function getAll() {
		if(isset(self::$_config)) {
			return self::$_config;
		}
		return false;
	}
	
	/**
	 * Sets a config value, if existing.
	 *
	 * @param String $index
	 * @param String $value
	 * @return Boolean TRUE if success
	 */
	public static function setItem($index,$value) {
		if(self::$_config == null) {
			self::load(self::$_configFilePath);
		}
		if(isset(self::$_config[$index])) {
			self::$_config[$index] = (string)$value;
		}
	}

	/**
	 * Sets an array of configuration items.
	 * @param	Associative Array of Items
	 * @return	void
	 */
	public static function setItems($values) {
		if(is_array($values)) {
			foreach($values as $index => $value) {
				self::setItem($index,$value); 
			}
		}
	}
	
	/**
	 * Adds a configuration item to currently loaded configuration.
	 * @param	String index
	 * @param	String value
	 */
	public static function addItem($index,$value) {
		if(self::$_config == null) {
			self::load(self::$_configFilePath);
		}
		if(!empty($index)) {
			if(!isset(self::$_config[$index])) {
				self::$_config[$index] = $value;
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Add an array of configuration items.
	 *
	 * @param Array $values
	 */
	public static function addItems($values) {
		if(is_array($values)) {
			foreach($values as $index => $value) {
				self::addItem($index,$value); 
			}
		}
	}
	
	/**
	 * Removes an index of the configuration items,
	 * if existing.
	 * @param	String index
	 * @return	Boolean TRUE if success
	 */
	public static function removeItem($index) {
		if(self::$_config == null) {
			self::load(self::$_configFilePath);
		}
		if(!empty($index)) {
			if((isset(self::$_config[$index]))) {
				unset(self::$_config[$index]);
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Loads a configuration file.
	 * @param	String full path to configuration file.
	 * @return	Boolean TRUE if success
	 */
	public static function load($configFile) {
		if($configFile != null && !empty($configFile)) {
			self::loadFile($configFile);
		} else {
			throw new Exception('Cannot load "'.$configFile.'" configuration file');
		}
	}
	
	/**
	 * Saves configuration to specified configuration file.
	 * @param	String configuration file full path
	 * @return	Boolean TRUE if success
	 */
	public static function save($configFile='') {
		if(self::$_config == null) {
			self::load(self::$_configFilePath);
		}
		if(self::isValidConfigFile($configFile)) {
			return self::writeConfigFile($configFile);
		} else {
			return self::writeConfigFile(self::getConfigFilePath());
		}
	}
	
	/**
	 * Checks if the specified configuration file is valid.
	 * @param	String full path to config file
	 * @return	Boolean TRUE if success
	 */
	protected static function isValidConfigFile($configFile) {
		return (	!empty($configFile)			&&
					file_exists($configFile)	&& 
					is_readable($configFile)		);
	}
	
	/**
	 * Loads a configuration file, and puts its content into static field.
	 *
	 * @param String $configFile
	 * @return boolean TRUE if success
	 */
	protected static function loadFile($configFile) {
		if( self::isValidConfigFile($configFile) ) {
			require_once $configFile;
		}
	}
	
	/**
	 * Cleans a configuration value.
	 * @param	String configuration value to clean
	 * @return	String cleaned configuration value
	 */
	protected static function cleanValue($value) {
		return TextSanitizer::addSlashes($value);
	}
	
	/**
	 * Writes currently loaded configuration to
	 * specified configuration file.
	 * @param	String full path to configuration file
	 * @return	boolean TRUE if success, else FALSE
	 */
	protected static function writeConfigFile($configFile) {
		$fileContent = '<'.'?php'."\n";
		$fileContent .= 'Config::addItems('."\n";
		$fileContent .= "\n\t".'array('."\n\n";
		if(is_array(self::$_config)) {
			foreach (self::$_config as $key => $value) {
				$value = self::cleanValue($value);	
				$fileContent .= 	"\t'".$key."' => '".self::cleanValue($value)."',\n";
			}
		}
		$fileContent .= "\n\t".')'."\n";
		$fileContent .= ');'."\n";
		$fileContent .= '?'.'>';
		$fh = fopen($configFile,'w');
		if (is_resource($fh)) {
			fputs($fh, $fileContent, strlen($fileContent) );
			fclose($fh);
			return true;
		}
		return false;
	}
	
	/**
	 * configuration file full path.
	 *
	 * @return String
	 */
	protected static function getConfigFilePath() {
		return self::$_configFilePath;
	}
	
	/**
	 * Set configuration file path. (full path strongly recommended)
	 *
	 * @param String $configFilePath
	 */
	public static function setConfigFilePath($configFilePath) {
		if(!empty($configFilePath)) {
			self::$_configFilePath = $configFilePath;
		}
	}
	
	/**
	 * @return string
	 */
	public static function getConfigName() {
		if(self::$_configName == null) {
			self::$_configName = self::DEFAULT_CONFIG_NAME;
		}
		return self::_configName;
	}
	
	/**
	 * @param string $_configName
	 */
	public static function setConfigName($_configName) {
		self::$_configName = $_configName;
	}
}

?>