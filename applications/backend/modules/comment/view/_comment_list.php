<div>
	<?php if(is_object($this->comments) && count($this->comments) > 0): ?>
		<div style="clear: both;"></div>
		<?php $colors = array('#FFF','#EEE')?>
		<form method="post" id="comments_form" action="<?php echo UrlHelper::routed_url(Route::CONTROLLER_ACTION,array('controller' => Context::getController(), 'action' => Context::getAction())) ?>">
			<table cellpadding="3" width="100%" style="text-align: center; border: 1px solid #CCC;">
				<?php echo TableHelper::headers(array('#','ip','Website','comment','Created on','Status')) ?>
				<?php $i = 0; ?>
				<?php foreach($this->comments as $comment): ?>
					<tr style="background-color: <?php echo $colors[$i%count($colors)] ?>">
						<td><input type="checkbox" name="comments[<?php echo $comment->id ?>]" id="comment_<?php echo $comment->id ?>" class="comment" /></td>
						<td><?php echo	$comment->ip ?></td>
						<td><?php echo $comment->Website->title ?></td>
						<td><?php echo $comment->text ?></td>
						<td><?php echo DateHelper::convert($comment->created_on,Config::get('date_format')) ?></td>
						<td><?php echo $comment->getHtmlStatus() ?></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</table>
			<div style="float: right;">
				<input type="button" id="toggle_button" value="<?php echo __('Check all') ?>" />
				<input type="submit" name="<?php echo CommentActions::APPROVE_ACTION ?>" value="<?php echo __('Approve') ?>" />
				<input type="submit" name="<?php echo CommentActions::MARK_AS_SPAM_ACTION ?>" value="<?php echo __('Spam') ?>" />
				<input type="submit" name="<?php echo CommentActions::DELETE_ACTION ?>" value="<?php echo __('Delete') ?>" onclick="return confirm('<?php echo __('Really delete selected comments ?') ?>') ? true: false;" />
			</div>
			<div style="clear: both;"></div>
		</form>
		<p style="text-align: right;"><?echo __('Pages') ?>: <?php echo $this->pager ?></p>
	<?php else: ?>
		<p><?php echo __('No comments found yet.') ?></p>
	<?php endif; ?>
</div>
<script type="text/javascript">
<!--
setSelectUnselectAllButton('toggle_button','comment',"<?php echo __('Check all')?>","<?php echo __('UnCheck all')?>");
-->
</script>