<?php

/**
 * 
 * Provides an object-oriented template system for PHP5.
 * 
 * @package Savant3
 * 
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * 
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * 
 * @version $Id: Savant3.php,v 1.42 2006/01/01 18:31:00 pmjones Exp $
 * 
 */

/**
 * 
 * Provides an object-oriented template system for PHP5.
 * 
 * Savant3 helps you separate business logic from presentation logic
 * using PHP as the template language. By default, Savant3 does not
 * compile templates. However, you may pass an optional compiler object
 * to compile template source to include-able PHP code.  It is E_STRICT
 * compliant for PHP5.
 * 
 * Please see the documentation at {@link http://phpsavant.com/}, and be
 * sure to donate! :-)
 * 
 * @author Paul M. Jones <pmjones@ciaweb.net>
 * 
 * @package Savant3
 * 
 * @version @package_version@
 * 
 */

class Savant3 {
	
	/**
	 * Path to scan in order to find templates.
	 *
	 * @var array
	 */
	protected $template_paths = array ( );
	
	/**
	 * All the assigned name - value pairs. 
	 *
	 * @var array
	 */
	protected $assignedVars = array ( );
	
	// -----------------------------------------------------------------
	//
	// Constructor and magic methods
	//
	// -----------------------------------------------------------------
	

	/**
	 * 
	 * Constructor.
	 * 
	 * @access public
	 * 
	 * @param array $config An associative array of configuration keys for
	 * the Savant3 object.  Any, or none, of the keys may be set.
	 * 
	 * @return object Savant3 A Savant3 instance.
	 * 
	 */
	
	public function __construct() {
	
	}
	
	/**
	 * 
	 * Magic method to echo this object as template output.
	 * 
	 * Note that if there is an error, this will output a simple
	 * error text string and will not return an error object.  Use
	 * fetch() to get an error object when errors occur.
	 * 
	 * @access public
	 * 
	 * @param string $tpl The template source to use.
	 * 
	 * @return string The template output.
	 * 
	 */
	
	public function __toString($tpl = null) {
		return $this->fetch ( $tpl );
	}
	
	/**
	 * 
	 * Reports the API version for this class.
	 * 
	 * @access public
	 * 
	 * @return string A PHP-standard version number.
	 * 
	 */
	
	public function apiVersion() {
		return '@package_version@';
	}
	
	// -----------------------------------------------------------------
	//
	// File management
	//
	// -----------------------------------------------------------------
	

	/**
	 *
	 * Sets an entire array of search paths for templates or resources.
	 *
	 * @access public
	 *
	 * @param string $type The type of path to set, typically 'template'
	 * or 'resource'.
	 * 
	 * @param string|array $path The new set of search paths.  If null or
	 * false, resets to the current directory only.
	 *
	 * @return void
	 *
	 */
	
	public function setPath($paths) {
		$this->template_paths = array();
		if (is_array ( $paths )) {
			foreach ( $paths as $path ) {
				$this->addPath ( $path );
			}
		} else if(is_string($paths)) {
			$this->addPath($paths);
		}
	}
	
	/**
	 *
	 * Adds to the search path for templates and resources.
	 *
	 * @access public
	 *
	 * @param string|array $path The directory or stream to search.
	 *
	 * @return void
	 *
	 */
	
	public function addPath($path) {
		// no surrounding spaces allowed!
		$dir = trim ( $path );
		
		// add trailing separators as needed
		if (strpos ( $dir, '://' ) && substr ( $dir, - 1 ) != '/') {
			// stream
			$dir .= '/';
		} elseif (substr ( $dir, - 1 ) != DIRECTORY_SEPARATOR) {
			// directory
			$dir .= DIRECTORY_SEPARATOR;
		}
		
		// add to the top of the search dirs
		if(!in_array($dir, $this->template_paths)) {
			array_unshift ( $this->template_paths, $dir );
		}
	}
	
