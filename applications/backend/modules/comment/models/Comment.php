<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Comment extends BaseComment
{
	/**
	 * All comment status.
	 */
	const PENDING_STATUS = 0;
	const APPROVED_STATUS = 1;
	const SPAM_STATUS = 2;
	
	/**
	 * Returns the comment HTML status, displayable 
	 * in page output.
	 *
	 * @return string
	 */
	public function getHtmlStatus() {
		$color = null;
		$status = null;
		switch($this->status) {
			case self::PENDING_STATUS:
				$color = 'orange';
				$status = 'Pending';
				break;
			case self::APPROVED_STATUS:
				$color = 'green';
				$status = 'Approved';
				break;
			case self::SPAM_STATUS:
				$color = 'red';
				$status = 'Spam';
				break;
				
		}
		return '<span style="color: '.$color.'">'.$status.'</span>';
	}
	
	/**
	 * Mark this comment as spam.
	 *
	 * @param Comment $comment
	 */
	public static function markAsSpam(Comment $comment) {
		BannedIp::ban($comment->ip);
		if(intval($comment->customer_id) > 1) {
			BannedEmail::ban($comment->Customer->email);
		}
	}
}