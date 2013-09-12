<?php

/**
 * Helps writing HTML related to modules elements like 
 * titles, sections...
 *
 * @author Jerome Loisel
 */
class ModuleHelper extends AbstractHelper {
	
	/**
	 * Module subtitle.
	 *
	 * @param string $title
	 * @return string
	 */
	public static function subTitle($title, $options=null) {
		return '<h2>'.__($title,$options).'</h2>'."\n";
	}
	
	/**
	 * Displays a little information paragraph.
	 *
	 * @param string $info
	 */
	public static function info($info,$options=null) {
		return '<p>'.ImageHelper::app_image('info.png','',array('style' => 'vertical-align: middle;')).'&nbsp;'.__($info,$options).'</p>';
	}
	
}

?>