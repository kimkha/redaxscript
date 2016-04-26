<?php

/**
 * admin panel list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_panel_list()
{
	$output = Redaxscript\Hook::trigger('adminPanelStart');

	/* define access variables */

	if (Redaxscript\Registry::get('categoriesNew') || Redaxscript\Registry::get('categoriesEdit') || Redaxscript\Registry::get('categoriesDelete'))
	{
		$categories_access = $contents_access = 1;
	}
	if (Redaxscript\Registry::get('articlesNew') || Redaxscript\Registry::get('articlesEdit') || Redaxscript\Registry::get('articlesDelete'))
	{
		$articles_access = $contents_access = 1;
	}
	if (Redaxscript\Registry::get('extrasNew') || Redaxscript\Registry::get('extrasEdit') || Redaxscript\Registry::get('extrasDelete'))
	{
		$extras_access = $contents_access = 1;
	}
	if (Redaxscript\Registry::get('commentsNew') || Redaxscript\Registry::get('commentsEdit') || Redaxscript\Registry::get('commentsDelete'))
	{
		$comments_access = $contents_access = 1;
	}
	if (Redaxscript\Registry::get('usersNew') || Redaxscript\Registry::get('usersEdit') || Redaxscript\Registry::get('usersDelete'))
	{
		$users_access = $access_access = 1;
	}
	if (Redaxscript\Registry::get('groupsNew') || Redaxscript\Registry::get('groupsEdit') || Redaxscript\Registry::get('groupsDelete'))
	{
		$groups_access = $access_access = 1;
	}
	if (Redaxscript\Registry::get('modulesInstall') || Redaxscript\Registry::get('modulesEdit') || Redaxscript\Registry::get('modulesUninstall'))
	{
		$modules_access = $system_access = 1;
	}
	if (Redaxscript\Registry::get('settingsEdit'))
	{
		$settings_access = $system_access = 1;
	}

	/* collect contents output */

	$counter = 1;
	if ($contents_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-contents"><span class="rs-admin-text-panel">' . Redaxscript\Language::get('contents') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-contents">';
		if ($categories_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/categories" class="rs-admin-link-panel' . (Redaxscript\Registry::get('categoriesNew') ? ' rs-admin-link-view' : null) . '">' . Redaxscript\Language::get('categories') . '</a>';
			if (Redaxscript\Registry::get('categoriesNew'))
			{
				$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/categories" class="rs-admin-link-panel' . (Redaxscript\Registry::get('categoriesNew') ? ' rs-admin-link-new' : null) . '">' . Redaxscript\Language::get('category_new') . '</a>';
			}
			$output .= '</li>';
		}
		if ($articles_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/articles" class="rs-admin-link-panel' . (Redaxscript\Registry::get('articlesNew') ? ' rs-admin-link-view' : null) . '">' . Redaxscript\Language::get('articles') . '</a>';
			if (Redaxscript\Registry::get('articlesNew'))
			{
				$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/articles" class="rs-admin-link-panel' . (Redaxscript\Registry::get('articlesNew') ? ' rs-admin-link-new' : null) . '">' . Redaxscript\Language::get('article_new') . '</a>';
			}
			$output .= '</li>';
		}
		if ($extras_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/extras" class="rs-admin-link-panel' . (Redaxscript\Registry::get('extrasNew') ? ' rs-admin-link-view' : null) . '">' . Redaxscript\Language::get('extras') . '</a>';
			if (Redaxscript\Registry::get('extrasNew'))
			{
				$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/extras" class="rs-admin-link-panel' . (Redaxscript\Registry::get('extrasNew') ? ' rs-admin-link-new' : null) . '">' . Redaxscript\Language::get('extra_new') . '</a>';
			}
			$output .= '</li>';
		}
		if ($comments_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/comments" class="rs-admin-link-panel' . (Redaxscript\Registry::get('commentsNew') ? ' rs-admin-link-view' : null) . '">' . Redaxscript\Language::get('comments') . '</a>';
			if (Redaxscript\Registry::get('commentsNew'))
			{
				$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/comments" class="rs-admin-link-panel' . (Redaxscript\Registry::get('commentsNew') ? ' rs-admin-link-new' : null) . '">' . Redaxscript\Language::get('comment_new') . '</a>';
			}
			$output .= '</li>';
		}
		$output .= '</ul></li>';
	}

	/* collect access output */

	if ($access_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-access"><span class="rs-admin-text-panel">' . Redaxscript\Language::get('access') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-access">';
		if (Redaxscript\Registry::get('myId'))
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/users/' . Redaxscript\Registry::get('myId') . '" class="rs-admin-link-panel">' . Redaxscript\Language::get('profile') . '</a></li>';
		}
		if ($users_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/users" class="rs-admin-link-panel' . (Redaxscript\Registry::get('usersNew') ? ' rs-admin-link-view' : null) . '">' . Redaxscript\Language::get('users') . '</a>';
			if (Redaxscript\Registry::get('usersNew'))
			{
				$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/users" class="rs-admin-link-panel' . (Redaxscript\Registry::get('usersNew') ? ' rs-admin-link-new' : null) . '">' . Redaxscript\Language::get('user_new') . '</a>';
			}
			$output .= '</li>';
		}
		if ($groups_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/groups" class="rs-admin-link-panel' . (Redaxscript\Registry::get('groupsNew') ? ' rs-admin-link-view' : null) . '">' . Redaxscript\Language::get('groups') . '</a>';
			if (Redaxscript\Registry::get('groupsNew'))
			{
				$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/new/groups" class="rs-admin-link-panel' . (Redaxscript\Registry::get('groupsNew') ? ' rs-admin-link-new' : null) . '">' . Redaxscript\Language::get('group_new') . '</a>';
			}
			$output .= '</li>';
		}
		$output .= '</ul></li>';
	}

	/* collect system output */

	if ($system_access == 1)
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-system"><span class="rs-admin-text-panel">' . Redaxscript\Language::get('system') . '</span><ul class="rs-admin-list-panel-children rs-admin-list-stystem">';
		if ($modules_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/view/modules" class="rs-admin-link-panel">' . Redaxscript\Language::get('modules') . '</a></li>';

			/* collect modules list */

			$admin_panel_list_modules = Redaxscript\Hook::trigger('adminPanelAddModule');
			if ($admin_panel_list_modules)
			{
				$output .= '<ul class="rs-admin-js-list-panel-children rs-admin-list-panel-children">' . $admin_panel_list_modules . '</ul>';
			}
			$output .= '</li>';
		}
		if ($settings_access == 1)
		{
			$output .= '<li><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/settings" class="rs-admin-link-panel">' . Redaxscript\Language::get('settings') . '</a></li>';
		}
		$output .= '</ul></li>';
	}

	/* collect profile */

	if (Redaxscript\Registry::get('myId'))
	{
		$counter++;
		$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-profile"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/users/' . Redaxscript\Registry::get('myId') . '" class="rs-admin-link-panel">' . Redaxscript\Language::get('profile') . '</a></li>';
	}

	/* collect logout */

	$output .= '<li class="rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-logout"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'logout" class="rs-admin-link-panel">' . Redaxscript\Language::get('logout') . '</a></li>';

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-js-list-panel rs-admin-list-panel rs-admin-c' . $counter . '">' . $output . '</ul>';
	}
	$output .= Redaxscript\Hook::trigger('adminPanelEnd');
	echo $output;
}

