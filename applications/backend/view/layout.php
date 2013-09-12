<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<?php include $this->partial('head') ?>
	</head>
	<body>
		<?php $width = 980; ?>
		<table cellpadding="0" cellspacing="0" style="margin: auto; border: 0px;">
			<?php include $this->partial('border_top') ?>
			<tr>
				<?php include $this->partial('border_left') ?>
				<td style="background: #FFF; padding: 15px;">
					<div style="min-height: 300px;">
						<?php Context::getUser()->isAuthenticated() and include(TplHelper::component('links')) ?>
						<?php echo $this->content ?>
					</div>
					<hr />
					<span id="bottom_link" style="float: right;"><?php echo UrlHelper::link_to('Back to directory',Config::get('site_url'))?>&nbsp;&raquo;</span>
				</td>
				<?php include $this->partial('border_right') ?>
			</tr>
			<?php include $this->partial('border_bottom') ?>
		</table>
		<?php include $this->partial('footer') ?>
	</body>
</html>