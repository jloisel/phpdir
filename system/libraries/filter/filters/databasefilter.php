<?php

/**
 * Database filter: 
 * In fact there is nothing to do with PHPDoctrine.
 * Everything is lazy initialized.
 * 
 * @author Jerome Loisel
 *
 */
final class DatabaseFilter extends AbstractFilter {
	
	public function preExecute() {
		if($this->isFirstCall()) {
			DBWrapper::init();
		}
	}
	
	public function postExecute() {
		if($this->isFirstCall()) {
			DBWrapper::stop();
		}
	}
	
}

?>