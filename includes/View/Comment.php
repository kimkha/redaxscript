<?php
namespace Redaxscript\View;

use Redaxscript\Module;

/**
 * children class to create the comment
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Comment extends ViewAbstract
{
	/**
	 * options of the comment
	 *
	 * @var array
	 */

	protected $_optionArray =
	[
		'tag' =>
		[
			'title' => 'h3',
			'box' => 'div'
		],
		'className' =>
		[
			'title' => 'rs-title-comment',
			'box' => 'rs-box-comment'
		]
	];

	/**
	 * stringify the comment
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
	 * @param array $optionArray options of the comment
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
	 * @param string $articleAlias alias of the article
	 *
	 * @return string
	 */

	public function render(string $articleAlias = null) : string
	{
		$output = Module\Hook::trigger('commentStart');
		$output .= Module\Hook::trigger('commentEnd');
		return $output;
	}
}
