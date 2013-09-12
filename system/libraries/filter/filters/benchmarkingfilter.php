<?php

/**
 * Benchmarking filter.
 * Provides access to several bench information about the script : 
 * the benchmark filter starts the benchmark.
 *
 * @author Jerome Loisel
 */
class BenchmarkingFilter extends AbstractFilter {

	public function __construct() {
		parent::__construct();
	}
	
	public function preExecute() {
		if($this->isFirstCall()) {
			Benchmark::getInstance()->init();
		}
	}
	
	public function postExecute() {
		// Benchmark is stopped only if 
		// someone retrieves a bench value from 
		// Benchmark class.
	}
}

?>
