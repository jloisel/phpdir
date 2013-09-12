<div id="redirect">
	<p><?php echo ImageHelper::module_image('icon-32x32.png') ?></p>
	<h3><?php echo __('You don\'t have sufficient credentials')?></h3>
	<p><?php echo __('You will be redirected in %s seconds...',array('<span id="redirect_delay">'.(CredentialsAction::REDIRECT_DELAY+1).'</span>'))?></p>
</div>
<script type="text/javascript">
<!--
	function redirection(){
		var pendingDelay = parseInt($('redirect_delay').get('text'))-1;
		if(pendingDelay > 0) {
			$('redirect_delay').set('text',pendingDelay);
			setTimeout('redirection()',1000);
		} else {
			window.location="<?php echo UrlHelper::routed_url(Route::CONTROLLER,array('controller' => 'dashboard')) ?>";
		}
	}

	/* Start redirection countdown */
	redirection();
-->
</script>