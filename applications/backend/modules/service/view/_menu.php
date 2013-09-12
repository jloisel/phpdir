<div class="module_menu">
	<?php $defaultAction = Context::getModuleLocator()->getLocalObject(Context::getController())->getDefaultAction() ?>
	<?php if(Context::getAction() == $defaultAction): ?>
		<?php echo __('Manage services')?>
	<?php else: ?>
		<?php echo UrlHelper::routed_link_to(
			'Manage services', 
			Route::CONTROLLER_ACTION,
			array(
				'controller' => Context::getController(), 
				'action' => $defaultAction
			)
		)?>
	<?php endif; ?>
	<?php foreach($this->types as $type): ?>
		<?php $service = Context::getServiceLocator()->getService(
			$type, 
			Context::getServiceManager()->getSelectedService($type)
		) ?>

		<?php if($service->getConfigurationForm() != null): ?>
			&nbsp;|&nbsp;<?php if($this->service == null || ($this->service->getName() != $service->getName())): ?>
				<a href="<?php echo UrlHelper::routed_url(
					Route::CONTROLLER_ACTION_ID, 
					array(
						'controller' => Context::getController(), 
						'action' => 'configure', 
						'id' => $service->getName()
					)
				)?>?type=<?php echo $type ?>"><?php echo ucfirst($service->getName())?></a>
			<?php else: ?>
				<?php echo ucfirst($service->getName()) ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach;?>
</div>
<div style="clear: both;">&nbsp;</div>