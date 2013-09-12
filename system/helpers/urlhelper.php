<?php
/**
 * URL helper has been designed to 
 * help writing routed urls.
 *
 * @author Jerome Loisel
 */
class UrlHelper extends AbstractHelper {
	
	/**
	 * Returns the routed url corresponding to the specified route
	 * and parameters.
	 *
	 * @param String $routeName (Ex: 'blogArchive')
	 * @param array $params (Ex: array('default,'index',''))
	 * @param boolean $withVirtualPath (link with site host or not)
	 * @return String
	 */
	public static function routed_url($routeName, array $params=null,$withHost=true,$withVirtualPath=true) {
		$router = Router::getInstance();
		if(!empty($routeName)) {
			$routedUrl = $router->generate($routeName,$params);
			if($routedUrl != null && is_string($routedUrl)) {
				$link = $routedUrl;
				if($withVirtualPath) {
					$link = Context::getParameterHolder()->get(Parameter::VIRTUAL_URL).$link;
				}
				if($withHost) {
					$link = Config::get('site_url').$link;
				}
				return $link;
			}
		}
		return null;
	}
	
	
	/**
	 * HTML link on a text.
	 *
	 * @param String $text (Ex: 'Hello !', translated automatically if available)
	 * @param String $href (Ex: http://www.google.com)
	 * @param Array $extras (Ex: array('target => '_blank' 'title' => 'my title'))
	 * @return String
	 */
	public static function link_to($text, $href, $extras=null) {
		$link = '';
		if(!empty($text)) {
			$link .= '<a href="'.$href.'"';
			if(is_array($extras) && count($extras) > 0) {
				foreach ($extras as $name => $value) {
					if($name == 'confirm') {
						$link .= 'onclick="if(confirm(\''.__($value).'\')) {return true;} else { return false;}"';
					} else {
						$link .= ' '.trim($name).'="'.trim($value).'"';
					}
					
				}
			}
			$link .= ' >';
			$link .= __($text);
			$link .= '</a>';
		}
		return $link;
	}
	
	/**
	 * Routed HTML link on a text (or HTML code).
	 *
	 * @param String $text (Ex: 'Hello World !', translated automatically if available)
	 * @param String $routeName (Ex: 'default')
	 * @param Array $routeParams (Ex: array('default','index,''))
	 * @param Array $extras (Ex: array('target => '_blank' 'title' => 'my title'))
	 * @return String
	 */
	public static function routed_link_to($text, $routeName, $routeParams=null, $extras=null) {
		$link = '';
		if(!empty($text)) {
			$link = self::link_to($text,self::routed_url($routeName,$routeParams),$extras);
		}
		return $link;
	}
}
?>