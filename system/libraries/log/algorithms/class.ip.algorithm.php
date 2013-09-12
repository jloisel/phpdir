<?php
/**
 * Ip logging algorithm.
 * @author Jerome Loisel
 */
class IpLoggerAlgorithm extends AbstractLoggerAlgorithm {
	/**
	 * Connection to database
	 *
	 * @var Doctrine_Connection
	 */
	private $con = null;
	
	private $_objectType = null;
	private $_objectId = null;

	/**
	 * Default Ip logger algorithm constructor.
	 */
	function __construct() {
		parent::__construct();
		$this->con = Doctrine_Manager::getInstance()->getCurrentConnection();
	}
	
	/**
	 * Checks if something is logged for the provided information.
	 *
	 * @param String $objectType
	 * @param Integer $objectId
	 * @param String $ip
	 * @return Boolean TRUE if success
	 */
	public function isLogged($objectType,$objectId,$ip) {
		if($this->isValidIp($ip)) {
			$this->_objectType = $objectType;
			$this->_objectId = $objectId;
		
			$q = new Doctrine_Query();
			$count = $q	->from('Log l')
						->where('l.ip=? AND l.object_id=? AND l.object_type=? AND date>=?')
						->limit(1)
						->count(array($ip,$objectId,$objectType,$this->getExpirationDate()));
			return ($count == 1);
		}
		return false;
	}
	
	/**
	 * Logs an event for the specified information.
	 *
	 * @param String $objectType
	 * @param Integer $objectId
	 * @param String $ip
	 */
	public function log($objectType,$objectId,$ip) {
		$attributes = array('ip' 			=>	$ip, 
							'object_id'		=>	intval($objectId), 
							'object_type'	=>	$objectType,
							'date'			=>	$this->now()		);
		$log = Doctrine::getTable('log')->create();
		$log->setArray($attributes);
		$log->save();
	}
	
	/**
	 * Garbage collector
	 * @return Integer cleaned items count
	 */
	public function gc() {
		$q = Doctrine::getTable('log')->createQuery();
		$cleanCount = $q->from('Log l')
						->where('l.object_id=? AND l.object_type=? AND date<=?')
						->delete()
						->execute(array($this->_objectId,$this->_objectType,$this->getExpirationDate()));
		return $cleanCount;
	}
}

?>