<?php

/**
 * This form allows to filter the list of 
 * keywords displayed.
 *
 * @author Jerome Loisel
 */
class KeywordFilterForm extends sfI18nForm {
	
	/**
	 * Column filter. (name of the column)
	 *
	 */
	const FILTER_ORDER_COLUMN = 'keyword_order_column';
	const DEFAULT_ORDER_COLUMN = 'id';
	
	/**
	 * Order type filter. (ASC or DESC)
	 *
	 */
	const FILTER_ORDER_TYPE = 'keyword_order_type';
	const DEFAULT_ORDER_TYPE = 'DESC';
	
	/**
	 * filter on keyword state.
	 *
	 */
	const FILTER_IS_BANNED = 'keyword_is_banned';
	const DEFAULT_IS_BANNED = 2;
	
	/**
	 * Filter on keyword text.s
	 *
	 */
	const FILTER_TEXT = 'keyword_text';
	const DEFAULT_FILTER_TEXT = '';
	
	/**
	 * Possible order type choices.
	 *
	 * @var array
	 */
	public static $order_type_choices = array(
		'ASC' => 'Ascending','DESC' => 'Descending'
	);
	
	/**
	 * Possible column choices.
	 *
	 * @var array
	 */
	public static $order_column_choices = array(
		'id' => 'id', 
		'text' => 'keyword', 
		'count' => 'searches', 
		'created_on' => 'created on', 
		'is_banned' => 'banned'
	);
	
	/**
	 * Possible
	 *
	 * @var array
	 */
	public static $banned_choices = array(
		0 => 'valid', 1 => 'banned', 2 => 'all'
	);
	
	public function configure() {
		$this->setWidgets(array(
			self::FILTER_ORDER_COLUMN => new sfWidgetFormSelect(array(
				'choices' => self::$order_column_choices
			)),
			self::FILTER_ORDER_TYPE => new sfWidgetFormSelect(array(
				'choices' => self::$order_type_choices
			)),
			self::FILTER_IS_BANNED => new sfWidgetFormSelect(array(
				'choices' => self::$banned_choices
			)),
			self::FILTER_TEXT => new sfWidgetFormInput()
		));
		
		$this->widgetSchema->setLabels(array(
			self::FILTER_ORDER_COLUMN => 'on',
			self::FILTER_ORDER_TYPE => 'order by', 
			self::FILTER_IS_BANNED => 'status',
			self::FILTER_TEXT => 'like'
		));
		
		$this->widgetSchema->setNameFormat('filter[%s]');
		$this->widgetSchema->setFormFormatterName('flat');
		$this->getValidatorSchema()->setOption('allow_extra_fields',true);
	}
	
}

?>