	/**
	 * 
	 * Searches the directory paths for a given file.
	 * 
	 * @param array $type The type of path to search (template or resource).
	 * 
	 * @param string $file The file name to look for.
	 * 
	 * @return string|bool The full path and file name for the target file,
	 * or boolean false if the file is not found in any of the paths.
	 *
	 */
	
	protected function findFile($file) {
		// start looping through the path set
		foreach ( $this->template_paths as $path ) {
			// get the path to the file
			$fullname = $path . $file;
			// is the path based on a stream?
			if (strpos ( $path, '://' ) === false) {
				// not a stream, so do a realpath() to avoid
				// directory traversal attempts on the local file
				// system. Suggested by Ian Eure, initially
				// rejected, but then adopted when the secure
				// compiler was added.
				$path = realpath ( $path ); // needed for substr() later
				$fullname = realpath ( $fullname );
			}
			//echo 'looking for: '.$file.' in '.$path.'<br />';
			// the substr() check added by Ian Eure to make sure
			// that the realpath() results in a directory registered
			// with Savant so that non-registered directores are not
			// accessible via directory traversal attempts.
			if (file_exists ( $fullname ) && is_readable ( $fullname ) && substr ( $fullname, 0, strlen ( $path ) ) == $path) {
				return $fullname;
			}
		}
		// could not find the file in the set of paths
		return null;
	}
	
	// -----------------------------------------------------------------
	//
	// Variable and reference assignment
	//
	// -----------------------------------------------------------------
	

	/**
	 * 
	 * Sets variables for the template (by copy).
	 * 
	 * This method is overloaded; you can assign all the properties of
	 * an object, an associative array, or a single value by name.
	 * 
	 * You are not allowed to assign any variable named '__config' as
	 * it would conflict with internal configuration tracking.
	 * 
	 * In the following examples, the template will have two variables
	 * assigned to it; the variables will be known inside the template as
	 * "$this->var1" and "$this->var2".
	 * 
	 * <code>
	 * $Savant3 = new Savant3();
	 * 
	 * // assign by object
	 * $obj = new stdClass;
	 * $obj->var1 = 'something';
	 * $obj->var2 = 'else';
	 * $Savant3->assign($obj);
	 * 
	 * // assign by associative array
	 * $ary = array('var1' => 'something', 'var2' => 'else');
	 * $Savant3->assign($ary);
	 * 
	 * // assign by name and value
	 * $Savant3->assign('var1', 'something');
	 * $Savant3->assign('var2', 'else');
	 * 
	 * // assign directly
	 * $Savant3->var1 = 'something';
	 * $Savant3->var2 = 'else';
	 * </code>
	 * 
	 * @access public
	 * 
	 * @return bool True on success, false on failure.
	 * 
	 */
	
	public function assign($name, $value) {
		$this->assignedVars [$name] = $value;
	}
	
	public function __set($name, $value) {
		$this->assign($name,$value);
	}
	
	public function __get($name) {
		return isset ( $this->assignedVars [$name] ) ? $this->assignedVars [$name] : null;
	}
	
	// -----------------------------------------------------------------
	//
	// Template processing
	//
	// -----------------------------------------------------------------
	

	/**
	 * 
	 * Displays a template directly (equivalent to <code>echo $tpl</code>).
	 * 
	 * @access public
	 * 
	 * @param string $tpl The template source to compile and display.
	 * 
	 * @return void
	 * 
	 */
	
	public function display($tpl = null) {
		echo $this->fetch ( $tpl );
	}
	
	/**
	 * 
	 * Compiles, executes, and filters a template source.
	 * 
	 * @access public
	 * 
	 * @param string $tpl The template to process; if null, uses the
	 * default template set with setTemplate().
	 * 
	 * @return mixed The template output string, or a Savant3_Error.
	 * 
	 */
	
	public function fetch($tpl = null) {
		$result = $this->findFile( $tpl );
		// did we get a path?
		if ($result != null) {
			unset ( $tpl );
			ob_start ();
			include $result;
			return ob_get_clean ();
		}
	}
}
?>