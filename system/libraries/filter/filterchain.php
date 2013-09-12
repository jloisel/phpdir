<?php

/**
 * The filter chain is responsible for 
 * invoking filters.
 * 
 * @author Jerome Loisel 
 *
 */
final class FilterChain {
	
	/**
	 * The filters loaded from configuration file.
	 * 
	 * 
	 * @var array
	 */
	protected $pendingFiltersStack = array();
	
	/**
	 * Currently executed filters stack.
	 *
	 * @var string
	 */
	protected $executedFiltersStack = array();
	
	/**
	 * Default constructor.
	 */
	public function __construct() {
	}

	/**
	 * Pops a filter from filter chain.
	 *
	 * @return array
	 */
	protected function popFilter() {
		if (is_array ( $this->pendingFiltersStack ) && count ( $this->pendingFiltersStack ) > 0) {
			$filter = array_pop($this->pendingFiltersStack);
			array_push($this->executedFiltersStack,$filter);
			return $filter;
		}
		return null;
	}
	
	/**
	 * Executes the filter chain.
	 *
	 */
	public function execute() {
		$filterClass = $this->popFilter ();
		if ($filterClass != null) {
			$filter = FilterFactory::getFilter($filterClass);
			if (is_object ( $filter )) {
				$filter->preExecute ();
				$filter->execute ( $this );
				$filter->postExecute ();
			}
		}
	}
	
	/**
	 * @param array $filters
	 */
	public function setFilters(array $filters) {
		$this->pendingFiltersStack = $filters;
	}
	
	public function __toString() {
		return '{'.__CLASS__.'} : <b>'.array_pop($this->executedFiltersStack).'</b>';
	}
	
	/**
	 * Cleans the filter chain.
	 *
	 */
	public function clean() {
		$this->pendingFiltersStack = array();
		$this->executedFiltersStack = array();
	}
}

?>