<div style="float: left">
	<?php echo ImageHelper::app_image('dashboard.png','',array('style' => 'vertical-align: bottom'))?>
	<?php echo UrlHelper::routed_link_to('Dashboard',Route::CONTROLLER,array('controller' => 'dashboard'))?>
</div>
<div style="float: right">
	<?php echo __(
		'Welcome <b>%s</b>, logged in as <b>%s</b>',
		array(Customer::getLogged()->email,Customer::getLogged()->getRole())
	)?>
	&nbsp;-&nbsp;<?php echo UrlHelper::routed_link_to('Profile',Route::CONTROLLER,array('controller' => 'profile'))?>
	<?php echo ImageHelper::app_image('profile.png','',array('style' => 'vertical-align: bottom'))?>
	&nbsp;-&nbsp;<?php echo UrlHelper::routed_link_to('Logout',Route::CONTROLLER_ACTION,array('controller' => 'login', 'action' => 'logout'))?>
	<?php echo ImageHelper::app_image('logout.png','',array('style' => 'vertical-align: bottom'))?>
</div>
<div style="clear: both"></div>
<hr />
<?php $module = Context::getModuleLocator()->getLocalObject(Context::getController()); ?>
<?php if(is_object($module)): ?>
	<div style="float: right;">
		<?php echo ImageHelper::app_image('help.png') ?>
		&nbsp;<?php echo $module->getDescription() ?></div>
	<h3 style="display: inline;">
		<?php echo $module->getIcon() ?>
		<?php echo $module->getName() ?><br />
	</h3>
<?php endif; ?>
<div>&nbsp;</div>