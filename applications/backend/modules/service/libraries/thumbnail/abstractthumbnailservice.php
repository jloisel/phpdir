<?php

/**
 * Thumbnail provider base class.
 *
 * @author Jerome Loisel
 */
abstract class AbstractThumbnailService extends ServiceAdapter implements ThumbnailService {
	
	/**
	 * Time base.
	 *
	 */
	const DAY_IN_SECONDS = 86400;
	
	/**
	 * The URL template to query for gavering the
	 * thumbnails.
	 *
	 * @var string
	 */
	private $thumbnailQueryUrl = null;
	
	/**
	 * Caching manager.
	 *
	 * @var CacheManager
	 */
	private $cacheManager = null;
	
	/**
	 * Thumbnail additional query data.
	 *
	 * @var array
	 */
	private $queryData = array ( );
	
	/**
	 * Thumbnail Cache relative to script root path. 
	 *
	 * @var string
	 */
	private $relativeCacheFolder = null;
	
	/**
	 * Thumbnails are cached only after this delay.
	 * (in seconds) 
	 *
	 * @var int
	 */
	private $cachingDelay = null;
	
	/**
	 * Http request.
	 *
	 * @var HttpRequest
	 */
	private $httpRequest = null;
	
	/**
	 * Default constructor :
	 * $thumbSize : MICRO_THUMB, SMALL_THUMB ...
	 * $thumbRenewalDelay : delay after which the thumbnail is downloaded again
	 * $cachingDelay : delay after which the thumbnail is cached for the first time 
	 * $renewalDelay : delay after which the cached thumbnail is updated
	 * 
	 * @param $queryUrl
	 */
	public function __construct($queryUrl) {
		$this->setThumbnailCacheFolder ( 'public/cache/thumbnails' );
		
		// URL of the Thumb service
		$this->setThumbnailQueryUrl ( $queryUrl );
		
		// Thumbnail is updated if the cached file age is greater than lifetime.
		$this->getCacheStrategy ()->setLifetime ( $this->getDefaultRenewalDelay() );
		
		// Caching delay : thumbnail is cached only after this amount of time, 
		// since the first request trying to download the thumbnail.
		$this->setCachingDelay ( $this->getDefaultCachingDelay() );
		
		// Websnapr provides JPG thumbnails
		$this->getCacheStrategy ()->setCachedFileExtension ( '.jpg' );
	}
	
	/**
	 * @return string
	 */
	protected function getThumbnailCacheFolder() {
		return $this->getCacheStrategy ()->getCacheFolder ();
	}
	
	/**
	 * @param string $thumbnailCacheFolder
	 */
	protected function setThumbnailCacheFolder($thumbnailCacheFolder) {
		$this->relativeCacheFolder = $thumbnailCacheFolder;
		$this->getCacheStrategy ()->setCacheFolder ( SCRIPT_ROOT_PATH . '/' . $thumbnailCacheFolder );
	}
	
	/**
	 * @return string
	 */
	protected function getThumbnailQueryUrl() {
		return $this->thumbnailQueryUrl;
	}
	
	/**
	 * @param string $thumbnailQueryUrl
	 */
	protected function setThumbnailQueryUrl($thumbnailQueryUrl) {
		$this->thumbnailQueryUrl = $thumbnailQueryUrl;
	}
	
