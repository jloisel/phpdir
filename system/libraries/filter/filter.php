<?php

/**
 * Base interface a filter should implement.
 *
 */
interface Filter {
	
	/**
	 * Executed before the execute() method. 
	 *
	 */
	public function preExecute();
	
	/**
	 * executes the filter.
	 *
	 * @param FilterChain $filterChain
	 */
	public function execute(FilterChain $filterChain); 
	
	/**
	 * Executed after the execute() method.
	 *
	 */
	public function postExecute();
	
}

?>