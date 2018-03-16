<?php
namespace Redaxscript\Admin\View;

use function Redaxscript\Admin\View\Helper\admin_control;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Validator;

/**
 * children class to create the admin module table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class ModuleTable extends ViewAbstract implements ViewInterface
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
		ob_start();
		admin_modules_list();
		return ob_get_clean();
	}
}

/**
 * admin modules list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_modules_list()
{
	$registry = Registry::getInstance();
	$language = Language::getInstance();
	$output = Module\Hook::trigger('adminModuleListStart');

	/* query modules */

	$result = Db::forTablePrefix('modules')->orderByAsc('name')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get('modules') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-module">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . $language->get('name') . '</th><th class="rs-admin-col-alias">' . $language->get('alias') . '</th><th class="rs-admin-col-version">' . $language->get('version') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . $language->get('name') . '</td><td>' . $language->get('alias') . '</td><td>' . $language->get('version') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = $language->get('module_no') . $language->get('point');
	}
	else if ($result)
	{
		$accessValidator = new Validator\Access();
		$output .= '<tbody>';
		foreach ($result as $r)
		{
			$access = $r['access'];

			/* access granted */

			if ($accessValidator->validate($access, $registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
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
				$modules_installed_array[] = $alias;

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

				$output .= admin_control('modules_installed', 'modules', $id, $alias, $status, $registry->get('tableInstall'), $registry->get('tableEdit'), $registry->get('tableUninstall'));

				/* collect alias and version output */

				$output .= '</td><td>' . $alias . '</td><td>' . $version . '</td></tr>';
			}
			else
			{
				$counter++;
			}
		}
		$output .= '</tbody>';

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = $language->get('access_no') . $language->get('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}

	/* modules not installed */

	if ($registry->get('modulesInstall') == 1)
	{
		/* modules directory */

		$modules_directory = new Filesystem\Filesystem();
		$modules_directory->init('modules');
		$modules_directory_array = $modules_directory->getSortArray();
		if ($modules_directory_array && $modules_installed_array)
		{
			$modules_not_installed_array = array_diff($modules_directory_array, $modules_installed_array);
		}
		else if ($modules_directory_array)
		{
			$modules_not_installed_array = $modules_directory_array;
		}
		if ($modules_not_installed_array)
		{
			$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="3">' . $language->get('install') . '</td></tr>';
			foreach ($modules_not_installed_array as $alias)
			{
				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				$output .= '><td colspan="3">' . $alias;

				/* collect control output */

				$output .= admin_control('modules_not_installed', 'modules', $id, $alias, $status, $registry->get('tableInstall'), $registry->get('tableEdit'), $registry->get('tableUninstall'));
				$output .= '</td></tr>';
			}
			$output .= '</tbody>';
		}
	}
	$output .= '</table></div>';
	$output .= Module\Hook::trigger('adminModuleListEnd');
	echo $output;
}
