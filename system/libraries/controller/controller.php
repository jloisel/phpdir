<?php

/**
 * Controller base interface.
 *
 * @author Jerome Loisel
 */
interface Controller {
	/**
	 * This method is called before execute() method. 
	 *
	 */
	public function preExecute();
	
	/**
	 * This method is called after execute() method. 
	 *
	 */
	public function postExecute();
}

?>
