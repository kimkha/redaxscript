<?php
namespace Redaxscript\Admin\View;

use function Redaxscript\Admin\View\Helper\admin_control;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * children class to create the admin group table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class GroupTable extends ViewAbstract implements ViewInterface
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		//TODO: refactor
		$registry = Registry::getInstance();
		$language = Language::getInstance();
		$output = Module\Hook::trigger('adminGroupListStart');

		/* query groups */

		$result = Db::forTablePrefix('groups')->orderByAsc('name')->findArray();
		$num_rows = count($result);

		/* collect listing output */

		$output .= '<h2 class="rs-admin-title-content">' . $language->get('groups') . '</h2>';
		$output .= '<div class="rs-admin-wrapper-button">';
		if ($registry->get('groupsNew'))
		{
			$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/new/groups" class="rs-admin-button-default rs-admin-button-create">' . $language->get('group_new') . '</a>';
		}
		$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-default-group">';

		/* collect thead and tfoot */

		$output .= '<thead><tr><th class="rs-admin-col-name">' . $language->get('name') . '</th><th class="rs-admin-col-alias">' . $language->get('alias') . '</th><th class="rs-admin-col-filter">' . $language->get('filter') . '</th></tr></thead>';
		$output .= '<tfoot><tr><td>' . $language->get('name') . '</td><td>' . $language->get('alias') . '</td><td>' . $language->get('filter') . '</td></tr></tfoot>';
		if (!$result || !$num_rows)
		{
			$error = $language->get('group_no') . $language->get('point');
		}
		else if ($result)
		{
			$output .= '<tbody>';
			foreach ($result as $r)
			{
				if ($r)
				{
					foreach ($r as $key => $value)
					{
						if ($key !== 'language')
						{
							$$key = stripslashes($value);
						}
					}
				}

				/* build class string */

				if ($status == 1)
				{
					$class_status = null;
				}
				else
				{
					$class_status = 'rs-admin-is-disabled';
				}

				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				if ($class_status)
				{
					$output .= ' class="' . $class_status . '"';
				}
				$output .= '><td>' . $name;

				/* collect control output */

				$output .= admin_control('access', 'groups', $id, $alias, $status, $registry->get('tableNew'), $registry->get('tableEdit'), $registry->get('tableDelete'));

				/* collect alias and filter output */

				$output .= '</td><td>' . $alias . '</td><td>' . $filter . '</td></tr>';
			}
			$output .= '</tbody>';
		}

		/* handle error */

		if ($error)
		{
			$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
		}
		$output .= '</table></div>';
		$output .= Module\Hook::trigger('adminGroupListEnd');
		return $output;
	}
}
