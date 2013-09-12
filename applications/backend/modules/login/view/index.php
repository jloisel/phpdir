<h2><?php echo __('Login')?></h2>
<div style="width: 300px; margin: auto;">
	<form id="login_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER,array('controller' => Context::getHttpRequest()->getController())) ?>" method="post">
		<table>
			<?php echo $this->form ?>
		</table>
		<p>
			<input type="submit" />
		</p>
	</form>
</div>
<div style="float: right;">
	<?php echo UrlHelper::routed_link_to(
			'Lost your password ?',
			Route::CONTROLLER_ACTION, 
			array(
				'controller' => Context::getHttpRequest()->getController(),
				'action' => 'lost'
			)
		)?> &raquo;
</div>
<div style="clear: both"></div>