	/**
	 * Returns the url of the thumbnail of the website at URL $url.
	 * When the thumbnail has been cached, it returns the URL or the cached one.
	 * When the thumbnail is not cached, (due to delay for example), it returns the online URL. 
	 *
	 * @param string $url
	 */
	public function getThumbnailUrl() {
		$thumbUrl = null;
		
		if (class_exists ( 'HttpRequest' )) {
			$request = $this->getHttpRequest ();
			if (is_array ( $this->queryData )) {
				$request->addQueryData ( $this->queryData );
			}
			try {
				$cacheKey = $this->getUrl ();
				$hasBeenCached = false;
				if (! $this->getCacheManager ()->isCached ( $cacheKey )) {
					if ($this->getCacheManager ()->hasExpired ( $cacheKey )) {
						// File is cached but expired : recache it again
						$hasBeenCached = $this->cacheThumbnail ( $cacheKey, $request );
					} else if (! $this->isCachingDelayed ()) {
						// Cache is not expired : no cache file existing, apply delay
						$hasBeenCached = $this->cacheThumbnail ( $cacheKey, $request );
					}
				} else {
					if ($this->getCacheManager ()->hasExpired ( $cacheKey )) {
						// File is cached but expired : recache it again
						$hasBeenCached = $this->cacheThumbnail ( $cacheKey, $request );
					} else {
						$hasBeenCached = true;
					}
				}
				
				if ($hasBeenCached) {
					// Return cached thumbnail URL
					$thumbUrl = $this->getCachedThumbnailUrl ( $cacheKey );
				} else {
					// Return remote thumbnail URL
					$thumbUrl = $this->getFullQueryUrl ();
				}
			} catch ( HttpException $e ) {
				throw new BaseException ( $e );
			}
		} else {
			$thumbUrl = $this->getThumbnailQueryUrl ();
			if (is_array ( $this->queryData )) {
				$thumbUrl .= '?';
				
				$isFirstParam = true;
				foreach ( $this->queryData as $name => $value ) {
					if ($isFirstParam) {
						$thumbUrl .= $name . '=' . urlencode ( $value );
						$isFirstParam = false;
					} else {
						$thumbUrl .= '&' . $name . '=' . urlencode ( $value );
					}
				}
				
				$cacheKey = $this->getUrl ();

				$hasBeenCached = false;
				if (! $this->getCacheManager ()->isCached ( $cacheKey )) {
					if ($this->getCacheManager ()->hasExpired ( $cacheKey )) {
						// File is cached but expired : recache it again
						$hasBeenCached = $this->cacheThumbnailCompat ( $cacheKey, $thumbUrl );
					} else if (! $this->isCachingDelayed ()) {
						// Cache is not expired : no cache file existing, apply delay
						$hasBeenCached = $this->cacheThumbnailCompat ( $cacheKey, $thumbUrl );
					}
				} else {
					if ($this->getCacheManager ()->hasExpired ( $cacheKey )) {
						// File is cached but expired : recache it again
						$hasBeenCached = $this->cacheThumbnailCompat ( $cacheKey, $thumbUrl );
					} else {
						$hasBeenCached = true;
					}
				}
				if ($hasBeenCached) {
					// Return cached thumbnail URL
					$thumbUrl = $this->getCachedThumbnailUrl ( $cacheKey );
				} else {
					// Return remote thumbnail URL
					return $thumbUrl;
				}
			}
		}
		return $thumbUrl;
	}
	
	/**
	 * Cache thumbnail locally.
	 *
	 * @param string $cacheKey
	 * @param HttpRequest $request
	 * @return bool
	 */
	protected function cacheThumbnail($cacheKey, $request) {
		$request->send ();
		if ($request->getResponseCode () == 200) {
			$this->getCacheManager ()->cache ( $cacheKey, $request->getResponseBody () );
			// File has been cached : get it from cache
			return true;
		}
		return false;
	}
	
	/**
	 * Cache thumbnail locally. (compatiblity mode)
	 *
	 * @param string $cacheKey
	 * @param HttpRequest $request
	 * @return bool
	 */
	protected function cacheThumbnailCompat($cacheKey, $thumbUrl) {
		$imgData = $this->file_get_contents ( $thumbUrl );
		if ($imgData) {
			$this->getCacheManager ()->cache ( $cacheKey, $imgData );
			// File has been cached : get it from cache
			return true;
		}
		return false;
	}
	
	/**
	 * Full URL wih request query data.
	 *
	 * @param HttpRequest $request
	 * @return string
	 */
	protected function getFullQueryUrl() {
		return $this->getHttpRequest ()->getUrl () . '?' . $this->getHttpRequest ()->getQueryData ();
	}
	
	/**
	 * Returns the URL of the website to take a snapshot.
	 *
	 */
	abstract protected function getUrl();
	
	/**
	 * Cleans the Url.
	 *
	 * @param string $url
	 */
	abstract protected function cleanUrl($url);
	
	/**
	 * Returns the default thumbnail size.
	 */
	abstract public function getDefaultThumbnailSize();
	
