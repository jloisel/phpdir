<?php

/**
 * Several infos about the script execution 
 * memory usage, execution time...
 * 
 * @author Jerome Loisel
 *
 */
final class Benchmark extends Singleton {
	
	/**
	 * Available bench infos.
	 */
	const MEMORY_USAGE = 0;
	const EXECUTION_TIME = 1;
	
	/**
	 * Has the bench been stopped ?
	 *
	 * @var boolean
	 */
	protected $stopped = false;
	
	/**
	 * Benchmark informations are stored here.
	 *
	 * @var array
	 */
	protected $benchInfos = array();
	
	protected function __construct() {
		parent::__construct();
	}
	
	public function init() {
		$this->startBench(self::MEMORY_USAGE,memory_get_usage());
		$this->startBench(self::EXECUTION_TIME, microtime());
	}
	
	public function stop() {
		$this->stopBench(self::MEMORY_USAGE,memory_get_usage());
		$this->stopBench(self::EXECUTION_TIME, microtime());
	}
	
	/**
	 * Sets the bench info start value;
	 *
	 * @param integer $bench
	 * @param mixed $currentValue
	 */
	protected function startBench($bench, $currentValue) {
		$this->{$bench} = $currentValue;
	}
	
	protected function stopBench($bench, $currentValue) {
		$this->{$bench} = abs($currentValue - $this->{$bench});
	}
	
	/**
	 * Returns the current value of the bench.
	 * the passed argument must be one of the following constants :
	 * - MEMORY_USAGE
	 * - EXECUTION_TIME
	 *
	 * @param integer $bench
	 * @return mixed
	 */
	public function getBench($bench) {
		if(!$this->stopped) {
			$this->stop();
			$this->stopped = true;
		}
		return $this->{$bench};
	}
	
	public function __set($name, $value) {
		$this->benchInfos[$name] = $value;
	}
	
	public function __get($name) {
		return isset($this->benchInfos[$name]) ? $this->benchInfos[$name] : '';
	}
	
	/**
	 * Unique instance of the benchmark.
	 *
	 * @return Benchmark
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>