<?php

class FileFilter implements ObjectFilter {
	
	public function __construct() {
		
	}
	
	/**
	 * @see ObjectFilter::passes()
	 *
	 * @param Object $o
	 * @return boolean
	 */
	public function passes($o) {
		return $o != null && $o->isFile();
	}
	
}

?>