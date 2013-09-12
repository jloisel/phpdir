<?php if(is_array($this->types) && count($this->types) > 0): ?>
	<?php include $this->partial('menu') ?>
	<?php echo ModuleHelper::subTitle('Manage services') ?>
	<?php echo ModuleHelper::info('You can install/update/delete services. You cannot delete services currently in use.') ?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<div id="service_types">
		<table style="width: 100%; border: 1px solid #CCC;">
			<tr style="background-color: #F0F0F0;">
				<th>&nbsp;</th>
				<th style="padding: 5px;"><?php echo __('Name')?></th>
				<th style="padding: 5px;"><?php echo __('Description')?></th>
				<th style="padding: 5px; width: 40px; text-align: center;"><?php echo __('Version')?></th>
				<th >#</th>
			</tr>
			<?php foreach($this->types as $type): ?>
				<tr>
					<td colspan="5" style="font-weight: bold; background-color: #DDD; padding: 5px;">
						<?php echo ucfirst($type) ?>
					</td>
				</tr>
				<?php $seen = array(); ?>
				<?php $services = Context::getServiceLocator()->getServices($type) ?>
				<?php include $this->partial('services')?>
				<?php $services = Context::getServiceLocator()->getRemoteServices($type) ?>
				<?php include $this->partial('services')?>
			<?php endforeach; ?>
		</table>
	</div>
	<div style="text-align: right;">
		<input type="submit" value="<?php echo __('Update') ?>" />
	</div>
	</form>
<?php endif; ?>