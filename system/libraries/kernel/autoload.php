<?php

/**
 * PHP class autoloader.
 * 
 * @author Jerome Loisel
 */
final class AutoLoad {
	
	/**
	 * This string designates any folder.
	 * Example of use:
	 * modules/ ** /forms to autoload all module forms
	 *
	 */
	const ANY_FOLDER = '**';
	/**
	 * Class path filename.
	 *
	 */
	const CLASSPATH_FILENAME = 'classpath.php';
	
	/**
	 * PHP class file regexp matcher.
	 *
	 */
	const PHP_CLASS_FILE_REGEXP = '([a-zA-Z0-9]+)';
	
	/**
	 * Folder regexp matcher.
	 *
	 */
	const FOLDER_REGEXP = '([a-z0-9]+)';
	
	/**
	 * PHP classes file accepted extension.
	 *
	 */
	const PHP_CLASS_FILE_EXT = '.php';
	
	/**
	 * Folders to scan to look for php classes.
	 *
	 * @var array
	 */
	protected static $foldersToScan = array();
	
	/**
	 * Folder where the classpath is cached.
	 * (full path)
	 *
	 * @var string
	 */
	protected static $classPathCacheFolder = null;
	
	/**
	 * PHP classes that can be loaded automatically.
	 *
	 * @var array
	 */
	protected static $autoloadableClasses = null;
	
	/**
	 * Class path file name.
	 *
	 * @var string
	 */
	protected static $classPathfilename = null;
	
	/**
	 * Does the directory name matches the directory 
	 * name mask.
	 *
	 * @param string $path
	 * @return boolean
	 */
	protected static function matchesDirMask($f) {
		return $f != '.' && $f != '..' && preg_match('`^'.self::FOLDER_REGEXP.'$`',$f);
	}
	
	/**
	 * Adds PHP classes relative paths to classpath.
	 *
	 * @param string $path
	 * @param string $folder
	 * @return string
	 */
	protected static function addFilesToCache($path,$folder) {
		$cache = '';
		if(is_dir($path)) {
			$dir = opendir($path);
			while ($f = readdir($dir)) {
				$item = $path.'/'.$f;
				if(is_file($item) && preg_match('`^'.self::PHP_CLASS_FILE_REGEXP.'\\'.self::PHP_CLASS_FILE_EXT.'$`',$f)) {
					$cache .= "\t\t'".strtolower(str_replace(self::PHP_CLASS_FILE_EXT,'',$f))."' => '".$folder.'/'.$f."',\n";
				} else if (is_dir($item) && self::matchesDirMask($f)) {
					$cache .= self::addFilesToCache($item,$folder.'/'.$f);
				}
			}
			closedir($dir);
		}
		return $cache;
	}

	/**
	 * Launches the autoload folders scan.
	 *
	 */
	public static function scan() {
		$cache = '<?php'."\n";
		$cache .= 'Autoload::registerClasses('."\n";
		$cache .= "\t".'array('."\n";

		if(is_array(self::$foldersToScan)) {
			foreach(self::$foldersToScan as $folder) {
				$path = SCRIPT_ROOT_PATH.'/'.$folder;
				$cache .= self::addFilesToCache($path, $folder);
			}
		}

		$cache .= "\t".')'."\n";
		$cache .= ');'."\n";
		$cache .= '?>';
		
		$file = self::getClassPathFilePath();
		if(!file_exists($file)) {
			touch($file);
			@chmod($file,0755);
		}
		
		if(is_writable($file)) {
			$f = fopen($file,'w');
			if($f) {
				fwrite($f,$cache);
				fclose($f);
			}
		} else {
			echo '<strong style="color: red;">"'.$file.'" file must be writeable (CHMOD 777)</strong>';
			exit();
		}
	}
	
