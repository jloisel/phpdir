<?php

/**
 * Helps writing some form tags and inputs.
 *
 * @author Jerome Loisel
 */
class FormHelper extends AbstractHelper {
	
	public static function select($name, $options=array(),$attributes=array()) {
		$select = new sfWidgetFormSelect($options,$attributes);
		return $select->render($name);
	}
	
}

?>