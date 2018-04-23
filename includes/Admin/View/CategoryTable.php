<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * children class to create the admin user table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class CategoryTable extends ViewAbstract implements ViewInterface
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
		$output = Module\Hook::trigger('adminCategoryTableStart');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$token = $this->_registry->get('token');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('categories'));
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-button'
			]);
		$linkNew = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default rs-admin-button-create',
				'href' => $parameterRoute . 'admin/new/categories'
			])
			->text($this->_language->get('category_new'));
		$linkSort = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default',
				'href' => $parameterRoute . 'admin/sort/categories/' . $token
			])
			->text($this->_language->get('sort'));

		/* collect output */

		$wrapperElement->html($linkNew . $linkSort);
		$tableElement = $this->_renderTable();
		$output .= $titleElement . $wrapperElement . $tableElement;
		$output .= Module\Hook::trigger('adminCategoryTableEnd');
		return $output;
	}

	/**
	 * render the table
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderTable() : string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'title' => $this->_language->get('title'),
			'alias' => $this->_language->get('alias'),
			'parent' => $this->_language->get('category_parent'),
			'rank' => $this->_language->get('rank')
		];
		$categoryModel = new Model\Category();
		$categories = $categoryModel->getAll();

		/* html element */

		$element = new Html\Element();
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-table'
			]);
		$tableElement = $element
			->copy()
			->init('table',
			[
				'class' => 'rs-admin-table-default'
			]);
		$theadElement = $element->copy()->init('thead');
		$tbodyElement = $element->copy()->init('tbody');
		$tfootElement = $element->copy()->init('tfoot');
		$trElement = $element->copy()->init('tr');
		$thElement = $element->copy()->init('th');
		$tdElement = $element->copy()->init('td');

		/* process table */

		foreach ($tableArray as $key => $value)
		{
			$outputHead .= $thElement->text($value);
			$outputFoot .= $tdElement->text($value);
		}

		/* process categories */

		foreach ($categories as $key => $value)
		{
			$outputBody .= $trElement->html(
				$tdElement->copy()->text($value->title) .
				$tdElement->copy()->text($value->alias) .
				$tdElement->copy()->text($value->parent ? $categories[$value->parent]->title : $this->_language->get('none')) .
				$tdElement->copy()->text($value->rank)
			);
		}

		/* collect output */

		$outputHead = $theadElement->html(
			$trElement->html($outputHead)
		);
		$outputBody = $tbodyElement->html($outputBody);
		$outputFoot = $tfootElement->html(
			$trElement->html($outputFoot)
		);
		$output .= $wrapperElement->copy()->html(
			$tableElement->html($outputHead . $outputBody .	$outputFoot)
		);
		return $output;
	}
}
