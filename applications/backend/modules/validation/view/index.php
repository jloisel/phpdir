<?php if(count($this->websites) > 0): ?>
	<?php include($this->partial('websites')) ?>
<?php else: ?>
	<p><?php echo __('There is no pending website.')?></p>
<?php endif; ?>