/**
 * admin dock
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 *
 * @param string $table
 * @param integer $id
 * @return string
 */

function admin_dock($table, $id)
{
	$output = Redaxscript\Hook::trigger('adminDockStart');

	/* define access variables */

	$edit = Redaxscript\Registry::get($table . 'Edit');
	$delete = Redaxscript\Registry::get($table . 'Delete');

	/* collect output */

	if ($edit == 1 || $delete == 1)
	{
		$output .= '<div class="rs-admin-wrapper-dock"><div class="rs-admin-js-dock rs-admin-box-dock rs-admin-clearfix">';
		if ($edit == 1)
		{
			$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-js-link-dock rs-admin-link-dock rs-admin-link-unpublish">' . Redaxscript\Language::get('unpublish') . '</a>';
			$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/' . $table . '/' . $id . '" class="rs-admin-js-link-dock rs-admin-link-dock rs-admin-link-edit">' . Redaxscript\Language::get('edit') . '</a>';
		}
		if ($delete == 1)
		{
			$output .= '<a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/delete/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-js-confirm rs-admin-js-link-dock rs-admin-link-dock rs-admin-link-delete">' . Redaxscript\Language::get('delete') . '</a>';
		}
		$output .= '</div></div>';
	}
	$output .= Redaxscript\Hook::trigger('adminDockEnd');
	return $output;
}

