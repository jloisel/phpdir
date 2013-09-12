<?php

/**
 * Add, remove, ban and unban keywords.
 *
 * @author Jerome Loisel
 */
class KeywordActions extends DefaultController {
	
	/**
	 * Form possible actions.
	 *
	 */
	const REMOVE_ACTION = 'remove_action';
	const BAN_ACTION = 'ban_action';
	const UNBAN_ACTION = 'unban_action';
	const FILTER_ACTION = 'filter_action';
	const RESET_FILTER_ACTION = 'reset_filter_action';
	const ADD_ACTION = 'add_action';
	
	const CURRENT_PAGE_ATTR = 'keyword_current_page';
	
	/**
	 * Add keyword form.
	 *
	 * @var KeywordAddForm
	 */
	protected static $addForm = null;
	
	/**
	 * Initializes the forms.
	 *
	 */
	public function preExecute() {
		$this->filterForm = new KeywordFilterForm();
		if(self::$addForm == null) {
			self::$addForm = new KeywordAddForm();
		}
		$this->addForm = self::$addForm;
	}
	
	/**
	 * Current viewed page.
	 *
	 * @return integer
	 */
	private function getCurrentPage() {
		return $this->getParameter('id',$this->getUser()->getAttribute(self::CURRENT_PAGE_ATTR,0));
	}
	
	/**
	 * Sets the current page into user session.
	 *
	 * @param integer $currentPage
	 */
	private function setCurrentPage($currentPage) {
		$this->getUser()->setAttribute(self::CURRENT_PAGE_ATTR,$currentPage);
	}
	
	/**
	 * Returns the filters: first from request, then if none, from session.
	 *
	 * @return array
	 */
	private function getFilters() {
		return $this->getParameter('filter',$this->getUser()->getAttribute('filter',array()));
	}
	
	/**
	 * Returns the parameter value associated to the 
	 * passed name.
	 * First retrieve from request, then from session, if none, 
	 * return default value.
	 *
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return string
	 */
	private function getFilter($name, $defaultValue=null) {
		$filters = $this->getFilters();
		return isset($filters[$name]) ? $filters[$name] : $defaultValue;
	}
	
	/**
	 * Returns the order type. (ASC or DESC)
	 *
	 * @return string
	 */
	private function getOrderType() {
		return $this->getFilter(
			KeywordFilterForm::FILTER_ORDER_TYPE,
			KeywordFilterForm::DEFAULT_ORDER_TYPE
		);
	}
	
	/**
	 * Returns the order column.
	 *
	 * @return string
	 */
	private function getOrderColumn() {
		return $this->getFilter(
			KeywordFilterForm::FILTER_ORDER_COLUMN,
			KeywordFilterForm::DEFAULT_ORDER_COLUMN
		);
	}
	
	/**
	 * Returns filter on keyword status. (banned or not banned)
	 *
	 * @return boolean
	 */
	private function isBanned() {
		return $this->getFilter(
			KeywordFilterForm::FILTER_IS_BANNED,
			KeywordFilterForm::DEFAULT_IS_BANNED
		);
	}
	
	/**
	 * Returns the text used to filter keywords.
	 *
	 * @return string
	 */
	private function getFilterText() {
		return $this->getFilter(
			KeywordFilterForm::FILTER_TEXT,
			KeywordFilterForm::DEFAULT_FILTER_TEXT
		);
	}
	
	/**
	 * Query retrieving the keywords.
	 *
	 * @return Doctrine_Query
	 */
	private function getQuery() {
		$q = Doctrine::getTable('Keyword')
						->createQuery()
						->orderBy($this->getOrderColumn()
							.' '.$this->getOrderType());
		$is_banned = $this->isBanned();
		if($is_banned == 0 || $is_banned == 1) {
			$q->addWhere('is_banned=?',intval($is_banned));
		}
		$filter_text = $this->getFilterText();
		if(is_string($filter_text) && !empty($filter_text)) {
			$q->addWhere('text LIKE ?','%'.$filter_text.'%');
		}
		return $q;
	}
	
	/**
	 * Assigns the pager and items to the template engine.
	 *
	 * @param string $tableName
	 * @param string $fieldName
	 */
	private function assignContent() {
		$this->pager = DbHelper::pagined(
			$this->getQuery(),
			$this->getCurrentPage(),
			UrlHelper::routed_url(
				Route::CONTROLLER_ACTION,
				array(
					'controller' => $this->getController(),
					'action' => $this->getAction()
				)
			)
		);
		
		$this->keywords = $this->pager->execute(array(),Doctrine::HYDRATE_ARRAY);
	}
	
