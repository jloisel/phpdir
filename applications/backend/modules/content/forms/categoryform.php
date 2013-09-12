<?php

/**
 * Category form.
 * 
 * @author Jerome Loisel
 */
class CategoryForm extends sfI18nForm {
	
	/**
	 * Form fields.
	 */
	const TITLE_FIELD = 'title';
	const DESCRIPTION_FIELD = 'description';
	const ALLOW_SUBMIT_FIELD = 'allow_submit';
	const IS_ADULT_FIELD = 'is_adult';
	const CATEGORY_ID_FIELD = 'category_id';
	
	private $isAdultCategory = 0;
	
	private static $allowSubmitChoices = array(
		'1' => 'Yes', 
		'0' => 'No'
	);
	
	private static $isAdultChoices = array(
		'1' => 'Yes', 
		'0' => 'No'
	);
	
	public function __construct($isAdult) {
		$this->isAdultCategory = $isAdult;
		parent::__construct();
	}
	
	public function configure() {
		parent::configure();
		$this->setWidgets(array(
			self::TITLE_FIELD => new sfWidgetFormInput(), 
			self::DESCRIPTION_FIELD => new sfWidgetFormTextarea(),
			self::ALLOW_SUBMIT_FIELD => new sfWidgetFormSelectRadio(array(
				'choices' => self::$allowSubmitChoices
			)), 
			self::IS_ADULT_FIELD => new sfWidgetFormSelectRadio(array(
				'choices' => self::$isAdultChoices
			)), 
			self::CATEGORY_ID_FIELD => new sfWidgetFormInputHidden()
		));
		
		$this->setValidators(array(
			self::TITLE_FIELD => new sfValidatorString(array(
				'min_length' => 2
			)), 
			self::DESCRIPTION_FIELD => new sfValidatorPass(), 
			self::ALLOW_SUBMIT_FIELD => new sfValidatorChoice(array(
				'choices' => array_keys(self::$allowSubmitChoices)
			)), 
			self::IS_ADULT_FIELD => new sfValidatorChoice(array(
				'choices' => array_keys(self::$isAdultChoices)
			)), 
			self::CATEGORY_ID_FIELD => new sfValidatorInteger(array(
				'min' => 0
			))
		));
		$this->setDefaults(array(
			self::ALLOW_SUBMIT_FIELD => 1, 
			self::IS_ADULT_FIELD => $this->isAdultCategory, 
			self::CATEGORY_ID_FIELD => intval(Context::getHttpRequest()->getParameter('id',0))
		));
		
		$this->widgetSchema->setNameFormat('category[%s]');
		$this->validatorSchema->setOption('allow_extra_fields', true);
	}
	
}

?>