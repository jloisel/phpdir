<?php

/**
 * Global script Configuration items are defined here.
 *
 * @author Jerome Loisel
 */
class ConfigurationItem {
	
	/**
	 * Unknown type 
	 */
	const TYPE_UNKNOWN = -1;
	
	/**
	 * Text configuration item.
	 */
	const TYPE_TEXT = 1;
	
	/**
	 * Yes - No choice configuration item.
	 */
	const TYPE_YES_NO = 2;
	
	/**
	 * Script mode: development or production.
	 * Defines the way the script should act.
	 */
	const CFG_SCRIPT_MODE = 'mode';
	
	/**
	 * Database driver name.
	 */
	const CFG_DB_DRIVER = 'db_driver';
	
	/**
	 * Database login.
	 */
	const CFG_DB_LOGIN = 'db_login';
	
	/**
	 * Database password.
	 */
	const CFG_DB_PASSWORD = 'db_pass';
	
	/**
	 * Database name.
	 */
	const CFG_DB_NAME = 'db_name';
	
	/**
	 * Database prefix.
	 */
	const CFG_DATABASE_PREFIX = 'db_prefix';
	
	/**
	 * Website URL. Used for writing internal links.
	 */
	const CFG_SITE_URL = 'site_url';
	
	/**
	 * HTML output encoding.
	 */
	const CFG_ENCODING = 'encoding';
	
	/**
	 * Default language.
	 */
	const CFG_LANGUAGE = 'language';
	
	/**
	 * Frontend theme. (look'n feel)
	 */
	const CFG_THEME = 'theme';
}

?>