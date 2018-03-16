<?php
namespace Redaxscript\View;

/**
 * children class to create the comment
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Comment extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the comment
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . implode($optionArray, ' ,');
	}
}
