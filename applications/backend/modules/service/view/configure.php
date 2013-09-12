<?php include $this->partial('menu') ?>
<?php if(is_object($this->form)): ?>
	<?php echo ModuleHelper::subTitle('Configure %s',$this->service->getName()) ?>
	<?php if($this->success): ?>
		<br />
		<div class="formSuccessMessage"><?php echo __('%s configuration updated successfully!',$this->service->getName())?></div>
	<?php endif; ?>
	<div>
		<form id="service_form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
			<table>
				<?php echo $this->form ?>
			</table>
			<p>
				<input type="submit" />
			</p>
		</form>
	</div>
<?php else: ?>
	<p><?php echo __('The service doesn\'t need to be configured.')?></p>
<?php endif; ?>