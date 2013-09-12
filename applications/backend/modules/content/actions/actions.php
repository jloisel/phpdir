<?php

class ContentActions extends DefaultController {
	
	/**
	 * Category actions.
	 */
	const ADD_CATEGORY_ACTION = 'add_category';
	const EDIT_CATEGORY_ACTION = 'edit_category';
	
	/**
	 * Website actions.
	 */
	const ADD_WEBSITE_ACTION = 'add_website';
	const EDIT_WEBSITE_ACTION = 'edit_website';
	
	/**
	 * A set of categories can be cutted, copied, pasted 
	 * or deleted.
	 */
	const CUT_CATEGORY_ACTION = 'cut_category';
	const COPY_CATEGORY_ACTION = 'copy_category';
	const PASTE_CATEGORY_ACTION = 'paste_category';
	const DELETE_CATEGORY_ACTION = 'delete_category';
	
	/**
	 * A set of websites can be cutted, copied, pasted 
	 * or deleted.
	 */
	const CUT_WEBSITE_ACTION = 'cut_website';
	const COPY_WEBSITE_ACTION = 'copy_website';
	const PASTE_WEBSITE_ACTION = 'paste_website';
	const DELETE_WEBSITE_ACTION = 'delete_website';
	
	/**
	 * Website feeds action.
	 */
	const ADD_WEBSITE_FEED_ACTION = 'add_website_feed';
	const DELETE_WEBSITE_FEED_ACTION = 'delete_website_feed';
	
	/**
	 * Parent category id attribute.
	 */
	const CATEGORY_PARENT_ID_ATTR = 'content_cat_parent_id';
	
	const CATEGORIES_WORK = 'content_categories_work';
	const WEBSITES_WORK = 'content_website_work';
	
	/**
	 * When editing a website or a category, 
	 * it opens a Javascript Box using Jquery 
	 * and Thickbox.
	 */
	const THICKBOX_WIDTH = 800;
	const THICKBOX_HEIGHT= 600;
	
	/**
	 * Parent category.
	 *
	 * @var Category
	 */
	private $parentCategory = null;
	
	/**
	 * Parent category ID.
	 *
	 * @var integer
	 */
	private $parentCategoryId = null;
	
	/**
	 * Is the parent category an adult category?
	 *
	 * @var boolean
	 */
	private $isParentAdult = null;
	
	/**
	 * Initializes the category form.
	 */
	public function preExecute() {
		$this->initCategoryForm();
		$this->initWebsiteForm();
	}
	
	/**
	 * Returns the parent category.
	 *
	 * @return Category
	 */
	private function getParentCategory() {
		if($this->parentCategory == null) {
			$this->parentCategory = Doctrine::getTable('Category')->find($this->getParentCategoryId());
			$this->tpl->assign('parentCategory',$this->parentCategory);
		}
		return $this->parentCategory;
	}
	
	/**
	 * Is the parent category an adult category?
	 *
	 * @return boolean
	 */
	private function isParentAdult() {
		if($this->isParentAdult == null) {
			$this->isParentAdult = false;
			$parentCategory = $this->getParentCategory();
			if(is_object($parentCategory)) {
				 $this->isParentAdult = $parentCategory->is_adult;
			}
		}
		return $this->isParentAdult;
	}
	
	/**
	 * Initializes the category form.
	 *
	 * @param boolean $isAdult
	 */
	private function initCategoryForm() {
		$this->categoryForm = new CategoryForm($this->isParentAdult());
	}
	
	private function initWebsiteForm() {
		$this->websiteForm = new WebsiteForm($this->getParentCategoryId());
	}
	
	/**
	 * Returns the parent category id.
	 *
	 * @return integer
	 */
	private function getParentCategoryId() {
		if($this->parentCategoryId == null) {
			$this->parentCategoryId = intval($this->getParameter('id',$this->getUser()->getAttribute(self::CATEGORY_PARENT_ID_ATTR,0)));
		}
		return $this->parentCategoryId;
	}
	
	/**
	 * Sets the parent category id.
	 *
	 * @param integer $parentCategoryId
	 */
	private function setParentCategoryId($parentCategoryId) {
		$this->getUser()->setAttribute(self::CATEGORY_PARENT_ID_ATTR,$parentCategoryId);
	}
	
	private function isAddCategoryAction() {
		return $this->getParameter(self::ADD_CATEGORY_ACTION) != null;
	}
	
