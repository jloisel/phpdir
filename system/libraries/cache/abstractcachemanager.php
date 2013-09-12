<?php

/**
 * Default cache manager implementation.
 *
 * @author Jerome Loisel
 */
abstract class AbstractCacheManager {

	/**
	 * Cache strategy.
	 *
	 * @var ICacheStrategy
	 */
	protected $cacheStrategy = null;
	
	/**
	 * Internal cache keys are cached.
	 *
	 * @var array
	 */
	protected $internalKeysCache = array();
	
	/**
	 * Default constructor.
	 *
	 * @param string $cacheFolder
	 * @param ICacheStrategy $cacheStrategy
	 * @param int $lifetime
	 */
	protected function __construct($cacheStrategy) {
		$this->cacheStrategy = $cacheStrategy;
	}
	
	/**
	 * Says if there is a cached value for this key.
	 *
	 * @param string $key
	 */
	public function isCached($key) {
		return $this->cacheStrategy->isCached($this->getInternalKey($key));
	}
	
	/**
	 * Caches the content $value at cache key $key.
	 *
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function cache($key,$value) {
		if($value != null && !empty($value)) {
			$this->cacheStrategy->cache($this->getInternalKey($key),$value);
		}
	}
	
	/**
	 * Returns the cached value at the cache key $key. 
	 *
	 * @param mixed $key
	 */
	public function getCache($key) {
		return $this->cacheStrategy->getCache($this->getInternalKey($key));
	}
	
	/**
	 * Delete the specified cache key.
	 *
	 * @param string $key
	 */
	public function delCache($key) {
		$this->cacheStrategy->delCache($this->getInternalKey($key));
	}
	
	/**
	 * Has the cached item for this key expired ?
	 *
	 * @param mixed $key
	 * @return bool
	 */
	public function hasExpired($key) {
		return $this->cacheStrategy->hasExpired($this->getInternalKey($key));
	}
	
	/**
	 * Returns the cache age.
	 *
	 * @return int
	 */
	public function getCacheAge($key) {
		return $this->cacheStrategy->getCacheAge($this->getInternalKey($key));
	}
	
	/**
	 * @return ICacheStrategy
	 */
	public function getCacheStrategy() {
		return $this->cacheStrategy;
	}
	
	/**
	 * this method must be implemented by subclasses :
	 * it defines the internal name of the cache key.
	 *
	 * @param string $key
	 */
	public abstract function getInternalKey($key);
}

?>
