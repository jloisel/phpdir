<?php

AutoLoad::addFoldersToScan(
	array(
		PUBLIC_FOLDER.'/'.'services', 
		SYSTEM_FOLDER.'/'.'helpers', 
		SYSTEM_FOLDER.'/'.'libraries',
		SYSTEM_FOLDER.'/'.'vendor/phpsavant',
		SYSTEM_FOLDER.'/'.'vendor/symfony',
		APPS_FOLDER.'/'.APP_NAME.'/libraries',
		APPS_FOLDER.'/'.APP_NAME.'/helpers',
		APPS_FOLDER.'/'.APP_NAME.'/modules/**/forms',
		APPS_FOLDER.'/'.APP_NAME.'/modules/**/validators',
		APPS_FOLDER.'/'.APP_NAME.'/modules/**/models',
		APPS_FOLDER.'/'.APP_NAME.'/modules/**/libraries'
	)
);

?>