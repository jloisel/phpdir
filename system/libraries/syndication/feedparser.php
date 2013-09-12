<?php

/**
 * Little RSS parser wrapping SimpleXML PHP class.
 * @author Jerome Loisel
 */
class FeedParser {
	/**
	 * Is caching enabled ?
	 * @var Boolean
	 */
	protected $_caching = null;
	/**
	 * Cache lifetime in seconds.
	 * @var Integer
	 */
	protected $_cacheLifetime = null;
	/**
	 * Cache directory (full path)
	 * @var String
	 */
	protected $_cacheDir = null;
	/**
	 * Parsed feed
	 * @var SimpleXMLElement
	 */
	protected $_feed = null;

	/**
	 * Is the loaded feed a cached one ?
	 *
	 * @var Boolean
	 */
	protected $_isCached = false;

	/**
	 * Default constructor
	 */
	public function __construct() {
		$this->_caching = true;
		$this->_cacheLifetime = 21600; // 6 hours
		$this->_cacheDir = SCRIPT_ROOT_PATH.'/public/cache';
	}

	/**
	 * Checks if caching is enabled
	 * @return 	boolean TRUE if success
	 */
	public function isCachingEnabled() {
		return ($this->_caching);
	}

	/**
	 * Sets caching to disabled/enabled
	 * @param	boolean TRUE if caching enabled
	 */
	public function setCaching($caching) {
		if(is_int($caching) && ($caching == 0 || $caching == 1))
		$this->_caching = $caching;
	}

	/**
	 * Sets caching lifetime
	 * @param	Integer cached file lifetime in seconds
	 */
	public function setCacheLifetime($cacheLifetime) {
		if(is_int($cacheLifetime) && $cacheLifetime > 0) {
			$this->_cacheLifetime = $cacheLifetime;
		}
	}

	/**
	 * Returns the cached file lifetime
	 * @return	Integer cache lifetime in seconds
	 */
	public function getCacheLifetime() {
		return $this->_cacheLifetime;
	}

	/**
	 * Sets the cache directory (where to store cache files, full path)
	 * @param	String full path to cache directory
	 */
	public function setCacheDir($cacheDir) {
		if(is_dir($cacheDir) && is_writeable($cacheDir)) {
			$this->_cacheDir = $cacheDir;
		}
	}

	/**
	 * Retrieves cached file content, if file exists.
	 * @param String $feedUrl
	 * @return String
	 */
	protected function getCachedFileContent($feedUrl) {
		$filepath = $this->getCacheFilename($feedUrl);
		if(!empty($filepath) && is_readable($filepath)) {
			if(file_exists($filepath) && is_readable($filepath)) {
				$f = fopen($filepath,'r');
				$fileContent = '';
				while (!feof($f)) {
					$fileContent .= fgets($f, 4096);
				}
				fclose($f);

				return $fileContent;
			}
		}
		return '';
	}

	/**
	 * Generates cached filename from feed URL.
	 * @param	String feed url
	 * @return	String corresponding cached filename
	 */
	protected function getCacheFilename($feedUrl) {
		return $this->_cacheDir.'/'.sha1($feedUrl);
	}

	/**
	 * Writes Serialized simpleXML object to cache file.
	 * @param	String feed URL
	 * @return	Boolean TRUE if Success, else FALSE
	 */
	protected function writeCacheFile($feedUrl) {
		if(is_object($this->_feed)) {
			$contentToCache = $this->_feed->asXML();
			if(!empty($this->_cacheDir) && is_writable($this->_cacheDir)) {
				$cachedFilepath = $this->getCacheFilename($feedUrl);
				$f = @fopen($cachedFilepath,'w');
				if($f && is_writable($cachedFilepath)) {
					fwrite ($f, $contentToCache, strlen($contentToCache));
					fclose($f);
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Loads a feed and caches it if caching is enabled.
	 * @param	String feed url
	 * @return	SimpleXMLElement
	 */
	public function load($feedUrl) {
		if($this->_caching) {
			$cachedFile = $this->getCacheFilename($feedUrl);

			if(file_exists($cachedFile)) {
				$timedif = @(time() - filemtime($cachedFile));
				if($timedif <  $this->_cacheLifetime) {
					$xml = $this->getCachedFileContent($feedUrl);
					$this->_feed = simplexml_load_string($xml);
					$this->_isCached = true;
				} else {
					$this->_feed = @simplexml_load_file($feedUrl);
					$this->writeCacheFile($feedUrl);
				}
			} else {
				$this->_feed = @simplexml_load_file($feedUrl);
				$this->writeCacheFile($feedUrl);
			}
		} else {
			$this->_feed = @simplexml_load_file($feedUrl);
		}
		return $this->_feed;
	}
}

?>