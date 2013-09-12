<?php

/**
 * Image helpers : helps to write HTML tags to display
 * images.
 *
 * @author Jerome Loisel
 */
class ImageHelper extends AbstractHelper {
	
	/**
	 * Simple helper to display an image HTML tag.
	 *
	 * @param String $src (Ex: 'www.website.com/image.png')
	 * @param String $alt (Ex: 'My favorite image')
	 * @param Array $extras (Ex: array('border' => 0))
	 */
	public static function image($src,$alt='',$extras=null) {
		$img = '';
		if(is_string($src) && !empty($src)) {
			$img = '<img src="'.$src.'" alt="'.$alt.'"';
			if($extras != null && is_array($extras)) {
				foreach ($extras as $name => $value) {
					$img .= ' '.trim($name).'="'.trim($value).'"';
				}
			}
			$img .= ' />';
		}
		return $img;
	}
	
	/**
	 * Full url to application image.
	 *
	 * @param String $src
	 * @return String
	 */
	public static function app_image_url($src) {
		return Config::get('site_url')
					.'/'.self::getApplicationsFolder().'/'
					.self::getApplicationName().'/view/images/'.$src;
	}
	
	/**
	 * Application image HTML tag.
	 * Images must be store into /view/images in your
	 * application folder.
	 *
	 * @param String $src (Ex: 'image.png')
	 * @param String $alt (Ex: 'My favorite image')
	 * @param Array $extras (Ex: array('border' => 0))
	 * @return String
	 */
	public static function app_image($src,$alt='',$extras=null) {
		$img = '';
		if(is_string($src) && !empty($src)) {
			$img = self::image(self::app_image_url($src),$alt,$extras);
		}
		return $img;
	}
	
	/**
	 * Theme image URL.
	 *
	 * @param string $src
	 * @return string
	 */
	public static function theme_image_url($src,$theme=null) {
		if($theme == null) {
			$theme = Config::get('theme');
		}
		return Config::get('site_url')
					.'/'.PUBLIC_FOLDER
					.'/themes/'.$theme
					.'/images/'.$src;
	}
	
	/**
	 * Theme image HTML tag.
	 * Images must be put into theme's "/themes/your_theme/images" folder.
	 *
	 * @param String $src (Ex: 'image.png')
	 * @param String $alt (Ex: 'My favorite image')
	 * @param Array $extras (Ex: array('border' => 0))
	 * @return String
	 */
	public static function theme_image($src,$alt='',$extras=null) {
		return self::image(self::theme_image_url($src),$alt,$extras);
	}
	
	/**
	 * Full url to module image.
	 *
	 * @param String $src
	 * @return String
	 */
	public static function module_image_url($src) {
		if(is_string($src) && !empty($src)) {
			$controller = Context::getHttpRequest()->getController();
			if(!empty($controller) && is_string($controller)) {
				$src = 	Config::get('site_url')
						.'/'.self::getApplicationsFolder()
						.'/'.self::getApplicationName().'/modules/'
						.$controller.'/view/images/'.$src;
			}
		}
		return $src;
	}
	
	/**
	 * Module image HTML tag.
	 *
	 * @param String $src (Ex: 'image.png')
	 * @param String $alt (Ex: 'My favorite image')
	 * @param Array $extras (Ex: array('border' => 0))
	 * @return String
	 */
	public static function module_image($src,$alt='',$extras=null) {
		return self::image(self::module_image_url($src),__($alt),$extras);
	}
	
	/**
	 * Core image full URL.
	 *
	 * @param string $src
	 * @return string
	 */
	public static function system_image_url($src='') {
		if(is_string($src) && !empty($src)) {
			$controller = Context::getHttpRequest()->getController();
			if(!empty($controller) && is_string($controller)) {
				$src = 	Config::get('site_url').'/'.CORE_FOLDER.'/images/'.$src;
			}
		}
		return $src;
	}
	
	/**
	 * Core images are stored in core/images/ folder.
	 *
	 * @param string $src
	 * @param string $alt
	 * @param array $extras
	 * @return string
	 */
	public static function system_image($src,$alt='',$extras=null) {
		return self::image(self::system_image_url($src),__($alt),$extras);
	}
	
	/**
	 * Returns the HTML image tag displaying the thumbnail of a website.
	 *
	 * @param string $website Example: http://www.google.com
	 * @param string $size Example: s.
	 * @param string $alt Example: Thumbnail of Google search engine.
	 * @param array $extras Example: array('style' => 'border: 0px')
	 * @return string
	 */
	public static function thumbnail($website,$size='',$alt='',$extras=null) {
		$type = Service::THUMBNAIL;
		$tp = Context::getServiceLocator()
				->getService($type,Context::getServiceManager()->getSelectedService($type));
		$tp->setUrl($website);
		$tp->setThumbnailSize($size == '' ? $tp->getDefaultThumbnailSize() : $size);
		return self::image(htmlentities($tp->getThumbnailUrl()),$alt,$extras);
	}
}

?>