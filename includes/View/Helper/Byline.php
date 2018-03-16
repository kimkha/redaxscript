<?php
namespace Redaxscript\View\Helper;

/**
 * helper class to create the byline
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Byline
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the byline
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . implode($optionArray, ' ,');
	}
}
