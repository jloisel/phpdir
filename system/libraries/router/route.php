<?php

/**
 * Route implementation.
 *
 * @author Jerome Loisel
 */
interface Route {
	/**
	 * Empty route
	 */
	const VOID = 'empty';
	/**
	 * Framework route defined for a standalone controller.
	 * (/:controller)
	 */
	const CONTROLLER = 'c';
	
	/**
	 * Controller and action route.
	 * (/:controller/:action)
	 */
	const CONTROLLER_ACTION = 'ca';
	
	/**
	 * Controller, action and id route
	 */
	const CONTROLLER_ACTION_ID = 'cai';
}

?>