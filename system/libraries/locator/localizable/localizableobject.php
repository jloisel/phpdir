<?php

/**
 * A localizable object is: a theme, a module... etc.
 * These objects are having a common implementation.
 * 
 * @author Jerome Loisel
 */
interface LocalizableObject {
	/**
	 * Base file name of the file containing the definition 
	 * of the localizable object.
	 *
	 */
	const DEFINITION_FILE = '__define.php';
	
	/**
	 * Name of the object.
	 */
	const NAME = 'name';
	
	/**
	 * Description of the object.
	 */
	const DESCRIPTION = 'description';
	
	/**
	 * Author of the object.
	 */
	const AUTHOR = 'author';
	
	/**
	 * Version of the object.
	 */
	const VERSION = 'version';
	
	/**
	 * Default localizable object version.
	 */
	const DEFAULT_VERSION = '1.0';
	
	/**
	 * Is the object up-to-date ?
	 */
	const IS_UPTODATE = 'isuptodate';
	
	/**
	 * Is the object installed locally ?
	 */
	const IS_INSTALLED = 'isinstalled';
	
	/**
	 * Local folder (if local object).
	 */
	const LOCAL_FOLDER = 'localfolder';
	
	/**
	 * Initializes the localizable object 
	 * attributes with the passed array.
	 *
	 * @param array $definition
	 */
	public function initialize(array $definition);
	
	/**
	 * Returns the name.
	 *
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the description.
	 *
	 * @return string
	 */
	public function getDescription();
	
	/**
	 * Returns the author of the object.
	 *
	 * @return string
	 */
	public function getAuthor();
	
	/**
	 * Returns the version.
	 *
	 * @return string
	 */
	public function getVersion();
	
	/**
	 * Is the object updatable ? 
	 * If not, that means a newer version is available.
	 *
	 * @return boolean
	 */
	public function isUptoDate();
	
	/**
	 * Is the localizable object installed on 
	 * local server.
	 *
	 * @return boolean
	 */
	public function isInstalled();
	
	/**
	 * Returns the localizable object local folder.
	 * (only if installed locally)
	 * 
	 */
	public function getLocalFolder();
}

?>