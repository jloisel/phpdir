<div style="padding: 10px; width: 200px; border: 1px dotted #CCC; float: right;">
	<p><strong><?php echo __('Help')?></strong></p>
	<?php echo ImageHelper::module_image('edit.png','',array('style' => 'vertical-align: top;'))?>&nbsp;<?php echo __('Edit category')?><br />
	<p>
		<?php echo ImageHelper::module_image('all.png','',array('style' => 'vertical-align: top;'))?>&nbsp;<?php echo __('Classic content')?><br/>
		<?php echo ImageHelper::module_image('adult.png','',array('style' => 'vertical-align: top;'))?>&nbsp;<?php echo __('Explicit content')?>
	</p>
	<p>
		<?php echo ImageHelper::module_image('accept.png','',array('style' => 'vertical-align: top;'))?>&nbsp;<?php echo __('Submissions allowed')?><br />
		<?php echo ImageHelper::module_image('stop.png','',array('style' => 'vertical-align: top;'))?>&nbsp;<del><?php echo __('Submissions')?></del>
	</p>
</div>