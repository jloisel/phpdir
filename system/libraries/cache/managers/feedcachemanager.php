<?php

/**
 * Little RSS feed cache wrapping SimpleXML PHP class.
 * 
 * @author Jerome Loisel
 */
final class FeedCacheManager extends Md5CacheManager {
	
	/**
	 * Default cache lifetime. (6 hours)
	 *
	 */
	const DEFAULT_CACHE_LIFETIME = 86400;
	
	/**
	 * Default location where feeds are cached.
	 *
	 */
	const DEFAULT_CACHE_DIR = 'cache/feeds';
	
	/**
	 * Cache directory (full path)
	 * @var String
	 */
	protected $_cacheDir = null;
	
	/**
	 * Cache strategy : the concrete manner the feeds are cached.
	 *
	 * @var ICacheStrategy
	 */
	protected $cacheStrategy = null;
	
	/**
	 * Cache manager is able to manipulated 
	 * cached data.
	 *
	 * @var CacheManager
	 */
	protected $cacheManager = null;

	/**
	 * Default constructor
	 */
	public function __construct() {
		parent::__construct(
			CacheStrategyFactory::getNew(CacheStrategyFactory::FILE_STRATEGY),
			self::DEFAULT_CACHE_LIFETIME
		);
		$defaultCacheFolder  = Context::getParameterHolder()->get(Parameter::PUBLIC_FOLDER).'/'.self::DEFAULT_CACHE_DIR;
		$this->getCacheStrategy()->setCacheFolder($defaultCacheFolder);
	}

	/**
	 * Returns the XML data of a feed from URL.
	 *
	 * @param string $url
	 * @return string
	 */
	protected function getXml($url) {
		$request = new RemoteRequest($url);
		return $request->getContent();
	}
	
	/**
	 * Loads a feed and caches it if caching is enabled.
	 * @param $url String feed url
	 * @return SimpleXMLElement
	 */
	public function getFeed($url) {
		if(!$this->isCached($url)) {
			$xml = $this->getXml($url);
			if($xml != null && !empty($xml)) {
				$this->cache(
					$url, 
					$xml
				);
			}
		}
		$xml = $this->getCache($url);
		return $xml != null ? simplexml_load_string($xml) : null;
	}
}

?>