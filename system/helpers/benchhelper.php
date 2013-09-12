<?php

/**
 * Benchmark helper. Provides variou statistics on 
 * script execution : memory usage, execution time... 
 * 
 * @author Jerome Loisel
 */
class BenchHelper extends AbstractHelper {

	/**
	 * A KiloByte : 1024 Bytes.
	 *
	 */
	const KB = 1024;
	
	/**
	 * A Megabyte : 1024 KBytes.
	 */
	const MB = 1048576;
	
	/**
	 * Returns the memory usage computed at the 
	 * time this method is called.
	 *
	 * @param integer $unit : BenchHelper::KB or MB
	 * @return string
	 */
	public static function memoryUsage($pattern='%.2f',$unit=1048576) {
		return sprintf($pattern,Benchmark::getInstance()->getBench(Benchmark::MEMORY_USAGE)/($unit));
	}
	
	/**
	 * Script execution time computed at the time this method is called.
	 *
	 * @param string $pattern
	 * @return string
	 */
	public static function executionTime($pattern='%.3f') {
		return sprintf($pattern,Benchmark::getInstance()->getBench(Benchmark::EXECUTION_TIME));
	}
}

?>
