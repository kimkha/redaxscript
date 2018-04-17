<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Language;

/**
 * helper class to create the admin notification
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Notification
{
	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * options of the notification
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
	];

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
	 *
	 * @param Language $language instance of the language class
	 */

	public function __construct(Language $language)
	{
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the notification
	 */

	public function init(array $optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		return 'to be implemented: ' . __CLASS__;
	}
}
