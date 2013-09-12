<?php

/**
 * Helpers designed to help writing HTML headers.
 *
 * @author Jerome Loisel
 */
class HeaderHelper extends AbstractHelper {

	/**
	 * Outputs <link ... /> xhtml tag.
	 *
	 * @param String $href
	 * @param String $rel
	 * @param String $type
	 * @param String $media
	 * @return String HTML output
	 */
	protected static function stylesheet($href,$rel='stylesheet',$type='text/css',$media='screen') {
		$styleSheet = HtmlHeaderFactory::createHeader('link');
		$attributes = array(
			'href' 	=> 	$href,
			'rel'	=> $rel,
			'type'	=> $type,
			'media'	=> $media
		);
	
		$styleSheet->setAttributes($attributes);
		$stylesheetHtml = $styleSheet->renderHtml()."\n";
		return $stylesheetHtml;
	}
	
	/**
	 * Application stylesheet url.
	 *
	 * @param string $href
	 * @return string
	 */
	public static function app_stylesheet_url($href) {
		return Config::get('site_url')
				.'/'.self::getApplicationsFolder()
				.'/'.self::getApplicationName().'/view/stylesheets/'.$href;
	}
	
	/**
	 * Global Module stylesheets.
	 *
	 * @param string $href
	 * @param string $module
	 * @return string
	 */
	public static function module_stylesheet_url($href,$module=null) {
		if($module == null) {
			$module = Context::getHttpRequest()->getController();
		}
		return  Config::get('site_url')
					.'/'.self::getApplicationsFolder()
					.'/'.self::getApplicationName()
					.'/modules/'.$module.'/view/stylesheets/'.$href;
	}
	
	/**
	 * Application specific stylesheet.
	 * These stylesheets have to be stored in APP_FOLDER/view/css.
	 *
	 * @param String $href
	 * @param String $rel
	 * @param String $type
	 * @param String $media
	 * @return String
	 */
	public static function app_stylesheet($href,$rel='stylesheet',$type='text/css',$media='screen') {
		return self::stylesheet(self::app_stylesheet_url($href),$rel,$type,$media);
	}
	
	/**
	 * Theme stylesheet HTML code.
	 *
	 * @param String $href
	 * @param String $rel
	 * @param String $type
	 * @param String $media
	 * @return String
	 */
	public static function theme_stylesheet($href,$rel='stylesheet',$type='text/css',$media='screen') {
		$href = Config::get('site_url').'/templates/'
				.Config::get('templates_folder').'/stylesheets/'.$href;
		return self::stylesheet($href,$rel,$type,$media);
	}
	
	/**
	 * Meta content type tag <meta content="text/html; charset=utf-8" />
	 *
	 * @param String $content (ex: text/html)
	 * @param String $charset (ex: utf-8)
	 * @return String HTML output
	 */
	public static function meta_content_type($content='text/html',$charset='utf-8') {
		$metaContentType = HtmlHeaderFactory::createHeader('MetaContentType');
	
		$content = $content.'; charset='.$charset;
	
		$metaContentType->setAttribute('content',$content);
	
		return $metaContentType->renderHtml()."\n";
	}
	
	/**
	 * Meta keywords tag <meta name="keywords"... />
	 *
	 * @param String $keywords (separated by commas)
	 * @return String HTML output
	 */
	public static function meta_keywords($keywords='') {
		$metaKeywords = HtmlHeaderFactory::createHeader('metaKeywords');
		$metaKeywords->setAttribute('content',$keywords);
	
		return $metaKeywords->renderHtml()."\n";
	}
	
	/**
	 * Meta description tag
	 *
	 * @param String $description
	 * @return String HTML output
	 */
	public static function meta_description($description='') {
		$metaDescription = HtmlHeaderFactory::createHeader('metaDescription');
	
		// Cleaning accents
		$description = str_replace('"','',$description);
		$description = str_replace("'",'',$description);
	
		$metaDescription->setAttribute('content',$description);
	
		return $metaDescription->renderHtml()."\n";
	}
	
	/**
	 * Script Html tag <script ... ></script>
	 *
	 * @param String $type (ex: text/javascript)
	 * @param String $src (ex: js/script.js)
	 * @return String Html output
	 */
	protected static function javascript($type='text/javascript',$src='') {
		$javascriptHeader = HtmlHeaderFactory::createHeader('Javascript');
	
		$attributes = array(
			'type'	=> $type,
			'src'	=> $src
		);
	
		$javascriptHeader->setAttributes($attributes);
	
		return $javascriptHeader->renderHtml()."\n";
	}
	
	/**
	 * Application Javascript full url.
	 *
	 * @param String $src
	 * @return String
	 */
	public static function app_javascript_url($src='') {
		return Config::get('site_url')
				.'/'.self::getApplicationsFolder()
				.'/'.self::getApplicationName()
				.'/view/javascript/'.$src;
	}
	
	/**
	 * Application javascript file <script ...></script> HTML code.
	 *
	 * @param String $type
	 * @param String $src
	 * @return String
	 */
	public static function app_javascript($src='',$type='text/javascript') {
		return self::javascript($type,self::app_javascript_url($src));
	}
	
	/**
	 * Theme javascript file HTML code.
	 *
	 * @param String $type
	 * @param String $src
	 * @return String
	 */
	public static function theme_javascript($type='text/javascript',$src='') {
		$src = Config::get('site_url')
				.'/templates/'.Config::get('templates_folder').'/javascript/'.$src;
		return self::javascript($type,$src);
	}
	
	/**
	 * Module javascript url.
	 *
	 * @param string $src
	 * @return string
	 */
	public static function module_javascript_url($src) {
		return Config::get('site_url')
						.'/'.self::getApplicationsFolder()
						.'/'.self::getApplicationName()
						.'/modules/'.Context::getHttpRequest()->getController()
						.'/view/javascript/'.$src;
	}
	
	/**
	 * Theme javascript file HTML code.
	 *
	 * @param String $type
	 * @param String $src
	 * @return String
	 */
	public static function module_javascript($src='',$type='text/javascript') {
		return self::javascript($type,self::module_javascript_url($src));
	}
	
	/**
	* Returns rendered dynamic headers. These headers are added through
	* PHP code in Controller logic. Dynamic headers are helpful to allow loading
	* headers only when needed.
	* How to add dynamic header in PHP code ? (not template code)
	* Ex : HtmlHeaders::getInstance()->addHeader(new LinkHeader(...));
	* @return String
	*/
	public static function dynamic_headers() {
		return HtmlHeaders::getInstance()->renderHtmlHeaders();
	}
}

?>