	private function isCutCategoriesAction() {
		return $this->getParameter(self::CUT_CATEGORY_ACTION) != null;
	}
	
	private function isCopyCategoriesAction() {
		return $this->getParameter(self::COPY_CATEGORY_ACTION) != null;
	}
	
	private function isPasteCategoriesAction() {
		return $this->getParameter(self::PASTE_CATEGORY_ACTION) != null;
	}
	
	private function isDeleteCategoriesAction() {
		return $this->getParameter(self::DELETE_CATEGORY_ACTION) != null;
	}
	
	private function isAddWebsiteAction() {
		return $this->getParameter(self::ADD_WEBSITE_ACTION) != null;
	}
	
	private function isEditWebsiteAction() {
		return $this->getParameter(self::EDIT_WEBSITE_ACTION) != null;
	}
	
	private function isCutWebsiteAction() {
		return $this->getParameter(self::CUT_WEBSITE_ACTION) != null;
	}
	
	private function isCopyWebsiteAction() {
		return $this->getParameter(self::COPY_WEBSITE_ACTION) != null;
	}
	
	private function isPasteWebsiteAction() {
		return $this->getParameter(self::PASTE_WEBSITE_ACTION) != null;
	}
	
	private function isDeleteWebsiteAction() {
		return $this->getParameter(self::DELETE_WEBSITE_ACTION) != null;
	}
	
	private function isAddWebsiteFeedAction() {
		return $this->getParameter(self::ADD_WEBSITE_FEED_ACTION) != null;
	}
	
	private function isDeleteWebsiteFeedAction() {
		return $this->getParameter(self::DELETE_WEBSITE_FEED_ACTION) != null;
	}
	
	/**
	 * Adds a new category to database.
	 */
	private function addCategory() {
		$this->editCategory(new Category());
	}
	
	/**
	 * Edits a category.
	 *
	 * @param Category $category
	 */
	private function editCategory(Category $category) {
		$params = $this->categoryForm->getValues();
		$category->title = $params[CategoryForm::TITLE_FIELD];
		$category->category_id = $params[CategoryForm::CATEGORY_ID_FIELD];
		$category->description = $params[CategoryForm::DESCRIPTION_FIELD];
		$category->allow_submit = intval($params[CategoryForm::ALLOW_SUBMIT_FIELD]);
		$category->is_adult = intval($params[CategoryForm::IS_ADULT_FIELD]);
		$category->save();
	}
	
	/**
	 * Category edition entry point.
	 *
	 * @return integer
	 */
	public function executeEditCategory() {
		// Iframe Layout
		$this->getHttpResponse()->setLayout('iframe_layout.php');
		
		$category = Doctrine::getTable('Category')
					->find(intval($this->getParameter('id',0)));
		
		// Edit an existing category
		if($this->getRequestMethod() == RequestMethod::POST) {
			$this->categoryForm->bind($this->getParameter('category'));
			if($this->categoryForm->isValid()) {
				$this->successCategory = true;
				$this->editCategory($category);
			}
		}
		$this->categoryForm->bind($category->getData());
		return View::TPL;
	}
	
	/**
	 * Adds a new website feed.
	 */
	private function addWebsiteFeed($website_id) {
		if($website_id != 0) {
			$data = $this->feedForm->getValues();
			$feed = new Feed();
			$feed->website_id = $website_id;
			$feed->title = $data['title'];
			$feed->link = $data['link'];
			$feed->save();
		}
	}
	
	/**
	 * Deletes the website feed.
	 * 
	 * @param $website_id
	 */
	private function deleteWebsiteFeed($website_id) {
		if($website_id != 0) {
			$feed_id = intval($this->getParameter('feed_id',0));
			$q = Doctrine::getTable('Feed')->createQuery();
			$q	->delete()
				->where('id=? AND website_id=?',array($feed_id,$website_id))
				->limit(1)
				->execute();
		}
	}
	
