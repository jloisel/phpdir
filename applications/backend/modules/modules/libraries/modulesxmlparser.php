<?php

/**
 * Remote modules XML parser.
 *
 * @author Jerome Loisel
 */
class ModulesXMLParser extends AbstractRemoteObjectXMLParser {
	
	/**
	 * @see AbstractRemoteObjectXMLParser::newLocalizableObject()
	 *
	 * @return LocalizableObject
	 */
	protected function newLocalizableObject() {
		return new Module();
	}


}

?>