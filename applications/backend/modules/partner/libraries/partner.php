<?php

/**
 * A partner is a link to a friend, 
 * a colleague or so.
 *
 * @author Jerome Loisel
 */
class Partner implements Serializable {
	
	/**
	 * Name of the partner.
	 *
	 * @var string
	 */
	protected $name = null;
	
	/**
	 * Link to the partner. Example: http://www.phpdir.org
	 *
	 * @var string
	 */
	protected $link = null;
	
	/**
	 * title of the partner.
	 *
	 * @var string
	 */
	protected $title = null;
	
	/**
	 * Partner target. Example: _blank, _parent.
	 *
	 * @var string
	 */
	protected $target = null;
	
	/**
	 * Default constructor.
	 */
	public function __construct() {
		
	}
	
	/**
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return string
	 */
	public function getTarget() {
		return $this->target;
	}
	
	/**
	 * @param string $link
	 */
	public function setLink($link) {
		$this->link = $link;
	}
	
	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * @param string $target
	 */
	public function setTarget($target) {
		$this->target = $target;
	}

	/**
	 * Serializes the partner.
	 *
	 * @return string
	 */
	public function serialize() {
		return serialize(array($this->name,$this->link,$this->target));
	}
	
	/**
	 * Unserializes the Partner.
	 *
	 * @param string $serialized
	 */
	public function unserialize($serialized) {
		$arr = unserialize($serialized);
		$this->name = $arr[0];
		$this->link = $arr[1];
		$this->target = $arr[2];
	}
	
	/**
	 * Renders the partner.
	 *
	 * @return string
	 */
	public function render() {
		return '<a href="'.$this->link.'" title="'.$this->title.'" target="'.$this->target.'">'.$this->name.'</a>'."\n";
	}
}

?>