<?php

/**
 * Base service configuration form implementation.
 *
 */
abstract class ServiceConfigurationForm extends sfI18nForm {
	
	/**
	 * Hidden form fields.
	 */
	const TYPE_FIELD = 'type';
	const ID_FIELD = 'id';
	
	/**
	 * Service Form request parameter.
	 */
	const FORM_PARAMETER = 'service';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function configure() {
		parent::configure();
		$r = Context::getHttpRequest();
		$this->setDefault(self::TYPE_FIELD,$r->getParameter(self::TYPE_FIELD));
		$this->setDefault(self::ID_FIELD,$r->getParameter(self::ID_FIELD));
		
		$this->widgetSchema->setNameFormat(self::FORM_PARAMETER.'[%s]');
	}
	
	public function setValidators(array $validators) {
		parent::setValidators(array_merge(
			array(
				self::TYPE_FIELD => new sfValidatorPass(),
				self::ID_FIELD => new sfValidatorPass()
			),
			$validators
		));
	}
	
	public function setWidgets(array $widgets) {
		parent::setWidgets(array_merge(
			array(
				self::TYPE_FIELD => new sfWidgetFormInputHidden(),
				self::ID_FIELD => new sfWidgetFormInputHidden()
			),
			$widgets
		));
	}
}

?>