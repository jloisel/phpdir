<?php

/**
 *	Language Manager. This Manager selects and loads a specified language.
 *	@package	Kernel
 *	@author	Jerome Loisel
 **/
final class l10n {
	/*
	 * Language translations
	 */
	protected static $_lang = null;
	/**
	 * Loads language file and assigns it to Template Engine if demanded.
	 * @param	String $languageFile full Path To Language File
	 * @param 	String $arrayName Name of the loaded translation array
	 * @param	Boolean $isMandatory (is this file mandatory)
	 * @return	Boolean	TRUE if loaded successfully, else FALSE
	 */
	public static function init($languageFile = '', $isMandatory = true) {
		if (! empty ( $languageFile )) {
			if (self::loadFile ( $languageFile, $isMandatory )) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Returns the whole language definitions.
	 * @return	Array language definitions
	 */
	public static function getAllTranslations() {
		return self::$_lang;
	}
	
	/**
	 * Returns the translation of a string, if available.
	 *
	 * @param String $toTranslate
	 * @return String translated
	 */
	public static function i18n($toTranslate = '') {
		if (! empty ( $toTranslate ) && is_array ( self::$_lang )) {
			if (isset ( self::$_lang [$toTranslate] )) {
				return self::$_lang [$toTranslate];
			}
		}
		return $toTranslate;
	}
	
	/**
	 * Translate the string using options.
	 *
	 * @param string $toTranslate
	 * @param array $options
	 * @return string
	 */
	public static function translate($toTranslate='',$options=array()) {
		return __(strtr($toTranslate,$options));
	}
	
	/**
	 * Loads a language file.
	 *
	 * @param String $languageFile (Full absolute path)
	 * @param String $arrayName (name of the language definitions array
	 * @param	Boolean $isMandatory (is this file mandatory)
	 * @return boolean
	 */
	protected static function loadFile($languageFile, $isMandatory = true) {
		if (file_exists ( $languageFile ) && is_readable ( $languageFile )) {
			require_once ($languageFile);
			if (isset ( ${ConfigLoader::I18N} )) {
				$langArray = ${ConfigLoader::I18N};
				if (is_array ( self::$_lang )) {
					self::$_lang = array_merge ( self::$_lang, $langArray );
				} else {
					self::$_lang = $langArray;
				}
				return true;
			}
		} else {
			if ($isMandatory) {
				throw new BaseException ( 'Language File "' . $languageFile . '" does not exists or is not readable' );
			}
		}
		return false;
	}
	
	public static function touch() {
		
	}
}

/**
 * Special helper designed to translate text
 * easily.
 *
 * @param string $toTranslate
 * @param array $options
 * @return string
 */
function __($toTranslate, $options = null) {
	if ($options == null) {
		return l10n::i18n ( $toTranslate );
	} else {
		$args = array_merge ( ( array ) __ ( $toTranslate ), ( array ) $options );
		return call_user_func_array ( 'sprintf', array_merge ( $args ) );
	}
}
?>