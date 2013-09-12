<?php

/**
 * Localizable object base implementation.
 *
 * @author Jerome Loisel
 */
abstract class AbstractLocalizableObject implements LocalizableObject {
	
	protected $definition = array();
	
	/**
	 * Constructor.
	 *
	 * @param array $definition
	 */
	public function __construct() {
		
	}
	
	/**
	 * Initializes the localizable object.
	 *
	 * @param array $definition
	 */
	public function initialize(array $definition) {
		if(is_array($definition)) {
			foreach($definition as $key => $value) {
				$this->definition[$key] = $value;
			}
		}
	}
	
	/**
	 * Returns the definition value at the passed key.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return string
	 */
	protected function getDefinition($key,$default=null) {
		return isset($this->definition[$key]) ? $this->definition[$key] : $default;
	}
	
	/**
	 * Returns the name of the theme.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->getDefinition(self::NAME,'');
	}
	
	/**
	 * Returns the version of the theme.
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->getDefinition(self::VERSION,self::DEFAULT_VERSION);
	}
	
	/**
	 * Returns the author of the theme.
	 *
	 * @return string
	 */
	public function getAuthor() {
		return $this->getDefinition(self::AUTHOR,'');
	}
	
	/**
	 * Returns the description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->getDefinition(self::DESCRIPTION,'');
	}
	
	/**
	 * @return boolean
	 */
	public function isInstalled() {
		return $this->getDefinition(self::IS_INSTALLED,false);
	}
	
	/**
	 * @return boolean
	 */
	public function isUptoDate() {
		return $this->getDefinition(self::IS_UPTODATE,true);
	}
	
	/**
	 * Returns the local folder. NULL if none.
	 *
	 * @return string
	 */
	public function getLocalFolder() {
		return $this->getDefinition(self::LOCAL_FOLDER,null);
	}
}

?>