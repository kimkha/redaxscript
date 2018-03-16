<?php
namespace Redaxscript\Admin\View\Helper;

/**
 * helper class to create the admin control
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Control
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the control
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . implode($optionArray, ' ,');
	}
}
