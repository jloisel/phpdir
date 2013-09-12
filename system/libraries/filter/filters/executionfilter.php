<?php

/**
 * The execution filter is in charge to delegate the
 * controller and action invocation to Controller invoker.
 *
 */
class ExecutionFilter extends AbstractFilter {

	/**
	 * Controller invoker.
	 *
	 * @var ControllerInvoker
	 */
	protected $controllerInvoker = null;
	
	/**
	 * The full path to the folder containing the actions.
	 *
	 * @var string
	 */
	protected $actionsFolderTpl = null;
	
	/**
	 * Default constructor.
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Returns the controller invoker.
	 *
	 * @return ControllerInvoker
	 */
	protected function getControllerInvoker() {
		if($this->controllerInvoker == null) {
			$this->controllerInvoker = new ControllerInvoker();
		}
		return $this->controllerInvoker;
	}
	
	/**
	 * Returns the full path to the folder containing the 
	 *
	 * @param string $controller
	 * @return string
	 */
	protected function getActionsFolder() {
		return Context::getParameterHolder()->getModulePath().DIRECTORY_SEPARATOR.'actions';
	}
	
	protected function initControllerInvoker() {
		$this	->getControllerInvoker()
				->setActionsFolder($this->getActionsFolder());
	}
	
	/**
	 * During the preExecution, controller is instanciated 
	 * and controller's action is invoked.
	 *
	 */
	public function preExecute() {
		$controller = Context::getHttpRequest()->getController();
		$action = Context::getHttpRequest()->getAction();
		if($controller != null && $action != null) {
			$controllerInvoker = $this->getControllerInvoker();
			$this->initControllerInvoker();
			if(Config::get('mode') == 'development') {
				ExecutionStack::getInstance()->push();
			}
			$viewMode = $controllerInvoker->invoke($controller,$action);
			if($viewMode != null) {
				Context::getHttpResponse()->setViewMode($viewMode);
			}
		}
	}
	
	public function postExecute() {
		
	}
}

?>
