<?php

/**
 * Singleton main class.
 * Every object which needs to be a singleton instance 
 * must extends this class.
 * 
 * @author Jerome Loisel
 *
 */
abstract class Singleton {
	/**
	 * Singleton instances
	 *
	 * @var Array
	 */
	protected static $_instance = array();
	
	/**
	 * Default constructor
	 */
	protected function __construct() {
		
	}
	
	/**
	 * Returns a unique instance of the class.
	 *
	 * @param String $class
	 * @return Singleton
	 */
	protected static function getInstance($class) {
		if(!isset(self::$_instance[$class])) {
			self::$_instance[$class] = new $class();
		}
		return self::$_instance[$class];
	}
}

?>