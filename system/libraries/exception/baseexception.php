<?php

/**
 * Exception base class.
 * @author Jerome Loisel
 */
class BaseException extends Exception {

	/**
	 * Template engine.
	 *
	 * @var TemplateEngine
	 */
	private $_tpl = null;
	/**
	 * Default constructor.
	 *
	 * @param String $message
	 * @param Integer $code
	 */
	public function __construct($message, $code=0) {
		parent::__construct($message,$code);

		$this->_tpl = TemplateEngine::getInstance();
		$this->_tpl->setPath(dirname(__FILE__).'/templates');
	}

	/**
	 * Name of the template to load.
	 *
	 * @return String
	 */
	private function getTemplateName() {
		return strtolower(__CLASS__).'.html';
	}

	/**
	 * ToString() magic method.
	 * @return String
	 */
	public function __toString() {
		$this->_tpl->assign('exception',$this);
		$tplName = $this->getTemplateName();

		header('HTTP/1.0 404 Not Found');
		return $this->_tpl->fetch($tplName);
	}
}

?>