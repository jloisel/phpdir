<div id="module_menu">
	<?php echo __('Banned')?>:&nbsp;
	<?php Context::getAction() != 'ip' and print UrlHelper::routed_link_to(
		'IPs',
		Route::CONTROLLER_ACTION,
		array('controller' => Context::getController(), 'action' => 'ip')
	) or print __('IPs')?>&nbsp;
	<?php Context::getAction() != 'email' and print UrlHelper::routed_link_to(
		'Emails',
		Route::CONTROLLER_ACTION,
		array('controller' => Context::getController(), 'action' => 'email')
	) or print __('Emails')?>&nbsp;
	<?php Context::getAction() != 'host' and print UrlHelper::routed_link_to(
		'Hosts',
		Route::CONTROLLER_ACTION,
		array('controller' => Context::getController(), 'action' => 'host')
	) or print __('Hosts')?>
</div>
<div style="clear: both"></div>