<?php

/**
 * These constants are the available parameters
 * within the registry.
 *
 * @author Jerome Loisel
 */
interface Parameter {
	
	/**
	 * Full server path to the application.
	 *
	 * @final integer
	 */
	const APP_PATH = 0;
	
	/**
	 * Name of the folder containing all the 
	 * applications.
	 *
	 * @final integer
	 */
	const APPS_FOLDER = 1;
	
	/**
	 * Name of the current application.
	 *
	 * @final integer
	 */
	const APP_NAME = 2;
	
	/**
	 * Full path to the current module.
	 *
	 * @final Integer
	 */
	const MODULE_PATH = 3;
	
	/**
	 * Full path to public folder on server.
	 *
	 */
	const PUBLIC_PATH = 4;
	
	/**
	 * Public folder.
	 */
	const PUBLIC_FOLDER = 5;
	
	/**
	 * System folder.
	 */
	const SYSTEM_FOLDER = 6;
	
	/**
	 * System server path.
	 */
	const SYSTEM_PATH = 7;
	
	/**
	 * Cache folder.
	 *
	 */
	const CACHE_FOLDER = 8;
	
	/**
	 * Cache server path.
	 */
	const CACHE_PATH = 9;
	
	/**
	 * Script root server path.
	 */
	const SCRIPT_ROOT_PATH = 10;
	
	/**
	 * Virtual URL.
	 */
	const VIRTUAL_URL = 11;
}

?>