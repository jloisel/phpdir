<?php

/**
 * File caching strategy.
 *
 * @author Jerome Loisel
 */
final class FileCacheStrategy extends AbstractCacheStrategy {

	/**
	 * Default file extension for cached files.
	 *
	 */
	const DEFAULT_CACHE_EXTENSION = '.cache';
	
	/**
	 * Cache folder on disk. (full path)
	 *
	 * @var string
	 */
	protected $cacheFolder = null;
	
	/**
	 * Extension used for cached files.
	 *
	 * @var string
	 */
	protected $cachedFileExtension =  null;
	
	/**
	 * Default constructor. 
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Is there a cached file for the given key ?
	 *
	 * @param string $key
	 * @return bool
	 */
	public function isCached($key) {
		if($this->getCacheAge($key) == null) {
			$cacheFile = $this->getCacheFile($key);
			if(file_exists($cacheFile)) {
				$this->setCacheAge($key,filemtime($cacheFile));
				return true;
			} else {
				return false;
			}
		}
	}
	
	/**
	 * Cache the content $value in a file named $key.
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function cache($key, $value) {
		$file = new File($this->getCacheFile($key));
		$file->setChmod(0755);
		$file->setContent($value);
		$success = $file->write();
		if(!$success) {
			throw new BaseException('Cannot cache content in "'.$file->getFullname().'" : file is not writeable');
		}
	}
	
	/**
	 * Returns the content of the cached file $key.
	 *
	 * @param string $key
	 */
	public function getCache($key) {
		$cachedFileContent = null;
		$cacheFile = $this->getCacheFile($key);
		if(is_readable($cacheFile)) {
			$f = fopen($cacheFile,'r');
			if(is_resource($f)) {
				// Acquire lock to read file
				if(flock($f,LOCK_SH)) {
					$cachedFileContent = fread($f,filesize($cacheFile));
					// release lock
					flock($f,LOCK_UN);
				}
				fclose($f);
			}
		}
		return $cachedFileContent;
	}
	
	/**
	 * Returns the full path of the cache file named $key.
	 *
	 * @param string $key
	 * @return string
	 */
	protected function getCacheFile($key) {
		return $this->cacheFolder.'/'.$this->getCachedFileName($key);
	}
	
	/**
	 * Deletes the cache item $key from cache.
	 *
	 * @param string $key
	 */
	public function delCache($key) {
		$cacheFile = $this->getCacheFile($key);
		if(file_exists($cacheFile)) {
			@unlink($cacheFile);
		}
	}
	
	/**
	 * @return string
	 */
	public function getCacheFolder() {
		return $this->cacheFolder;
	}
	
	/**
	 * @param string $cacheFolder
	 */
	public function setCacheFolder($cacheFolder) {
		$this->cacheFolder = $cacheFolder;
		if(!is_dir($cacheFolder)) {
			mkdir($cacheFolder,null,true);
		}
	}
	
	/**
	 * @return string
	 */
	public function getCachedFileExtension() {
		if($this->cachedFileExtension == null) {
			$this->cachedFileExtension = self::DEFAULT_CACHE_EXTENSION;
		}
		return $this->cachedFileExtension;
	}
	
	/**
	 * Returns the name of the cached file, including 
	 * file extension, without path.
	 *
	 * @param string $key
	 * @return string
	 */
	public function getCachedFileName($key) {
		return $key.$this->getCachedFileExtension();
	}
	
	/**
	 * @param string $cachedFileExtension
	 */
	public function setCachedFileExtension($cachedFileExtension) {
		$this->cachedFileExtension = $cachedFileExtension;
	}
	
	/**
	 * Unique instance of File cache strategy.
	 *
	 * @return FileCacheStrategy
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>