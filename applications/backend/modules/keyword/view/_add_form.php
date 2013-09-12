<form method="post" id="add_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getController(), 'action' => 'add')) ?>">
	<table>
		<?php echo $this->addForm ?>
		<tr>
			<td><input type="submit" name="<?php echo KeywordActions::ADD_ACTION ?>" value="<?php echo __('Add')?>" /></td>
		</tr>
	</table>
</form>