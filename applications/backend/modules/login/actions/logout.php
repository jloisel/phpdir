<?php

/**
 * Logs out the user.
 *
 * @author Jerome Loisel
 */
class LogoutAction extends DefaultController {
	
	public function executeAction() {
		$this->getUser()->setAuthenticated(false);
		$this->redirect(Route::CONTROLLER,array('controller' => $this->getController()));
		return View::NONE;
	}
	
}

?>