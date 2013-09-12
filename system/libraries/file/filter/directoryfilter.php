<?php

class DirectoryFilter implements ObjectFilter {
	
	/**
	 * @see ObjectFilter::passes()
	 *
	 * @param Object $o
	 */
	public function passes($o) {
		return 	$o != null && 
				$o->isDirectory() && 
				$o->getName() != '.' && 
				$o->getName() != '..';
	}

	
}

?>