<?php

/**
 * The template helper helps to include a component / partial 
 * within the view.
 *
 * @author Jerome Loisel
 */
class TplHelper extends AbstractHelper {
	
	/**
	 * Finds and returns a partial. Returns the full path of 
	 * the partial.
	 * Use : <?php include TemplateHelper::partial('my_partial') ?>
	 *
	 * @param string $partialName
	 * @return string full path to partial
	 */
	public static function partial($partialName) {
		return Context::getTemplateEngine()->partial($partialName);
	}
	
	/**
	 * Calls a component. Returns the full path the the component partial file.
	 * Use : <?php include TemplateHelper::component('my_component') ?>
	 * 
	 * @param string $componentName
	 * @return string
	 */
	public static function component($componentName) {
		return Context::getTemplateEngine()->component($componentName);
	}
	
}

?>