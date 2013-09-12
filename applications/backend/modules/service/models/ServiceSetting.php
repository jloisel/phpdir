<?php

/**
 * Service settings.
 *
 * @author Jerome Loisel
 */
class ServiceSetting extends Setting {
	
	const MODULE_NAME = 'service';
	
	/**
	 * Returns the service setting value.
	 *
	 * @param string $key
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public static function getValue($key,$defaultValue=null) {
		return parent::getValue('service_'.$key,$defaultValue);
	}
	
	/**
	 * Sets the Service setting value.
	 *
	 * @param string $moduleName
	 * @param string $key
	 * @param mixed $value
	 */
	public static function setValue($key,$value) {
		parent::setValue('service_'.$key,$value);
	}
	
}

?>