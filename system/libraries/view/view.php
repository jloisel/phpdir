<?php

/**
 * View class defines several constants which influence the
 * display behavior after Controller execution :
 * - NONE : the template engine display is not called
 * - TPL : the template engine is called to display content
 * - HEAD : only Http headers are sent
 *
 * View class also defines the application view folders name.
 * @author Jerome Loisel
 * @package	View
 */
abstract class View {
	/**
	 * Nothing is displayed.
	 *
	 * @var Integer
	 */
	const NONE = 1;
	/**
	 * Http headers and template view are sent.
	 *
	 * @var Integer
	 */
	const TPL = 2;

	/**
	 * Sends only Http headers.
	 */
	const HEAD = 3;

	/**
	 * Available view modes
	 *
	 * @var Array
	 */
	public static $availableViewModes = array(
		self::NONE, self::TPL, self::HEAD
	);
}

?>