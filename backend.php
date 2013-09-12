<?php
/**
 * Defines the server script absolut path.
 */
define('SCRIPT_ROOT_PATH',dirname(__FILE__));
/**
 * Application name.
 */
define('APP_NAME','backend');

/**
 * Includes the bootstrap script which will initialize
 * the framework.
 */
include (SCRIPT_ROOT_PATH.'/system/boot.php');

/**
 * Launches the script which will generate the output.
 */
Context::getFrontController()->dispatch();
?>