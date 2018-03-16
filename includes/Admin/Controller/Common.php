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

class Common
{
	public function __call($name, $arguments)
	{
		return 'to be implemented: ' . __CLASS__ . ' ' . $name . ' ' . implode($arguments, ' ,');
	}
}
