<?php if($this->successCategory != ''):?>
	<div class="formSuccessMessage"><?php echo __('Category updated successfully') ?></div>
<?php endif; ?>
<div id="category_form">
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<table>
				<?php echo $this->categoryForm ?>
				<tr>
					<td>
						<input type="submit" name="<?php echo ContentActions::EDIT_CATEGORY_ACTION ?>" value="<?php echo __('Edit') ?>"/>
					</td>
				</tr>
		</table>
	</form>
</div>