<?php

/**
 * Query router. A query reaches the front controller,
 * then the router is called to decode the query.
 * Router loads Routes and then compares each route to the current query.
 * The first matching query is parsed :
 * - Extracting Controller and Action
 * - Extracting dynamic parameters from URL
 * (which may override Controller and/or Action)
 * @author Jerome Loisel
 */
class Router extends Singleton {
	
	/**
	 * Name of the route matching the current request.
	 *
	 * @var string
	 */
	protected $currentRouteName = null;
	
	/**
	 * Routes to check.
	 *
	 * @var Array {@link Route}
	 */
	protected $routes = array ();
	
	/**
	 * Default constructor.
	 *
	 */
	protected function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Fetches the default application routes
	 *
	 * @return Array
	 */
	protected function getRoutes() {
		return $this->routes;
	}
	
	/**
	 * Parses a URL to find a matching route.
	 *
	 * Returns null if no route match the URL.
	 *
	 * @param  string URL to be parsed
	 *
	 * @return array  An array of parameters
	 */
	public function parse($url) {
		if (is_string ( $url )) {
			if ((isset ( $url [0] ) && '/' != $url [0])) {
				// Add '/' at the beginning
				$url = '/' . $url;
			}
			$urlLength = strlen ( $url ) - 1;
			if (($urlLength > 1 && '/' == $url [$urlLength])) {
				// remove '/' at the end
				$url = substr ( $url, 0, $urlLength );
			}
		}
		
		// we remove the query string
		$pos = strpos ( $url, '?' );
		if ($pos) {
			$url = substr ( $url, 0, $pos );
		}
		
		// we remove multiple /
		$url = preg_replace ( '#/+#', '/', $url );
		
		foreach ( $this->routes as $route_name => $route ) {
			$out = array ();
			$r = null;
			
			list ( $route, $regexp, $names, $names_hash, $defaults, $requirements, $suffix ) = $route;
			
			$break = false;
			
			if (preg_match ( $regexp, $url, $r )) {
				$break = true;
				
				// remove the first element, which is the url
				array_shift ( $r );
				
				// hack, pre-fill the default route names
				foreach ( $names as $name ) {
					$out [$name] = null;
				}
				
				// defaults
				if (is_array ( $defaults )) {
					foreach ( $defaults as $name => $value ) {
						if (preg_match ( '#[a-z_\-]#i', $name )) {
							$out [$name] = urldecode ( $value );
						} else {
							$out [$value] = true;
						}
					}
				}
				
				
				$pos = 0;
				foreach ( $r as $found ) {
					// if $found is a named url element (i.e. ':action')
					if (isset ( $names [$pos] )) {
						$out [$names [$pos]] = urldecode ( $found );
					} else {
						$pass = explode ( '/', $found );
						$found = '';
						for($i = 0, $max = count ( $pass ); $i < $max; $i += 2) {
							if (! isset ( $pass [$i + 1] ))
								continue;
							
							$found .= $pass [$i] . '=' . $pass [$i + 1] . '&';
						}
						
						parse_str ( $found, $pass );
						
						if (get_magic_quotes_gpc ()) {
							$pass = TextSanitizer::stripslashesDeep ( ( array ) $pass );
						}
						
						foreach ( $pass as $key => $value ) {
							// we add this parameters if not in conflict with named url element (i.e. ':action')
							if (! isset ( $names_hash [$key] )) {
								$out [$key] = $value;
							}
						}
					}
					
					$pos ++;
				}
				
				// we must have found all :var stuffs in url? except if default values exists
				foreach ( $names as $name ) {
					if ($out [$name] == null) {
						$break = false;
					}
				}
				
				if ($break) {
					// we store route name
					$this->setCurrentRouteName ( $route_name );
					break;
				}
			}
		}
		
		// no route found
		if (! $break) {
			throw new BaseException ( '{Router} no matching route found' );
		}
		
		return $out;
	}
	
	/**
	 * Adds a new route at the end of the current list of routes.
	 *
	 * A route string is a string with 2 special constructions:
	 * - :string: :string denotes a named paramater (available later as $request->getParameter('string'))
	 * - *: * match an indefinite number of parameters in a route
	 *
	 * Here is a very common rule in a symfony project:
	 *
	 * <code>
	 * $r->connect('/:module/:action/*');
	 * </code>
	 *
	 * @param  string The route name
	 * @param  string The route string
	 * @param  array  The default parameter values
	 * @param  array  The regexps parameters must match
	 *
	 * @return array  current routes
	 */
	public function connect($name, $route, $default = array(), $requirements = array()) {
		// route already exists?
		if (isset ( $this->routes [$name] )) {
			$error = 'This named route already exists ("%s").';
			$error = sprintf ( $error, $name );
			
			throw new BaseException ( $error );
		}
		
		$parsed = array ();
		$names = array ();
		$suffix = '';
		
		// used for performance reasons
		$names_hash = array ();
		
		$r = null;
		if (($route == '') || ($route == '/')) {
			$regexp = '/^[\/]*$/';
			$this->routes [$name] = array ($route, $regexp, array (), array (), $default, $requirements, $suffix );
		} else {
			$elements = array ();
			foreach ( explode ( '/', $route ) as $element ) {
				if (trim ( $element )) {
					$elements [] = $element;
				}
			}
			
			if (! isset ( $elements [0] )) {
				return false;
			}
			
			// specific suffix for this route?
			// or /$ directory
			if (preg_match ( '/^(.+)(\.\w*)$/i', $elements [count ( $elements ) - 1], $matches )) {
				$suffix = ($matches [2] == '.') ? '' : $matches [2];
				$elements [count ( $elements ) - 1] = $matches [1];
				$route = '/' . implode ( '/', $elements );
			} else if ($route {strlen ( $route ) - 1} == '/') {
				$suffix = '/';
			}
			
			$regexp_suffix = preg_quote ( $suffix );
			
			foreach ( $elements as $element ) {
				if (preg_match ( '/^:(.+)$/', $element, $r )) {
					$element = $r [1];
					
					// regex is [^\/]+ or the requirement regex
					if (is_array ( $requirements ) && isset ( $requirements [$element] )) {
						$regex = $requirements [$element];
						if (0 === strpos ( $regex, '^' )) {
							$regex = substr ( $regex, 1 );
						}
						if (strlen ( $regex ) - 1 === strpos ( $regex, '$' )) {
							$regex = substr ( $regex, 0, - 1 );
						}
					} else {
						$regex = '[^\/]+';
					}
					
					$parsed [] = '(?:\/(' . $regex . '))?';
					$names [] = $element;
					$names_hash [$element] = 1;
				} elseif (preg_match ( '/^\*$/', $element, $r )) {
					$parsed [] = '(?:\/(.*))?';
				} else {
					$parsed [] = '/' . $element;
				}
			}
			$regexp = '#^' . join ( '', $parsed ) . $regexp_suffix . '$#';
			
			$this->routes [$name] = array ($route, $regexp, $names, $names_hash, $default, $requirements, $suffix );
		}
	}
	
