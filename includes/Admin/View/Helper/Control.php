<?php
namespace Redaxscript\Admin\View\Helper;

use Redaxscript\Html;
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
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param int $id identifier of the item
	 * @param string $alias alias of the item
	 * @param int $status status of the item
	 *
	 * @return string|null
	 */

	public function render(string $table = null, int $id = null, string $alias = null, int $status = null) : ?string
	{
		$output = Module\Hook::trigger('adminControlStart');
		$outputItem = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$token = $this->_registry->get('token');
		$publishArray =
		[
			'categories',
			'articles',
			'extras',
			'comments'
		];
		$enableArray =
		[
			'groups',
			'users',
			'modules'
		];
		$deleteArray =
		[
			'categories',
			'articles',
			'extras',
			'comments',
			'groups',
			'users'
		];

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => 'rs-admin-list-control'
			]);
		$itemElement = $element
			->copy()
			->init('li',
			[
				'class' => 'rs-admin-item-control'
			]);
		$linkElement = $element
			->copy()
			->init('a');
		$textElement = $element
			->copy()
			->init('span');

		/* collect enable */

		if (in_array($table, $enableArray) && $this->_hasPermission($table, 'edit'))
		{
			$enableAction = $status === 1 ? 'disable' : 'enable';
			$outputItem .= $itemElement
				->copy()
				->addClass($enableAction === 'disable' ? 'rs-admin-item-disable' : 'rs-admin-item-enable')
				->html(
					$linkElement
						->copy()
						->attr('href', $parameterRoute . 'admin/' . $enableAction . '/' . $table . '/' . $id . '/' . $token)
						->text($enableAction === 'disable' ? $this->_language->get('disable') : $this->_language->get('enable'))
				);
		}

		/* collect publish */

		if (in_array($table, $publishArray) && $this->_hasPermission($table, 'edit'))
		{
			if ($status === 2)
			{
				$outputItem .= $itemElement
					->copy()
					->addClass('rs-admin-item-future-posting')
					->html(
						$textElement
							->copy()
							->text($this->_language->get('future_posting'))
					);
			}
			else
			{
				$publishAction = $status === 1 ? 'unpublish' : 'publish';
				$outputItem .= $itemElement
					->copy()
					->addClass($publishAction === 'unpublish' ? 'rs-admin-item-unpublish' : 'rs-admin-item-publish')
					->html(
						$linkElement
							->copy()
							->attr('href', $parameterRoute . 'admin/' . $publishAction . '/' . $table . '/' . $id . '/' . $token)
							->text($publishAction === 'unpublish' ? $this->_language->get('unpublish') : $this->_language->get('publish'))
					);
			}
		}

		/* collect install */

		if ($table === 'modules')
		{
			if ($status === 1 && $this->_hasPermission($table, 'uninstall'))
			{
				$outputItem .= $itemElement
					->copy()
					->addClass('rs-admin-item-uninstall')
					->html(
						$linkElement
							->copy()
							->attr('href', $parameterRoute . 'admin/uninstall/' . $table . '/' . $id . '/' . $token)
							->text($this->_language->get('uninstall'))
					);
			}
			else if ($this->_hasPermission($table, 'install'))
			{
				$outputItem .= $itemElement
					->copy()
					->addClass('rs-admin-item-install')
					->html(
						$linkElement
							->copy()
							->attr('href', $parameterRoute . 'admin/install/' . $table . '/' . $alias . '/' . $token)
							->text($this->_language->get('install'))
					);
			}
		}

		/* collect edit */

		if ($this->_hasPermission($table, 'edit'))
		{
			$outputItem .= $itemElement
				->copy()
				->addClass('rs-admin-item-edit')
				->html(
					$linkElement
						->copy()
						->attr('href', $parameterRoute . 'admin/edit/' . $table . '/' . $id)
						->text($this->_language->get('edit'))
				);
		}

		/* collect delete */

		if (in_array($table, $deleteArray) && $this->_hasPermission($table, 'delete'))
		{
			$outputItem .= $itemElement
				->copy()
				->addClass('rs-admin-item-delete')
				->html(
					$linkElement
						->copy()
						->addClass('rs-admin-js-confirm')
						->attr('href', $parameterRoute . 'admin/delete/' . $table . '/' . $id . '/' . $token)
						->text($this->_language->get('delete'))
				);
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('adminControlEnd');
		return $output;
	}

	/**
	 * has the permission
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param string $type
	 *
	 * @return bool
	 */

	protected function _hasPermission(string $table = null, string $type = null) : bool
	{
		return $this->_registry->get($table . ucfirst($type));
	}
}