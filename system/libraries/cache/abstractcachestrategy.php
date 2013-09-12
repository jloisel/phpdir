<?php

/**
 * Cache strategy common implementation.
 *
 * @author Jerome Loisel
 */
abstract class AbstractCacheStrategy extends Singleton implements ICacheStrategy {
	
	/**
	 * Cache lifetime.
	 *
	 * @var int
	 */
	protected $lifetime = null;
	
	/**
	 * Associative array : cache key => cache file age
	 *
	 * @var array
	 */
	protected $cacheAges = array();
	

	/**
	 * Default constructor.
	 *
	 */
	public function __construct($lifetime=86400) {
		$this->lifetime = $lifetime;
	}
	
	/**
	 * @return int
	 */
	public function getLifetime() {
		return $this->lifetime;
	}
	
	/**
	 * @param int $lifetime
	 */
	public function setLifetime($lifetime) {
		$this->lifetime = $lifetime;
	}
	
	/**
	 * @return int
	 */
	public function getCacheAge($key) {
		if(isset($this->cacheAges[$key])) {
			return $this->cacheAges[$key];
		}
		return null;
	}
	
	/**
	 * @param int $cacheAge
	 */
	public function setCacheAge($key,$cacheAge) {
		$this->cacheAges[$key] = intval($cacheAge);
	}
	
	/**
	 * is the cached file expired ?
	 *
	 * FALSE if no cached content; TRUE if expired
	 * 
	 * @param mixed $key
	 * @return bool
	 */
	public function hasExpired($key) {
		if(!isset($this->cacheAges[$key])) {
			return false;
		}
		return 	($this->cacheAges[$key] < (time() - $this->lifetime));
	}
}

?>