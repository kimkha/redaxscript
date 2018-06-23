<?php
namespace Redaxscript\Admin\Controller;

/**
 * children class to handle common tasks
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Common extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @param string $action action to process
	 *
	 * @return string
	 */

	public function process(string $action = null) : string
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . $action;
	}
}
