<?php

/**
 * Hit logger
 * @author Jerome Loisel
 */
class InLogger extends AbstractLogger {
	
	/**
	 * Default constructor
	 *
	 * @param Integer $objectId
	 */
	public function __construct($objectId = 0) {
		if(intval($objectId) != 0) {
			parent::__construct(LoggerFactory::INS_LOGGER, intval($objectId));
		} else {
			parent::__construct(LoggerFactory::INS_LOGGER);
		}
	}
}

?>