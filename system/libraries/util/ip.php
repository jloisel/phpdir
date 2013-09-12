<?php

/**
 * Class that gives ips the more safely possible.
 * @author Jerome Loisel
 *
 */
class IP {
	
	/**
	 * Remote IP address. (User ip)
	 * Takes care that user uses or not a proxy.
	 *
	 * @return String IP
	 */
	public static final function remote() {
		$ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_SERVER['HTTP_X_FORWARDED'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED'];
		}
		return $ip;
	}
	
	/**
	 * Server IP address.
	 *
	 * @return String
	 */
	public static final function server() {
		return $_SERVER['SERVER_ADDR'];
	}
}