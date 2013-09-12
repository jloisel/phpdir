<?php

/**
 * Hit logger
 * @author Jerome Loisel
 */
class OutLogger extends AbstractLogger {
	
	/**
	 * Default constructor
	 *
	 * @param Integer $objectId
	 */
	public function __construct($objectId = 0) {
		if(intval($objectId) != 0) {
			parent::__construct(LoggerFactory::OUTS_LOGGER, intval($objectId));
		} else {
			parent::__construct(LoggerFactory::OUTS_LOGGER);
		}
	}
}

?>