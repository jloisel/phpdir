<?php include $this->partial('way_to_category') ?>
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
	<table style="width: 100%">
		<?php if(is_array($this->categories) && count($this->categories) > 0): ?>
			<tr style="background-color: #F0F0F0;">
				<th>#</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th><?php echo __('Category')?></th>
				<th><?php echo __('Website(s)')?></th>
			</tr>
			<?php $colors = array('#F0F0F0', '#FFF'); $i=0; ?>
			<?php foreach($this->categories as $category): ?>
				<tr style="background-color: <?php echo $colors[$i%1] ?>;">
					<td style="text-align: center; width: 40px;">
						<input type="checkbox" class="category_checkbox" name="category[<?php echo $category['id'] ?>]" id="category_<?php echo $category['id'] ?>" value="<?php echo $category['id'] ?>" />
					</td>
					<td style="text-align: center; width: 40px;">
						<a title="<?php echo __('Editing category <b>%s</b>',$category['title'])?>" href="<?php echo UrlHelper::routed_url(
							Route::CONTROLLER_ACTION_ID,
							array('controller' => $this->getController(), 'action' => 'editCategory', 'id' => $category['id']), 
							array('rel' => 'lightbox[category]', 'id' => 'edit_category_'.$category['id'], 'class' => 'edit_category')
						)
						?>?KeepThis=true&amp;TB_iframe=true&amp;height=<?php echo ContentActions::THICKBOX_HEIGHT ?>&amp;width=<?php echo ContentActions::THICKBOX_WIDTH ?>" class="thickbox">
							<?php echo ImageHelper::module_image('edit.png') ?>
						</a>
					</td>
					<td style="text-align: center; width: 40px;">
						<?php $category['is_adult'] == 1 and print ImageHelper::module_image('adult.png') or print ImageHelper::module_image('all.png') ?>
					</td>
					<td style="text-align: center; width: 40px;">
						<?php echo ImageHelper::module_image($category['allow_submit'] == 0 ? 'stop.png' : 'accept.png') ?>
					</td>
					<td>
						&nbsp;<?php echo UrlHelper::routed_link_to(
							$category['title'],
							Route::CONTROLLER_ACTION_ID,
							array('controller' => $this->getController(), 'action' => $this->getAction(), 'id' => $category['id'])) ?>
					</td>
					<td style="width: 80px; text-align: center">
						<?php echo $category['website_count'] ?>
					</td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		<?php else:?>
			<tr><td colspan="6"><p><?php echo __('This category does not contain any sub-category.')?></p></td></tr>
		<?php endif;?>
		<tr>
			<td colspan="6">
				<?php if(is_array($this->categories) && count($this->categories) > 0):?>
					<div style="float: left;">
						<input type="button" name="toggle_button" id="category_toggle_button" value="<?php echo __('Select All') ?>"/>
						<script type="text/javascript">
							setSelectUnselectAllButton('category_toggle_button','category_checkbox',"<?php echo __('Select all')?>","<?php echo __('Unselect all')?>");
						</script>
					</div>
				<?php endif; ?>
				<div style="float: right">
					<?php if(is_array($this->categories) && count($this->categories) > 0):?>
						<input type="submit" name="<?php echo ContentActions::CUT_CATEGORY_ACTION ?>" value="<?php echo __('Cut') ?>" />
						<input type="submit" name="<?php echo ContentActions::COPY_CATEGORY_ACTION?>" value="<?php echo __('Copy') ?>" />
						<input type="submit" name="<?php echo ContentActions::DELETE_CATEGORY_ACTION ?>" value="<?php echo __('Delete') ?>" onclick="return confirm('<?php echo __('Really Delete selected categories ?') ?>')" />
					<?php endif; ?>
					<?php if(is_array(Context::getUser()->getAttribute(ContentActions::CATEGORIES_WORK))): ?>
						<input type="submit" name="<?php echo ContentActions::PASTE_CATEGORY_ACTION?>" value="<?php echo __('Paste') ?>" />
					<?php endif; ?>
				</div>
				<div style="clear: both;">&nbsp;</div>
			</td>
		</tr>
	</table>
</form>