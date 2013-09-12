<?php

/**
 * Uninstalls the selected module.
 *
 * @author Jerome Loisel
 */
class UninstallAction extends DefaultController {
	
	public function executeAction() {
		$moduleName = $this->getParameter('id');
		if($moduleName != null) {
			ModuleManager::getInstance()->uninstall($moduleName);
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