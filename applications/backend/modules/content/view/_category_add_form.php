<div id="category_form">
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>#category_form" method="post">
		<table>
				<?php echo $this->categoryForm ?>
				<tr>
					<td>
						<input type="submit" name="<?php echo ContentActions::ADD_CATEGORY_ACTION ?>" value="<?php echo __('Add') ?>"/>
					</td>
				</tr>
		</table>
	</form>
</div>