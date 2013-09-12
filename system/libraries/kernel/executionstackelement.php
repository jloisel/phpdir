<?php

class ExecutionStackElement {

	protected $controller = null;
	
	protected $action = null;
	
	public function __construct($controller, $action) {
		$this->controller = $controller;
		$this->action = $action;
	}
	
	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * @return string
	 */
	public function getController() {
		return $this->controller;
	}
	
	/**
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}
	
	/**
	 * @param string $controller
	 */
	public function setController($controller) {
		$this->controller = $controller;
	}
	
	/**
	 * Dumps the execution stack element.
	 *
	 * @return string
	 */
	public function dump() {
		return $this->controller.'->'.$this->action.'()';
	}
	
	public function __toString() {
		echo $this->dump();
	}
}

?>
