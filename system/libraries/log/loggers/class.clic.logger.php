<?php

/**
 * Hit logger
 * @author Jerome Loisel
 */
class ClicLogger extends AbstractLogger {
	
	/**
	 * Default constructor
	 *
	 * @param Integer $objectId
	 */
	public function __construct($objectId = 0) {
		if(intval($objectId) != 0) {
			parent::__construct(LoggerFactory::CLIC_LOGGER, intval($objectId));
		} else {
			parent::__construct(LoggerFactory::CLIC_LOGGER);
		}
	}
}

?>