	/**
	 * Computes all the subfolder of a dynamic folder.
	 * a dynamic folder path contains the string self::ANY_FOLDER,
	 * which designates any subfolder.
	 *
	 * @param string $dynamicFolder
	 * @return array
	 */
	protected static function getMatchingFolders($dynamicFolder) {
		$folders = array();
		$splits = explode('/'.self::ANY_FOLDER.'/',$dynamicFolder);
		if(is_array($splits) && count($splits) == 2) {
			$path = SCRIPT_ROOT_PATH.'/'.$splits[0];
			if(is_dir($path)) {
				$dir = opendir($path);
				while ($f = readdir($dir)) {
					if(self::matchesDirMask($f)) {
						$folders[] = str_replace(
							SCRIPT_ROOT_PATH.'/','',
							$path.'/'.$f.'/'.$splits[1]
						);
					}
				}
				closedir($dir);
			}
		}
		return $folders;
	}
	
	/**
	 * Adds a folder to scan. folder path is relative 
	 * to script root path.
	 *
	 * @param string $folder
	 */
	public static function addFolderToScan($folder) {
		if(strpos($folder,self::ANY_FOLDER) === false) {
			self::$foldersToScan[] = $folder;
		} else {
			self::addFoldersToScan(self::getMatchingFolders($folder));
		}
	}
	
	/**
	 * Add several folder to scan for autoloading php 
	 * classes.
	 *
	 * @param array $folders
	 */
	public static function addFoldersToScan($folders) {
		if(is_array($folders)) {
			foreach ($folders as $folder) {
				self::addFolderToScan($folder);
			}
		}
	}
	
	/**
	 * Sets the classpath cache folder.
	 * (full path)
	 * 
	 * @param string $folder
	 */
	public static function setClassPathCacheFolder($folder) {
		self::$classPathCacheFolder = $folder;
	}
	
	/**
	 * Is the classpath file cached ?
	 *
	 * @return boolean
	 */
	public static function isCached() {
		return 	is_dir(self::$classPathCacheFolder) && 
				file_exists(self::getClassPathFilePath());
	}
	
	/**
	 * Returns the name of the classpath file.
	 *
	 * @return string
	 */
	protected static function getClasspathFilename() {
		if(self::$classPathfilename == null) {
			self::$classPathfilename = APP_NAME.'.php';
		}
		return self::$classPathfilename;
	}
	
	/**
	 * Returns the full path to the class path file.
	 *
	 * @return string
	 */
	public static function getClassPathFilePath() {
		return self::$classPathCacheFolder.'/'.self::getClasspathFilename();
	}

	/**
	 * Registers the PHP classes that can be loaded automatically.
	 *
	 * @param array $classes
	 */
	public static function registerClasses($classes) {
		self::$autoloadableClasses = $classes;
	}
	
	/**
	 * Autoloads a php class.
	 *
	 * @param string $class
	 */
	public static function __load($class) {
		$class = strtolower($class);
		if(isset(self::$autoloadableClasses[$class])) {
			include SCRIPT_ROOT_PATH.'/'.self::$autoloadableClasses[$class];
		}
	}
	
	/**
	 * Initializes and registers the PHP Autoloader.
	 *
	 */
	public static function initialize($cacheFolder) {
		/**
		 * Initialize PHP class autoloader.
		 */
		spl_autoload_register(array(__CLASS__, '__load'));
		
		/**
		 * Start the autoloader.
		 */
		self::setClassPathCacheFolder($cacheFolder);
		if(!self::isCached()) {
			// Add folders to scan
			require_once (SCRIPT_ROOT_PATH.'/'.CONFIG_FOLDER.'/autoload.php');
			self::scan();
		}
		include self::getClassPathFilePath();
	}
	
	/**
	 * Regenerates the cached classpath file.
	 * This must be done if a new class has been 
	 * added.
	 */
	public static function reset() {
		$file = self::getClassPathFilePath();
		if(file_exists($file)) {
			if(!is_writable($file)) {
				@chmod($file,0755);
			}
			if(is_writable($file)) {
				unlink($file);
			}
		}
	}
}

?>