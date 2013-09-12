<?php
/**
 * Class to "clean up" text for various uses.
 *
 * @package Util
 * @author	Kazumi Ono
 * @author	Goghs Cheng
 */
class TextSanitizer {
	
	/**
	 * Convert linebreaks to <br /> tags
	 *
	 * @param	String  $text
	 *
	 * @return	String
	 */
	public static function nl2Br($text) {
		return preg_replace ( "/(\015\012)|(\015)|(\012)/", '<br />', $text );
	}
	
	/**
	 * Add slashes to the text if magic_quotes_gpc is turned off.
	 *
	 * @param   String  $text
	 * @return  String
	 **/
	public static function addSlashes($text) {
		return (get_magic_quotes_gpc () ? $text : addslashes ( $text ));
	}
	
	/**
	 * if magic_quotes_gpc is on, stirip back slashes
	 *
	 * @param	String  $text
	 *
	 * @return	String
	 */
	public static function stripSlashesGPC($text) {
		return (get_magic_quotes_gpc () ? stripslashes ( $text ) : $text);
	}
	
	/**
	 *  for displaying data in html textbox forms
	 *
	 * @param	String  $text
	 *
	 * @return	String
	 */
	public static function htmlSpecialChars($text) {
		//return preg_replace("/&amp;/i", '&', htmlspecialchars($text, ENT_QUOTES));
		return preg_replace ( array ("/&amp;/i", "/&nbsp;/i" ), array ('&', '&amp;nbsp;' ), htmlspecialchars ( $text, ENT_QUOTES ) );
	}
	
	/**
	 * Reverses {@link htmlSpecialChars()}
	 *
	 * @param   String  $text
	 * @return  String
	 **/
	public static function undoHtmlSpecialChars($text) {
		return preg_replace ( array ("/&gt;/i", "/&lt;/i", "/&quot;/i", "/&#039;/i" ), array (">", "<", "\"", "'" ), $text );
	}
	
	/**
	 * htmlentities
	 *
	 * @param   String  $text
	 * @return  String
	 **/
	public static function htmlEntities($text) {
		return htmlentities ( $text );
	}
	
	/**
	 * decodes htmlentities
	 *
	 * @param   String  $text
	 * @param int quote style : php code for quote style
	 * @return  String
	 **/
	public static function decodeEntities($text, $quote_style = ENT_COMPAT) {
		if (function_exists ( 'html_entity_decode' )) {
			$text = html_entity_decode ( $text, $quote_style, 'ISO-8859-1' ); // NOTE: UTF-8 does not work!
		} else {
			$trans_tbl = get_html_translation_table ( HTML_ENTITIES, $quote_style );
			$trans_tbl = array_flip ( $trans_tbl );
			$text = strtr ( $text, $trans_tbl );
		}
		$text = preg_replace ( '~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $text );
		$text = preg_replace ( '~&#([0-9]+);~e', 'chr("\\1")', $text );
		return $text;
	}
	
	/**
	 * Encodes string in UTF-8 encoding.
	 *
	 * @param String $string
	 * @return String
	 */
	public static function utf8Encode($string) {
		if (! self::isUTF8 ( $string )) {
			return utf8_encode ( $string );
		}
		return $string;
	}
	
	/**
	 * Decodes utf8 strings.
	 * @param String $string
	 * @return String
	 */
	public static function utf8Decode($string) {
		if (self::isUTF8 ( $string )) {
			return utf8_decode ( $string );
		}
		return $string;
	}
	
	/**
	 * Detect UTF-8 characters in strings
	 * @param	String to verify
	 * @return	Boolean	TRUE if UTF-8 encoded, else FALSE
	 */
	public static function isUTF8($string) {
		return preg_match ( '%(?:
		[\xC2-\xDF][\x80-\xBF]				# non-overlong 2-byte
		|\xE0[\xA0-\xBF][\x80-\xBF]			# excluding overlongs
		|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}	# straight 3-byte
		|\xED[\x80-\x9F][\x80-\xBF]			# excluding surrogates
		|\xF0[\x90-\xBF][\x80-\xBF]{2}		# planes 1-3
		|[\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
		|\xF4[\x80-\x8F][\x80-\xBF]{2}		# plane 16
		)+%xs', $string );
	}
	
	public static function stripslashesDeep($value) {
		return is_array ( $value ) ? array_map ( array ('TextSanitizer', 'stripslashesDeep' ), $value ) : stripslashes ( $value );
	}
	
	/**
	 * Access the only instance of this class
	 *
	 * @return	object
	 *
	 * @static
	 * @staticvar   object
	 */
	public static function getInstance() {
		return parent::getInstance ( __CLASS__ );
	}
}
?>