	/**
	 * Is the user requesting a filter update ?
	 *
	 * @return boolean
	 */
	private function isFilterAction() {
		return $this->getParameter(self::FILTER_ACTION) != null;
	}
	
	/**
	 * Is the user requesting a filter update ?
	 *
	 * @return boolean
	 */
	private function isResetFilterAction() {
		return $this->getParameter(self::RESET_FILTER_ACTION) != null;
	}
	
	/**
	 * User is requesting a remove.
	 *
	 * @return boolean
	 */
	private function isRemoveAction() {
		return $this->getParameter(self::REMOVE_ACTION) != null;
	}
	
	/**
	 * Ban action requested.
	 *
	 * @return boolean
	 */
	private function isBanAction() {
		return $this->getParameter(self::BAN_ACTION) != null;
	}
	
	/**
	 * Unban action requested.
	 *
	 * @return boolean
	 */
	private function isUnbanAction() {
		return $this->getParameter(self::UNBAN_ACTION) != null;
	}
	
	/**
	 * Saves filters into user session.
	 *
	 */
	private function saveFilters($values) {
		$this->getUser()->setAttribute('filter',$values);
	}
	
	/**
	 * Resets remembered filters.
	 *
	 */
	private function resetFilters() {
		$this->saveFilters(null);
		Context::getHttpRequest()->setParameter('filter',null);
		$this->setCurrentPage(0);
	}
	
	/**
	 * Adds the keyword into the database.
	 *
	 * @param array $values
	 */
	private function handleAddKeyword($values) {
		$keyword = new Keyword();
		$keyword->text = $values['text'];
		$keyword->count = 0;
		$keyword->save();
	}
	
	/**
	 * Updates the ban status of the database keywords.
	 *
	 * @param array $keywords
	 * @param integer $is_banned
	 */
	private function handleBan($keywords, $is_banned) {
		if(is_array($keywords) && count($keywords) > 0) {
			foreach($keywords as $id => $value) {
				if($value != 'on') continue;
					Doctrine_Query::create()
							->update('Keyword')
							->where('id=?',$id)
							->set('is_banned','?',$is_banned)
							->execute();
			}
		}
	}
	
	/**
	 * Removes keywords from database.
	 *
	 * @param array $keywords
	 */
	private function handleRemove($keywords) {
		if(is_array($keywords) && count($keywords) > 0) {
			foreach($keywords as $id => $value) {
				if($value != 'on') continue;
				
				Doctrine::getTable('Keyword')
					->createQuery()
					->where('id=?',intval($id))
					->delete()
					->execute();
			}
		}
	}
	
	/**
	 * Add a new keyword.
	 *
	 * @return integer
	 */
	public function executeAdd() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			$values = $this->getParameter('add');
			$this->addForm->bind($values);
			if($this->addForm->isValid()) {
				$this->handleAddKeyword($values);
			}
		}
		$this->forward($this->getController(),'index');
		return View::NONE;
	}
	
	/**
	 * Update keywords action.
	 *
	 */
	public function executeUpdate() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			$keywords = $this->getParameter('keyword',array());
			if($this->isBanAction()) {
				$this->handleBan($keywords,Keyword::BANNED);
			} else if($this->isUnbanAction()) {
				$this->handleBan($keywords,Keyword::NOT_BANNED);
			} else if($this->isRemoveAction()) {
				$this->handleRemove($keywords);
			}
		}
		$this->forward($this->getController(),'index');
		return View::NONE;
	}
	
	/**
	 * Filter action.
	 *
	 * @return integer
	 */
	public function executeFilter() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			if($this->isFilterAction()) {	
				$filters = $this->getFilters();
				$this->filterForm->bind($filters);
				if($this->filterForm->isValid()) {
					$this->saveFilters($filters);
				}
			} else if($this->isResetFilterAction()) {
				$this->resetFilters();
			}
		}
		$this->forward($this->getController(),'index');
		return View::NONE;
	}
	
	/**
	 * Default action.
	 *
	 * @return integer
	 */
	public function executeIndex() {
		// Assign keywords to the template
		$this->assignContent();
		// Initialize form default values
		$this->filterForm->setDefaults(array(
			KeywordFilterForm::FILTER_ORDER_TYPE => $this->getOrderType(), 
			KeywordFilterForm::FILTER_ORDER_COLUMN => $this->getOrderColumn(),
			KeywordFilterForm::FILTER_IS_BANNED => $this->isBanned(), 
			KeywordFilterForm::FILTER_TEXT => $this->getFilterText()
		));
		// Initializes the current page
		$this->setCurrentPage($this->getCurrentPage());
		return View::TPL;
	}
	
}

?>