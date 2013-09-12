<?php

/**
 * Website form: allows to add/edit a website.
 *
 * @author Jerome Loisel
 */
class WebsiteForm extends sfI18nForm {
	
	/**
	 * All website form fields.
	 */
	const LINK_FIELD = 'link';
	const TITLE_FIELD = 'title';
	const SUBTITLE_FIELD = 'subtitle';
	const DESCRIPTION_FIELD = 'description';
	const BACKLINK_FIELD = 'backlink';
	const COUNTRY_FIELD = 'country';
	const INS_FIELD = 'ins';
	const OUTS_FIELD = 'outs';
	const PRIORITY_FIELD = 'priority';
	const CATEGORY_ID_FIELD = 'category_id';
	const TAGS_FIELD = 'tags';
	
	private $parentCategoryId = null;
	
	public function __construct($parentCategoryId) {
		$this->parentCategoryId = $parentCategoryId;
		parent::__construct();
	}
	
	private static $countries = array(
		'fr' => 'French',
		'en' => 'English'
	);
	
	public function configure() {
		$this->setWidgets(array(
			self::LINK_FIELD => new sfWidgetFormInput(), 
			self::TITLE_FIELD => new sfWidgetFormInput(), 
			self::SUBTITLE_FIELD => new sfWidgetFormInput(),
			self::DESCRIPTION_FIELD => new sfWidgetFormTextarea(),	
			self::BACKLINK_FIELD => new sfWidgetFormInput(),
			self::COUNTRY_FIELD => new sfWidgetFormSelect(array(
				'choices' => self::$countries
			)), 
			self::INS_FIELD => new sfWidgetFormInput(),
			self::OUTS_FIELD => new sfWidgetFormInput(),
			self::PRIORITY_FIELD => new sfWidgetFormInput(), 
			self::TAGS_FIELD => new sfWidgetFormInput(),
			self::CATEGORY_ID_FIELD => new sfWidgetFormInputHidden()
		));
		
		$this->setValidators(array(
			self::LINK_FIELD => new sfValidatorAnd(array(
				new sfValidatorUrl(),
				new sfValidatorString(array(
					'max_length' => 255
				))
			)),
			self::TITLE_FIELD => new sfValidatorString(array(
				'min_length' => 2,
				'max_length' => 255
			)),
			self::SUBTITLE_FIELD => new sfValidatorString(array(
				'min_length' => 0,
				'max_length' => 255
			)),
			self::DESCRIPTION_FIELD => new sfValidatorString(array(
				'min_length' => 0, 
				'max_length' => 65536
			)), 
			self::BACKLINK_FIELD => new sfValidatorPass(), 
			self::COUNTRY_FIELD => new sfValidatorChoice(array(
				'choices' => array_keys(self::$countries)
			)), 
			self::INS_FIELD => new sfValidatorNumber(),
			self::OUTS_FIELD => new sfValidatorNumber(),
			self::PRIORITY_FIELD => new sfValidatorNumber(array(
				'min' => 0, 
				'max' => 9
			)), 
			self::TAGS_FIELD => new sfValidatorPass(),
			self::CATEGORY_ID_FIELD => new sfValidatorNumber()
		));
		
		$this->setDefaults(array(
			self::LINK_FIELD => 'http://',
			self::COUNTRY_FIELD => 'en',
			self::INS_FIELD => 0, 
			self::OUTS_FIELD => 0,
			self::PRIORITY_FIELD => 0, 
			self::CATEGORY_ID_FIELD => $this->parentCategoryId
		));
		
		$this->getWidgetSchema()->setHelps(array(
			self::BACKLINK_FIELD => 'URL. Example: http://www.google.fr.', 
			self::PRIORITY_FIELD => 'From 0 (default) to 9. 9 is the highest value.', 
			self::TAGS_FIELD => 'Separate tags using ",".'
		));
		
		$this->widgetSchema->setNameFormat('website[%s]');
		$this->validatorSchema->setOption('allow_extra_fields', true);
	}
	
}

?>