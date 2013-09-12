<?php echo ModuleHelper::subTitle('Directory configuration')?><br />
<?php 	if(Context::getHttpRequest()->getRequestMethod() == RequestMethod::POST): ?>
	<div class="formSuccessMessage"><?php echo __('Configuration updated successfully!') ?></div>
<?php endif; ?>
<div id="directory_config">
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<table style="width: 90%; margin: auto;">
			<tr><td colspan="3"><p style="font-weight: bold;"><?php echo __('General configuration')?></p></td></tr>
			<tr>
				<td><label for="site_url"><?php echo __('Directory URL')?></label>:</td>
				<td><input type="text" name="site_url" id="site_url" value="<?php echo Config::get('site_url')?>" /></td>
				<td><?php echo ImageHelper::app_image('help.png')?>&nbsp;<?php echo __('Example: http://www.mydirectory.com.')?></td>
			</tr>
			<tr>
				<td><label for="encoding"><?php echo __('Encoding')?></label>:</td>
				<td>
					<select name="encoding" id="encoding" class="configuration">
						<option value="UTF-8" <?php Config::get('encoding') == 'UTF-8' && print 'selected="selected"'?>>UTF-8</option>
						<option value="ISO-8859-1" <?php Config::get('encoding') == 'ISO-8859-1' && print 'selected="selected"'?>>ISO-8859-1</option>
					</select>
				</td>
				<td><?php echo ImageHelper::app_image('help.png')?>&nbsp;<?php echo __('Defines the HTML output encoding. Default encoding is UTF-8.')?></td>
			</tr>
			<tr>
				<td><label for="language"><?php echo __('Language')?></label>:</td>
				<td>
					<select name="language" id="language" class="configuration">
						<option value="fr_FR" <?php Config::get('language') == 'fr_FR' && print 'selected="selected"'?>><?php echo __('French')?></option>
						<option value="en_EN" <?php Config::get('language') == 'en_EN' && print 'selected="selected"'?>><?php echo __('English')?></option>
					</select>
				</td>
				<td><?php echo ImageHelper::app_image('help.png')?>&nbsp;<?php echo __('Defines the directory language.')?></td>
			</tr>
			<tr>
				<td><label for="templates_folder"><?php echo __('Theme')?></label>:</td>
				<td>
					<select name="templates_folder" id="templates_folder" class="configuration">
						<?php foreach($this->themes as $theme): ?>
							<option value="<?php echo $theme ?>"><?php echo $theme ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td><?php echo ImageHelper::app_image('help.png')?>&nbsp;<?php echo __('Defines the directory look\'n feel.')?></td>
			</tr>
			<tr>
				<td><label for="date_format"><?php echo __('Date format') ?></label>:</td>
				<td><input type="text" name="date_format" id="date_format" value="<?php echo Config::get('date_format') ?>" /></td>
				<td><?php echo ImageHelper::app_image('help.png')?>&nbsp;<?php echo __('Default date format is D-m-Y.')?></td>
			</tr>
			<tr><td colspan="3"><p style="font-weight: bold;"><?php echo __('Captcha configuration')?></p></td></tr>
			<tr>
				<td><label for="use_captcha_yes"><?php echo __('Use Captcha')?></label>:</td>
				<td>
					<input type="radio" name="use_captcha" id="use_captcha_yes" value="1" <?php Config::get('use_captcha') == '1' and print 'checked="checked"' ?> /><?php echo __('Yes')?>
					<input type="radio" name="use_captcha" id="use_captcha_no" value="0" <?php Config::get('use_captcha') == '0' and print 'checked="checked"' ?>  /><?php echo __('No')?>
				</td>
				<td><?php echo ImageHelper::app_image('help.png')?>&nbsp;<?php echo __('Captcha protects the directory against automated submissions.')?></td>
			</tr>
		</table>
		<p style="text-align: right;"><input type="submit" value="<?php echo __('Update') ?>" /></p>
	</form>
</div>