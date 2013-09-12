<?php

/**
 * Front Controller receives all the requests.
 * The front controller delegates the execution to filter chain 
 * and listens to thrown Exception.
 * 
 * @author Jerome Loisel
 */
final class FrontController extends Singleton {
	
	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct();
	}
	
	/**
	 * Dispatches the received request. FrontController has several roles :
	 * - Loading Controller php class file
	 * - Instanciating Controller
	 * - Calling Controller action
	 */
	public function dispatch() {
		try {
			$filterChain = new FilterChain();
			$filterChain->setFilters($this->getFilters());
			$filterChain->execute();
		}catch(BaseException $e) {
			echo $e;
			exit();
		} catch(Exception $e) {
    		echo $e;
    		exit();
		}
	}
	
	/**
	 * Loads the filters from configuration file.
	 *
	 */
	protected function getFilters() {
		$filters = GlobalConfigLoader::getInstance ()->getConfig ( ConfigLoader::FILTERS, true );
		if (is_array ( $filters )) {
			$filters = array_reverse ( $filters );
		}
		return $filters;
	}
	
	/**
	 * Singleton instance of the Front Controller.
	 *
	 * @return FrontController
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>