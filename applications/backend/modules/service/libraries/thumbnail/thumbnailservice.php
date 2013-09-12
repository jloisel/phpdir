<?php

/**
 * Base Implementation of a thumbnail service.
 * 
 * @author Jerome Loisel
 *
 */
interface ThumbnailService {
	/**
	 * Returns the URL of the thumbnail image to display.
	 * Depending on if the thumbnail is cached or not, 
	 * the thumbnail URL will change.
	 * 
	 * @return string
	 */
	public function getThumbnailUrl();
	
	/**
	 * Sets the URL of the website whose thumbnail 
	 * should be displayed.
	 *
	 * @param string $url
	 */
	public function setUrl($url);
	
	/**
	 * Sets the thumbnail size. The passed argument
	 * depends on the thumbnail service.
	 *
	 * @param string $size
	 */
	public function setThumbnailSize($size);
	
}

?>
