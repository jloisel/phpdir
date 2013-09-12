<?php

/**
 * Creates a new cache strategy.
 *
 * @author Jerome Loisel
 */
class CacheStrategyFactory {

	/**
	 * File cache strategy.
	 *
	 */
	const FILE_STRATEGY = 0;
	
	/**
	 * Returns a new instance of a cache strategy.
	 *
	 * @param int $strategy
	 * @return ICacheStrategy
	 */
	public static function getNew($strategy) {
		switch($strategy) {
			case self::FILE_STRATEGY:
				return new FileCacheStrategy();

			default:
				return null;
		}
	}
	
}

?>
