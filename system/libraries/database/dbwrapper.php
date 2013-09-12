<?php

/**
 * Database connection wrapper :
 * wrapps the connection in order to lazy instanciate 
 * database connection only when necessary.
 *
 * @author Jerome Loisel
 */
class DBWrapper {
	
	/**
	 * Database connection.
	 *
	 * @var Doctrine_Connection
	 */
	private static $con = null;
	
	/**
	 * Initializes PHPDoctrine autoloader.
	 *
	 */
	private static function initAutoload() {
		require_once Context::getParameterHolder()->get(Parameter::SYSTEM_PATH).'/vendor/phpdoctrine/Doctrine.compiled.php';
		spl_autoload_register(array('Doctrine', 'autoload'));
		spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
		Doctrine_Core::loadModels(Context::getParameterHolder()->get(Parameter::SYSTEM_PATH).'/models');
		Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
		
	}
	
	/**
	 * Initializes PHPDoctrine connection.
	 *
	 */
	private static function getNewConnection() {
		return Doctrine_Manager::connection(
						Config::get('db_driver').
						'://'.Config::get('db_login').':'.Config::get('db_pass')
						.'@'.Config::get('db_host').'/'.Config::get('db_name'));
	}
	
	/**
	 * Initialize the database.
	 *
	 */
	public static function init() {
		self::initAutoload();
		self::$con = self::getNewConnection();
	}
	
	/**
	 * Closes the database connection.
	 *
	 */
	public static function stop() {
		self::$con->close();
	}
	
	/**
	 * Returns the database connection, Lazy initialization.
	 *
	 * @param boolean $lazyInit lazy initialize connection
	 * @return Doctrine_Connection
	 */
	public static function getDatabaseConnection() {
		return self::$con;
	}
	
	/**
	 * Returns the number of queries made during
	 *
	 * @return integer
	 */
	public static function getQueryCount() {
		if(self::$con == null) return 0;
		return self::$con->count();
	}
}

?>