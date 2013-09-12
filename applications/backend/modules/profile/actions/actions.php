<?php

class ProfileActions extends DefaultController {

	/**
	 * Returns an array of the logged in customer 
	 * fields.
	 *
	 * @return array
	 */
	private function getLoggedCustomerData() {
		$arr = Customer::getLogged()->getData();
		unset($arr['password']);
		return $arr;
	}
	
	/**
	 * Edits customer profile.
	 *
	 * @param Customer $customer
	 */
	private function editCustomerProfile(Customer $customer, sfForm $form) {
		$customer->email = $form->getValue('email');
		$newPassword = $form->getValue('new_password');
		if($newPassword != null) {
			$customer->password = md5($newPassword);
		}
		$customer->firstname = $form->getValue('firstname');
		$customer->lastname = $form->getValue('lastname');
		$customer->save();
	}
	
	public function executeIndex() {
		$this->form = new ProfileEditForm();
		if($this->getRequestMethod() == RequestMethod::POST) {
			$this->form->bind($this->getParameter('profile'));
			if($this->form->isValid()) {
				$this->editCustomerProfile(Customer::getLogged(),$this->form);
			}
		}
		$this->form->setDefaults($this->getLoggedCustomerData());
		return View::TPL;
	}
	
}

?>