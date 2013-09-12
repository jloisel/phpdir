<?php include TplHelper::partial('module_menu') ?>
<?php echo ModuleHelper::subTitle('Banned IPs')?>
<?php echo ModuleHelper::info('Banned IPs cannot do any action of the directory.') ?>
<?php include TplHelper::partial('item_list') ?>
<?php echo ModuleHelper::subTitle('Ban a new IP') ?>
<?php include TplHelper::partial('add_ban_form') ?>