<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Html;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * helper class to create the admin panel
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Panel
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * options of the dock
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' =>
			[
				'panel' => 'rs-admin-js-list-panel rs-admin-list-panel rs-admin-fn-clearfix',
				'content' => 'rs-admin-list-panel-children rs-admin-list-contents'
			],
			'item' =>
			[
				'content' => 'rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-content',
				'profile' => 'rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-profile',
				'logout' => 'rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-logout'
			],
			'text' =>
			[
				'content' => 'rs-admin-text-panel rs-admin-text-content',
				'group' => 'rs-admin-text-panel-group'
			],
			'link' =>
			[
				'panel' => 'rs-admin-link-panel',
				'view' => 'rs-admin-link-view',
				'new' => 'rs-admin-link-new',
				'profile' => 'rs-admin-link-panel rs-admin-link-profile',
				'logout' => 'rs-admin-link-panel rs-admin-link-logout'
			]
		]
	];

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Language $language instance of the language class
	 */

	public function __construct(Registry $registry, Language $language)
	{
		$this->_registry = $registry;
		$this->_language = $language;
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the dock
	 */

	public function init(array $optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		}
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminPanelStart');
		$outputItem = null;

		$counter = 1;

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_optionArray['className']['list']['panel']
		]);

		/* collect item output */

		if ($this->_hasPermission('contents'))
		{
			$counter++;
			$outputItem .= $this->_renderContent();
		}
		if ($this->_hasPermission('profile'))
		{
			$counter++;
			$outputItem .= $this->_renderProfile();
		}
		$outputItem .= $this->_renderLogout();

		/* collect output */

		$output .= $listElement
			->append($outputItem)
			->attr('data-column', $counter);
		$output .= Module\Hook::trigger('adminPanelEnd');
		return $output;
	}

	/**
	 * has the permission
	 *
	 * @since 4.0.0
	 *
	 * @param string $type
	 *
	 * @return string
	 */

	protected function _hasPermission(string $type = null)
	{
		$permissionArray = [];
		$categoriesNew = $this->_registry->get('categoriesNew');
		$categoriesEdit = $this->_registry->get('categoriesEdit');
		$categoriesDelete = $this->_registry->get('categoriesDelete');
		$articlesNew = $this->_registry->get('articlesNew');
		$articlesEdit = $this->_registry->get('articlesEdit');
		$articlesDelete = $this->_registry->get('articlesDelete');
		$extrasNew = $this->_registry->get('extrasNew');
		$extrasEdit = $this->_registry->get('extrasEdit');
		$extrasDelete = $this->_registry->get('extrasDelete');
		$commentsNew = $this->_registry->get('commentsNew');
		$commentsEdit = $this->_registry->get('commentsEdit');
		$commentsDelete = $this->_registry->get('commentsDelete');
		$usersNew = $this->_registry->get('usersNew');
		$usersEdit = $this->_registry->get('usersEdit');
		$usersDelete = $this->_registry->get('usersDelete');
		$groupsNew = $this->_registry->get('groupsNew');
		$groupsEdit = $this->_registry->get('groupsEdit');
		$groupsDelete = $this->_registry->get('groupsDelete');
		$modulesInstall = $this->_registry->get('modulesInstall');
		$modulesEdit = $this->_registry->get('modulesEdit');
		$modulesUninstall = $this->_registry->get('modulesUninstall');
		$settingsEdit = $this->_registry->get('settingsEdit');
		$myId = $this->_registry->get('myId');

		/* handle permission */

		if ($categoriesNew || $categoriesEdit || $categoriesDelete)
		{
			$permissionArray['categories'] = $permissionArray['contents'] = true;
		}
		if ($articlesNew || $articlesEdit || $articlesDelete)
		{
			$permissionArray['articles'] = $permissionArray['contents'] = true;
		}
		if ($extrasNew || $extrasEdit || $extrasDelete)
		{
			$permissionArray['extras'] = $permissionArray['contents'] = true;
		}
		if ($commentsNew || $commentsEdit || $commentsDelete)
		{
			$permissionArray['comments'] = $permissionArray['contents'] = true;
		}
		if ($usersNew || $usersEdit || $usersDelete)
		{
			$permissionArray['users'] = $permissionArray['access'] = true;
		}
		if ($groupsNew || $groupsEdit || $groupsDelete)
		{
			$permissionArray['groups'] = $permissionArray['access'] = true;
		}
		if ($modulesInstall || $modulesEdit || $modulesUninstall)
		{
			$permissionArray['modules'] = $permissionArray['system'] = true;
		}
		if ($settingsEdit)
		{
			$permissionArray['settings'] = $permissionArray['system'] = true;
		}
		if ($myId)
		{
			$permissionArray['profile'] = true;
		}
		return array_key_exists($type, $permissionArray);
	}

	/**
	 * render the content
	 *
	 * @since 4.0.0
	 *
	 * @return string|null
	 */

	protected function _renderContent() : ?string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$contentArray =
		[
			'categories',
			'articles',
			'extras',
			'comments'
		];

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_optionArray['className']['list']['content']
		]);
		$itemElement = new Html\Element();
		$itemElement->init('li');
		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'class' => $this->_optionArray['className']['link']['panel']
		]);
		$textElement = new Html\Element();
		$textElement->init('span');

		/* process content */

		foreach ($contentArray as $type)
		{
			$tableNew = $this->_registry->get($type . 'New');
			if ($this->_hasPermission($type))
			{
				$listElement->append(
					$itemElement
						->copy()
						->html(
							$textElement
								->copy()
								->addClass($this->_optionArray['className']['text']['group'])
								->html(
									$linkElement
										->copy()
										->addClass($this->_optionArray['className']['link']['view'])
										->attr('href', $parameterRoute . 'admin/view/' . $type)
										->text($this->_language->get($type))
								)
								->append($tableNew ? $linkElement
									->copy()
									->addClass($this->_optionArray['className']['link']['new'])
									->attr('href', $parameterRoute . 'admin/new/' . $type)
									->text($this->_language->get('new')) : null
								)
						)
				);
			}
		}

		/* collect output */

		$output .= $itemElement
			->copy()
			->addClass($this->_optionArray['className']['item']['content'])
			->html(
				$textElement
					->copy()
					->addClass($this->_optionArray['className']['text']['content'])
					->text($this->_language->get('contents'))
				. $listElement
			);
		return $output;
	}

	/**
	 * render the profile
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderProfile() : string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$myId = $this->_registry->get('myId');

		/* html elements */

		$itemElement = new Html\Element();
		$itemElement->init('li',
		[
			'class' => $this->_optionArray['className']['item']['profile']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'href' => $parameterRoute . 'admin/edit/users/' . $myId,
			'class' => $this->_optionArray['className']['link']['profile']
		])
		->text($this->_language->get('profile'));

		/* collect item output */

		$output = $itemElement->html($linkElement);
		return $output;
	}

	/**
	 * render the logout
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderLogout() : string
	{
		$output = null;
		$parameterRoute = $this->_registry->get('parameterRoute');

		/* html elements */

		$itemElement = new Html\Element();
		$itemElement->init('li',
		[
			'class' => $this->_optionArray['className']['item']['logout']
		]);
		$linkElement = new Html\Element();
		$linkElement->init('a',
		[
			'href' => $parameterRoute . 'logout',
			'class' => $this->_optionArray['className']['link']['logout']
		])
		->text($this->_language->get('logout'));

		/* collect item output */

		$output = $itemElement->html($linkElement);
		return $output;
	}
}
