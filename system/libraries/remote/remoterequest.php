<?php

/**
 * The purpose of the remote request is to 
 * retrieve content from a remote file 
 * with different methods in order to ensure 
 * maximum compatibility.
 *
 * @author Jerome Loisel
 */
class RemoteRequest {
	
	private $url = null;
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	/**
	 * Is allow_url_fopen enabled ?
	 *
	 * @return boolean
	 */
	private function isAllowUrlFopenEnabled() {
		$allow = ini_get('allow_url_fopen');
		return ($allow == 1 || $allow == 'on') && function_exists('file_get_contents');
	}
	
	/**
	 * Is PHP CURL enabled ?
	 *
	 * @return boolean
	 */
	private function isCURLEnabled() {
		return function_exists('curl_init');
	}
	
	/**
	 * Is HTTP Request enabled ?
	 *
	 * @return booleans
	 */
	private function isHTTPRequestEnabled() {
		return class_exists('HttpRequest');
	}
	
	/**
	 * Retrieves content from remote location 
	 * using 
	 *
	 * @return string
	 */
	private function execAllowUrlFopen() {
		return file_get_contents($this->url);
	}
	
	/**
	 * Retrieves content from remote location
	 * using CURL PHP library.
	 *
	 * @return string
	 */
	private function execCurl() {
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$this->url);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    $contents = curl_exec($ch);
	    curl_close($ch);
	    return $contents;
	}
	
	private function isFsockOpenEnabled() {
		return function_exists('fsockopen');
	}
	
	/**
	 * Retrieves content from remote location
	 * using HttpRequest PHP library.
	 *
	 * @return string
	 */
	private function execHttpRequest() {
		$r = new HttpRequest ( $this->url, HttpRequest::METH_GET );
		$r->setOptions (
			array ('User-agent' => isset ( $_SERVER ['HTTP_USER_AGENT'] ) ? $_SERVER ['HTTP_USER_AGENT'] : 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)' ) 
		);
		$r->send ();
		if ($r->getResponseCode () == 200) {
			return $r->getResponseBody ();
		}
		return null;
	}
	
	/**
	 * Retrieve the content at the URL location.
	 *
	 * @param string $url
	 * @return string
	 */
	public function getContent() {
		// URL Fopen method
		if($this->isAllowUrlFopenEnabled()) {
			return $this->execAllowUrlFopen();
		}
		
		// PHP Curl method
		if($this->isCURLEnabled()) {
			return $this->execCurl();
		}
		
		// HTTP Request methods
		if($this->isHTTPRequestEnabled()) {
			return $this->execHttpRequest();
		}
		return null;
	}
}

?>