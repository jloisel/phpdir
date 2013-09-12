<?php

/**
 * This parser is responsible of extracting the 
 * count of remote objects.
 * 
 * @author Jerome Loisel
 *
 */
class RemoteObjectCountXMLParser extends AbstractRemoteObjectXMLParser {
	
	/**
	 * Parses the XML and returns the remote 
	 * object count.
	 *
	 * @return integer
	 */
	public function parse() {
		$xml = $this->getXML();
		if(!is_object($xml)) return 0;
		return isset($xml['count']) ? intval($xml['count']) : 0;
	}
	
}

?>