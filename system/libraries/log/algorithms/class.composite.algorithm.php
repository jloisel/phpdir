<?php

/**
 * Default available composite algorithm which combines Cookies and IP
 * algorithms.
 * @author Jerome Loisel
 */
class CompositeLoggerAlgorithm extends AbstractCompositeLoggerAlgorithm {
	
	/**
	 * Default constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->addChild(	LoggerAlgorithmFactory::get(
							LoggerAlgorithmFactory::COOKIE_ALGORITHM	)	);
		$this->addChild(	LoggerAlgorithmFactory::get(
							LoggerAlgorithmFactory::IP_ALGORITHM	)	);
	}
}

?>