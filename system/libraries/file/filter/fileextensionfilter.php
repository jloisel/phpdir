<?php

/**
 * Filters a file on its extension.
 * 
 * @author Jerome Loisel
 *
 */
class FileExtensionFilter extends FileFilter {
	
	/**
	 * The file extension the File object must match.
	 *
	 * @var string
	 */
	protected $ext = null;
	
	/**
	 * A file extension filter absolutely needs 
	 * a file extension.
	 * Ex: php, css, js.
	 *
	 * @param string $fileExtension
	 */
	public function __construct($fileExtension) {
		$this->ext = $fileExtension;
	}
	
	/**
	 * @see FileFilter::passes()
	 *
	 * @param File $o
	 * @return boolean
	 */
	public function passes($o) {
		return 	parent::passes($o) && 
				$o->getExtension() == $this->ext;
	}

	
}

?>