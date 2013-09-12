<?php

/**
 * An object filter says if the object
 * passes the filter or not.
 *
 * @author Jerome Loisel
 */
interface ObjectFilter {

	/**
	 * Does this object passes the filter
	 * or not ?
	 *
	 * @param Object $o
	 * @return boolean
	 */
	public function passes($o);
	
}

?>
