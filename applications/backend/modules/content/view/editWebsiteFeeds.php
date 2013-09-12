<?php if($this->success): ?>
	<div class="formSuccessMessage"><?php echo __('Website feeds updated successfully')?></div>
<?php endif; ?>
<?php echo ModuleHelper::subTitle('%s Feeds',$this->website['title']) ?>
<?php if(is_array($this->feeds) && count($this->feeds) > 0):?>
	<table id="website_feeds" style="width: 100%;">
		<tr>
			<th><?php echo __('Title')?></th>
			<th><?php echo __('URL')?></th>
			<th style="width: 75px;"><?php echo __('Charset')?></th>
			<th style="width: 75px;">#</th>
		</tr>
		<?php $colors = array('#F0F0F0', "#FFF"); $i=0; ?>
		<?php foreach($this->feeds as $feed):?>
			<tr style="text-align: center; background-color: <?php echo $colors[$i%2] ?>;">
				<td>&nbsp;<?php echo UrlHelper::link_to(
						$feed['title'],
						$feed['link'],
						array('target' => '_blank')) 
					?>
				</td>
				<td style="width: 75px;"><?php echo $feed['link'] ?></td>
				<td style="width: 75px;"><?php echo $feed['charset'] ?></td>
				<td style="width: 75px;">
					<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
						<input type="hidden" name="feed_id" value="<?php echo $feed['id'] ?>" />
						<input type="submit" name="<?php echo ContentActions::DELETE_WEBSITE_FEED_ACTION?>" value="<?php echo __('Delete') ?>" />
					</form>
				</td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p><?php echo __('This website has no feeds.')?></p>
<?php endif;?>
<br />
<?php echo ModuleHelper::subTitle('Add a new RSS feed') ?>
<div style="float: right">
	<p><?php echo ImageHelper::thumbnail($this->website['link'],'','',array('style' => 'border: 1px solid #000;')) ?></p>
</div>
<div id="website_feed_form">
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<table>
				<?php echo $this->feedForm ?>
				<tr>
					<td>
						<input type="submit" name="<?php echo ContentActions::ADD_WEBSITE_FEED_ACTION ?>" value="<?php echo __('Add') ?>"/>
					</td>
				</tr>
		</table>
	</form>
</div>