<?php

/**
 * WebSnapr website Thumbnail provider.
 * 
 * @author Jerome Loisel
 *
 */
class Robothumb extends AbstractThumbnailService {
	
	/**
	 * Micro thumbnail
	 *
	 */
	const MICRO_THUMB = '120x90';
	
	/**
	 * Small thumbnail.
	 *
	 */
	const SMALL_THUMB = '180x135';
	
	/**
	 * Websnapr HTTP parameter : thumbnail size.
	 *
	 */
	const THUMB_SIZE_PARAM = 'size';
	
	/**
	 * Websnapr HTTP parameter : URL of the website to 
	 * take a snapshot.
	 *
	 */
	const URL_PARAM = 'url';
	
	/**
	 * Default constructor.
	 */
	public function __construct() {
		parent::__construct('http://www.robothumb.com/src/');
		$this->setThumbnailSize ( $this->getDefaultThumbnailSize());
	}
	
	
	/**
	 * Returns the size of the thumbnail.
	 * (internal class code)
	 * 
	 * @return string
	 */
	public function getThumbnailSize() {
		return $this->getQueryParam ( self::THUMB_SIZE_PARAM );
	}
	
	/**
	 * Set the thumbnail size. (internal class code)
	 * 
	 * @param string $thumbnailSize
	 */
	public function setThumbnailSize($thumbnailSize) {
		$this->setQueryParam ( self::THUMB_SIZE_PARAM, $thumbnailSize );
	}
	
	/**
	 * Cleans the URL of the website.
	 *
	 * @param string $url
	 * @return string
	 */
	protected function cleanUrl($url) {
		return str_replace ( 'http://', '', $url );
	}
	
	/**
	 * Sets the URL of the website which thumbnail should be generated.
	 *
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->setQueryParam ( self::URL_PARAM, $url );
	}
	
	/**
	 * URL of the website which thumbnail must be generated.
	 *
	 * @return string
	 */
	protected function getUrl() {
		return $this->getQueryParam ( self::URL_PARAM );
	}
	
	/**
	 * Default thumbnail size.
	 *
	 * @return string
	 */
	public function getDefaultThumbnailSize() {
		return self::SMALL_THUMB;
	}
	
	/**
	 * Service description.
	 *
	 * @return string
	 */
	public function getDescription() {
		return 'Generate website thumbnails.';
	}
	
	/**
	 * Returns the version of the service.
	 *
	 * @return string
	 */
	public function getVersion() {
		return '1.0';
	}
}

?>