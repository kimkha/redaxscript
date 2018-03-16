<?php
namespace Redaxscript\Admin\View;

use function Redaxscript\Admin\View\Helper\admin_control;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * children class to create the admin user table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class UserTable extends ViewAbstract implements ViewInterface
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
		admin_users_list();
		return ob_get_clean();
	}
}

/**
 * admin users list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_users_list()
{
	$registry = Registry::getInstance();
	$language = Language::getInstance();
	$output = Module\Hook::trigger('adminUserListStart');

	/* query users */

	$result = Db::forTablePrefix('users')->orderByDesc('last')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get('users') . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($registry->get('usersNew'))
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/new/users" class="rs-admin-button-default rs-admin-button-create">' . $language->get('user_new') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-user">';

	/* collect thead and tfoot */

	$output .= '<thead><tr><th class="rs-admin-col-name">' . $language->get('name') . '</th><th class="rs-admin-col-user">' . $language->get('user') . '</th><th class="rs-admin-col-group">' . $language->get('groups') . '</th><th class="rs-admin-col-session">' . $language->get('session') . '</th></tr></thead>';
	$output .= '<tfoot><tr><td>' . $language->get('name') . '</td><td>' . $language->get('user') . '</td><td>' . $language->get('groups') . '</td><td>' . $language->get('session') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = $language->get('user_no') . $language->get('point');
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
			if ($user)
			{
				$output .= ' id="' . $user . '"';
			}
			if ($class_status)
			{
				$output .= ' class="' . $class_status . '"';
			}
			$output .= '><td>';
			if ($r['language'])
			{
				$output .= '<span class="rs-admin-has-language" data-language="' . $r['language'] . '">';
			}
			$output .= $name;
			if ($r['language'])
			{
				$output .= '</span>';
			}

			/* collect control output */

			$output .= admin_control('access', 'users', $id, $alias, $status, $registry->get('tableNew'), $registry->get('tableEdit'), $registry->get('tableDelete'));

			/* collect user and parent output */

			$output .= '</td><td>' . $user . '</td><td>';
			if ($groups)
			{
				$groups_array = array_filter(explode(', ', $groups));
				$groups_array_keys = array_keys($groups_array);
				$groups_array_last = end($groups_array_keys);
				foreach ($groups_array as $key => $value)
				{
					$group_alias = Db::forTablePrefix('groups')->where('id', $value)->findOne()->alias;
					if ($group_alias)
					{
						$group_name = Db::forTablePrefix('groups')->where('id', $value)->findOne()->name;
						$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/groups/' . $value . '" class="rs-admin-link-parent">' . $group_name . '</a>';
						if ($groups_array_last != $key)
						{
							$output .= ', ';
						}
					}
				}
			}
			else
			{
				$output .= $language->get('none');
			}
			$output .= '</td><td>';
			if ($first == $last)
			{
				$output .= $language->get('none');
			}
			else
			{
				$settingModel = new Admin\Model\Setting();
				$minute_ago = date('Y-m-d H:i:s', strtotime('-1 minute'));
				$day_ago = date('Y-m-d H:i:s', strtotime('-1 day'));
				if ($last > $minute_ago)
				{
					$output .= $language->get('online');
				}
				else if ($last > $day_ago)
				{
					$time = date($settingModel->get('time'), strtotime($last));
					$output .= $language->get('today') . ' ' . $language->get('at') . ' ' . $time;
				}
				else
				{
					$date = date($settingModel->get('date'), strtotime($last));
					$output .= $date;
				}
			}
			$output .= '</td></tr>';
		}
		$output .= '</tbody>';
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="3">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Module\Hook::trigger('adminUserListEnd');
	echo $output;
}
