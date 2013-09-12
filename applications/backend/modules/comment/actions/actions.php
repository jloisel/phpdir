<?php

class CommentActions extends DefaultController {
	
	const UNAPPROVE_ACTION = 'unapprove';
	const APPROVE_ACTION = 'approve';
	const MARK_AS_SPAM_ACTION = 'mark_as_spam';
	const DELETE_ACTION = 'delete';
	
	const CURRENT_ACTION = 'comment_action';
	const DEFAULT_CURRENT_ACTION = 'all';
	const CURRENT_PAGE_ATTR = 'comment_current_page';
	
	/**
	 * Is the submitted action the approve action ?
	 *
	 * @return boolean
	 */
	private function isApproveAction() {
		return $this->getParameter(self::APPROVE_ACTION) != null;
	}
	
	/**
	 * Is the submitted action the mark as spam action ?
	 *
	 * @return boolean
	 */
	private function isMarkAsSpamAction() {
		return $this->getParameter(self::MARK_AS_SPAM_ACTION) != null;
	}
	
	/**
	 * Is the submitted action the delete action ?
	 *
	 * @return boolean
	 */
	private function isDeleteAction() {
		return $this->getParameter(self::DELETE_ACTION) != null;
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
	 * Current viewed action.
	 *
	 * @return integer
	 */
	private function getCurrentAction() {
		return $this->getUser()->getAttribute(self::CURRENT_ACTION,self::DEFAULT_CURRENT_ACTION);
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
	 * Sets the current viewed action.
	 *
	 * @param string $currentAction
	 */
	private function setCurrentAction($currentAction) {
		$this->getUser()->setAttribute(self::CURRENT_ACTION,(string)$currentAction);
	}
	
	/**
	 * Query retrieving the keywords.
	 *
	 * @return Doctrine_Query
	 */
	private function getQuery() {
		$q = new Doctrine_Query();
		$q->from('Comment c');
		switch($this->getAction()) {
			case 'approved':
				$q->where('c.status=?',Comment::APPROVED_STATUS);
				break;
			case 'pending':
				$q->where('c.status=?',Comment::PENDING_STATUS);
				break;
			case 'spam':
				$q->where('c.status=?',Comment::SPAM_STATUS);
				break;
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
		$this->comments = $this->pager->execute();
	}
	
	/**
	 * Returns the selected comments.
	 *
	 * @return array()
	 */
	private function getSelectedComments() {
		return $this->getParameter('comments',array());
	}
	
	/**
	 * Creates a query on passed comments array.
	 *
	 * @param array $comments
	 * @return Doctrine_Query
	 */
	private function createUpdateQuery($comments) {
		$q = null;
		if(is_array($comments) && count($comments) > 0) {
			$q = new Doctrine_Query();
			$q->update('Comment c');
			foreach($comments as $id => $comment) {
				$q->orWhere('c.id=?',$id);
			}
			$q->limit(count($comments));
		}
		return $q;
	}
	
	/**
	 * Sets the is_approved comments field and saves it.
	 *
	 * @param Doctrine_Query $q
	 * @param boolean $is_approved
	 */
	private function setIsApproved(Doctrine_Query $q, $is_approved) {
		if($q != null ) {
			$q	->update('Comment c')
				->set('c.status','?',$is_approved);
			$q->execute();
		}
	}
	
	/**
	 * Sets the status comments field and saves it.
	 *
	 * @param Doctrine_Query $q
	 * @param boolean $is_approved
	 */
	private function setIsSpam(Doctrine_Query $q, $is_spam) {
		if($q != null ) {
			$q	->update('Comment c')
				->set('c.status','?',$is_spam);
			$q->execute();
		}
	}
	
	/**
	 * Approves the passed comments.
	 *
	 * @param array $comments
	 */
	private function approveComments($comments) {
		$this->setIsApproved($this->createUpdateQuery($comments),Comment::APPROVED_STATUS);
	}
	
	/**
	 * Approves the passed comments.
	 *
	 * @param array $comments
	 */
	private function unapproveComments($comments) {
		$this->setIsApproved($this->createUpdateQuery($comments),Comment::PENDING_STATUS);
	}
	
	/**
	 * Marks a comment as spam.
	 *
	 * @param array $comments
	 */
	private function markAsSpam($comments) {
		$this->setIsSpam($this->createUpdateQuery($comments),Comment::SPAM_STATUS);
	}
	
	/**
	 * Deletes passed comments.
	 *
	 * @param array $comments
	 */
	private function deleteComments($comments) {
		if(is_array($comments) && count($comment) > 0) {
			$q = new Doctrine_Query();
			$q->from('Comment c');
			foreach($comments as $id => $comment) {
				$q->orWhere('c.id=?',$id);
			}
			
			$q	->limit(count($comments))
				->delete()
				->execute();
		}
	}
	
	/**
	 * Generic action implementation.
	 */
	private function executeAction() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			$comments = $this->getSelectedComments();
			if($this->isApproveAction()) {
				$this->approveComments($comments);
			} else if($this->isMarkAsSpamAction()) {
				$this->markAsSpam($comments);
			} else if($this->isDeleteAction()) {
				$this->deleteComments($this->getSelectedComments());
			}
		}
		$this->setCurrentAction($this->getAction());
	}
	
	public function executeAll() {
		$this->executeAction();
		return View::TPL;
	}
	
	public function executePending() {
		$this->executeAction();
		return View::TPL;
	}
	
	public function executeApproved() {
		$this->executeAction();
		return View::TPL;
	}
	
	public function executeSpam() {
		$this->executeAction();
		return View::TPL;
	}
	
	public function executeIndex() {
		$this->redirect(
			Route::CONTROLLER_ACTION,
			array(
				'controller' => $this->getController(),
				'action' => $this->getCurrentAction()
			)
		);
		return View::NONE;
	}
	
	public function postExecute() {
		$this->assignContent();
		$this->setCurrentPage($this->getCurrentPage());
	}
}

?>