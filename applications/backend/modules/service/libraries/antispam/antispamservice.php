<?php

/**
 * Implementations of an antispam service.
 *
 * @author Jerome Loisel
 */
interface AntispamService extends Service {
	
	/**
	 * Says if a comment is a spam or not.
	 *
	 * @param string $type Example: comment.
	 * @param string $email Example: max@aol.com
	 * @param string $site Example: http://www.mywebsite.com
	 * @param string $ip Example: 127.0.0.1
	 * @param string $content Example: 'This is my comment.'
	 * @param Website $website 
	 */
	public function isSpam($type, $email, $site, $ip, $content, Website $website);
	
	/**
	 * Trains the filter, in case the comment antispam service needs to 
	 * be trained.
	 *
	 * @param string $type Example: comment.
	 * @param string $email Example: max@aol.com
	 * @param string $site Example: http://www.mywebsite.com
	 * @param string $ip Example: 127.0.0.1
	 * @param string $content Example: 'This is my comment.'
	 * @param Comment $comment
	 */
	public function trainFilter($type, $email, $site, $ip, $content, Comment $comment);
}

?>