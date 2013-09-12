<?php

class Keyword extends BaseKeyword {
	
	const NOT_BANNED = '0';
	const BANNED = '1';
	
	/**
	 * On keyword insertion.
	 *
	 * @param Doctrine_Event $event
	 */
	public function preInsert($event) {
		$this->created_on = date('Y-m-d H:i:s');
		$this->is_banned = self::NOT_BANNED;
	}
}

?>