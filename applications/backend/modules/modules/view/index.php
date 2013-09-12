<?php echo ModuleHelper::subTitle('Installed modules')?>
<?php echo ModuleHelper::info('Modules extend and expand the functionality of the directory. Modules can be installed, uninstalled and deleted.') ?>
<?php if(is_array($this->modules) && count($this->modules) > 0):?>
	<table style="width: 850px; margin: auto;">
		<?php $i = 0; ?>
			<?php foreach($this->modules as $module): ?>
				<?php if($i%2 == 0): ?><tr><?php endif; ?>
				<td style="width: 50%; vertical-align: top;">
					<div class="module">
						<div style="float: right; font-size: 9px;">
							<?php echo $module->getAuthor() ?>&nbsp;-
							<?php echo $module->getVersion() ?>
						</div>
						<p>
							<span style="float: left; margin-right: 4px;"><?php echo $module->getIcon() ?></span>
							<span class="name"><?php echo $module->getName() ?></span><br />
							<span class="description"><?php echo $module->getDescription() ?></span>
						</p>
						<div class="module_actions">
							<?php if(!$module->isInstalled()): ?>
								<?php echo UrlHelper::routed_link_to(
									'Install', 
									Route::CONTROLLER_ACTION_ID,
									array(
										'controller' => Context::getController(),
										'action' => 'install',
										'id' => $module->getLocalFolder()
									)
								)?>
								|
								<?php echo UrlHelper::routed_link_to(
									'Remove', 
									Route::CONTROLLER_ACTION_ID,
									array(
										'controller' => Context::getController(),
										'action' => 'delete',
										'id' => $module->getLocalFolder()
									),
									array(
										'confirm' => 'Really delete module \''.$module->getName().'\' ?'
									)
								)?>
							<?php elseif(!$module->isSystem()): ?>
								<?php echo UrlHelper::routed_link_to(
									'Uninstall', 
									Route::CONTROLLER_ACTION_ID,
									array(
										'controller' => Context::getController(),
										'action' => 'uninstall',
										'id' => $module->getLocalFolder()
									),
									array(
										'confirm' => 'Really uninstall module '.$module->getName().' ?'
									)
								)?>
							<?php else:?>
								<?php echo ImageHelper::module_image('system.png')?>
							<?php endif;?>
						</div>
						<div style="clear: both;"></div>
					</div>
				</td>
			<?php $i++; ?>
			<?php if($i%2 == 0): ?></tr><?php endif; ?>
			<?php endforeach; ?>
		<?php if($i%2 != 0): ?></tr><?php endif; ?>
	</table>
<?php else:?>
	<p style="font-weight: bold; text-align: center;"><?php echo __('No Module installed yet.')?></p>
<?php endif; ?>