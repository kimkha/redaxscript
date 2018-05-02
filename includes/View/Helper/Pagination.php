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

		/* handle range */

		if ($current == 2 || $current == $total - 1)
		{
			$range++;
		}
		if ($current == 1 || $current == $total)
		{
			$range = $range + 2;
		}

		/* process range */

		for ($i = $current - $range; $i < $current + $range; $i++)
		{
			if (intval($i) === intval($current))
			{
				$range++;
				$outputItem .=  $itemElement
					->copy()
					->addClass($this->_optionArray['className']['item']['number'])
					->addClass($this->_optionArray['className']['item']['active'])
					->html(
						$textElement->html($i)
					);
			}
			else if ($i > 0 && $i < $total + 1)
			{
				$outputItem .= $itemElement
					->copy()
					->addClass($this->_optionArray['className']['item']['number'])
					->html(
						$linkElement
							->copy()
							->attr('href', $parameterRoute . $route . '/' . $i)
							->text($i)
					);
			}
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

		$output .= $listElement->html($outputItem);
		$output .= Module\Hook::trigger('paginationEnd');
		return $output;
	}
}
