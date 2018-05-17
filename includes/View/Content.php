<?php
namespace Redaxscript\View;

use Redaxscript\Module;

/**
 * children class to create the content
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Content extends ViewAbstract
{
	/**
	 * options of the content
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'tag' =>
		[
			'title' => 'h2',
			'box' => 'div'
		],
		'className' =>
		[
			'title' => 'rs-title-content',
			'box' => 'rs-box-content'
		]
	];

	/**
	 * stringify the content
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function __toString() : string
	{
		return $this->render();
	}

	/**
	 * init the class
	 *
	 * @since 4.0.0
	 *
	 * @param array $optionArray options of the content
	 */

	public function init(array $optionArray = [])
	{
		if (is_array($optionArray))
		{
			$this->_optionArray = array_replace_recursive($this->_optionArray, $optionArray);
		}
	}

	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @param string $categoryAlias alias of the category
	 *
	 * @return string
	 */

	public function render(string $categoryAlias = null) : string
	{
		$output = Module\Hook::trigger('contentStart');
		$output .= Module\Hook::trigger('contentEnd');
		$output .= $categoryAlias;
		return $output;
	}
}
