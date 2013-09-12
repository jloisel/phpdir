<div id="website_form">
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>#website_form" method="post">
		<table>
				<?php echo $this->websiteForm ?>
				<tr>
					<td>
						<input type="submit" name="<?php echo ContentActions::ADD_WEBSITE_ACTION ?>" value="<?php echo __('Add') ?>"/>
					</td>
				</tr>
		</table>
	</form>
</div>