<?php

/**
 * Add banned item form.
 *
 * @author Jerome Loisel
 */
abstract class AddBannedItemForm extends sfI18nForm {
	
	/**
	 * Banned items table name.
	 *
	 * @var string
	 */
	private $tableName = null;
	
	/**
	 * Banned item type.
	 *
	 * @var integer
	 */
	private $type = null;
	
	/**
	 * Default constructor
	 *
	 * @param string $tableName
	 */
	public function __construct($tableName, $type) {
		$this->tableName = $tableName;
		$this->type = $type;
		parent::__construct();
	}
	
	public function configure() {
		parent::configure();
		$this->setWidgets(array(
			'value' => new sfWidgetFormInput()
		));
		
		$this->setValidators(array(
			'value' => new sfValidatorAnd(array(
				new BannedItemExistsValidator($this->tableName,$this->type),
				$this->getSpecificFieldValidator()
			))
		));
		
		$this->widgetSchema->setNameFormat($this->tableName.'[%s]');
	}
	
	/**
	 * Returns the field specific field validator
	 *
	 */
	protected abstract function getSpecificFieldValidator();
}

?>