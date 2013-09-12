<div>
	<?php if(is_array($this->websites) && count($this->websites) > 0):?>
		<div style="float: left;">
			<input type="button" name="toggle_button" id="website_toggle_button" value="<?php echo __('Select All') ?>"/>
			<script type="text/javascript">
				setSelectUnselectAllButton('website_toggle_button','website_checkbox',"<?php echo __('Select all')?>","<?php echo __('Unselect all')?>");
			</script>
		</div>
	<?php endif; ?>
	<div style="float: right">
		<?php if(is_array($this->websites) && count($this->websites) > 0):?>
			<input type="submit" name="<?php echo ContentActions::CUT_WEBSITE_ACTION ?>" value="<?php echo __('Cut') ?>" />
			<input type="submit" name="<?php echo ContentActions::COPY_WEBSITE_ACTION?>" value="<?php echo __('Copy') ?>" />
			<input type="submit" name="<?php echo ContentActions::DELETE_WEBSITE_ACTION ?>" value="<?php echo __('Delete') ?>" onclick="return confirm('<?php echo __('Really Delete selected websites ?') ?>')" />
		<?php endif; ?>
		<?php if(is_array(Context::getUser()->getAttribute(ContentActions::WEBSITES_WORK))): ?>
			<input type="submit" name="<?php echo ContentActions::PASTE_WEBSITE_ACTION?>" value="<?php echo __('Paste') ?>" />
		<?php endif; ?>
	</div>
	<div style="clear: both;">&nbsp;</div>
</div>