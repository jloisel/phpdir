<div>
	<form method="post" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION, array('controller' => Context::getController(), 'action' => Context::getAction()))?>">
		<table>
			<?php echo $this->form ?>
			<tr><td colspan="2"><input type="submit" name="add_ban" /></td></tr>
		</table>
	</form>
</div>