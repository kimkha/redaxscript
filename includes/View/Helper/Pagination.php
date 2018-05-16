<?php
namespace Redaxscript\View\Helper;

use Redaxscript\Html;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Language;

/**
 * helper class to create the pagination
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Pagination
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
	 * options of the pagination
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'className' =>
		[
			'list' => 'rs-list-pagination',
			'item' =>
			[
				'first' => 'rs-item-first',
				'previous' => 'rs-item-previous',
				'next' => 'rs-item-next',
				'last' => 'rs-item-last',
				'number' => 'rs-item-number',
				'active' => 'rs-item-active'
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
	 * @param array $optionArray options of the pagination
	 */

	public function init(array $optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_merge($this->_optionArray, $optionArray);
		};
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param string $route
	 * @param int $current
	 * @param int $total
	 * @param int $range
	 *
	 * @return string
	 */

	public function render(string $route = null, int $current = null, int $total = null, int $range = null) : string
	{
		$output = Module\Hook::trigger('paginationStart');
		$outputItem = null;
		$parameterRoute = $this->_registry->get('parameterRoute');
		$numberArray = $this->_getNumberArray($current, $total, $range);

		/* html element */

		$element = new Html\Element();
		$listElement = $element
			->copy()
			->init('ul',
			[
				'class' => $this->_optionArray['className']['list']
			]);
		$itemElement = $element->copy()->init('li');
		$linkElement = $element->copy()->init('a');
		$textElement = $element->copy()->init('span');

		/* first and previous */

		if ($current > 1)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['first'])
				->html(
					$linkElement
						->copy()
						->attr('href', $parameterRoute . $route)
						->text($this->_language->get('first'))
				);
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['previous'])
				->html(
					$linkElement
						->copy()
						->attr(
						[
							'href' => $parameterRoute . $route . '/' . ($current - 1),
							'rel' => 'prev'
						])
						->text($this->_language->get('previous'))
				);
		}

		/* process number */

		foreach ($numberArray as $value)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['number'])
				->addClass($value['active'] ? $this->_optionArray['className']['item']['active'] : null)
				->html(
					$value['active'] ? $textElement->text($value['number']) : $linkElement
						->copy()
						->attr('href', $parameterRoute . $route . '/' . $value['number'])
						->text($value['number'])
				);
		}

		/* next and last */

		if ($current < $total)
		{
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['next'])
				->html(
					$linkElement
						->copy()
						->attr(
						[
							'href' => $parameterRoute . $route . '/' . ($current + 1),
							'rel' => 'next'
						])
						->text($this->_language->get('next'))
				);
			$outputItem .= $itemElement
				->copy()
				->addClass($this->_optionArray['className']['item']['last'])
				->html(
					$linkElement
						->copy()
						->attr('href', $parameterRoute . $route . '/' . $total)
						->text($this->_language->get('last'))
				);
		}

		/* collect output */

		if ($outputItem)
		{
			$output .= $listElement->html($outputItem);
		}
		$output .= Module\Hook::trigger('paginationEnd');
		return $output;
	}

	/**
	 * get the number array
	 *
	 * @since 4.0.0
	 *
	 * @param int $current
	 * @param int $total
	 * @param int $range
	 *
	 * @return array
	 */

	public function _getNumberArray(int $current = null, int $total = null, int $range = null) : array
	{
		$numberArray = [];
		$start = $current - $range;
		$end = $current + $range + 1;

		/* process range */

		for ($i = $start; $i < $end; $i++)
		{
			if ($i < 1)
			{
				$end++;
			}
			if ($i > $total)
			{
				$start--;
			}
		}

		/* process number */

		for ($i = $start; $i < $end; $i++)
		{
			if ($i >= 1 && $i <= $total)
			{
				$numberArray[] =
				[
					'number' => $i,
					'active' => $i === $current
				];
			}
		}
		return $numberArray;
	}
}
