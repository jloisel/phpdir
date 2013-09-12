<?php

/**
 * Header Factory helps instanciating
 * Html headers.
 * @author Jerome Loisel
 */
class HtmlHeaderFactory {

	/**
	 * Returns an instance of HtmlHeader.
	 * Ex : tag = 'Meta', method returns a new MetaHeader.
	 * @param	String name of the Instance
	 * @return	HtmlHeader
	 */
	public static function createHeader($tag = '') {
		if(!empty($tag) && $tag != 'Html') {
			$class = ucfirst($tag).'Header';
			if(class_exists($class)) {
				return new $class();
			}
		}
		return false;
	}
}


/**
 * Html Header Abstract object. It defines
 * main attributes and implementation.
 * @author Jerome Loisel
 * @abstract
 */
abstract class HtmlHeader {

	/*
	 * Name of the header
	 * Ex : <link ... /> : name is "link"
	 */
	private $_tagName = '';
	/*
	 * Attributes of the header
	 * Ex : <link rel="alternate" ... />
	 * "rel" is the attribute key, "alternate" is the attribute value
	 * array : attribute name => value
	 */
	private $_attributes = array();
	/*
	 * Mandatory attributes : these attributes must be defined
	 * for the specific Html Header.
	 * Ex : 'rel', 'type'.
	 */
	private $_mandatoryAttributes = array();
	/*
	 * Is it a self closing HTML tag ?
	 * Ex:
	 * - self closing; <link ... />
	 * - not self closing; <script ...></script>
	 */
	private $_selfClose = false;
	/*
	 * HTML Header may have content.
	 * Ex : <script ...>Content here</script>
	 */
	private $_content = '';

	/**
	 * Returns name of the Html header tag.
	 * Ex : <link ... />, returns "link"
	 * @return	String tagName
	 */
	public function getTagName() {
		return $this->_tagName;
	}

	/**
	 * Sets tag name.
	 * @param	String tag name
	 */
	public function setTagName($tagName = '') {
		if(!empty($tagName)) {
			$this->_tagName = (string)$tagName;
		}
	}

	/**
	 * Sets an attribute.
	 * @param	String attribute
	 * @param	String value
	 */
	public function setAttribute($attribute='', $value='') {
		if(!empty($attribute) && !empty($value)) {
			$this->_attributes[$attribute] = $value;
		}
	}

	/**
	 * Sets an array of attributes.
	 * @param	array String attribute => String value
	 */
	public function setAttributes($attributesArray = array()) {
		if(count($attributesArray) > 0) {
			foreach($attributesArray as $attribute => $value) {
				$this->setAttribute($attribute,$value);
			}
		}
	}

	/**
	 * Adds a mandatory attribute if not already added.
	 * @param	String name of the mandatory attribute
	 */
	public function addMandatoryAttribute($attrName='') {
		if(!empty($attrName) && !in_array($attrName,$this->_mandatoryAttributes)) {
			$this->_mandatoryAttributes[] = $attrName;
		}
	}

	/**
	 * Sets an array of mandatory attributes.
	 * @param	array Integer key => String mandatory attribute
	 */
	public function setMandatoryAttributes($attrNameArray = array()) {
		if(count($attrNameArray) > 0) {
			foreach($attrNameArray as $attrName) {
				$this->addMandatoryAttribute($attrName);
			}
		}
	}

	/**
	 * Returns the mandatory attributes.
	 * @return array Integer key => String mandatory attribute
	 */
	public function getMandatoryAttributes() {
		return $this->_mandatoryAttributes;
	}

	/**
	 * Sets selfClose to the wanted value.
	 * @param	Boolean self close
	 */
	public function setSelfClose($isSelfClose) {
		$this->_selfClose = $isSelfClose;
	}

	/**
	 * Indicates if this Header is self closing or not.
	 * @return	is self closing, TRUE if success
	 */
	public function isSelfClose() {
		return $this->_selfClose;
	}

	/**
	 * Returns the content of this html header.
	 * @return	String content
	 */
	public function getContent() {
		return $this->_content;
	}

	public function setContent($content='') {
		if(!!empty($content)) {
			$this->_content = $content;
		}
	}

