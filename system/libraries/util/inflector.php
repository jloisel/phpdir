<?php

/**
 * Various operations on Strings.
 * @author Jerome Loisel
 * @package Util
 */
class Inflector {
	
	/**
	* Returns given word as CamelCased.
	* 
	* Converts a word like "send_email" to "SendEmail". It
	* will remove non alphanumeric character from the word, so
 	* "who's online" will be converted to "WhoSOnline".
	*
	* @param String $word
	*/
	public static function toCamelCase($word) {
       if(preg_match_all('/\/(.?)/',$word,$got)){
           foreach ($got[1] as $k=>$v){
               $got[1][$k] = '::'.strtoupper($v);
           }
           $word = str_replace($got[0],$got[1],$word);
       }
       return str_replace(' ','',ucwords(preg_replace('/[^A-Z^a-z^0-9^:]+/',' ',$word)));
	}
	
	/**
	 * Same as toCamelCase but first char is lowercased.
	 * Converts a word like "send_email" to "sendEmail".
	 *
	 * @param String $word
	 * @return String
	 */
	public static function variablize($word) {
		$word = self::toCamelCase($word);
		return strtolower($word[0]).substr($word,1);
	}
	
	/**
	 * Removes all accents from String.
	 *
	 * @param String $text
	 * @return String
	 */
	public static function cleanAccents($text) {
		$map = array(
			'À'=>'A', '�?'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C',
			'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', '�?'=>'I', 'Î'=>'I', '�?'=>'I',
			'�?'=>'D', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O',
			'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', '�?'=>'Y', 'Þ'=>'T', 'ß'=>'s', '� '=>'a',
			'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e',
			'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'e',
			'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
			'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'þ'=>'t', 'ÿ'=>'y');
		return str_replace(array_keys($map), array_values($map), $text);
	}
	
	/**
	* Returns subject replaced with regular expression matchs.
	*
	* @param mixed subject to search
	* @param array array of search => replace pairs
	*/
	private static function pregtr($search, $replacePairs) {
		return preg_replace(array_keys($replacePairs), array_values($replacePairs), $search);
	}
	
	/**
	* Returns an underscore-syntaxed version or the CamelCased string.
	*
	* @param string String to underscore.
	* @return string Underscored string.
	*/
	public static function underscore($camel_cased_word) {
		$tmp = $camel_cased_word;
		$tmp = str_replace('::', '/', $tmp);
		$tmp = self::pregtr($tmp, array('/([A-Z]+)([A-Z][a-z])/' => '\\1_\\2',
		                                 '/([a-z\d])([A-Z])/'     => '\\1_\\2'));
		
		return strtolower($tmp);
	}
	
	/**
	* Returns a human-readable string from a lower case and underscored word by replacing underscores
	* with a space, and by upper-casing the initial characters.
	*
	* @param string String to make more readable.
	* @return string Human-readable string.
	*/
	public static function humanize($lower_case_and_underscored_word) {
		if (substr($lower_case_and_underscored_word, -3) === '_id') {
			$lower_case_and_underscored_word = substr($lower_case_and_underscored_word, 0, -3);
		}
		return ucfirst(str_replace('_', ' ', $lower_case_and_underscored_word));
	}
}