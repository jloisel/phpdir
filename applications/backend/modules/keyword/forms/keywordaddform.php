<?php

/**
 * Keyword add form.
 * 
 * @author Jerome Loisel
 *
 */
class KeywordAddForm extends sfI18nForm {
	
	public function configure() {
		$this->setWidgets(array(
			'text' => new sfWidgetFormInput()
		));
		
		$this->setValidators(array(
			'text' => new sfValidatorAnd(array(
					new sfValidatorString(array(
						'min_length' => Config::get('keyword_min_length'),
						'max_length' => 50
					)),
					new KeywordExistsValidator()
				))
			));
		
		$this->widgetSchema->setNameFormat('add[%s]');
	}
	
}

?>