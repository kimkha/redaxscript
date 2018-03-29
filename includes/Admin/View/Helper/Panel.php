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
			'list' => 'rs-admin-js-list-panel rs-admin-list-panel rs-admin-fn-clearfix',
			'item' =>
			[
				'profile' => 'rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-profile',
				'logout' => 'rs-admin-js-item-panel rs-admin-item-panel rs-admin-item-logout'
			],
			'link' =>
			[
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
		$myId = $this->_registry->get('myId');
		$counter = 1;

		/* html elements */

		$listElement = new Html\Element();
		$listElement->init('ul',
		[
			'class' => $this->_optionArray['className']['list']
		]);

		/* collect item output */

		if ($myId)
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
