<?php

/**
 * Helps to manage / display dates.
 *
 * @author Jerome Loisel
 */
class DateHelper extends AbstractHelper {
	
	/**
	 * Returns the full name of the month.
	 *
	 * @param integer $i
	 * @return string
	 */
	public static function get_month($i) {
		switch($i) {
			case 1:
				return __('January');
			case 2:
				return __('February');
			case 3:
				return __('March');
			case 4:
				return __('April');
			case 5:
				return __('May');
			case 6:
				return __('June');
			case 7:
				return __('July');
			case 8:
				return __('August');
			case 9:
				return __('September');
			case 10:
				return __('October');
			case 11:
				return __('November');
			case 12:
				return __('December');
			default:
				return 'Invalid Month index "'.$i.'"';
		}
	}
	
	/**
	 * Formats a timestamp into a readable date.
	 *
	 * @param integer $timestamp
	 * @param string $pattern
	 * @return string
	 */
	public static function to_date($timestamp,$pattern='Y-m-d H:i:s') {
		return date($pattern,$timestamp);
	}
	
	/**
	 * Converts a date from a pattern to another one.
	 *
	 * @param string $date
	 * @param string $pattern
	 * @return string
	 */
	public static function convert($date, $pattern='Y-m-d H:i:s') {
		return date($pattern,strtotime($date));
	}
}

?>