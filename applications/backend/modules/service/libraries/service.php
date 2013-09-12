<?php

/**
 * Every service must implement that interface.
 *
 * @author Jerome Loisel
 */
interface Service {

	/**
	 * Captcha service.
	 *
	 */
	const CAPTCHA = 'captcha';
	
	/**
	 * Antispam service.
	 *
	 */
	const ANTISPAM = 'antispam';
	
	/**
	 * Thumbnail services.
	 *
	 */
	const THUMBNAIL = 'thumbnail';
	
	/**
	 * Returns the type of the service.
	 * 
	 * @return string
	 */
	public function getType();
	
	/**
	 * Returns the name of the service.
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Returns the service description.
	 *
	 * @return string
	 */
	public function getDescription();
	
	/**
	 * Returns the version of the service.
	 * Example: '1.0'
	 */
	public function getVersion();
	
	/**
	 * When the service is installed, this method is 
	 * called. It allows the service to create its 
	 * environment (configuration items for example)
	 * 
	 * @return boolean TRUE if success
	 */
	public function install();
	
	/**
	 * When the service is uninstalled, this method is called.
	 * 
	 * @return boolean TRUE if success
	 */
	public function uninstall();
	
	/**
	 * Returns an instance of the service configuration form.
	 * This allows to dynamically create a service configuration.
	 * 
	 * @return sfI18nForm NULL if no configuration needed
	 */
	public function getConfigurationForm();
	
	/**
	 * When the configuration Form is valid (validation passes), 
	 * this method is called to allow the service to update its 
	 * configuration.
	 * 
	 * @param sfI18nForm $form
	 * @return void
	 */
	public function onValidConfigurationForm(sfI18nForm $form);
}

?>