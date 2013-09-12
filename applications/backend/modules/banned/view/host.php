<?php include TplHelper::partial('module_menu') ?>
<?php echo ModuleHelper::subTitle('Banned hosts')?>
<?php echo ModuleHelper::info('Banned hosts can no more be submitted.') ?>
<?php include TplHelper::partial('item_list') ?>
<?php echo ModuleHelper::subTitle('Ban a new host') ?>
<?php include TplHelper::partial('add_ban_form') ?>
<?php echo ModuleHelper::info('Do not put "www." in hostname.') ?>