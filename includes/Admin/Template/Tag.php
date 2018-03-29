<?php
namespace Redaxscript\Admin\Template;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Template\Tag as BaseTag;

/**
 * parent class to provide admin template tags
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Template
 * @author Henry Ruhs
 */

class Tag extends BaseTag
{
	/**
	 * panel
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public static function panel() : string
	{
		$panel = new Helper\Panel(Registry::getInstance(), Language::getInstance());
		return $panel->render();
	}
}
