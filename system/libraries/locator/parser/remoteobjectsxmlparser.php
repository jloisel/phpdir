<?php

/**
 * Public implementation of a remote objects 
 * XML parser.
 *
 */
interface RemoteObjectXMLParser {
	
	/**
	 * Parses the XML retrieved from 
	 * online, and returns a Collection of 
	 * Localizable objects.
	 *
	 * @return array
	 */
	public function parse();
	
}

?>