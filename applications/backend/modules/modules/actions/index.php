<?php

class IndexAction extends DefaultController {
	
	public function preExecute() {
		$css = HtmlHeaderFactory::createHeader('Stylesheet');
		$css->setAttribute('href',HeaderHelper::module_stylesheet_url('style.css'));
		$this->addHtmlHeader($css);
	}
	
	/**
	 * Displays the installed modules.
	 * Only non-system modules are displayed.
	 *
	 * @return integer
	 */
	public function executeAction() {
		$this->modules = Context::getModuleLocator()->getAllLocalObjects();
		return View::TPL;
	}	
}

?>