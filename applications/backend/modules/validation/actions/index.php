<?php

class IndexAction extends DefaultController {
	
	const CURRENT_PAGE_PARAMETER = 'id';
	
	const WEBSITE_COUNT_PER_PAGE = 10;
	
	/**
	 * Website actions.
	 */
	const VALIDATE_WEBSITE_ACTION = 'validate';
	const DELETE_WEBSITE_ACTION = 'delete';
	
	public function preExecute() {
		parent::preExecute();
		$this->tpl->addPath(Context::getParameterHolder()->getApplicationPath().'/modules/content/view');
	}
	
	protected function getCurrentPage() {
		return intval($this->getParameter(
			self::CURRENT_PAGE_PARAMETER,
			$this->getUser()->getAttribute(self::CURRENT_PAGE_PARAMETER,0))
		);
	}
	
	protected function applyUserAction() {
		$action = $this->getParameter('action');
		if($action != null) {
			
		}
	}
	
	public function executeAction() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			$this->applyUserAction();
		}
		
		$q = Doctrine::getTable('Website')->createQuery();
		$q	->where('website.state=?',array(Website::STATE_PENDING));
		
		$this->pager = DbHelper::pagined(
			$q, 
			$this->getCurrentPage(), 
			UrlHelper::routed_url(
				Route::CONTROLLER_ACTION,
				array('controller' => $this->getController(), 'action' => $this->getAction())
			),
			self::WEBSITE_COUNT_PER_PAGE
		);
		$this->websites = $this->pager->execute(array(),Doctrine::HYDRATE_ARRAY);
		
		return View::TPL;
	}
	
}

?>