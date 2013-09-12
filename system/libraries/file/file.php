<?php

/**
 * Simple file.
 *
 * @author Jerome Loisel
 */
final class File {
	
	/**
	 * Name of the file.
	 * Ex: toto.php
	 *
	 * @var string
	 */
	private $name = null;
	
	/**
	 * Full path to the directory containing the file.
	 *
	 * @var string
	 */
	private $path = null;
	
	/**
	 * File extension
	 * 
	 * @var string
	 */
	private $extension = null;
	
	/**
	 * Content of the file if any.
	 */
	private $content = null;
	
	/**
	 * File CHMod.
	 *
	 * @var string
	 */
	private $chmod = '0755';
	
	/**
	 * Default constructor.
	 *
	 * @param string $name
	 * @param string $path
	 */
	public function __construct($path,$name=null) {
		$this->path = $path;
		if($name != null) {
			$this->name = $name;
		}
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
	public function getPath() {
		return $this->path;
	}
	
	/**
	 * Returns the concatenation of 
	 * the full path to the directory containing the 
	 * file and the file name.
	 *
	 * @return string
	 */
	public function getFullname() {
		return $this->getPath().($this->getName() != null ? '/'.$this->getName() : '');
	}
	
	/**
	 * Is this instance mapping a file ?
	 *
	 * @return boolean
	 */
	public function isFile() {
		return is_file($this->getFullname());
	}
	
	/**
	 * Is this instance mapping a directory ?
	 *
	 * @return boolean
	 */
	public function isDirectory() {
		return is_dir($this->getFullname());
	}
	
	/**
	 * Does the file/directory exists ?
	 *
	 * @return boolean
	 */
	public function exists() {
		return $this->isDirectory() || ($this->isFile() && file_exists($this->getFullname()));
	}
	
	/**
	 * Returns an array of File objects.
	 *
	 * @return array
	 */
	public function getSubdirectories() {
		return $this->getFilteredFiles(new DirectoryFilter());
	}
	
	/**
	 * Returns an array of File in this directory, including 
	 * this one.
	 *
	 * @param ObjectFilter $filter
	 * @return array
	 */
	public function getFiles(ObjectFilter $filter=null) {
		if($filter == null) {
			$filter = new FileFilter();
		}
		return $this->getFilteredFiles($filter);
	}
	
	/**
	 * Returns an array of File.
	 *
	 * @param ObjectFilter $filter
	 * @return array
	 */
	protected function getFilteredFiles(ObjectFilter $filter) {
		$files = array();
		$currentDir = $this->getPath();
		if(	$currentDir != null && 
			!empty($currentDir) && 
			is_dir($currentDir)		) {
			$dir = opendir($currentDir);
			if(is_resource($dir)) {
				$f = readdir($dir);
				while($f) {
					$myFile = new File($currentDir,$f);
					if($filter->passes($myFile)) {
						$files[] = $myFile;
					}
					$f = readdir($dir);
				}
			}
			closedir($dir);
		}
		return $files;
	}
	
	/**
	 * Deletes the file or directory, by recursively 
	 * deleting the content.
	 *
	 * @param $isRecursive if TRUE, deletes all sub-folders and files within.
	 */
	public function delete($isRecursive=true) {
		if($this->isFile()) {
			if(!is_writable($this->getFullname())) {
				$this->chmod();
			}
			unlink($this->getFullname());
		} else if($isRecursive) {
			$subFiles = $this->getSubdirectories();
			if(is_array($subFiles)) {
				foreach($subFiles as $file) {
					$file->delete();
				}
			}
			if(!is_writable($this->getFullname())) {
				$this->chmod();
			}
			rmdir($this->getFullname());
		}
	}
	
	/**
	 * Returns the content of the file.
	 *
	 * @return string
	 */
	public function read() {
		if($this->content == null) {
			$this->content = '';
			if($this->isFile()) {
				if(!is_readable($this->getFullname())) {
					$this->chmod();
				}
				$size = $this->getFileSize();
				if($size > 0) {
					$f = fopen($this->getFullname(),'r');
					if(is_resource($f)) {
						// Acquire lock to read file
						if(flock($f,LOCK_SH)) {
							$this->content = fread($f,$size);
							// release lock
							flock($f,LOCK_UN);
						}
						fclose($f);
					}
				}
			}
		}
		return $this->content;
	}
	
	/**
	 * Returns the size of the file. (if is a file)
	 * Returns 0 if the file is a directory.
	 *
	 * @return integer
	 */
	public function getFileSize() {
		if($this->isFile()) {
			return filesize($this->getFullname());
		}
		return 0;
	}
	
	/**
	 * Write content into the file
	 *
	 * @param string $content
	 */
	public function appendContent($content) {
		if($this->isFile()) {
			$this->read();
			$this->content .= $content;
		}
	}
	
	/**
	 * Sets the file content. Overwrites existing content.
	 *
	 * @param string $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 * Writes the content of the file to disk.
	 * After the content has been written, content is 
	 * flushed from file object. (content is not available anymore 
	 * after write)
	 *
	 * @return boolean TRUE if written correctly
	 */
	public function write() {
		if(!$this->exists()) {
			$this->touch();
		}
		if(!is_writeable($this->getFullname())) {
			$this->chmod();
		}
		if($this->content != null) {
			$f = fopen($this->getFullname(),'w');
			if(is_resource($f)) {
				// Acquire lock to write the file
				if(flock($f,LOCK_EX)) {
					fwrite($f,$this->content);
					// release lock
					flock($f,LOCK_UN);
				}
				fclose($f);
				$this->content = null;
				return true;
			}
		} else {
			touch($this->getFullname());
		}
		return false;
	}
	
	/**
	 * Set file chmod.
	 *
	 * @param string $chmod
	 */
	public function setChmod($chmod) {
		$this->chmod = $chmod;
	}
	
	/**
	 * File chmod
	 *
	 * @return string
	 */
	public function getChmod() {
		return $this->chmod;
	}
	
	/**
	 * Apply chmod on mapped file or directory.
	 */
	public function chmod() {
		if(!$this->isFile() || !$this->isDirectory()) {
			@chmod($this->getFullname(),$this->getChmod());
		}
	}
	
	/**
	 * Returns the file extension.
	 * Ex: php, css, js.
	 *
	 * @return string
	 */
	public function getExtension() {
		if($this->extension == null) {
			$this->extension = '';
			if(is_string($this->name) && !empty($this->name)) {
				$explodedName = explode('.',$this->name);
				if(is_array($explodedName)) {
					$index = count($explodedName)-1;
					if(isset($explodedName[$index])) {
						$this->extension =  $explodedName[$index];
					}
				}
			}
		}
		return $this->extension;
	}
	
	/**
	 * Touches the file to update the file 
	 * modification date.
	 *
	 */
	public function touch() {
		touch($this->getFullname());
	}
	
	/**
	 * Creates the file or directory if not existing 
	 * on disk.
	 */
	public function create() {
		$this->touch();
	}
	
	public function __toString() {
		return $this->getName();
	}
}

?>