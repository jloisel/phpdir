<h2><?php echo __('Password lost')?></h2>
<div style="width: 300px; margin: auto;">
	<form id="login_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getHttpRequest()->getController(),'action' => 'lost')) ?>" method="post">
		<p>
			<?php echo __('Please enter your email. A new password will be sent.')?>
		</p>
		<table>
			<?php echo $this->form ?>
		</table>
		<p>
			<input type="submit" />
		</p>
	</form>
</div>
<div style="float: left">
	<p>
		&laquo; <?php echo UrlHelper::routed_link_to(
				'Back to login',
				Route::CONTROLLER_ACTION, 
				array(
					'controller' => Context::getHttpRequest()->getController(),
					'action' => 'index'
				)
			)?>
	</p>
</div>
<div style="clear: both"></div>