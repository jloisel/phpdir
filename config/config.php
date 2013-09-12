<?php
Config::addItems(
	array(
	'mode' => 'production',
	'db_driver' => 'mysql',
	'db_host' => 'localhost',
	'db_login' => 'root',
	'db_pass' => '',
	'db_name' => 'phpdir',
	'db_prefix' => '',
	'site_url' => 'http://127.0.0.1/phpdir',
	'encoding' => 'UTF-8',
	'language' => 'fr_FR',
	'theme' => 'default',
	'date_format' => 'd-m-Y',
	'use_captcha' => '0',
	'akismet_api_key' => 'c4a571716c83',
	'thumbnail_service' => 'websnapr',
	'captcha_service' => 'recaptcha',
	'antispam_service' => 'akismet',
	'repository' => 'http://www.phpdir.org',
	)
);
?>