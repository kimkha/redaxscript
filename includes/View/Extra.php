<?php
namespace Redaxscript\View;

/**
 * children class to create the extra
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Extra extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias alias of the extra
	 *
	 * @return string
	 */

	public function render(string $extraAlias = null) : string
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . $extraAlias;
	}
}
