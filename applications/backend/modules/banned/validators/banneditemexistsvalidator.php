<?php

/**
 * IP validator.
 *
 * @author Jerome Loisel
 */
class BannedItemExistsValidator extends sfValidatorBase {
	/**
	 * Banned table name.
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
	
	public function __construct($tableName,$type) {
		parent::__construct ( array (), array() );
		$this->addMessage('banned_item_exists','"%value%" is already blacklisted');
		$this->tableName = $tableName;
		$this->type = $type;
	}
	
	/**
	 * checks that no item with the same 
	 * value already exists in database.s
	 *
	 * @param string $value
	 * @return string
	 */
	protected function doClean($value) {
		$q = Doctrine::getTable($this->tableName)
				->createQuery()
				->where('type=? AND value=?',array($this->type,$value))
				->limit(1);
		if ($q->count() == 1) {
			throw new sfValidatorError($this, 'banned_item_exists', array('value' => $value));
		}
		return $value;
	}
}

?>