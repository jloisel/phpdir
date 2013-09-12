<?php

/**
 * This validator checks if the 
 * keyword already exists in database.
 *
 * @author Jerome Loisel
 */
class KeywordExistsValidator extends sfValidatorBase {

	public function __construct() {
		parent::__construct();
		$this->addMessage('already_exists','%value% already exists in database');
	}
	
	protected function doClean($value) {
		$count = Doctrine::getTable('Keyword')->createQuery()
					->where('text=?',$value)
					->limit(1)
					->count();
		if($count > 0) {
			throw new sfValidatorError($this,'already_exists',array('value' => $value));
		}
		return $value;
	}
}

?>