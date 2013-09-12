<?php

/**
 * Services actions: install, uninstall, update and remove
 * them.
 *
 * @author Jerome Loisel
 */
class ServiceActions extends DefaultController {
	
	/**
	 * Service type.
	 * 
	 * @var string
	 */
	private $type = null;
	
	/**
	 * Service name.
	 * 
	 * @var string
	 */
	private $service = null;
	
	/**
	 * Redirect user after action ?
	 * 
	 * @var boolean
	 */
	private $redirect = true;
	
	/**
	 * Initializes the parameters.
	 * 
	 * @see system/libraries/controller/DefaultController#preExecute()
	 */
	public function preExecute() {
		$this->type = $this->getParameter('type');
		$this->types = Context::getServiceLocator()->getServiceTypes();
		$this->service = $this->getParameter('service');
	}
	
	/**
	 * Executes the action.
	 *
	 * @return integer
	 */
	public function executeInstall() {
		if($this->type != null && $this->service != null) {		
			Context::getServiceManager()->install($this->type,$this->service);
		}

		return View::NONE;
	}
	
	/**
	 * The update of the service consists of 
	 * an uninstallation / installation.
	 * 
	 * @return integer
	 */
	public function executeUpdate() {
		$this->executeUninstall();
		$this->executeInstall();
		
		return View::NONE;
	}
	
	/**
	 * Executes the action.
	 *
	 * @return integer
	 */
	public function executeUninstall() {
		if($this->type != null && $this->service != null) {		
			Context::getServiceManager()->uninstall($this->type,$this->service);
		}
		
		return View::NONE;
	}
	
	/**
	 * When services selection is updated, 
	 * this method is called to update the 
	 * configuration.
	 *
	 */
	private function handleServicesUpdate() {
		$services = $this->getParameter('services',array());
		if(is_array($services) && count($services) > 0) {
			$sm = Context::getServiceManager();
			foreach($services as $type => $name) {
				$sm->setSelectedService($type,$name);
			}
			
			// Save configuration.
			Config::save();
		}
	}
	
	/**
	 * Index action entry point.
	 *
	 * @return integer
	 */
	public function executeIndex() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			$this->handleServicesUpdate();
		}
		
		$this->redirect = false;
		
		return View::TPL;
	}
	
	/**
	 * Service configuration action.
	 *
	 * @return integer
	 */
	public function executeConfigure() {
		$service = null;
		$type = null;
		if($this->getRequestMethod() == RequestMethod::POST) {
			$arr = $this->getParameter(ServiceConfigurationForm::FORM_PARAMETER);
			$service = isset($arr[ServiceConfigurationForm::ID_FIELD]) ? $arr[ServiceConfigurationForm::ID_FIELD] : null;
			$type = isset($arr[ServiceConfigurationForm::TYPE_FIELD]) ? $arr[ServiceConfigurationForm::TYPE_FIELD] : null;
		} else {
			$service = $this->getParameter('id');
			$type = $this->getParameter('type');
		}
		
		if($type != null && $service != null) {
			$service = Context::getServiceLocator()->getService($type,$service);
			$form = $service->getConfigurationForm();
			if($form != null) {
				if($this->getRequestMethod() == RequestMethod::POST) {
					$form->bind($this->getParameter(ServiceConfigurationForm::FORM_PARAMETER));
					if($form->isValid()) {
						$service->onValidConfigurationForm($form);
						$this->success = true;
					}
				}
				$this->form = $form;
			}
			
			$this->tpl->assign('type',$type);
			$this->tpl->assign('service',$service);
			$this->redirect = false;
		}
		return View::TPL;
	}
	
	/**
	 * Redirect user.
	 * 
	 * @see system/libraries/controller/DefaultController#postExecute()
	 */
	public function postExecute() {
		if(!$this->redirect) return;
		$this->redirect(
			Route::CONTROLLER_ACTION,
			array(
				'controller' => $this->getController(), 
				'action' => ModuleManager::getInstance()->getModuleDefinitionItem($this->getController(),ModuleDefinition::DEFAULT_ACTION)
			)
		);
	}
}

?>