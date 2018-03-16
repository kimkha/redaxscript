<?php
namespace Redaxscript\Admin\Router;

use Redaxscript\Admin;
use Redaxscript\Module;
use Redaxscript\Router\RouterAbstract;

/**
 * parent class to provide the admin router
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Router
 * @author Henry Ruhs
 */

class Router extends RouterAbstract
{
	/**
	 * route the header
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	public function routeHeader() : bool
	{
		Module\Hook::trigger('adminRouteHeader');

		/* handle break */

		if ($this->_registry->get('adminRouterBreak'))
		{
			$this->_registry->set('contentError', false);
		}
		return $this->_registry->get('adminRouterBreak');
	}

	/**
	 * route the content
	 *
	 * @since 3.3.0
	 *
	 * @return string|bool
	 */

	public function routeContent()
	{
		Module\Hook::trigger('adminRouteContent');
		$firstParameter = $this->getFirst();
		$adminParameter = $this->getAdmin();
		$tableParameter = $this->getTable();

		/* handle admin */

		if ($firstParameter === 'admin')
		{
			/* handle guard */

			if ($adminParameter)
			{
				if ($this->_tokenGuard())
				{
					return $this->_errorToken();
				}
				if ($this->_routeGuard() || $this->_authGuard())
				{
					return $this->_errorAccess();
				}
			}

			/* handle update */

			if (!$adminParameter || $adminParameter == 'view' && $tableParameter == 'users' || $this->_registry->get('cronUpdate'))
			{
				$userModel = new Admin\Model\User();
				$userModel->updateLastSeen();
			}

			/* handle post */

			if ($this->_request->getPost('Redaxscript\Admin\View\CategoryForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ArticleForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ExtraForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\CommentForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\UserForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\GroupForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\ModuleForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}
			if ($this->_request->getPost('Redaxscript\Admin\View\SettingForm'))
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->create();
			}

			/* handle route */

			if ($adminParameter === 'view')
			{
				return $this->_renderView();
			}
			if ($adminParameter === 'new')
			{
				return $this->_renderNew();
			}
			if ($adminParameter === 'edit')
			{
				return $this->_renderEdit();
			}
			if ($adminParameter === 'delete')
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->delete();
			}
			if ($adminParameter === 'up' || $adminParameter === 'down')
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->move();
			}
			if ($adminParameter === 'sort')
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->sort();
			}
			if ($adminParameter === 'publish' || $adminParameter === 'enable')
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->status(1);
			}
			if ($adminParameter === 'unpublish' || $adminParameter === 'disable')
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->status(0);
			}
			if ($adminParameter === 'install' || $adminParameter === 'uninstall')
			{
				$contentModel = new Admin\Model\Content();
				$contentModel->install();
			}
		}
		return $this->_registry->get('adminRouterBreak');
	}

	/**
	 * token guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _tokenGuard() : bool
	{
		$adminParameter = $this->getAdmin();
		$tokenParameter = $this->getToken();
		$tokenArray =
		[
			'up',
			'down',
			'sort',
			'enable',
			'disable',
			'publish',
			'unpublish',
			'install',
			'uninstall',
			'delete'
		];
		return $this->_request->getPost() && $this->_request->getPost('token') !== $this->_registry->get('token') || in_array($adminParameter, $tokenArray) && !$tokenParameter;
	}

	/**
	 * route guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _routeGuard() : bool
	{
		$adminParameter = $this->getAdmin();
		$tableParameter = $this->getTable();
		$idParameter = $this->getId();
		$aliasParameter = $this->getAlias();
		$adminArray =
		[
			'new',
			'view',
			'edit',
			'up',
			'down',
			'sort',
			'publish',
			'unpublish',
			'enable',
			'disable',
			'install',
			'uninstall',
			'delete'
		];
		$tableArray =
		[
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users',
			'modules',
			'settings'
		];
		$idArray =
		[
			'edit',
			'up',
			'down',
			'publish',
			'unpublish',
			'enable',
			'disable'
		];
		$aliasArray =
		[
			'install',
			'uninstall'
		];
		$invalidAdmin = !in_array($adminParameter, $adminArray);
		$invalidTable = !in_array($tableParameter, $tableArray);
		$invalidId = in_array($adminParameter, $idArray) && !$idParameter && !$tableParameter === 'settings';
		$invalidAlias = in_array($adminParameter, $aliasArray) && !$aliasParameter;
		return $invalidAdmin || $invalidTable || $invalidId || $invalidAlias;
	}

	/**
	 * auth guard
	 *
	 * @since 3.3.0
	 *
	 * @return bool
	 */

	protected function _authGuard() : bool
	{
		$adminParameter = $this->getAdmin();
		$tableParameter = $this->getTable();
		$idParameter = $this->getId();
		$editArray =
		[
			'edit',
			'view',
			'up',
			'down',
			'sort',
			'enable',
			'disable',
			'publish',
			'unpublish'
		];
		$permissionNew = $adminParameter === 'new' && $this->_registry->get('tableNew');
		$permissionEdit = in_array($adminParameter, $editArray) && $this->_registry->get('tableEdit');
		$permissionDelete = $adminParameter === 'delete' && $this->_registry->get('tableDelete');
		$permissionInstall = $adminParameter === 'install' && $this->_registry->get('tableInstall');
		$permissionUninstall = $adminParameter === 'uninstall' && $this->_registry->get('tableUninstall');
		$permissionProfile = $tableParameter === 'users' && $idParameter === $this->_registry->get('myId');
		return !$permissionNew && !$permissionEdit && !$permissionDelete && !$permissionInstall && !$permissionUninstall && !$permissionProfile;
	}

	/**
	 * render the view
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _renderView() : string
	{
		$tableParameter = $this->getTable();

		/* handle table */

		ob_start();
		if ($tableParameter == 'categories')
		{
			$contentTable = new Admin\View\ContentTable($this->_registry, $this->_language);
			return $contentTable->render();
		}
		if ($tableParameter == 'articles')
		{
			$contentTable = new Admin\View\ContentTable($this->_registry, $this->_language);
			return $contentTable->render();
		}
		if ($tableParameter == 'extras')
		{
			$contentTable = new Admin\View\ContentTable($this->_registry, $this->_language);
			return $contentTable->render();
		}
		if ($tableParameter == 'comments')
		{
			$contentTable = new Admin\View\ContentTable($this->_registry, $this->_language);
			return $contentTable->render();
		}
		if ($tableParameter == 'users')
		{
			$userTable = new Admin\View\UserTable($this->_registry, $this->_language);
			return $userTable->render();
		}
		if ($tableParameter == 'groups')
		{
			$groupTable = new Admin\View\GroupTable($this->_registry, $this->_language);
			return $groupTable->render();
		}
		if ($tableParameter == 'modules')
		{
			$moduleTable = new Admin\View\ModuleTable($this->_registry, $this->_language);
			return $moduleTable->render();
		}
		return ob_get_clean();
	}

	/**
	 * render the new
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _renderNew() : string
	{
		$tableParameter = $this->getTable();

		/* handle table */

		if ($tableParameter == 'categories')
		{
			$categoryForm = new Admin\View\CategoryForm($this->_registry, $this->_language);
			return $categoryForm->render();
		}
		if ($tableParameter == 'articles')
		{
			$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);
			return $articleForm->render();
		}
		if ($tableParameter == 'extras')
		{
			$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);
			return $extraForm->render();
		}
		if ($tableParameter == 'comments')
		{
			$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);
			return $commentForm->render();
		}
		if ($tableParameter == 'users')
		{
			$userForm = new Admin\View\UserForm($this->_registry, $this->_language);
			return $userForm->render();
		}
		if ($tableParameter == 'groups')
		{
			$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);
			return $groupForm->render();
		}
	}

	/**
	 * render the edit
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _renderEdit() : string
	{
		$tableParameter = $this->getTable();
		$idParameter = $this->getId();

		/* handle table */

		if ($tableParameter == 'categories' && $idParameter)
		{
			$categoryForm = new Admin\View\CategoryForm($this->_registry, $this->_language);
			return $categoryForm->render($idParameter);
		}
		if ($tableParameter == 'articles' && $idParameter)
		{
			$articleForm = new Admin\View\ArticleForm($this->_registry, $this->_language);
			return $articleForm->render($idParameter);
		}
		if ($tableParameter == 'extras' && $idParameter)
		{
			$extraForm = new Admin\View\ExtraForm($this->_registry, $this->_language);
			return $extraForm->render($idParameter);
		}
		if ($tableParameter == 'comments' && $idParameter)
		{
			$commentForm = new Admin\View\CommentForm($this->_registry, $this->_language);
			return $commentForm->render($idParameter);
		}
		if ($tableParameter == 'users' && $idParameter)
		{
			$userForm = new Admin\View\UserForm($this->_registry, $this->_language);
			return $userForm->render($idParameter);
		}
		if ($tableParameter == 'groups' && $idParameter)
		{
			$groupForm = new Admin\View\GroupForm($this->_registry, $this->_language);
			return $groupForm->render($idParameter);
		}
		if ($tableParameter == 'modules' && $idParameter)
		{
			$moduleForm = new Admin\View\ModuleForm($this->_registry, $this->_language);
			return $moduleForm->render($idParameter);
		}
		if ($tableParameter == 'settings')
		{
			$settingForm = new Admin\View\SettingForm($this->_registry, $this->_language);
			return $settingForm->render();
		}
	}

	/**
	 * show the token error
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _errorToken() : string
	{
		$messenger = new Admin\Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'admin')
			->error($this->_language->get('token_incorrect'), $this->_language->get('error_occurred'));
	}

	/**
	 * show the access error
	 *
	 * @since 3.3.0
	 *
	 * @return string
	 */

	protected function _errorAccess() : string
	{
		$messenger = new Admin\Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), 'admin')
			->error($this->_language->get('access_no'), $this->_language->get('error_occurred'));
	}
}