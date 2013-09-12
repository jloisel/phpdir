<?php

/**
 * Logger factory. builds loggers on demand.
 * @author Jerome Loisel
 */
class LoggerFactory {
	/**
	 * Available Loggers (ObjectType)
	 */
	const INS_LOGGER		= 1;
	const OUTS_LOGGER		= 2;
	const CLIC_LOGGER		= 3;
	
	protected static $loggers = array(
		1 => 'in', 
		2 => 'out', 
		3 => 'clic'
	);
	
	/**
	 * Instanciates a logger on demand, depending on the name passed to the
	 * method.
	 * @param	Integer $loggerName (Ex : 1)
	 * @param	Integer $objectId
	 * @return	AbstractLogger
	 */
	private static function instanciate($loggerId,$objectId) {
		$loggerName = LoggerFactory::$loggers[$loggerId];
		$loggerClassFile = 	dirname(__FILE__)
							.'/loggers/class.'
							.$loggerName.'.logger.php';

		if(file_exists($loggerClassFile) && is_readable($loggerClassFile)) {
			require_once $loggerClassFile;
			
			$loggerClassName = ucfirst($loggerName.'Logger');
			if(class_exists($loggerClassName)) {
				$logger = null;
				if(isset($objectId) && is_integer($objectId) && $objectId != 0) {
					$logger = new $loggerClassName($objectId);
				} else {
					$logger = new $loggerClassName();
				}
				return $logger;
			}
		}
		return null;
	}
	
	/**
	 * Returns an instance of an AbstractLogger.
	 *
	 * @param String $loggerName
	 * @param Integer $objectId (optional)
	 * @return AbstractLogger
	 */
	public static function get($loggerName,$objectId=0) {
		if(!empty($loggerName)) {
			return self::instanciate($loggerName,$objectId);
		}
		return null;
	}
}

?>