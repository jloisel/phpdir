<?php

/**
 * Abstract base filter.
 * 
 * @author Jerome Loisel
 *
 */
abstract class AbstractFilter implements Filter {
	
	/**
	 * Is the filter called the first time ?
	 *
	 * @var boolean
	 */
	protected $isFirstCall = true;
	
	public function __construct() {
		
	}
	
	/**
	 * Is the filter called for the first time ?
	 *
	 * @return boolean
	 */
	public final function isFirstCall() {
		if($this->isFirstCall) {
			$this->isFirstCall = false;
			return true;
		}
		return false;
	}
	
	/**
	 * Returns the parameter holder.
	 *
	 * @return ParameterHolder
	 */
	protected final function getParameterHolder() {
		return Context::getParameterHolder();
	}
	
	/**
	 * Executes the filter.
	 *
	 * @param FilterChain $filterChain
	 */
	public function execute(FilterChain $filterChain) {
		$filterChain->execute();
	}
}

?>