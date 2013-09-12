<?php echo ModuleHelper::subTitle('Websites') ?>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<?php include($this->partial('website_list'))?>
		<?php include($this->partial('website_actions'))?>
</form>
<script type="text/javascript">
	jQuery('#websites').accordion({
		header: '.website_title'
	});
</script>