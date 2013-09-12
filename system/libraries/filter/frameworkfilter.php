<?php

/**
 * All the filters integrated in framework are 
 * listed here.
 *
 * @author Jerome Loisel
 */
interface FrameworkFilter {
	
	/**
	 * Rendering filter : 
	 * displays the output.
	 *
	 */
	const RENDERING_FILTER = 'RenderingFilter';
	
	/**
	 * Routing filter : 
	 * invokes router and parses 
	 * routes.
	 *
	 */
	const ROUTING_FILTER = 'RoutingFilter';
	
	/**
	 * Execution filter :
	 * invokes the controller and action.
	 *
	 */
	const EXECUTION_FILTER = 'ExecutionFilter';
	
	/**
	 * Internationalization filter.
	 *
	 */
	const I18N_FILTER = 'I18nFilter';
	
	/**
	 * Security filter.
	 *
	 */
	const SECURITY_FILTER = 'SecurityFilter';
	
	/**
	 * Benchmarking filter.
	 *
	 */
	const BENCHMARKING_FILTER = 'BenchmarkingFilter';
	
	/**
	 * Database filter.
	 *
	 */
	const DATABASE_FILTER = 'DatabaseFilter';
	
	/**
	 * Filter offering cache feature.
	 *
	 */
	const CACHING_FILTER = 'CachingFilter';
	
}

?>
