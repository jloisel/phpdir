<?php

/**
 * Helps outputing HTML tables.
 *
 * @author Jerome Loisel
 */
class TableHelper extends AbstractHelper {
	
	/**
	 * Writes table headers.
	 *
	 * @param array $headers
	 */
	public static function headers(array $headers=array(),$color='#EEE') {
		$out = '<tr style="background-color: '.$color.'">'."\n";
		foreach($headers as $title) {
			$out .= '<th>'.$title.'</th>'."\n";
		}
		$out .= '</tr>'."\n";
		return $out;
	}
	
	/**
	 * Writes a table item <td>...</td>.
	 *
	 * @param array $array
	 * @return string
	 */
	public static function columns(array $array=array()) {
		$out = '';
		foreach($array as $value) {
			$out .= '<td>'.$value.'</td>'."\n";
		}
		return $out;
	}
	
	/**
	 * Writes a new table line <tr>...</tr>.
	 *
	 * @param array $columns
	 * @param string $color
	 * @return string
	 */
	public static function line(array $columns,$color) {
		$out = '<tr style="background-color: '.$color.';">'."\n";
		$out .= self::columns($columns);
		$out .= '</tr>'."\n";
		return $out;
	}
	
	/**
	 * Writes table content.
	 *
	 * @param array of array $data
	 * @return string
	 */
	public static function content(array $data=array(),array $colors=array('#FFF','#EEE')) {
		$out = '';
		$i = 0;
		foreach($data as $columns) {
			$out .= self::line($columns,$colors[$i%count($colors)]);
			$i++;
		}
		return $out;
	}
}

?>