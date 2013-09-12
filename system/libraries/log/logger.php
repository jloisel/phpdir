<?php

/**
 * Minimal methods that must be implemented in a logger class.
 * @author Jerome Loisel
 */
interface Logger {
	public function isLogged($ip);
	public function log($ip);
	public function gc();
}

?>