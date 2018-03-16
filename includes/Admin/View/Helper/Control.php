<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

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
	 * @param array $optionArray options of the form
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		ob_start();
		admin_control($optionArray['type'], $optionArray['table'], $optionArray['id'], $optionArray['alias'], $optionArray['status'], $optionArray['new'], $optionArray['edit'], $optionArray['delete']);
		return ob_get_clean();
	}
}

/**
 * admin control
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 *
 * @param string $type
 * @param string $table
 * @param int $id
 * @param string $alias
 * @param int $status
 * @param string $new
 * @param string $edit
 * @param string $delete
 * @return string
 */

function admin_control($type, $table, $id, $alias, $status, $new, $edit, $delete)
{
	$registry = Registry::getInstance();
	$language = Language::getInstance();
	$output = Module\Hook::trigger('adminControlStart');

	/* define access variables */

	if ($type == 'access' && $id == 1)
	{
		$delete = 0;
	}
	if ($type == 'modules_not_installed')
	{
		$edit = $delete = 0;
	}

	/* collect modules output */

	if ($new == 1 && $type == 'modules_not_installed')
	{
		$output .= '<li class="rs-admin-item-control rs-admin-item-install"><a href="' . $registry->get('parameterRoute') . 'admin/install/' . $table . '/' . $alias . '/' . $registry->get('token') . '">' . $language->get('install') . '</a></li>';
	}

	/* collect contents output */

	if ($type == 'contents')
	{
		if ($status == 2)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-future-posting"><span>' . $language->get('future_posting') . '</span></li>';
		}
		if ($edit == 1)
		{
			if ($status == 1)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-unpublish"><a href="' . $registry->get('parameterRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('unpublish') . '</a></li>';
			}
			else if ($status == 0)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-publish"><a href="' . $registry->get('parameterRoute') . 'admin/publish/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('publish') . '</a></li>';
			}
		}
	}

	/* collect access and system output */

	if ($edit == 1 && ($type == 'access' && $id > 1 || $type == 'modules_installed'))
	{
		if ($status == 1)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-disable"><a href="' . $registry->get('parameterRoute') . 'admin/disable/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('disable') . '</a></li>';
		}
		else if ($status == 0)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . $registry->get('parameterRoute') . 'admin/enable/' . $table . '/' . $id . '/' . $registry->get('token') . '">' . $language->get('enable') . '</a></li>';
		}
	}

	/* collect general edit and delete output */

	if ($edit == 1)
	{
		$output .= '<li class="rs-admin-item-control rs-admin-item-edit"><a href="' . $registry->get('parameterRoute') . 'admin/edit/' . $table . '/' . $id . '">' . $language->get('edit') . '</a></li>';
	}
	if ($delete == 1)
	{
		if ($type == 'modules_installed')
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-uninstall"><a href="' . $registry->get('parameterRoute') . 'admin/uninstall/' . $table . '/' . $alias . '/' . $registry->get('token') . '" class="rs-admin-js-confirm">' . $language->get('uninstall') . '</a></li>';
		}
		else
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-delete"><a href="' . $registry->get('parameterRoute') . 'admin/delete/' . $table . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-js-confirm">' . $language->get('delete') . '</a></li>';
		}
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-list-control">' . $output . '</ul>';
	}
	$output .= Module\Hook::trigger('adminControlEnd');
	return $output;
}
