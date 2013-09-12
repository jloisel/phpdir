<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseWebsite extends Doctrine_Record
{
  public function setTableDefinition()
  {
    $this->setTableName('website');
    $this->hasColumn('id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'primary' => true, 'autoincrement' => true));
    $this->hasColumn('category_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'notnull' => true));
    $this->hasColumn('customer_id', 'integer', 4, array('type' => 'integer', 'length' => 4, 'notnull' => true));
    $this->hasColumn('ip', 'string', 15, array('type' => 'string', 'length' => 15, 'notnull' => true));
   	$this->hasColumn('link', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('title', 'string', 255, array('type' => 'string', 'length' => 255, 'notnull' => true));
    $this->hasColumn('subtitle', 'string', 80, array('type' => 'string', 'length' => 80));
    $this->hasColumn('description', 'string', null, array('type' => 'string', 'notnull' => true));
    $this->hasColumn('backlink', 'string', 255, array('type' => 'string', 'length' => 255));
    $this->hasColumn('country', 'string', 2, array('type' => 'string', 'length' => 2, 'notnull' => true));
    $this->hasColumn('ins', 'integer', 4, array('type' => 'integer', 'length' => 4, 'default' => '0', 'notnull' => true));
    $this->hasColumn('outs', 'integer', 4, array('type' => 'integer', 'length' => 4, 'default' => '0', 'notnull' => true));
    $this->hasColumn('priority', 'integer', 1, array('type' => 'integer', 'length' => 1, 'default' => '0', 'notnull' => true));
    $this->hasColumn('state', 'integer', 1, array('type' => 'integer', 'length' => 1, 'default' => '0', 'notnull' => true));
    $this->hasColumn('is_broken', 'enum', 1, array('type' => 'enum', 'length' => 1, 'values' => array(0 => '0', 1 => '1'), 'default' => '0', 'notnull' => true));
   	$this->hasColumn('created_on', 'timestamp', null, array('type' => 'timestamp', 'notnull' => true));
    $this->hasColumn('updated_on', 'timestamp', null, array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'notnull' => true));
    $this->hasColumn('validated_on', 'timestamp', null, array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00', 'notnull' => true));
    $this->hasColumn('options', 'string', null, array('type' => 'string', 'notnull' => true, 'default' => ''));
  }
  
  public function setUp() {
  	$this->hasOne('Customer',array('local' => 'customer_id', 'foreign' => 'id'));
  	$this->hasMany('Comment as Comments', array('local' => 'id', 'foreign' => 'website_id'));
  	$this->hasMany('Tag as Tags', array('local' => 'website_id', 'foreign' => 'tag_id', 'refClass' => 'WebsiteHasTag'));
  	$this->hasMany('Feed as Feeds', array('local' => 'id', 'foreign' => 'website_id'));
  }
}