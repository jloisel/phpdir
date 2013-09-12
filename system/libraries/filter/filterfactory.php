<?php

/**
 * The filter factory is in charge to create a unique instance 
 * of each demanded filter. A filter is instanciated by filter type.
 *
 * @author Jerome Loisel
 */
class FilterFactory {
	/**
	 * All the filter instances are kept in factory.
	 *
	 * @var array
	 */
	protected static $filterInstances = array();
	
	/**
	 * Creates and returns a unique instance of each filter.
	 *
	 * @param string $filterClass
	 * @return Filter
	 */
	public static function getFilter($filterClass) {
		if (is_string ( $filterClass ) && !empty($filterClass)) {
			if(!isset(self::$filterInstances [$filterClass])) {
				self::$filterInstances [$filterClass] = new $filterClass ( );
			}
			return self::$filterInstances [$filterClass];
		}
		return null;
	}
	
	/**
	 * Returns the execution filter.
	 *
	 * @return ExecutionFilter
	 */
	public static function getExecutionFilter() {
		return self::getFilter(FrameworkFilter::EXECUTION_FILTER);
	}
}

?>
