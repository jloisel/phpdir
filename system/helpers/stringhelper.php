<?php

class StringHelper extends AbstractHelper {
	
	/**
	 * Cuts a string if it's longer than the specified length.
	 *
	 * @param string $str
	 * @param integer $length
	 * @param string $endWith
	 * @return string
	 */
	public static function cut($str,$length=20,$endWith='..') {
		return strlen($str) > $length ? substr($str,0,$length).$endWith : $str;
	}
	
}

?>