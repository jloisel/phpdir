<?php

/**
 * Logger algorithm factory. Returns an algorithm instance on demand.
 * @author Jerome Loisel
 */
class LoggerAlgorithmFactory {
	/**
	 * Available Logging algorithms :
	 */
	const COOKIE_ALGORITHM			= 'cookie';
	const IP_ALGORITHM				= 'ip';
	const IP_AND_COOKIE_ALGORITHM	= 'composite';
	
	/**
	 * Returns an instanceof AbstractLoggerAlgorithm.
	 * @param	String name of the desired logger algorithm
	 * @return	AbstractLoggerAlgorithm
	 */
	public static function get($logAlgorithm) {
		if(!empty($logAlgorithm)) {
			$className = ucfirst($logAlgorithm).'LoggerAlgorithm';
			
			if(!class_exists($className)) {
				$classFilename = dirname(__FILE__).'/algorithms/class.'
									.$logAlgorithm.'.algorithm.php';
				if(file_exists($classFilename) && is_readable($classFilename)) {
						require_once ($classFilename);
				}
			}
			return new $className();
		}
		return null;
	}
}

?>