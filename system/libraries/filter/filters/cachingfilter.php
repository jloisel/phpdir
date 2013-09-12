<?php

/**
 * This filter checks if caching is enabled for a specific
 * controller and action, and then applies the caching policy.
 *
 * @author Jerome Loisel
 */
class CachingFilter extends AbstractFilter {
	
	const DEFAULT_CACHE_KEY = 'default';
	
	/**
	 * Indicates that content will be cached.
	 *
	 */
	const CACHE_CONTENT_STATUS = 0;
	
	/**
	 * Indicates that cached content is rendered.
	 *
	 */
	const RENDER_CACHED_CONTENT = 1;
	
	/**
	 * Cache manager.
	 *
	 * @var ContextCacheManager
	 */
	protected $cacheManager = null;
	
	/**
	 * Cache strategy applied.
	 *
	 * @var ICacheStrategy
	 */
	protected $cacheStrategy = null;
	
	/**
	 * Should the content be cached.
	 *
	 * @var boolean
	 */
	protected $cacheContent = false;
	
	/**
	 * Unique ID of the page currently viewed. 
	 * Used to cache it.
	 * 
	 * @var string
	 */
	protected $cacheKey = null;
	
	/**
	 * Contains the current filter status.
	 *
	 * @var integer
	 */
	protected $status = -1;
	
	/**
	 * Default constructor.
	 *
	 */
	public function __construct() {
		parent::__construct();
	}
	
	
	public function preExecute() {
		if($this->isFirstCall()) {
			if($this->isCachingEnabled()) {
				$cacheKey = $this->getCacheKey();
				if(!$this->getCacheManager()->isCached($cacheKey)) {
					$this->status = self::CACHE_CONTENT_STATUS;
					ob_start();
				} else {
					$this->status = self::RENDER_CACHED_CONTENT;
					echo $this->getCacheManager()->getCache($cacheKey);
				}
			}
		}
	}
	
	/**
	 * Executes the filter.
	 *
	 * @param FilterChain $filterChain
	 */
	public function execute(FilterChain $filterChain) {
		if($this->status == self::RENDER_CACHED_CONTENT) {
			// Short circuit execution stack : cached content is send to client
			$filterChain->clean();
		} else {
			$filterChain->execute();
		}
	}
	
	public function postExecute() {
		if($this->isCachingEnabled()) {
			$this->getCacheManager()->cache($this->getCacheKey(), ob_get_contents());
			ob_end_flush();
		}
	}
	
	/**
	 * Cache manager instance.
	 *
	 * @return Md5CacheManager
	 */
	protected function getCacheManager() {
		if($this->cacheManager == null) {
			$this->cacheManager = new Md5CacheManager($this->getCacheStrategy());
		}
		return $this->cacheManager;
	}

	/**
	 * Cache store strategy.
	 *
	 * @return ICacheStrategy
	 */
	protected function getCacheStrategy() {
		if($this->cacheStrategy == null) {
			$this->cacheStrategy = CacheStrategyFactory::getNew($this->getConfig('strategy'));
			$this->cacheStrategy->setLifetime($this->getConfig('lifetime'));
			
			$options = $this->getConfig('strategy_options');
			if(is_array($options)) {
				foreach($options as $key => $value) {
					$this->cacheStrategy->{'set'.ucfirst($key)}($value);
				}
			}
		}
		return $this->cacheStrategy;
	}
	
	/**
	 * Returns the unique key which identifies the cached page.
	 * 
	 * @return string
	 */
	protected function getCacheKey() {
		if($this->cacheKey == null) {
			$cacheKey = $this->getConfig('cache_key');
			if($cacheKey == null) {
				$cacheKey = self::DEFAULT_CACHE_KEY;
			}
			$this->cacheKey = $cacheKey;
		}
		return $this->cacheKey;
	}
	
	/**
	 * Is caching enabled ?
	 *
	 * @return boolean
	 */
	protected function isCachingEnabled() {
		if($this->cacheContent == null) {
			$this->cacheContent = $this->getConfig('is_enabled') == true;
		}
		return $this->cacheContent;
	}
	
	/**
	 * Returns the configuration item value.
	 *
	 * @param string $name
	 * @return mixed
	 */
	protected function getConfig($name,$isMandatory=true) {
		$item = $this->getModuleConfigLoader()->getItem(ConfigLoader::CACHING,$name,false);
		if($item == null) {
			$item = $this->getAppConfigLoader()->getItem(ConfigLoader::CACHING,$name,$isMandatory);
		}
		return $item;
	}
	
	/**
	 * Application module loader.
	 *
	 * @return AppConfigLoader
	 */
	protected function getAppConfigLoader() {
		return AppConfigLoader::getInstance();
	}
	
	/**
	 * module configuration loader.
	 *
	 * @return ModuleConfigLoader
	 */
	protected function getModuleConfigLoader() {
		return Context::getModuleConfigLoader();
	}
}

?>