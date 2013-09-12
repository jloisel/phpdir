<?php

class BannedActions extends DefaultController {
	
	/**
	 * Is the add ban event triggered ?
	 *
	 * @return boolean
	 */
	private function isAddBan() {
		return $this->getParameter('add_ban') != null;
	}
	
	/**
	 * Is the remove ban event triggered ?
	 *
	 * @return boolean
	 */
	private function isRemoveBan() {
		return $this->getParameter('remove_ban') != null;
	}
	
	/**
	 * Current viewed page.
	 *
	 * @return integer
	 */
	private function getCurrentPage() {
		return intval($this->getParameter('id',1));
	}
	
	/**
	 * Removes checked items from database.
	 *
	 */
	private function handleRemoveBans($tableName) {
		$ip_ids = $this->getParameter('item',array());
		if(is_array($ip_ids) && count($ip_ids) > 0) {
			foreach($ip_ids as $id => $value) {
				if($value != 'on') continue;
				
				Doctrine::getTable($tableName)->createQuery()
					->where('id=?',$id)
					->limit(1)
					->delete()
					->execute();
			}
		}
	}
	
	/**
	 * Adds a new ban into database.
	 *
	 * @param string $tableName
	 * @param array $fields
	 */
	private function handleAddBan($tableName, $fields) {
		$record = Doctrine::getTable($tableName)->create();
		$record->setArray($fields);
		$record->save();
	}
	
	/**
	 * Assigns the pager and items to the template engine.
	 *
	 * @param string $tableName
	 * @param integer $type
	 */
	private function assignContent($tableName,$type) {
		$this->pager = DbHelper::pagined(
			Doctrine::getTable($tableName)->createQuery()
						->select('value, created_on')
						->orderBy('created_on DESC')
						->where('type=?',$type),
			$this->getCurrentPage(),
			UrlHelper::routed_url(
				Route::CONTROLLER_ACTION,
				array(
					'controller' => $this->getController(),
					'action' => $this->getAction()
				)
			)
		);
		
		$this->items = $this->pager->execute(array(),Doctrine::HYDRATE_ARRAY);
	}
	
	/**
	 * Generic action being able to deal with all 
	 * banned items tables.
	 *
	 * @param string $tableName
	 * @param integer $type
	 */
	private function executeAction($tableName, $type) {
		if($this->getRequestMethod() == RequestMethod::POST) {
			if($this->isRemoveBan()) {
				$this->handleRemoveBans($tableName);
			} else if($this->isAddBan()) {
				$this->form->bind($this->getParameter($tableName));
				if($this->form->isValid()) {
					$this->handleAddBan($tableName,$this->form->getValues());
				}
			}
		}
		$this->assignContent($tableName,$type);
	}
	
	/**
	 * IP bans entry point.
	 *
	 * @return integer
	 */
	public function executeIp() {
		$this->form = new AddBannedIpForm();
		$this->fieldName = __('IP');
		$this->executeAction('BannedIp',BannedIp::TYPE);
		return View::TPL;
	}
	
	/**
	 * Email bans entry point.
	 *
	 * @return integer
	 */
	public function executeEmail() {
		$this->form = new AddBannedEmailForm();
		$this->fieldName = __('Email');
		$this->executeAction('BannedEmail', BannedEmail::TYPE);
		return View::TPL;
	}
	
	/**
	 * Host bans entry point.
	 *
	 * @return integer
	 */
	public function executeHost() {
		$this->form = new AddBannedHostForm();
		$this->fieldName = __('Host');
		$this->executeAction('BannedHost',BannedHost::TYPE);
		return View::TPL;
	}

}

?>