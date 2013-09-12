<div id="footer">
	<?php echo DBWrapper::getQueryCount() ?> <?php echo ImageHelper::app_image('database.png','Database queries') ?> - 
	<?php echo BenchHelper::memoryUsage() ?>MB <?php echo ImageHelper::app_image('drive-harddisk.png','Memory usage') ?> - 
	<?php echo BenchHelper::executionTime() ?> sec <?php echo ImageHelper::app_image('execution-time.png','Execution time') ?>
</div>