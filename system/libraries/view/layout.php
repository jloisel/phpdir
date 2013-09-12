<?php

/**
 * An application can have several layouts, 
 * depending on the display output of the controller.
 *
 * @author Jerome Loisel
 */
class Layout {
	
	/**
	 * Classic HTML layout.
	 *
	 */
	const HTML = 'layout.php';
	
	/**
	 * Ajax layout.
	 *
	 */
	const AJAX = 'ajax_layout.php';
	
	/**
	 * Iframe layout.
	 *
	 */
	const IFRAME = 'iframe_layout.php';
}

?>