	/**
	 * Website feeds page.
	 *
	 * @return integer
	 */
	public function executeEditWebsiteFeeds() {
		// Iframe Layout
		$this->getHttpResponse()->setLayout('iframe_layout.php');
		
		$this->feedForm = new FeedForm();
		$website_id = intval($this->getParameter('id',0));
		$this->website = Doctrine::getTable('Website')->find($website_id,array(),Doctrine::HYDRATE_ARRAY);
		
		if($this->getRequestMethod() == RequestMethod::POST) {
			if($this->isAddWebsiteFeedAction()) {
				$this->feedForm->bind($this->getParameter('feed'));
				if($this->feedForm->isValid()) {
					$this->addWebsiteFeed($website_id);
					$this->success = true;
					$this->feedForm = new FeedForm();
				}
			} else if($this->isDeleteWebsiteFeedAction()) {
				$this->deleteWebsiteFeed($website_id);
				$this->success = true;
			}
		}
		
		$this->feeds = Doctrine::getTable('Feed')->createQuery()
							->where('website_id=?',$website_id)
							->execute(array(), Doctrine::HYDRATE_ARRAY);
		
		return View::TPL;
	}
	
	/**
	 * Edits a website.
	 *
	 * @return integer
	 */
	public function executeEditWebsite() {
		// Iframe Layout
		$this->getHttpResponse()->setLayout('iframe_layout.php');
	
		$q = new Doctrine_Query();
		
		$q	->select('w.*')
			->from('Website w')
			->where('w.id=?',intval($this->getParameter('id',0)))
			->limit(1);
		$this->website = $q->fetchOne();
		
		// Edit an existing website
		if($this->getRequestMethod() == RequestMethod::POST) {
			if($this->isEditWebsiteAction()) {
				$this->websiteForm->bind($this->getParameter('website'));
				if($this->websiteForm->isValid()) {
					$this->successWebsite = true;
					$this->editWebsite($this->website);
				}
			}
		}
		
		$data = $this->website->getData();
		$data[WebsiteForm::TAGS_FIELD] = $this->website->getTagsAsString();
		$this->websiteForm->bind($data);
		return View::TPL;
	}
	
	/**
	 * Assigns the categories to the template.
	 */
	private function assignCategories() {
		$q = Doctrine::getTable('Category')->createQuery();		
			
		$categoriesToIgnore = $this->getCategoriesWork();
		if(isset($categoriesToIgnore['action']) && $categoriesToIgnore['action'] == self::CUT_CATEGORY_ACTION) {
			if(is_array($categoriesToIgnore) && count($categoriesToIgnore) > 0) {
				$q->whereNotIn('id',array_values($categoriesToIgnore));
			}
		}
		$q->addWhere('category_id=?',$this->getParentCategoryId());
		$this->categories = $q->execute(array(),Doctrine::HYDRATE_ARRAY);
	}
	
	/**
	 * Assigns the websites to the template.
	 */
	private function assignWebsites() {
		$q = new Doctrine_Query();
		$q	->select('w.*, t.title, c.email')
			->from('Website w')
			->leftJoin('w.Tags t')
			->leftJoin('w.Customer c')
			->addWhere('w.category_id=? AND w.state=?',array($this->getParentCategoryId(),Website::STATE_ACCEPTED));
		
		$websitesToIgnore = $this->getWebsitesWork();
		if(isset($websitesToIgnore['action']) && $websitesToIgnore['action'] == self::CUT_WEBSITE_ACTION) {
			if(is_array($websitesToIgnore) && count($websitesToIgnore) > 0) {
				$q->whereNotIn('id',array_values($websitesToIgnore));
			}
		}

		$this->websites = $q->execute(array(),Doctrine::HYDRATE_ARRAY);
	}
	
	/**
	 * Sets the temporary selected categories which 
	 * are going to be stored in user session (for cut, copy purpose)
	 *
	 * @param array $work
	 */
	private function setCategoriesWork($work) {
		$this->getUser()->setAttribute(self::CATEGORIES_WORK,$work);
	}
	
	/**
	 * Returns the temporary selected categories which 
	 * have been stored in user session (for cut, copy purpose)
	 *
	 * @return mixed (array or null)
	 */
	private function getCategoriesWork() {
		return $this->getUser()->getAttribute(self::CATEGORIES_WORK);
	}
	
	private function setWebsitesWork($work) {
		$this->getUser()->setAttribute(self::WEBSITES_WORK,$work);
	}
	
	private function getWebsitesWork() {
		return $this->getUser()->getAttribute(self::WEBSITES_WORK);
	}
	
