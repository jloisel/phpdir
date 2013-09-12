<?php

/**
 * Application context.
 *
 * @author Jerome Loisel
 * @package Kernel
 */
final class Context {

	/**
	 * Returns the unique instance of the
	 * Front Controller.
	 *
	 * @return FrontController
	 */
	public static function getFrontController() {
		return FrontController::getInstance();
	}

	/**
	 * Http Request unique instance.
	 *
	 * @return MyHttpRequest
	 */
	public static function getHttpRequest() {
		return MyHttpRequest::getInstance();
	}

	/**
	 * Http Response unique instance.
	 *
	 * @return MyHttpResponse
	 */
	public static function getHttpResponse() {
		return MyHttpResponse::getInstance();
	}

	/**
	 * Template Engine unique instance.
	 *
	 * @return TemplateEngine
	 */
	public static function getTemplateEngine() {
		return TemplateEngine::getInstance();
	}

	/**
	 * Router unique instance.
	 *
	 * @return Router
	 */
	public static function getRouter() {
		return Router::getInstance();
	}

	/**
	 * Database connection unique instance.
	 *
	 * @return Doctrine_Connection
	 */
	public static function getDatabaseConnection() {
		return DBWrapper::getDatabaseConnection();
	}

	/**
	 * Application configuration loader
	 *
	 * @return AppConfigLoader
	 */
	public static function getAppConfigLoader() {
		return AppConfigLoader::getInstance();
	}

	/**
	 * Core configuration loader.
	 *
	 * @return GlobalConfigLoader
	 */
	public static function getGlobalConfigLoader() {
		return GlobalConfigLoader::getInstance();
	}

	/**
	 * Module Configuration loader. Module name is mandatory.
	 *
	 * @return ModuleConfigLoader
	 */
	public static function getModuleConfigLoader() {
		return ModuleConfigLoader::getInstance();
	}

	/**
	 * Dynamic Html headers. These headers must be set by the Controller.
	 * It makes able to define HTML headers on the fly, only when needed.
	 * Example of use : Load an heavy Javascript header only on the pages
	 * that need this javascript code.
	 *
	 * @return HtmlHeaders
	 */
	public static function getDynamicHtmlHeaders() {
		return HtmlHeaders::getInstance();
	}

	/**
	 * Unique instance of the User.
	 *
	 * @return User
	 */
	public static function getUser() {
		return User::getInstance();
	}
	
	/**
	 * Unique instance of the credentials manager.
	 *
	 * @return CredentialsManager
	 */
	public static function getCredentialsManager() {
		return CredentialsManager::getInstance();
	}
	
	/**
	 * Unique instance of the session.
	 *
	 * @return Session
	 */
	public static function getSession() {
		return Session::getInstance();
	}
	
	/**
	 * The parameter holder holds global application 
	 * parameters.
	 *
	 * @return ParameterHolder
	 */
	public static function getParameterHolder() {
		return ParameterHolder::getInstance();
	}
	
	/**
	 * Returns the current controller name.
	 *
	 * @return string
	 */
	public static function getController() {
		return self::getHttpRequest()->getController();
	}
	
	/**
	 * Returns the current action.
	 *
	 * @return string
	 */
	public static function getAction() {
		return self::getHttpRequest()->getAction();
	}
}

?>