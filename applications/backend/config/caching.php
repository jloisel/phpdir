<?php


$__CACHING = array(
	'is_enabled' => false, 
	'lifetime' => 3600, 
	'strategy' => CacheStrategyFactory::FILE_STRATEGY, 
	'strategy_options' => array(
		'cacheFolder' => SCRIPT_ROOT_PATH
							.'/public/cache/'.Context::getParameterHolder()->getApplicationName().'/'
							.'/'.Context::getHttpRequest()->getController()
							.'/'.Context::getHttpRequest()->getAction()
	)
);

?>