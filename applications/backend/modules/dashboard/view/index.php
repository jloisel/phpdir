<?php 
	$user = Context::getUser();
?>
<div id="modules">
	<?php $i = 0; ?>
	<?php foreach($this->modules as $module): ?>
		<?php if($module->hasBackend() && $user->hasCredential($module->getCredentials())): ?>
			<?php $i++;?>
			<div class="module_big">
				<?php echo UrlHelper::routed_link_to(
					$module->getIcon(), 
					Route::CONTROLLER_ACTION,
					array('controller' => $module->getLocalFolder(), 'action' => $module->getDefaultAction())
				)?>
				<p><?php echo ucfirst($module->getName()) ?></p>
			</div>
			<?php if($i==4): ?><div style="clear: both;"></div><?php $i=0;endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<div style="clear: both;">&nbsp;</div>