	/**
	 * Renders the Html Header.
	 * @return	String Html code of this Html Header if SUCCESS,
	 * 			empty string if Html Header is not valid. (all the mandatory
	 * 			attritbutes have not been set)
	 */
	public function renderHtml() {
		$htmlHeader = '';
		if($this->isValid()) {
			$htmlHeader .= '<'.$this->_tagName;

			foreach($this->_attributes as $attribute => $value) {
				$htmlHeader .= ' '.$attribute.'="'.$value.'"';
			}

			if($this->_selfClose) {
				$htmlHeader .= ' />';
			} else {
				$htmlHeader .= '>';
				if(!empty($this->_content)) {
					$htmlHeader .= $this->_content;
				}
				$htmlHeader .= '</'.$this->_tagName.'>';
			}
		}
		return $htmlHeader;
	}

	/**
	 * Magic method toString, String representation of the object.
	 * @return	String representation of this object
	 */
	public function __toString() {
		return $this->renderHtml();
	}

	/**
	 * Returns the object as an array.
	 * @return	array of String
	 */
	public function __toArray() {
		return array(
			'htmlTag' 		=> $this->_tagName,
			'attributes' 	=> $this->_attributes,
			'selfClose'		=> $this->_selfClose,
			'content'		=> $this->_content
		);
	}

	/**
	 * Checks if all the mandatory attributes of this HTML Header
	 * have been set.
	 * @return TRUE if success, else FALSE.
	 */
	protected function isValid() {
		$attributesCount = count($this->_attributes);
		$mandatoryAttributesCount = count($this->_mandatoryAttributes);
		if($attributesCount >= $mandatoryAttributesCount) {
			foreach($this->_mandatoryAttributes as $key => $mandatoryAttribute) {
				if(!isset($this->_attributes[$mandatoryAttribute])) {
					return false;
				}
			}
		}
		return true;
	}
}


/**
 * Meta Header.
 * Ex: <meta name="xxx" content="Meta content goes here" />
 * @author Jerome Loisel
 * @abstract
 */
abstract class MetaHeader extends HtmlHeader {

	public function __construct() {
		$this->setTagName('meta');
		$this->setSelfClose(true);
		$this->addMandatoryAttribute('content');
	}
}


/**
 * Link Header.
 * Ex: <link rel="stylesheet" type="text/css" href="style.css" />
 * @author Jerome Loisel
 */
class LinkHeader extends HtmlHeader {

	public function __construct() {
		$this->setTagName('link');
		$this->setSelfClose(true);

		$this->setMandatoryAttributes(
		array('href','rel','type')
		);
	}
}

/**
 * Stylesheet header.
 *
 */
class StyleSheetHeader extends LinkHeader {
	
	public function __construct() {
		parent::__construct();
		$this->setAttributes(array(
			'rel' => 'stylesheet',
			'type' => 'text/css'
		));
	}
}


/**
 * Meta Content-Type Header.
 * Ex: <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 * @author Jerome Loisel
 */
class MetaContentTypeHeader extends HtmlHeader {

	public function __construct() {
		$this->setTagName('meta');
		$this->setSelfClose(true);
		$this->setAttribute('http-equiv','Content-Type');
		$this->addMandatoryAttribute('content');
	}
}

/**
 * Meta description Header.
 * Ex: <meta name="description" content="Meta description goes here" />
 * @author Jerome Loisel
 */
class MetaDescriptionHeader extends MetaHeader {

	public function __construct() {
		parent::__construct();
		$this->setAttribute('name','description');
	}
}

/**
 * JavaScript Header.
 * Ex: <script type="text/javascript" src="file.js"></script>
 * @author Jerome Loisel
 */
class JavascriptHeader extends HtmlHeader {

	public function __construct() {
		$this->setTagName('script');
		$this->setSelfClose(false);
		$this->setAttribute('type','text/javascript');
		$this->addMandatoryAttribute('src');
	}
}

/**
 * Meta keywords Header.
 * Ex: <meta name="keywords" content="keyword1, keyword2" />
 * @author Jerome Loisel
 */
class MetaKeywordsHeader extends MetaHeader {

	public function __construct() {
		parent::__construct();
		$this->setAttribute('name','keywords');
	}
}

?>