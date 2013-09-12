<?php

/**
 * This factory is in charge to load and instanciate 
 * the controllers.
 * 
 * Controllers can be either :
 * - in an actions.php file, containing several controllers
 * - in an {action}.php file, containing just one controller
 *
 */
final class ControllerInvoker {
	
	/**
	 * the action is defined in a separate php file.
	 *
	 */
	const SINGLE_ACTION_CLASS = 0;
	
	/**
	 * The action which is invoked is 
	 * aggregated with other actions in the same 
	 * php file.
	 *
	 */
	const MULTIPLE_ACTION_CLASS = 1;
	
	/**
	 * Used by controller infos to 
	 * designate the controller's class to 
	 * instanciate.
	 *
	 */
	const CONTROLLER_CLASS = 0;
	
	/**
	 * Used by controller infos to 
	 * designate the controller's method to invoke.
	 *
	 */
	const CONTROLLER_METHOD = 1;
	
	/**
	 * This is the method to invoke on a controller defined 
	 * in its own PHP file. 
	 *
	 */
	const SINGLE_CONTROLLER_METHOD = 'executeAction';
	
	/**
	 * This is the method signature of the controller 
	 * pre execution.
	 *
	 */
	const CONTROLLER_PRE_EXECUTE_METHOD = 'preExecute';
	
	/**
	 * This is the method signature of the controller 
	 * post execution.
	 *
	 */
	const CONTROLLER_POST_EXECUTE_METHOD = 'postExecute';
	
	/**
	 * The Controller actions base path. Controller actions will be searched 
	 * within this folder.
	 *
	 * @var string (full path without trailing slash)
	 */
	protected $actionsFolder = null;
	
	public function __construct() {
		
	}
	
	/**
	 * Instanciates the controller, given the name of the 
	 * controller.
	 *
	 * @param string $name
	 * @return Controller
	 * @throws BaseException
	 */
	public function invoke($controller, $action) {
		$controllerInfos = $this->getControllerInfos($controller, $action);
		if(is_array($controllerInfos)) {
			try {
				return $this->invokeControllerMethods(
					$controllerInfos[self::CONTROLLER_CLASS], 
					$controllerInfos[self::CONTROLLER_METHOD]
				);
			} catch(Exception $e) {
				throw new BaseException($e->getMessage(),$e->getCode());
			}
		} else {
			throw new BaseException('Controller instanciator could not build controller infos');
		}
		return null;
	}
	
	/**
	 * Invokes the controller and returns the controller viewMode.
	 *
	 * @param string $controller
	 * @param string $method
	 * @return integer
	 * @throws BaseException
	 */
	protected function invokeControllerMethods($controller, $method) {
		$reflectClass = new ReflectionClass($controller);
		$instance = $reflectClass->newInstance();
		
		// Controller preExecute
		$this->invokeSpecificControllerMethod(
			$reflectClass,
			$instance, 
			self::CONTROLLER_PRE_EXECUTE_METHOD
		);
		
		// Controller action
		$viewMode = $this->invokeSpecificControllerMethod(
			$reflectClass,
			$instance, 
			$method, 
			true
		);
		
		// Controller postExecute
		$this->invokeSpecificControllerMethod(
			$reflectClass,
			$instance, 
			self::CONTROLLER_POST_EXECUTE_METHOD
		);
		
		return $viewMode;
	}
	
	/**
	 * Invokes a specific method on an instance using reflection.
	 *
	 * @param ReflectionClass $reflectClass
	 * @param Controller $instance
	 * @param string $methodName
	 * @param boolean $mandatory
	 * @return mixed
	 * @throws BaseException
	 */
	protected function invokeSpecificControllerMethod($reflectClass, $instance, $methodName, $mandatory=false) {
		if(	$reflectClass->hasMethod($methodName)) {
			$reflectMethod = $reflectClass->getMethod($methodName);
			if(	$reflectMethod->isPublic()) {
				return $reflectMethod->invoke($instance);
			}
		} else {
			if($mandatory) {
				throw new BaseException('Controller "'.$reflectClass.'" method "'.$methodName.'" does not exists');
			}
		}
		return null;
	}
	
	/**
	 * Tries to find the right method to invoke and 
	 * includes the controller PHP file.
	 *
	 * @return array
	 * @throws BaseException
	 */
	protected function getControllerInfos($controller, $action) {
		$controllerInfos = array();
		$action = strtolower($action);
		$controller = strtolower($controller);
		$actionsFolder = $this->getActionsFolder();
		if($actionsFolder == null) {
			throw new BaseException('Controller Invoker Actions folder is not set');
		}
		
		$controllerFound = false;
		
		$phpFile = $actionsFolder . '/' . $action . '.php';
		
		if (file_exists ( $phpFile )) {
			$controllerInfos[self::CONTROLLER_CLASS] = $action . 'Action';
			$controllerInfos[self::CONTROLLER_METHOD] = self::SINGLE_CONTROLLER_METHOD;
			$controllerFound = true;
		}
		
		if(!$controllerFound) {
			$phpFile = $actionsFolder . '/actions.php';
			if (file_exists ( $phpFile )) {
				$controllerInfos[self::CONTROLLER_CLASS] = ucfirst($controller) . 'Actions';
				$controllerInfos[self::CONTROLLER_METHOD] = 'execute' . ucfirst($action);
				$controllerFound = true;
			}
		}
		
		if($controllerFound) {
			$this->requireControllerPHPFile($controller, $phpFile);
		} else {
			throw new BaseException ( 'Controller "'.$controller.'" with Action "'.$action.'" not found' );
		}
		return $controllerInfos;
	}

	/**
	 * Loads Controller php file if existing.
	 *
	 * @return Boolean (True if success)
	 * @throws BaseException
	 */
	protected function requireControllerPHPFile($controller, $file) {
		if(	file_exists($file) && 
			is_readable($file)		) {
			require_once($file);
		} else {
			throw new BaseException(
				'Controller "'.$controller.'" PHP file "'.$file.'" not found');
		}
	}
	
	/**
	 * Returns the full path to the directory supposed to contain controllers.
	 *
	 * @return string
	 */
	public function getActionsFolder() {
		return $this->actionsFolder;
	}
	
	/**
	 * Defines the controller actions folder.
	 *
	 * @param string $actionsFolder
	 */
	public function setActionsFolder($actionsFolder) {
		$this->actionsFolder = $actionsFolder;
	}
}

?>