<?php include TplHelper::partial('module_menu') ?>
<?php echo ModuleHelper::subTitle('Banned emails')?>
<?php echo ModuleHelper::info('Banned emails can no more be used to create an account, submit websites or login using an existing account.') ?>
<?php include TplHelper::partial('item_list') ?>
<?php echo ModuleHelper::subTitle('Ban a new email') ?>
<?php include TplHelper::partial('add_ban_form') ?>