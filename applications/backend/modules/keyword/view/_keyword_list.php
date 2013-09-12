<br />
<div>
	<?php include $this->partial('filter_form') ?>
	<?php if(is_array($this->keywords) && count($this->keywords) > 0): ?>
		<div style="clear: both;"></div>
		<?php $colors = array('#FFF','#EEE')?>
		<form method="post" id="keywords_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getController(), 'action' => 'update')) ?>">
			<table cellpadding="3" width="100%" style="text-align: center; border: 1px solid #CCC;">
				<?php echo TableHelper::headers(array('#','keyword','searches','Created on','Banned')) ?>
				<?php $i = 0; ?>
				<?php foreach($this->keywords as $keyword): ?>
					<tr style="background-color: <?php echo $colors[$i%count($colors)] ?>">
						<td><input type="checkbox" name="keyword[<?php echo $keyword['id'] ?>]" id="item_<?php echo $keyword['id'] ?>" class="keyword" /></td>
						<td style="text-align: left; width: 50%"><?php echo $keyword['text'] ?></td>
						<td><?php echo $keyword['count'] ?></td>
						<td><?php echo DateHelper::convert($keyword['created_on'],Config::get('date_format')) ?></td>
						<td>
							<span style="font-weight: bold; color: <?php echo $keyword['is_banned'] == '1' ? 'red':'green'; ?>">
								<?php echo $keyword['is_banned'] == '1' ? __('Yes') : __('No'); ?>
							</span>
						</td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</table>
			<div style="float: right;">
				<input type="button" id="toggle_button" value="<?php echo __('Check all') ?>" />
				<input	type="submit" name="<?php echo KeywordActions::REMOVE_ACTION ?>" value="<?php echo __('Remove') ?>" 
						onclick="return confirm('<?php echo __('Really delete ?') ?>') ? true: false;"/>
				<input type="submit" name="<?php echo KeywordActions::BAN_ACTION ?>" value="<?php echo __('Ban') ?>"/>
				<input type="submit" name="<?php echo KeywordActions::UNBAN_ACTION ?>" value="<?php echo __('Unban') ?>"/>
			</div>
			<div style="clear: both;"></div>
		</form>
		<p style="text-align: right;"><?echo __('Pages') ?>: <?php echo $this->pager ?></p>
	<?php else: ?>
		<p><?php echo __('No keywords found.') ?></p>
	<?php endif; ?>
</div>
<script type="text/javascript">
<!--
setSelectUnselectAllButton('toggle_button','keyword',"<?php echo __('Check all')?>","<?php echo __('UnCheck all')?>");
-->
</script>