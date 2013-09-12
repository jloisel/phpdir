<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class BannedIp extends BaseBan 
{
	/**
	 * IP banned type.
	 */
	const TYPE = 2;
	
	/**
	 * Sets type on insert.
	 *
	 * @param Doctrine_Event $event
	 */
	public function preInsert($event) {
		$this->type = self::TYPE;
	}
	
	/**
	 * Is the IP banned ?
	 *
	 * @param string $ip
	 * @return boolean
	 */
	public static function isBanned($ip) {
		return parent::isBanned(get_class($this),self::TYPE,$ip);
	}
	
	/**
	 * Ban an ip.
	 *
	 * @param string $ip
	 */
	public static function ban($ip) {
		parent::ban(get_class($this),self::TYPE,$ip);
	}
	
	/**
	 * Removes IP from banned ones.
	 *
	 * @param string $ip
	 */
	public static function removeBan($ip) {
		parent::removeBan(get_class($this),self::TYPE,$ip);
	}
}