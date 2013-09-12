<?php

class IndexAction extends DefaultController {
	
	public function compare(Module $a, Module $b) {
		$weightA = $a->getWeight();
		$weightB = $b->getWeight();
		if($weightA == $weightB) return 0;
		return $weightA > $weightB ? 1 : -1;
	}
	
	public function executeAction() {
		$modules = Context::getModuleLocator()->getAllLocalObjects();
		uasort($modules,array($this,'compare'));
		foreach($modules as $key => $module) {
			if(!$module->isInstalled()) {
				unset($modules[$key]);
			}
		}
		$this->modules = $modules;
		return View::TPL;
	}
}

?>