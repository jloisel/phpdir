<?php

class IndexAction extends DefaultController {
	
	/**
	 * Updates the directory configuration.
	 */
	private function updateConfiguration() {
		$configs = $this->getParameters();
		if(is_array($configs) && count($configs) > 0) {
			foreach($configs as $name => $value) {
				Config::setItem($name,str_replace("'","\\'",$value));
			}
			Config::save();
		}
	}
	
	/**
	 * Action main entry point.
	 *
	 * @return integer
	 */
	public function executeAction() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			$this->updateConfiguration();
		}
		$themeDirectory = new File(SCRIPT_ROOT_PATH.'/public/themes');
		$this->themes = $themeDirectory->getSubdirectories();
		return View::TPL;
	}
}

?>