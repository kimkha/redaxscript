<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * helper class to create the admin dock
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Dock
{
	/**
	 * render the view
	 *
	 * @param array $optionArray options of the form
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		//TODO: refactor
		$registry = Registry::getInstance();
		$language = Language::getInstance();
		$output = Module\Hook::trigger('adminDockStart');

		/* define access variables */

		$edit = $registry->get($optionArray['table'] . 'Edit');
		$delete = $registry->get($optionArray['table'] . 'Delete');

		/* collect output */

		if ($edit == 1 || $delete == 1)
		{
			$output .= '<div class="rs-admin-wrapper-dock"><div class="rs-admin-js-dock rs-admin-box-dock rs-admin-fn-clearfix">';
			if ($edit == 1)
			{
				$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/unpublish/' . $optionArray['table'] . '/' . $optionArray['id'] . '/' . $registry->get('token') . '" class="rs-admin-link-dock rs-admin-link-unpublish" data-description="'. $language->get('unpublish') . '">' . $language->get('unpublish') . '</a>';
				$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/' . $optionArray['table'] . '/' . $optionArray['id'] . '" class="rs-admin-link-dock rs-admin-link-edit" data-description="'. $language->get('edit') . '">' . $language->get('edit') . '</a>';
			}
			if ($delete == 1)
			{
				$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/delete/' . $optionArray['table'] . '/' . $optionArray['id'] . '/' . $registry->get('token') . '" class="rs-admin-js-confirm rs-admin-link-dock rs-admin-link-delete" data-description="'. $language->get('delete') . '">' . $language->get('delete') . '</a>';
			}
			$output .= '</div></div>';
		}
		$output .= Module\Hook::trigger('adminDockEnd');
		return $output;
	}
}
