<?php

class PartnerActions extends DefaultController {
	
	/**
	 * Add or remove a partner actions.
	 */
	const ADD_PARTNER_ACTION = 'add_partner_action';
	const REMOVE_PARTNER_ACTION = 'remove_partner_action';
	
	/**
	 * Partners configuration file name.
	 *
	 */
	const PARTNERS_CONFIGURATION_FILE = '__partners.php';
	
	/**
	 * Partners configuration file.
	 *
	 * @var File
	 */
	private $file = null;
	
	/**
	 * Partners array.
	 *
	 * @var array
	 */
	private $partners = null;
	
	/**
	 * In any case, initializes partners add form.
	 *
	 */
	public function preExecute() {
		$this->form = new PartnerForm();
	}
	
	/**
	 * IS add action ?
	 *
	 * @return boolean
	 */
	private function isAddPartnerAction() {
		return $this->getParameter(self::ADD_PARTNER_ACTION) != null;
	}
	
	/**
	 * Is remove partner action ?
	 *
	 * @return boolean
	 */
	private function isRemovePartnerAction() {
		return $this->getParameter(self::REMOVE_PARTNER_ACTION) != null;
	}
	
	/**
	 * Returns a new File mapping the 
	 * partners PHP configuration file.
	 *
	 * @return File
	 */
	private function getPartnersFile() {
		if($this->file == null) {
			$this->file = new File(
				SCRIPT_ROOT_PATH
				.'/public/modules'
				.'/'.$this->getController()
				.'/'.self::PARTNERS_CONFIGURATION_FILE
			);
		}
		return $this->file;
	}
	
	/**
	 * Returns the partners.
	 *
	 * @return array
	 */
	private function getPartners() {
		if(!is_array($this->partners)) {
			$file = $this->getPartnersFile();
			if(!$file->exists()) {
				$file->create();
				$this->partners = array();
			} else {
				$this->partners = unserialize($file->read());
			}
		}
		return $this->partners;
	}
	
	/**
	 * Saves the partners.
	 */
	private function savePartners() {
		$file = $this->getPartnersFile();
		$file->setContent(serialize($this->getPartners()));
		$file->write();
	}
	
	/**
	 * add a new partner.
	 */
	private function addPartner() {
		$partner = new Partner();
		$partner->setName($this->form->getValue('name'));
		$partner->setLink($this->form->getValue('link'));
		$partner->setTarget($this->form->getValue('target'));
		
		$this->getPartners();
		$this->partners[] = $partner;
		$this->savePartners();
	}
	
	/**
	 * Removes a partner.
	 *
	 * @param Partner $partner
	 */
	private function removePartner($index) {
		$this->getPartners();
		if(isset($this->partners[$index])) {
			unset($this->partners[$index]);
		}
	}
	
	/**
	 * Removes selected partners.
	 */
	private function removeSelectedPartners() {
		$partners = $this->getParameter('partner');
		if(is_array($partners) && count($partners) > 0) {
			foreach($partners as $i => $partner) {
				$this->removePartner($i);
			}
			$this->savePartners();
			return true;
		}
		return false;
	}
	
	/**
	 * Index action.
	 *
	 * @return integer
	 */
	public function executeIndex() {
		if($this->getRequestMethod() == RequestMethod::POST) {
			if($this->isAddPartnerAction()) {
				$this->form->bind($this->getParameter('partner'));
				if($this->form->isValid()) {
					$this->addPartner();
				}
			} else if($this->isRemovePartnerAction()) {
				$this->removeSelectedPartners();
			}
		}
		$this->tpl->assign('partners',$this->getPartners());
		return View::TPL;
	}
	
}

?>