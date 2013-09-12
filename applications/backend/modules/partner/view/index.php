<?php echo ModuleHelper::subTitle('Manage partners')?>
<br />
<div>
	<?php if(is_array($this->partners) && count($this->partners) > 0): ?>
		<?php $colors = array('#FFF','#EEE')?>
		<form method="post" id="partners_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getController(), 'action' => Context::getAction())) ?>">
		<table cellpadding="3" width="100%" style="text-align: center; border: 1px solid #CCC;">
			<?php echo TableHelper::headers(array('#','Name','Link')) ?>
			<?php $i = 0; ?>
			<?php foreach($this->partners as $index => $partner): ?>
				<tr style="background-color: <?php echo $colors[$i%count($colors)] ?>">
					<td><input type="checkbox" name="partner[<?php echo $index ?>]" id="partner_<?php echo $index ?>" class="partner" /></td>
					<td><?php echo $partner->getName() ?></td>
					<td><?php echo $partner->render() ?></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</table>
		<div style="float: right;">
			<input type="button" id="toggle_button" value="<?php echo __('Check all') ?>" />
			<input type="submit" name="<?php echo PartnerActions::REMOVE_PARTNER_ACTION ?>" value="<?php echo __('Remove selected') ?>"/>
		</div>
		<div style="clear: both;"></div>
		</form>
	<?php else: ?>
		<p><?php echo __('You don\'t have any partner now.')?></p>
	<?php endif; ?>
</div>
<script type="text/javascript">
<!--
setSelectUnselectAllButton('toggle_button','partner',"<?php echo __('Check all')?>","<?php echo __('UnCheck all')?>");
-->
</script>
<?php echo ModuleHelper::subTitle('Add a new partner') ?>
<div id="partner_form">
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<table>
				<?php echo $this->form ?>
				<tr>
					<td><input type="submit" name="<?php echo PartnerActions::ADD_PARTNER_ACTION ?>"/></td>
				</tr>
		</table>
	</form>
</div>