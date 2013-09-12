<br />
<div>
	<?php if(is_array($this->items) && count($this->items) > 0): ?>
		<?php $colors = array('#FFF','#EEE')?>
		<form method="post" id="items_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getController(), 'action' => Context::getAction())) ?>">
		<table cellpadding="3" width="100%" style="text-align: center; border: 1px solid #CCC;">
			<?php echo TableHelper::headers(array('#',strtoupper($this->fieldName),'Date')) ?>
			<?php $i = 0; ?>
			<?php foreach($this->items as $item): ?>
				<tr style="background-color: <?php echo $colors[$i%count($colors)] ?>">
					<td><input type="checkbox" name="item[<?php echo $item['id'] ?>]" id="item_<?php echo $item['id'] ?>" class="item" /></td>
					<td><?php echo $item['value'] ?></td>
					<td><?php echo DateHelper::convert($item['created_on'],Config::get('date_format')) ?></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</table>
		<div style="float: right;">
			<input type="button" id="toggle_button" value="<?php echo __('Check all') ?>" />
			<input type="submit" name="remove_ban" value="<?php echo __('Remove selected') ?>"/>
		</div>
		<div style="clear: both;"></div>
		</form>
		<p style="text-align: right;"><?echo __('Pages') ?>: <?php echo $this->pager ?></p>
	<?php else: ?>
		<p><?php echo __('No banned %s found.',array($this->fieldName))?></p>
	<?php endif; ?>
</div>
<script type="text/javascript">
<!--
setSelectUnselectAllButton('toggle_button','item',"<?php echo __('Check all')?>","<?php echo __('UnCheck all')?>");
-->
</script>