	/**
	 * Generates a valid URLs for parameters.
	 *
	 * @param  array  The parameter values
	 * @param  string The divider between key/value pairs
	 * @param  string The equal sign to use between key and value
	 *
	 * @return string The generated URL
	 */
	public function generate($name, $params, $querydiv = '/', $divider = '/', $equals = '/') {
		if (! empty ( $name )) {
			if (! isset ( $this->routes [$name] )) {
				$error = 'The route "%s" does not exist.';
				$error = sprintf ( $error, $name );
				
				throw new BaseException ( $error );
			}
			
			list ( $url, $regexp, $names, $names_hash, $defaults, $requirements, $suffix ) = $this->routes [$name];
			
			// all params must be given
			foreach ( $names as $tmp ) {
				if (! isset ( $params [$tmp] ) && ! isset ( $defaults [$tmp] )) {
					throw new BaseException ( sprintf ( 'Route named "%s" has a mandatory "%s" parameter', $name, $tmp ) );
				}
			}
		} else {
			throw new BaseException ( 'Cannot generate routed URL without valid Route name' );
		}
		
		$params = ArrayUtils::arrayDeepMerge ( $defaults, $params );
		
		$real_url = preg_replace ( '/\:([^\/]+)/e', 'urlencode($params["\\1"])', $url );
		
		// we add all other params if *
		if (strpos ( $real_url, '*' )) {
			$tmp = array ();
			foreach ( $params as $key => $value ) {
				if (isset ( $names_hash [$key] ) || isset ( $defaults [$key] ))
					continue;
				
				if (is_array ( $value )) {
					foreach ( $value as $v ) {
						$tmp [] = $key . $equals . urlencode ( $v );
					}
				} else {
					$tmp [] = urlencode ( $key ) . $equals . urlencode ( $value );
				}
			}
			$tmp = implode ( $divider, $tmp );
			if (strlen ( $tmp ) > 0) {
				$tmp = $querydiv . $tmp;
			}
			$real_url = preg_replace ( '/\/\*(\/|$)/', "$tmp$1", $real_url );
		}
		
		// strip off last divider character
		if (strlen ( $real_url ) > 1) {
			$real_url = rtrim ( $real_url, $divider );
		}
		
		if ($real_url != '/') {
			$real_url .= $suffix;
		}
		
		return $real_url;
	}
	
	/**
	 * Adds a route to the routes array.
	 *
	 * @param String $name
	 * @param PatternRoute $route
	 */
	public function addRoute($route) {
		if (is_object ( $route )) {
			$this->connect ( $route->getName (), $route->getUrl (), $route->getParameters (), $route->getRequirements () );
		}
	}
	
	/**
	 * Returns a specific route, depending on the name specified.
	 *
	 * @param String $name
	 * @return PatternRoute
	 */
	public function getRoute($name) {
		if (! empty ( $name ) && isset ( $this->routes [$name] )) {
			return $this->routes [$name];
		}
		return null;
	}
	
	/**
	 * Removes a route from router.
	 *
	 * @param String $name
	 */
	public function removeRoute($name) {
		if (! empty ( $name ) && isset ( $this->routes [$name] )) {
			unset ( $this->routes [$name] );
		}
	}
	
	/**
	 * Cleans all routes.
	 */
	public function cleanRoutes() {
		$this->routes = null;
	}
	
	/**
	 * Add an array of Route objects.
	 *
	 * @param Array $routes
	 */
	public function addRoutes($routes) {
		if ($routes != null && is_array ( $routes )) {
			foreach ( $routes as $route ) {
				$this->addRoute ( $route );
			}
		}
	}
	
	/**
	 * @return string
	 */
	public function getCurrentRouteName() {
		return $this->currentRouteName;
	}
	
	/**
	 * @param string $currentRouteName
	 */
	public function setCurrentRouteName($currentRouteName) {
		$this->currentRouteName = $currentRouteName;
	}
	
	/**
	 * Router cleanup.
	 */
	public function clean() {
		$this->cleanRoutes ();
	}
	
	/**
	 * Load the routes from a PHP file containing 
	 * routes definition.
	 *
	 * @param string $filepath
	 */
	public function loadRoutes($filepath) {
		if (file_exists ( $filepath )) {
			include $filepath;
		}
	}
	
	/**
	 * Unique instance of the Router class.
	 *
	 * @return Router
	 */
	public static function getInstance() {
		return parent::getInstance ( __CLASS__ );
	}
}

?>