	/**
	 * Applies the user action (cut, copy) to the 
	 * selected categories.
	 *
	 * @param string $action
	 */
	private function applyCategoryUserActions($action) {
		$work = array('action' => $action);
		$categories = $this->getParameter('category');
		if($categories != null && is_array($categories) && count($categories) > 0) {
			foreach($categories as  $id) {
				$work[] = $id;
			}
		}
		if(count($work) > 1) {
			// 	Store selected categories and action in session
			$this->setCategoriesWork($work);
		}
	}
	
	/**
	 * Applies the user action (cut, copy) to the 
	 * selected websites.
	 *
	 * @param string $action
	 */
	private function applyWebsiteUserActions($action) {
		$work = array('action' => $action);
		$websites = $this->getParameter('website');
		if($websites != null && is_array($websites) && count($websites) > 0) {
			foreach($websites as  $id) {
				$work[] = $id;
			}
		}
		if(count($work) > 1) {
			// Store selected websites and action in session
			$this->setWebsitesWork($work);
		}
	}
	
	/**
	 * Applies the Paste action on the categories stored in session.
	 */
	private function applyCategoryPasteUserAction() {
		$work = $this->getCategoriesWork();
		if($work == null || !is_array($work)) return;
		
		$action = null;
		if(isset($work['action'])) {
			$action = $work['action'];
			unset($work['action']);
		}
		if($action == null) return;
		
		$categories = Doctrine::getTable('Category')
						->createQuery()
						->whereIn('id',array_values($work))
						->execute();
		if(is_object($categories) && count($categories) > 0) {
			$newParentCategoryId = $this->getParentCategoryId();
			
			switch ($action) {
				case self::CUT_CATEGORY_ACTION:
					foreach($categories as $category) {
						$category->category_id = $newParentCategoryId;
					}
					$categories->save();
					break;
				
				case self::COPY_CATEGORY_ACTION;
					$copies = new Doctrine_Collection(Doctrine::getTable('Category'));
					foreach($categories as $category) {
						$copy = $category->copy();
						$copy->category_id = $newParentCategoryId;
						$copies[] = $copy;
					}
					$copies->save();
					break;
			}
		}
		
		// Reset categories work
		$this->setCategoriesWork(null);
	}
	
	/**
	 * Applies the Paste action on the websites stored in session.
	 */
	private function applyWebsitePasteUserAction() {
		$work = $this->getWebsitesWork();
		if($work == null || !is_array($work)) return;
		
		$action = null;
		if(isset($work['action'])) {
			$action = $work['action'];
			unset($work['action']);
		}
		if($action == null) return;
		
		$websites = Doctrine::getTable('Website')
						->createQuery()
						->whereIn('id',array_values($work))
						->execute();
		if(is_object($websites) && count($websites) > 0) {
			$newParentCategoryId = $this->getParentCategoryId();
			
			switch ($action) {
				case self::CUT_WEBSITE_ACTION:
					foreach($websites as $website) {
						$website->category_id = $newParentCategoryId;
					}
					$websites->save();
					break;
				
				case self::COPY_WEBSITE_ACTION;
					$copies = new Doctrine_Collection(Doctrine::getTable('Website'));
					foreach($websites as $website) {
						$copy = $website->copy();
						$copy->category_id = $newParentCategoryId;
						$copies[] = $copy;
					}
					$copies->save();
					break;
			}
		}
		
		// Reset websites work
		$this->setWebsitesWork(null);
	}
	
	/**
	 * Deletes selected categories.
	 */
	private function deleteSelectedCategories() {
		$categories = $this->getParameter('category');
		if($categories != null && is_array($categories) && count($categories) > 0) {
			foreach($categories as  $id) {
				Doctrine::getTable('Category')->createQuery()
					->where('id=?',$id)
					->limit(1)
					->delete()
					->execute();
			}
		}
	}
	
	/**
	 * Deletes selected websites.
	 */
	private function deleteSelectedWebsites() {
		$websites = $this->getParameter('website');
		if($websites != null && is_array($websites) && count($websites) > 0) {
			foreach($websites as  $id) {
				Doctrine::getTable('Website')->createQuery()
					->where('id=?',$id)
					->limit(1)
					->delete()
					->execute();
			}
		}
	}
	
	/**
	 * Adds a new website to database.
	 */
	private function addWebsite() {
		$website = new Website();
		$website->state = Website::STATE_ACCEPTED;
		$website->validated_on = date('d-m-Y H:i:s');
		$this->editWebsite($website);
	}
	
