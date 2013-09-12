<?php foreach($services as $name => $service): ?>
	<?php if($seen[$type][$name]) continue;?>
	<?php 
		$onlineService = Context::getServiceLocator()->getRemoteService($type, $name);
		$isSelected = Context::getServiceManager()->isSelected($service);
		$isInstalled = Context::getServiceManager()->isInstalled($service);
		$isUptoDate = Context::getServiceManager()->isUptoDate(
			$service, 
			$onlineService
		);
	?>
	<tr style="background-color: #F0F0F0;">
		<td style="text-align: center; padding: 5px;">
			<input 
				type="radio" 
				name="services[<?php echo $type?>]" 
				value="<?php echo $name ?>"
				<?php $isSelected and print 'checked="checked"'?>
				/>
		</td>
		<td style="padding: 5px;"><?php echo ucfirst($service->getName()) ?></td>
		<td style="padding: 5px; width: 500px;"><?php echo $service->getDescription() ?></td>
		<td style="padding: 5px; text-align: center;">
			<?php echo $service->getVersion(); ?>
		</td>
		<td style="text-align: center; width: 130px;">
			<?php if(!$isUptoDate): ?>
				<a href="<?php echo UrlHelper::routed_url(
					Route::CONTROLLER_ACTION_ID,
					array(
						'controller' => Context::getController(),
						'action' => 'update', 
						'id' => $name
					)
				)?>?type=<?php echo $type ?>"><?php echo __('Update (%s)',$onlineService->getVersion()) ?></a><br/>
			<?php endif; ?>
			<?php if($isInstalled && !$isSelected): ?>
				<a href="<?php echo UrlHelper::routed_url(
					Route::CONTROLLER_ACTION_ID,
					array(
						'controller' => Context::getController(),
						'action' => 'uninstall', 
						'id' => $name
					)
				)?>?type=<?php echo $type ?>"><?php echo __('Delete') ?></a>
			<?php elseif(!$isInstalled): ?>
				<a href="<?php echo UrlHelper::routed_url(
					Route::CONTROLLER_ACTION_ID,
					array(
						'controller' => Context::getController(),
						'action' => 'install', 
						'id' => $name
					)
				)?>?type=<?php echo $type ?>"><?php echo __('Install') ?></a>
			<?php endif; ?>
		</td>
	</tr>
	<?php $seen[$type][$name] = true; ?>
<?php endforeach;?>