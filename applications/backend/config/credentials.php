<?php

$__CREDENTIALS = array(
    'is_secure' => true,
    'session_timeout' => 3600, 
	
	'login_controller' => 'login',
	'login_action' => 'index', 
	'secure_controller' => 'redirect', 
	'secure_action' => 'credentials',

	'credentials' => Customer::$ADMINISTRATOR_CREDENTIALS
);

?>