	/**
	 * Edits the website fields.
	 *
	 * @param Website $website
	 */
	private function editWebsite(Website $website) {
		$params = $this->websiteForm->getValues();
		$website->link = $params[WebsiteForm::LINK_FIELD];
		$website->title = $params[WebsiteForm::TITLE_FIELD];
		$website->subtitle = $params[WebsiteForm::SUBTITLE_FIELD];
		$website->description = $params[WebsiteForm::DESCRIPTION_FIELD];
		$website->backlink = $params[WebsiteForm::BACKLINK_FIELD];
		$website->country = $params[WebsiteForm::COUNTRY_FIELD];
		$website->ins = $params[WebsiteForm::INS_FIELD];
		$website->outs = $params[WebsiteForm::OUTS_FIELD];
		$website->priority = $params[WebsiteForm::PRIORITY_FIELD];
		$website->category_id = $params[WebsiteForm::CATEGORY_ID_FIELD];
		$website->updated_on = DateHelper::to_date(time());
		$website->validated_on = DateHelper::to_date(time());
		$this->editWebsiteTags(intval($website->id), $params[WebsiteForm::TAGS_FIELD]);
		$website->save();
	}
	
	/**
	 * Edits website tags.
	 *
	 * @param integer $website_id
	 * @param array $tags
	 */
	private function editWebsiteTags($website_id, $submittedTags) {
		// Delete all 
		Doctrine::getTable('WebsiteHasTag')
			->createQuery()
			->where('website_id=?',$website_id)
			->delete()
			->execute();

		// Add the tags
		$submittedTags = !empty($submittedTags) ? explode(',',$submittedTags) : array();
		if(is_array($submittedTags) && count($submittedTags) > 0) {
			foreach($submittedTags as $submittedTag) {
				$tag = Doctrine::getTable('Tag')->createQuery()
						->where('title=?',$submittedTag)
						->limit(1)
						->fetchOne();
				
				$submittedTag = trim($submittedTag);
				if(!empty($submittedTag)) {
					$tag = Doctrine::getTable('Tag')->createQuery()
								->where('title=?',$submittedTag)
								->limit(1)
								->fetchOne();
					if(!is_object($tag)) {
						$tag = new Tag();
						$tag->title = $submittedTag;
						$tag->is_banned = 0;
						$tag->save();
	 				}
	
	 				$websiteHasTag = new WebsiteHasTag();
	 				$websiteHasTag->website_id = $website_id;
	 				$websiteHasTag->tag_id = $tag->id;
	 				$websiteHasTag->save();
				}
			}
		}
		
		// Remove orphain tags
		Tag::removeOrphainTags();
	}
	
	/**
	 * Main action entry point.
	 *
	 * @return integer
	 */
	public function executeIndex() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			if($this->isAddWebsiteAction()) {
				$this->websiteForm->bind($this->getParameter('website'));
				if($this->websiteForm->isValid()) {
					$this->addWebsite();
					$this->initWebsiteForm();
				}
			} else if($this->isCopyWebsiteAction()) {
				 $this->applyWebsiteUserActions(self::COPY_WEBSITE_ACTION);
			} else if($this->isCutWebsiteAction()) {
				$this->applyWebsiteUserActions(self::CUT_WEBSITE_ACTION);
			} else if($this->isPasteWebsiteAction()) {
				$this->applyWebsitePasteUserAction();
			} else if($this->isDeleteWebsiteAction()) {
				$this->deleteSelectedWebsites();
			}
			
			else if($this->isAddCategoryAction()) {
				$this->categoryForm->bind($this->getParameter('category'));
				if($this->categoryForm->isValid()) {
					$this->addCategory();
					$this->initCategoryForm($this->get);
				}
			} else if($this->isCopyCategoriesAction()) {
				 $this->applyCategoryUserActions(self::COPY_CATEGORY_ACTION);
			} else if($this->isCutCategoriesAction()) {
				$this->applyCategoryUserActions(self::CUT_CATEGORY_ACTION);
			} else if($this->isPasteCategoriesAction()) {
				$this->applyCategoryPasteUserAction();
			} else if($this->isDeleteCategoriesAction()) {
				$this->deleteSelectedCategories();
			}
		}
		
		// Categories
		$this->assignCategories();
		// Websites
		$this->assignWebsites();
		// Set parent category id
		$this->setParentCategoryId($this->getParentCategoryId());
		return View::TPL;
	}
}

?>