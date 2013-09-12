<?php 
/**
 * Website feed form.
 * 
 * @author Jerome Loisel
 */
class FeedForm extends sfI18nForm {
	
	private static $charsetChoices = array(
		'ISO-8859-1' => 'ISO-8859-1', 
		'UTF-8' => 'UTF-8'
	);
	
	public function configure() {
		$this->setWidgets(array(
			'title' => new sfWidgetFormInput(), 
			'link' => new sfWidgetFormInput(), 
			'charset' => new sfWidgetFormSelect(array(
				'choices' => self::$charsetChoices
			))
		));
		
		$this->setValidators(array(
			'title' => new sfValidatorString(array(
				'min_length' => 2, 
				'max_length' => 80
			)), 
			'link' => new sfValidatorAnd(array(
				new sfValidatorString(array(
					'min_length' => 7, 
					'max_length' => 255
				)),
				new sfValidatorUrl()
			)), 
			'charset' => new sfValidatorChoice(array(
				'choices' => array_keys(self::$charsetChoices)
			))
		));
		
		$this->setDefaults(array(
			'title' => '', 
			'link' => 'http://'
		));
		
		$this->widgetSchema->setNameFormat('feed[%s]');
	}
	
}

?>