	/**
	 * Really put in cache the response to this request ?
	 * Uses a dummy cache file to ensure cache delaying.
	 *
	 * @param HttpRequest $request
	 */
	protected function isCachingDelayed() {
		$delayed = true;
		$cacheDelay = $this->getCachingDelay ();
		
		$tmp = $this->getCacheStrategy ()->getCachedFileExtension ();
		$this->getCacheStrategy ()->setCachedFileExtension ( '.delay' );
		
		if ($cacheDelay > 0) {
			$cacheKey = 'delay_' . $this->getUrl ();
			if (! $this->getCacheManager ()->isCached ( $cacheKey )) {
				$this->getCacheManager ()->cache ( $cacheKey, date ( 'd-m-Y H:i:s' ) );
			} else {
				
				$cacheAge = time () - $this->getCacheManager ()->getCacheAge ( $cacheKey );
				if ($cacheAge > $cacheDelay) {
					$this->getCacheManager ()->delCache ( $cacheKey );
					$delayed = false;
				}
			}
		} else if ($cacheDelay == 0) {
			// When setting cache delay to 0, 
			// no cache delay file is used.
			$delayed = false;
		}
		$this->getCacheStrategy ()->setCachedFileExtension ( $tmp );
		return $delayed;
	}
	
	/**
	 * URL of the cached thumbnail corresponding to 
	 * the passed $url variable. (this is the cache key)
	 *
	 * @param string $url
	 * @return string
	 */
	protected function getCachedThumbnailUrl($url) {
		return Config::get('site_url') 
				. '/' 
				. $this->relativeCacheFolder 
				. '/' . $this->getCacheStrategy ()->getCachedFileName ($this->getCacheManager()->getInternalKey($url));
	}
	
	/**
	 * Thumbnail cache manager.
	 *
	 * @return AbstractCacheManager
	 */
	protected function getCacheManager() {
		if ($this->cacheManager == null) {
			$this->cacheManager = new Md5CacheManager ( new FileCacheStrategy() );
		}
		return $this->cacheManager;
	}
	
	/**
	 * Sets the query param
	 *
	 * @param string $name
	 * @param string $value
	 */
	protected function setQueryParam($name, $value) {
		$this->queryData [$name] = $value;
	}
	
	/**
	 * Returns the query param value.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function getQueryParam($name) {
		if (isset ( $this->queryData [$name] )) {
			return $this->queryData [$name];
		}
		return null;
	}
	
	/**
	 * Thumbnail cache strategy.
	 *
	 * @return FileCacheStrategy
	 */
	protected function getCacheStrategy() {
		return $this->getCacheManager ()->getCacheStrategy ();
	}
	
	/**
	 * @param string $cachingDelay
	 */
	protected function setCachingDelay($cachingDelay) {
		$this->cachingDelay = $cachingDelay;
	}
	
	/**
	 * Caching delay (in seconds).
	 *
	 * @return int
	 */
	protected function getCachingDelay() {
		return $this->cachingDelay;
	}
	
	/**
	 * Http request method.
	 *
	 * @return int
	 */
	protected function getHttpMethod() {
		return HttpRequest::METH_GET;
	}
	
	/**
	 * Alternative to HTTPRequest. (for backward compatibility)
	 *
	 * @param string $filename
	 * @param bool $incpath
	 * @return string
	 */
	protected function file_get_contents($filename, $incpath = false) {
		if (function_exists ( 'file_get_contents' )) {
			return file_get_contents ( $filename );
		} else {
			if (false === $fh = fopen ( $filename, 'rb', $incpath )) {
				trigger_error ( 'file_get_contents() failed to open stream: No such file or directory', E_USER_WARNING );
				return false;
			}
			
			clearstatcache ();
			if ($fsize = @filesize ( $filename )) {
				$data = fread ( $fh, $fsize );
			} else {
				$data = '';
				while ( ! feof ( $fh ) ) {
					$data .= fread ( $fh, 8192 );
				}
			}
			
			fclose ( $fh );
			return $data;
		}
	}
	
	/**
	 * Http request.
	 *
	 * @return HttpRequest
	 */
	protected function getHttpRequest() {
		if ($this->httpRequest == null) {
			$this->httpRequest = new HttpRequest ( $this->getThumbnailQueryUrl (), $this->getHttpMethod () );
			$this->httpRequest->setOptions ( array ('User-agent' => isset ( $_SERVER ['HTTP_USER_AGENT'] ) ? $_SERVER ['HTTP_USER_AGENT'] : 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)' ) );
		}
		return $this->httpRequest;
	}
	
	/**
	 * Returns the default caching delay. (in seconds)
	 *
	 * @return integer
	 */
	public function getDefaultCachingDelay() {
		return 10*60;
	}
	
	/**
	 * Returns the default renewal Delay. (in seconds)
	 *
	 * @return integer
	 */
	public function getDefaultRenewalDelay() {
		return 7*86400;
	}
}

?>