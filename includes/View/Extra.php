<?php
namespace Redaxscript\View;

use Redaxscript\Admin;
use Redaxscript\Config;
use Redaxscript\Content;
use Redaxscript\Html;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Request;
use Redaxscript\Registry;
use Redaxscript\Language;
use Redaxscript\Validator;

/**
 * children class to create the extra
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Extra extends ViewAbstract
{
	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * options of the extra
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
			'title' => 'rs-title-extra',
			'box' => 'rs-box-extra'
		]
	];

	/**
	 * constructor of the class
	 *
	 * @since 4.0.0
	 *
	 * @param Registry $registry instance of the registry class
	 * @param Request $request instance of the request class
	 * @param Language $language instance of the language class
	 * @param Config $config instance of the config class
	 */

	public function __construct(Registry $registry, Request $request, Language $language, Config $config)
	{
		parent::__construct($registry, $language);
		$this->_request = $request;
		$this->_config = $config;
	}

	/**
	 * stringify the extra
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
	 * @param array $optionArray options of the extra
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
	 * @param string $extraAlias alias of the extra
	 *
	 * @return string
	 */

	public function render(string $extraAlias = null) : string
	{
		$output = Module\Hook::trigger('extraStart');
		$accessValidator = new Validator\Access();
		$extraModel = new Model\Extra();
		$contentParser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$language = $this->_registry->get('language');
		$loggedIn = $this->_registry->get('loggedIn');
		$token = $this->_registry->get('token');
		$firstParameter = $this->_registry->get('firstParameter');
		$myGroups = $this->_registry->get('myGroups');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init($this->_optionArray['tag']['title'],
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$boxElement = $element
			->copy()
			->init($this->_optionArray['tag']['box'],
			[
				'class' => $this->_optionArray['className']['box']
			]);

		/* query extras */

		$extras = $extraAlias ? $extraModel->getResultByAliasAndLanguage($extraAlias, $language) : $extraModel->getResultByLanguage($language);

		/* process extras */

		foreach ($extras as $value)
		{
			if ($accessValidator->validate($value->access, $myGroups) === Validator\ValidatorInterface::PASSED)
			{
				$output .= Module\Hook::trigger('extraFragmentStart', $value);
				if (intval($value->headline) === 1)
				{
					$output .= $titleElement
						->attr('id', 'extra-' . $value->alias)
						->text($value->title);
				}
				$contentParser->process($value->text);
				$output .= $boxElement->html($contentParser->getOutput()) . Module\Hook::trigger('extraFragmentEnd', $value);

				/* admin dock */

				if ($loggedIn === $token && $firstParameter !== 'logout')
				{
					$output .= $this->_renderAdminDock('extras', $value->id);
				}
			}
		}
		$output .= Module\Hook::trigger('extraEnd');
		return $output;
	}

	/**
	 * render the admin dock
	 *
	 * @since 4.0.0
	 *
	 * @param string $table name of the table
	 * @param string $id identifier of the item
	 *
	 * @return string
	 */

	protected function _renderAdminDock(string $table = null, string $id = null) : string
	{
		$adminDock = new Admin\View\Helper\Dock($this->_registry, $this->_language);
		$adminDock->init();
		return $adminDock->render($table, $id);
	}
}
