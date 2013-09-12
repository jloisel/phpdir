<?php

/**
 * Partner Form.
 *
 * @author Jerome Loisel
 */
class PartnerForm extends sfI18nForm {
	
	private static $targets = array(
		'_blank' => '_blank',
		'_top' => '_top',
		'' => 'none'
	);
	
	public function __construct() {
		parent::__construct();
	}
	
	public function configure() {
		$this->setWidgets(array(
			'name' => new sfWidgetFormInput(),
			'title' => new sfWidgetFormInput(),
			'link' => new sfWidgetFormInput(),
			'target' => new sfWidgetFormSelectRadio(array(
					'choices' => self::$targets
			)),
		));
		
		$this->setValidators(array(
			'name' => new sfValidatorString(),
			'title' => new sfValidatorString(),
			'link' => new sfValidatorUrl(),
			'target' => new sfValidatorPass()
		));
		
		$this->widgetSchema->setHelps(array(
			'name' => 'This is the link text.', 
			'title' => 'This is the link hidden title.',
			'link' => 'Example: http://www.google.com'
		));
		
		$this->setDefaults(array(
			'target' => '_blank', 
			'link' => 'http://www.'
		));
		
		$this->getWidgetSchema()->setNameFormat('partner[%s]');
	}
}

?>