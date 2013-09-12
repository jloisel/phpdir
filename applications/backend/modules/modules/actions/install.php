<?php

/**
 * Installs the selected module once the module has been copied on the 
 * directory.
 *
 * @author Jerome Loisel
 */
class InstallAction extends DefaultController {
	
	public function executeAction() {
		$moduleName = $this->getParameter('id');
		if($moduleName != null) {
			ModuleManager::getInstance()->install($moduleName);
		}
		$this->redirect(
			Route::CONTROLLER_ACTION,
			array(
				'controller' => $this->getController(),
				'action' => ModuleManager::getInstance()->getModuleDefinitionItem(
					$this->getController(),
					ModuleDefinition::DEFAULT_ACTION
				)
			)
		);
		return View::NONE;
	}
	
}

?>