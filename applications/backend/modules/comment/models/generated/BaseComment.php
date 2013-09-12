<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseComment extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('comment');
    $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('website_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'notnull' => true));
    $this->hasColumn('customer_id', 'integer', 4, array('type' => 'integer', 'length' => 4));
    $this->hasColumn('ip', 'string', 15, array('type' => 'string', 'length' => 15, 'notnull' => true));
    $this->hasColumn('text', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('created_on', 'timestamp', null, array('type' => 'timestamp', 'notnull' => true));
    $this->hasColumn('status', 'enum', 1, array('type' => 'enum', 'length' => 1, 'values' => array(0 => '0', 1 => '1', 2 => '2'), 'default' => '0', 'notnull' => true));
  }

  public function setUp()
  {
    $this->hasOne('Website', array('local' => 'website_id','foreign' => 'id', 'onDelete' => 'cascade'));
    
    $this->hasOne('Customer',array('local' => 'customer_id', 'foreign' => 'id', 'onDelete' => 'cascade'));
  }
}