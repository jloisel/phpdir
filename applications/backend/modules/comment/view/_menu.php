<?php echo ModuleHelper::subTitle('Edit comments') ?><br />
<div id="module_menu">
	<?php if(Context::getAction() != 'all'): ?>
		<?php echo UrlHelper::routed_link_to(
			'All',
			Route::CONTROLLER_ACTION,
			array(
				'controller' => Context::getController(),
				'action' => 'all'
			)
		) ?>
	<?php else: ?>
		<?php echo sprintf(__('All (%s)'),$this->pager->getPager()->getNumResults()) ?>
	<?php endif; ?>
	&nbsp;|&nbsp;
	<?php if(Context::getAction() != 'pending'): ?>
		<?php echo UrlHelper::routed_link_to(
			'Pending',
			Route::CONTROLLER_ACTION,
			array(
				'controller' => Context::getController(),
				'action' => 'pending'
			)
		)?>
	<?php else: ?>
		<?php echo sprintf(__('Pending (%s)'),$this->pager->getPager()->getNumResults()) ?>
	<?php endif; ?>
	&nbsp;|&nbsp;
	<?php if(Context::getAction() != 'approved'): ?>
		<?php echo UrlHelper::routed_link_to(
			'Approved',
			Route::CONTROLLER_ACTION,
			array(
				'controller' => Context::getController(),
				'action' => 'approved'
			)
		) ?>
	<?php else: ?>
		<?php echo sprintf(__('Approved (%s)'),$this->pager->getPager()->getNumResults()) ?>
	<?php endif; ?>
	&nbsp;|&nbsp;
	<?php if(Context::getAction() != 'spam'): ?>
		<?php echo UrlHelper::routed_link_to(
			'Spam',
			Route::CONTROLLER_ACTION,
			array(
				'controller' => Context::getController(),
				'action' => 'spam'
			)
		) ?>
	<?php else: ?>
		<?php echo sprintf(__('Spam (%s)'),$this->pager->getPager()->getNumResults()) ?>
	<?php endif; ?>
</div>
<div style="clear: both">&nbsp;</div>