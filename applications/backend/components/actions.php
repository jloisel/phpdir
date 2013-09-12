<?php

/**
 * Backend components.
 *
 * @author Jerome Loisel
 */
class ComponentsActions extends ComponentController  {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function executeLinks() {
		return View::TPL;
	}
}

?>