<?php

/**
 * Routing filter.
 * Invokes the router which parses the 
 * query string.
 * 
 * @author Jerome Loisel
 *
 */
class RoutingFilter extends AbstractFilter {
	
	/**
	 * Default constructor.
	 *
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Initializes the global application routes.
	 */
	private function initGlobalRoutes() {
		$path = Context::getParameterHolder()->get(Parameter::SCRIPT_ROOT_PATH) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
		Context::getRouter ()->loadRoutes ($path);
	}
	
	/**
	 * Initialize the application specific routes.
	 *
	 */
	private function initApplicationRoutes() {
		$path = Context::getParameterHolder()->get(Parameter::APP_PATH) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
		Context::getRouter ()->loadRoutes ($path);
	}
	
	/**
	 * Initializes the controller and the action with default ones 
	 * if any configured.
	 *
	 * @param MyHttpRequest $httpRequest
	 */
	private function initControllerAndAction(MyHttpRequest $httpRequest) {
		$generalConfig = AppConfigLoader::getInstance()->getConfig ( ConfigLoader::GENERAL, false );
		
		if ($httpRequest->getController () == null && isset ( $generalConfig ['default_controller'] )) {
			$httpRequest->setController ( $generalConfig ['default_controller'] );
		}
		if ($httpRequest->getAction () == null) {
			if(isset ( $generalConfig ['default_action'] )) {
				$httpRequest->setAction ( $generalConfig ['default_action'] );
			} else {
				$httpRequest->setAction (Context::getModuleConfigLoader()->getItem(ModuleConfigLoader::GENERAL,'default_action'));
			}
		}
	}
	
	/**
	 * Routing is done only in pre execution.
	 *
	 */
	public function preExecute() {
		if ($this->isFirstCall ()) {
			$this->initGlobalRoutes ();
			$this->initApplicationRoutes ();
			
			$httpRequest = Context::getHttpRequest ();
			$router = Context::getRouter ();
			$parameters = $router->parse ( $httpRequest->getQueryString () );
			
			if (is_array ( $parameters )) {
				foreach ( $parameters as $name => $value ) {
					$httpRequest->setParameter ( $name, $value );
				}
			}
			$this->initControllerAndAction($httpRequest);
	
			if($router->getCurrentRouteName() == null) {
				throw new BaseException ( '{' . __CLASS__ . '} : No matching route found' );
			}
			if ($httpRequest->getController () == null) {
				throw new BaseException ( '{' . __CLASS__ . '} matching route found (' . Context::getRouter ()->getCurrentRouteName () . '), but no controller could be resolved' );
			}
			if ($httpRequest->getAction () == null) {
				throw new BaseException ( '{' . __CLASS__ . '} matching route found (' . Context::getRouter ()->getCurrentRouteName () . '), but no action could be resolved' );
			}
		}
	}
	
	public function postExecute() {
	
	}
}

?>
