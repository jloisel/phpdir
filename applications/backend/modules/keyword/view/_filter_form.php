<div id="module_menu">
	<form method="post" id="filter_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getController(), 'action' => 'filter')) ?>">
	<p style="display: inline"><?php echo __('Filters')?>:&nbsp;</p><?php echo $this->filterForm ?>
		<p style="display: inline">
			<input type="submit" name="<?php echo KeywordActions::FILTER_ACTION ?>" value="<?php echo __('Filter')?>" />
			<input type="submit" name="<?php echo KeywordActions::RESET_FILTER_ACTION ?>" value="<?php echo __('Reset')?>" />
		</p>
	</form>
</div>
<div style="clear: both;">&nbsp;</div>