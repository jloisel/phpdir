<?php if(is_array($this->websites) && count($this->websites) > 0): ?>
	<div id="websites" class="basic">
		<?php foreach($this->websites as $website): ?>
			<div class="website_title">
				<div style="float: right; text-align: right;">
					<b><?php echo __('Ins')?></b>: <?php echo $website['ins'] ?>&nbsp;|&nbsp;
					<b><?php echo __('Outs')?></b>: <?php echo $website['outs'] ?>&nbsp;|&nbsp;
					<?php echo DateHelper::convert($website['created_on'],Config::get('date_format'))?>
				</div>
				<a style="font-weight: bold;" ><?php echo $website['title'] ?></a>,&nbsp;
				<?php if(isset($website['Customer'])): ?>
					<?php echo __('By %s',$website['Customer']['email'])?>
				<?php else: ?>
					<?php echo __('By %s',__('Anonymous'))?>
				<?php endif; ?>
			</div>
			<div>
				<table style="width: 100%; height: 152px">
					<tr style="background-color:  #F0F0F0;">
						<td style="text-align: center; width: 40px;"><input type="checkbox" class="website_checkbox" name="website[<?php echo $website['id'] ?>]" id="website_<?php echo $website['id'] ?>" value="<?php echo $website['id'] ?>" /></td>
						<td style="text-align: center; width: 40px;">
							<p>
								<a title="<?php echo __('Editing <b>%s</b> details',$website['title'])?>" href="<?php echo UrlHelper::routed_url(
									Route::CONTROLLER_ACTION_ID,
									array('controller' => $this->getController(), 'action' => 'editWebsite', 'id' => $website['id']), 
									array('rel' => 'lightbox[website]', 'id' => 'edit_website_'.$website['id'], 'class' => 'edit_website')
								)
								?>?KeepThis=true&amp;TB_iframe=true&amp;height=<?php echo ContentActions::THICKBOX_HEIGHT ?>&amp;width=<?php echo ContentActions::THICKBOX_WIDTH ?>" class="thickbox">
									<?php echo ImageHelper::module_image('edit.png') ?>
								</a>
							</p>
							<p>
							<a title="<?php echo __('Editing <b>%s</b> feeds',$website['title'])?>" href="<?php echo UrlHelper::routed_url(
								Route::CONTROLLER_ACTION_ID,
								array('controller' => $this->getController(), 'action' => 'editWebsiteFeeds', 'id' => $website['id']), 
								array('rel' => 'lightbox[websiteFeeds]', 'id' => 'edit_website_'.$website['id'], 'class' => 'edit_website')
							)
							?>?KeepThis=true&amp;TB_iframe=true&amp;height=<?php echo ContentActions::THICKBOX_HEIGHT ?>&amp;width=<?php echo ContentActions::THICKBOX_WIDTH ?>" class="thickbox">
								<?php echo ImageHelper::module_image('feed.png') ?>
							</a>
							</p>
						</td>
						<td style="vertical-align: top; padding: 2px;">
							<b><?php echo __('Title')?>:</b> <?php echo $website['title'] ?><br/>
							<b><?php echo __('Subtitle')?>:</b> <?php echo $website['subtitle'] ?>
							<p>
								<b><?echo __('Description')?>:</b> <?php echo $website['description'] ?>
							</p>
							<p>
								<b><?php echo __('Tags')?></b>: <?php echo Website::getTagsAsStringUtils($website['Tags']) ?>
							</p>
						</td>
						<td style="width: 202px;">
							<?php echo ImageHelper::thumbnail($website['link'],'','',array('style' => 'width: 202px; height: 152px; border: 1px solid #000;')) ?>
						</td>
					</tr>
				</table>
			</div>
		<?php endforeach; ?>
	</div>
<?php else:?>
	<div><p><?php echo __('No website found.')?></p></div>
<?php endif;?>