<?php

/**
 * Filters are executed in the same order as defined below.
 * (from top to bottom)
 * 
 * @author Jerome Loisel
 */
$__FILTERS = array(
	/**
	 * The benchmark filter makes available several bench 
	 * information about the script. (memory usage, execution time...) 
	 */
	FrameworkFilter::BENCHMARKING_FILTER,
	
	/**
	 * Routing filter. Parses the query string and matches 
	 * one of the defined routes.
	 */
	FrameworkFilter::ROUTING_FILTER,
	
	/**
	 * Controller and action can be cached.
	 */
	FrameworkFilter::CACHING_FILTER, 
	
	/**
	 * Rendering filter. Renders the output depending on the 
	 * output type of the controller. (templates, headers..)
	 */
	FrameworkFilter::RENDERING_FILTER,
	
	/**
	 * Database filter initializes the database connection.
	 */
	FrameworkFilter::DATABASE_FILTER, 	
	
	/**
	 * Security filter : protect application and controllers 
	 * which need authentication.
	 */
	FrameworkFilter::SECURITY_FILTER,

	/**
	 * Internationalization filter. Allows to use language files to 
	 * internationalize the application.
	 */
	FrameworkFilter::I18N_FILTER, 
	
	/**
	 * Execution filter : invokes the right controller and action.
	 */
	FrameworkFilter::EXECUTION_FILTER,
);

?>