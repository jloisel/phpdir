<?php if($this->successWebsite != ''):?>
	<div class="formSuccessMessage"><?php echo __('Website updated successfully') ?></div>
<?php endif; ?>
<div style="float: right">
	<p><?php echo ImageHelper::thumbnail($this->website->link,'','',array('style' => 'border: 1px solid #000;')) ?></p>
</div>
<div id="website_form">
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<table>
				<?php echo $this->websiteForm ?>
				<tr>
					<td>
						<input type="submit" name="<?php echo ContentActions::EDIT_WEBSITE_ACTION ?>" value="<?php echo __('Edit') ?>"/>
					</td>
				</tr>
		</table>
	</form>
</div>