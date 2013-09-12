<div class="module_menu">
	<?php echo ImageHelper::module_image('add.png')?>&nbsp;<a href="<?php echo $_SERVER['REQUEST_URI'] ?>#category_form"><?php echo __('Add a new Category')?></a>
	<?php if(Context::getHttpRequest()->getParameter('id',0) != 0): ?>
		<?php echo ImageHelper::module_image('add.png')?>&nbsp;<a href="<?php echo $_SERVER['REQUEST_URI'] ?>#website_form"><?php echo __('Add a new Website')?></a>
	<?php endif; ?>
</div>
<div style="clear: both">&nbsp;</div>
<?php include $this->partial('categories')?>
<?php if(is_object($this->parentCategory)): ?>
	<?php include $this->partial('websites')?>
	<br/>
	<?php echo ModuleHelper::subTitle('Add a new website') ?><br />
	<?php include $this->partial('website_add_form')?>
<?php endif; ?>
<?php echo ModuleHelper::subTitle('Add a new category') ?><br />
<?php include $this->partial('help_icons')?>
<?php include $this->partial('category_add_form')?>
<div style="clear: both;">&nbsp;</div>