<?php

/**
 * The default controller uses view folder within the 
 * application to render itself.
 *
 * @author Jerome Loisel
 */
class DefaultController extends AbstractController {

	public function __construct() {
		parent::__construct();
		$this->initView();
	}
	
	/**
	 * Initializes the view folders and Layout type.
	 *
	 */
	private function initView() {
		$this->tpl->setPath(
			array(
				$this->getParameterHolder()->getModulePath().DIRECTORY_SEPARATOR.'view', 
				$this->getParameterHolder()->getApplicationPath().DIRECTORY_SEPARATOR.'view'
			)
		);
		$this->getHttpResponse()->setLayout(Layout::HTML);
	}
	
	/**
	 * Add a new Html header.
	 *
	 * @param HtmlHeader $header
	 */
	protected final function addHtmlHeader($header) {
		HtmlHeaders::getInstance()->addHeader($header);
	}
	
	/**
	 * Method called before Controller Action invokation.
	 */
	public function preExecute() {

	}

	/**
	 * Method called after Controller Action invokation.
	 */
	public function postExecute() {

	}
	
	/**
	 * Returns the default action of the current controller.
	 *
	 * @return string
	 */
	protected final function getDefaultAction() {
		return Context::getModuleConfigLoader()->getItem(ConfigLoader::GENERAL, 'default_action');
	}
}

?>
