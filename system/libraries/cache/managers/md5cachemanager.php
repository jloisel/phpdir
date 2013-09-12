<?php

/**
 * Cache manager. Allows to do caching.
 * 
 * @author Jerome Loisel
 */
class Md5CacheManager extends AbstractCacheManager {
	
	/**
	 * Default constructor.
	 *
	 * @param string $cacheFolder
	 * @param ICacheStrategy $cacheStrategy
	 */
	public function __construct($cacheStrategy) {
		parent::__construct($cacheStrategy);
	}
	
	/**
	 * Internal value of the passed key.
	 * This cache manager uses the md5 implementation.
	 *
	 * @param string $key
	 * @return string
	 */
	public function getInternalKey($key) {
		if(!isset($this->internalKeysCache[$key])) {
			// for a given key, md5 is computed once
			$this->internalKeysCache[$key] = md5($key);
		}
		return $this->internalKeysCache[$key];
	}
}

?>