<?php

/**
 * When a controller and action is invoked, 
 * then they are stacked in action stack.
 * Action stack has been designed initially to avoid
 * endless loops.
 * 
 * @author Jerome Loisel
 */
class ExecutionStack extends Singleton {
	
	/**
	 * Execution stack.
	 *
	 * @var array
	 */
	protected $executionStack = array();
	
	/**
	 * The maximum stack size.
	 *
	 * @var integer
	 */
	protected $maxStackSize = null;
	
	/**
	 * Controller action cannot forward execution more
	 * than this constant value.
	 *
	 */
	const MAX_STACK_SIZE = 10;
	
	protected function __construct() {
		parent::__construct();
		$this->init();
	}
	
	protected function init() {
		$this->maxStackSize = self::MAX_STACK_SIZE;
	}
	
	/**
	 * Pushes the current controller and action into 
	 * the execution stack.
	 *
	 */
	public function push() {
		if(count($this->executionStack) == $this->maxStackSize) {
			throw new BaseException(
				'<p>Max ActionStack size has been reached ('.$this->maxStackSize.'), execution stack :</p>'
				.$this->dump());
		}
		$this->executionStack[] = new ExecutionStackElement(
			Context::getHttpRequest()->getController(), 
			Context::getHttpRequest()->getAction()
		);
	}
		
	/**
	 * Dumps the execution stack in HTML format.
	 *
	 * @return string
	 */
	public function dump() {
		$dump = '';
		if(is_array($this->executionStack)) {
			foreach($this->executionStack as $execStackElt) {
				$dump .= $execStackElt->dump().'<br />'."\n";
			}
		}
		return $dump;
	}
	
	/**
	 * Outputs this object.
	 *
	 */
	public function __toString() {
		echo $this->dump();
	}
	
	/**
	 * Returns the unique instance of the Action stack.
	 *
	 * @return ExecutionStack
	 */
	public static function getInstance() {
		return parent::getInstance(__CLASS__);
	}
}

?>