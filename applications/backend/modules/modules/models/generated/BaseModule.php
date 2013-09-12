<?php

abstract class BaseModule extends Doctrine_Record {
	
	public function setTableDefinition() {
		$this->setTableName ( 'module' );
		$this->hasColumn ( 'id', 'integer', 4, array ('type' => 'integer', 'length' => 4, 'primary' => true, 'autoincrement' => true ) );
		$this->hasColumn ( 'name', 'string', 20, array ('type' => 'string', 'length' => 20, 'notnull' => true ) );
		$this->hasColumn ( 'installed_on', 'timestamp', null, array ('type' => 'timestamp', 'notnull' => true ) );
	}

}

?>