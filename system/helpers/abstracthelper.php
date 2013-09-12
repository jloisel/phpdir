<?php

/**
 * Helpers common implementation.
 *
 * @author Jerome Loisel
 */
abstract class AbstractHelper {

	/**
	 * Returns the applications folder.
	 *
	 * @return string
	 */
	protected static function getApplicationsFolder() {
		return Context::getParameterHolder()->getApplicationsFolder();
	}
	
	/**
	 * Returns the application name.
	 *
	 * @return string
	 */
	protected static function getApplicationName() {
		return Context::getParameterHolder()->getApplicationName();
	}
	
	/**
	 * Returns the full server application path.
	 *
	 * @return string
	 */
	protected static function getApplicationPath() {
		return Context::getParameterHolder()->getApplicationPath();
	}
	
	/**
	 * Returns the full server module path.
	 *
	 * @return string
	 */
	protected static function getModulePath() {
		return Context::getParameterHolder()->getModulePath();
	}
}

?>
