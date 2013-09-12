<div style="width: 300px; margin: auto;">
	<form action="<?php echo UrlHelper::routed_url(Route::CONTROLLER,array('controller' => Context::getHttpRequest()->getController())) ?>" method="post">
		<table>
			<?php echo $this->form ?>
		</table>
		<p>
			<input type="submit" />
		</p>
	</form>
</div>