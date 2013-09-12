<?php

// Default route loaded everytime
$this->addRoutes(
	array(
		new PatternRoute(Route::VOID,'/'),
		
		new PatternRoute(
			Route::CONTROLLER,
			'/:controller',
			null, 
			array('controller' => '[[:alpha:]]{2,}')
		),
		
		new PatternRoute(
			Route::CONTROLLER_ACTION,
			'/:controller/:action', 
			null,
			array('controller' => '[[:alpha:]]{2,}', 'action' => '[[:alpha:]]+')
		),
		
		new PatternRoute(
			Route::CONTROLLER_ACTION_ID,
			'/:controller/:action/:id', 
			null,
			array('controller' => '[[:alpha:]]{2,}', 'action' => '[[:alpha:]]+', 'id' => '.+')
		)
	)
);

?>