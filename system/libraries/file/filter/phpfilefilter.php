<?php

/**
 * PHP file filter :
 * only *.php files are accepted.
 *
 */
class PHPFileFilter extends FileExtensionFilter {
	
	public function __construct() {
		parent::__construct('php');
	}	
}

?>