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
						<?php echo $this->content ?>
					</div>
				</td>
				<?php include $this->partial('border_right') ?>
			</tr>
			<?php include $this->partial('border_bottom') ?>
		</table>
	</body>
</html>