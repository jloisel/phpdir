<?php
/**
 * Base implementation for antispam service.
 * 
 * @author Jerome
 *
 */
abstract class AbstractAntispamService extends ServiceAdapter implements AntispamService {

	protected function __construct() {
		parent::__construct();
	}
}

?>