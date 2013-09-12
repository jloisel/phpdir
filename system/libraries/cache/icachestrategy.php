<?php

/**
 * Cache strategy interface.
 * 
 * Every cache strategy must be registered here using 
 * a constant.
 *
 */
interface ICacheStrategy {
	
	/**
	 * Returns the cache lifetime. (in secs)
	 *
	 * @return int
	 */
	public function getLifetime();
	
	/**
	 * Sets the cache lifetime. (in secs)
	 *
	 * @param int $lifetime
	 */
	public function setLifetime($lifetime);
	
	/**
	 * Is there an existing cached item for the given key ?
	 *
	 * @param mixed $key
	 */
	public function isCached($key);
	
	/**
	 * Cache the value at the given key.
	 *
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function cache($key,$value);
	
	/**
	 * Returns the content of the cache item $key.
	 *
	 * @param mixed $key
	 */
	public function getCache($key);
	
	
	/**
	 * Returns the cache age in secs.
	 *
	 */
	public function getCacheAge($key);
	
	/**
	 * Sets the cache age.
	 *
	 * @param int $cacheAge
	 */
	public function setCacheAge($key,$cacheAge);
	
	/**
	 * Delete the specified $key cache item.
	 *
	 * @param mixed $key
	 */
	public function delCache($key);
	
	/**
	 * Is the cache file expired ? (file existing and older than 
	 * cache lifetime)
	 *
	 * @param mixed $key
	 */
	public function hasExpired($key);
}

?>