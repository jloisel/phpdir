<?php

interface LoggerAlgorithm {
	public function isLogged($objectId,$objectType,$ip);
	public function log($objectId,$objectType,$ip);
	public function gc();
}

?>