/**
 * admin notification
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_notification()
{
	$output = Redaxscript\Hook::trigger('adminNotificationStart');

	/* insecure file warning */

	if (Redaxscript\Registry::get('myId') == 1)
	{
		if (file_exists('install.php'))
		{
			$output .= '<div class="rs-box-note rs-note-warning">' . Redaxscript\Language::get('file_remove') . Redaxscript\Language::get('colon') . ' install.php' . Redaxscript\Language::get('point') . '</div>';
		}
		if (is_writable('config.php'))
		{
			$output .= '<div class="rs-box-note rs-note-warning">' . Redaxscript\Language::get('file_permission_revoke') . Redaxscript\Language::get('colon') . ' config.php' . Redaxscript\Language::get('point') . '</div>';
		}
	}
	$output .= Redaxscript\Hook::trigger('adminNotificationEnd');
	echo $output;
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
 * @param integer $id
 * @param string $alias
 * @param integer $status
 * @param string $new
 * @param string $edit
 * @param string $delete
 * @return string
 */

function admin_control($type, $table, $id, $alias, $status, $new, $edit, $delete)
{
	$output = Redaxscript\Hook::trigger('adminControlStart');

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
		$output .= '<li class="rs-admin-item-control rs-admin-link-install"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/install/' . $table . '/' . $alias . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('install') . '</a></li>';
	}

	/* collect contents output */

	if ($type == 'contents')
	{
		if ($status == 2)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-future-posting"><span>' . Redaxscript\Language::get('future_posting') . '</span></li>';
		}
		if ($edit == 1)
		{
			if ($status == 1)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-unpublish"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/unpublish/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('unpublish') . '</a></li>';
			}
			else if ($status == 0)
			{
				$output .= '<li class="rs-admin-item-control rs-admin-item-publish"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/publish/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('publish') . '</a></li>';
			}
		}
	}

	/* collect access and system output */

	if ($edit == 1 && ($type == 'access' && $id > 1 || $type == 'modules_installed'))
	{
		if ($status == 1)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-disable"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/disable/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('disable') . '</a></li>';
		}
		else if ($status == 0)
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/enable/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '">' . Redaxscript\Language::get('enable') . '</a></li>';
		}
	}

	/* collect general edit and delete output */

	if ($edit == 1)
	{
		$output .= '<li class="rs-admin-item-control rs-admin-item-edit"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/edit/' . $table . '/' . $id . '">' . Redaxscript\Language::get('edit') . '</a></li>';
	}
	if ($delete == 1)
	{
		if ($type == 'modules_installed')
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/uninstall/' . $table . '/' . $alias . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-js-confirm">' . Redaxscript\Language::get('uninstall') . '</a></li>';
		}
		else
		{
			$output .= '<li class="rs-admin-item-control rs-admin-item-enable"><a href="' . Redaxscript\Registry::get('parameterRoute') . 'admin/delete/' . $table . '/' . $id . '/' . Redaxscript\Registry::get('token') . '" class="rs-admin-confirm">' . Redaxscript\Language::get('delete') . '</a></li>';
		}
	}

	/* collect list output */

	if ($output)
	{
		$output = '<ul class="rs-admin-list-control">' . $output . '</ul>';
	}
	$output .= Redaxscript\Hook::trigger('adminControlEnd');
	return $output;
}