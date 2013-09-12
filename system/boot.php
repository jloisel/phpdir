<?php
/**
 * Defines the name of the folder containing the script
 * applications.
 * This can be changed for security purpose.
 */
define('APPS_FOLDER',	'applications');
define('PUBLIC_FOLDER',	'public');
define('CACHE_FOLDER',	PUBLIC_FOLDER.'/cache');

/**
 * Include autoload class manually.
 */
include (dirname(__FILE__).'/libraries/kernel/autoload.php');
AutoLoad::initialize(SCRIPT_ROOT_PATH.'/'.CACHE_FOLDER);

/**
 * Initialize global system configuration.
 */
Config::setConfigFilePath(SCRIPT_ROOT_PATH.'/config/config.php');

/**
 * Registering application super globals.
 */
$ph = Context::getParameterHolder();
$ph->register(Parameter::SCRIPT_ROOT_PATH,	SCRIPT_ROOT_PATH	);

// System folder and path
$ph->register(Parameter::SYSTEM_FOLDER, 	'system'			);
$ph->register(Parameter::SYSTEM_PATH,		dirname(__FILE__)	);

// Application name, folder and server path
$ph->register(Parameter::APP_NAME,			APP_NAME			);
$ph->register(Parameter::APPS_FOLDER,		'applications'		);
$ph->register(Parameter::APP_PATH,			SCRIPT_ROOT_PATH.'/'.APPS_FOLDER.'/'.APP_NAME	);

// Public folder and path
$ph->register(Parameter::PUBLIC_FOLDER, 	PUBLIC_FOLDER			);
$ph->register(Parameter::PUBLIC_PATH,		SCRIPT_ROOT_PATH.'/'.PUBLIC_FOLDER	);

// Cache folder and path
$ph->register(Parameter::CACHE_FOLDER, 		CACHE_FOLDER			);
$ph->register(Parameter::CACHE_PATH,		SCRIPT_ROOT_PATH.'/'.CACHE_FOLDER	);

// Handles Virtual path
if(isset($_REQUEST['v'])) {
	$ph->register(Parameter::VIRTUAL_URL,	'/'.$_REQUEST['v']			);
	unset($_REQUEST['v']);
}
unset($ph);
?>