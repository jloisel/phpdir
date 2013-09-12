<?php $wayStr =  UrlHelper::routed_link_to(
	'Home',
	Route::CONTROLLER_ACTION_ID,
	array('controller' => $this->getController(), 'action' => $this->getAction(), 'id' => 0)
)?>
<?php if(is_object($this->parentCategory)):?>
	<?php $way = $this->parentCategory->getWayToRoot(); ?>
	<?php while(($category=array_pop($way)) != null):?>
		<?php $wayStr .= '&nbsp;&raquo;&nbsp;';?>
		<?php $wayStr .= UrlHelper::routed_link_to(
			$category->title,
			Route::CONTROLLER_ACTION_ID,
			array('controller' => $this->getController(), 'action' => $this->getAction(), 'id' => $category->id)
		)?>
	<?php endwhile; ?>
<?php endif;?>
<?php echo ModuleHelper::subTitle('Category: %s',$wayStr) ?>