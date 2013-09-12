<?php

/**
 * Internationalization filter.
 * 
 * @author Jerome Loisel
 *
 */
class I18nFilter extends AbstractFilter {
	
	/**
	 * Is the application i18n ? 
	 *
	 * @var boolean
	 */
	protected $isI18n = null;
	
	/**
	 * Default constructor.
	 *
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Is the application internationalized ?
	 *
	 * @return boolean
	 */
	protected function isI18n() {
		if (! is_bool ( $this->isI18n )) {
			$this->isI18n = ModuleConfigLoader::getInstance ()->getItem ( ConfigLoader::I18N, 'is_i18n', false );
			if ($this->isI18n == null) {
				// Application i18n configuration is mandatory
				$this->isI18n = AppConfigLoader::getInstance ()->getItem ( ConfigLoader::I18N, 'is_i18n', true );
			}
		}
		return $this->isI18n;
	}
	
	public function preExecute() {
		if ($this->isI18n ()) {
			if($this->isFirstCall()) {
				$this->loadApplicationI18n ();
			}
			$this->loadControllerI18n ();
		} else {
			l10n::touch();
		}
	}
	
	public function postExecute() {
	
	}
	
	/**
	 * Loads application l10n file.
	 *
	 * @return TRUE if initialized correctly 
	 */
	protected function loadApplicationI18n() {
		$l10nFile = $this->getParameterHolder()->getApplicationPath()
						 . DIRECTORY_SEPARATOR . 'locales' . DIRECTORY_SEPARATOR 
						 . Config::get ( 'language' ) . DIRECTORY_SEPARATOR . 'i18n.php';
		return l10n::init ( $l10nFile, true );
	}
	
	/**
	 * Loads controller internationalization files.
	 *
	 * @return TRUE if initialized correctly
	 */
	protected function loadControllerI18n() {
		$l10nFile = $this->getParameterHolder()->getModulePath()
						. DIRECTORY_SEPARATOR . 'locales' . DIRECTORY_SEPARATOR 
						. Config::get ( 'language' ) . DIRECTORY_SEPARATOR . 'i18n.php';
		return l10n::init ( $l10nFile, true );
	}

}

?>
