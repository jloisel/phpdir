<?php

/**
 *	Template Engine.
 *	@package	View
 *	@author		Jerome Loisel
 */
class TemplateEngine extends Savant3 {
	/**
	 * Partials are always named the same way. 
	 *
	 */
	const PARTIAL_NAMING_FORMAT = '_%s.php';
	
	/**
	 * Templates are always named the same way.
	 *
	 */
	const TEMPLATE_NAMING_FORMAT = '%s.php';
	
	/**
	 * Class in which components actions are defined, 
	 * when the actions are grouped in a single file.
	 *
	 */
	const COMPONENTS_CONTROLLER = 'components';
	
	/**
	 * 	Static instance.
	 *	@var TemplateEngine
	 */
	protected static $_instance = null;
	
	/**
	 * The controller invoker used for global application components.
	 * The global components are within the main application, whereas 
	 * module components are within a module.
	 *
	 * @var ControllerInvoker
	 */
	protected $globalComponentsControllerInvoker = null;
	
	/**
	 * The template to display, within layout.
	 *
	 * @var string
	 */
	protected $_template = null;
	
	/**
	 * Default constructor. PHPSavant configuration can be passed.
	 *
	 * @param Array $_config
	 */
	public function __construct() {
		parent::__construct ();
	}
	
	/**
	 * Returns the absolut path of the partial.
	 *
	 * @param String $partialName
	 * @return String
	 */
	public function partial($partialName) {
		return parent::findFile( sprintf ( self::PARTIAL_NAMING_FORMAT, strtolower ( $partialName ) ) );
	}
	
	/**
	 * Load a component from view.
	 *
	 * @param String $component
	 * @return String (processed component HTML content)
	 */
	public function component($action) {
		$this->getComponentsControllerInvoker ()->invoke ( self::COMPONENTS_CONTROLLER, $action );
		return $this->partial ( $action );
	}
	
	/**
	 * Returns the components controller invoker.
	 *
	 * @return ControllerInvoker
	 */
	protected function getComponentsControllerInvoker() {
		if ($this->globalComponentsControllerInvoker == null) {
			$this->globalComponentsControllerInvoker = new ControllerInvoker ( );
			
			$ph = Context::getParameterHolder();
			$this->globalComponentsControllerInvoker->setActionsFolder ( 
				SCRIPT_ROOT_PATH
					.DIRECTORY_SEPARATOR.$ph->getApplicationsFolder()
					.DIRECTORY_SEPARATOR.$ph->getApplicationName()
					.DIRECTORY_SEPARATOR.'components'
			);
		}
		return $this->globalComponentsControllerInvoker;
	}
	
	/**
	 * Builds the name of the template associated to
	 * the Controller and the Action that have been loaded.
	 *
	 * @return String
	 */
	protected function getTemplateName() {
		if ($this->_template == null) {
			$this->_template = sprintf ( self::TEMPLATE_NAMING_FORMAT, Context::getHttpRequest ()->getAction () );
		}
		return $this->_template;
	}
	
	/**
	 * Displays the layout content.
	 *
	 * @param String $layout
	 */
	public function display($layout = 'layout.php') {
		if ($layout != null && ! empty ( $layout )) {
			$templateName = $this->getTemplateName ();
			$content = $this->fetch ( $templateName );
			$this->assign ( 'content', $content );
			return parent::display ( $layout );
		}
		return null;
	}
	
	/**
	 * Sets the content template to display.
	 *
	 * @param String $tpl
	 */
	public function setTemplate($tpl) {
		$this->_template = $tpl;
	}
	
	public function getController() {
		return Context::getController();
	}
	
	public function getAction() {
		return Context::getAction();
	}
	
	/**
	 *	Returns single instance of template engine
	 * 	@param array
	 *	@return	TemplateEngine
	 */
	public static function getInstance($_config = null) {
		if (! isset ( self::$_instance )) {
			self::$_instance = new TemplateEngine ( $_config );
		}
		return self::$_instance;
	}
	
	/**
	 * 	New instance of template engine
	 * 	@return TemplateEngine
	 */
	public static function newInstance() {
		$reflectionClass = new ReflectionClass ( __CLASS__ );
		return $reflectionClass->newInstance ();
	}
}

?>