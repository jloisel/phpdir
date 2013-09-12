<?php

/**
 * HTML Headers. This is a container for HtmlHeader objects.
 * @author Jerome Loisel
 */
class HtmlHeaders extends Singleton {
	/**
	 * Array of Html headers.
	 * @var array
	 */
	protected $_headers = null;

	/**
	* Constructor
	*/
	protected function __construct() {
		parent::__construct();
	}

	/**
	* Initializes Headers on demand.
	*/
	protected function initHeaders() {
		if($this->_headers == null) {
			$this->_headers = array();
		}
	}

	/**
	 * Adds an Html Header to the HtmlHeader collection.
	 * @param	HtmlHeader
	 */
	public function addHeader($header) {
		if(		is_object($header) &&
				is_subclass_of($header,'HtmlHeader')) {
			$this->initHeaders();
			$this->_headers[] = $header;
		}
	}

	/**
	 * Renders all the Html Header and return the rendered Html code.
	 * @return String HTML code
	 */
	public function renderHtmlHeaders() {
		$htmlHeaders = '';
		if(is_array($this->_headers) && count($this->_headers > 0)) {
			foreach($this->_headers as $header) {
				$htmlHeader = $header->renderHtml();
				if(!empty($htmlHeader)) {
					$htmlHeaders .= $htmlHeader."\n";
				}
			}
		}
		return $htmlHeaders;
	}

	/**
	 * Singleton method to retrieve the Html Headers from anywhere.
	 * @return HtmlHeaders
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>