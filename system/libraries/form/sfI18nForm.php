<?php

/**
 * Wraps the symfony Form object in order 
 * to enable internationalization.
 *
 * @author Jerome Loisel
 */
abstract class sfI18nForm extends sfForm {
	
	public function __construct() {
		parent::__construct();
		$this->initI18n();
	}
	
	/**
	 * Configures internationalization.
	 *
	 */
	private function initI18n() {
		$this->getWidgetSchema()->getFormFormatter()->setTranslationCallable(array(new l10n(), 'translate'));
	}
}

?>