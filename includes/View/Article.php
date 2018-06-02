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
 * children class to create the article
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Article extends ViewAbstract
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
	 * options of the article
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
	 * stringify the article
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
	 * @param array $optionArray options of the article
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
	 * @param string $categoryId alias of the category
	 *
	 * @return string
	 */

	public function render(string $categoryId = null) : string
	{
		$output = Module\Hook::trigger('articleStart');
		$accessValidator = new Validator\Access();
		$articleModel = new Model\Article();
		$contentParser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$language = $this->_registry->get('language');
		$loggedIn = $this->_registry->get('loggedIn');
		$token = $this->_registry->get('token');
		$firstParameter = $this->_registry->get('firstParameter');
		$lastTable =  $this->_registry->get('lastTable');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$myGroups = $this->_registry->get('myGroups');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init($this->_optionArray['tag']['title'],
			[
				'class' => $this->_optionArray['className']['title']
			]);
		$linkElement = $element->copy()->init('a');
		$boxElement = $element
			->copy()
			->init($this->_optionArray['tag']['box'],
			[
				'class' => $this->_optionArray['className']['box']
			]);

		/* query articles */

		$articles = $categoryId ? null : $articleModel->getManyByLanguage($language);

		/* process articles */

		foreach ($articles as $value)
		{
			if ($accessValidator->validate($value->access, $myGroups) === Validator\ValidatorInterface::PASSED)
			{
				$output .= Module\Hook::trigger('articleFragmentStart', $value);
				if (intval($value->headline) === 1)
				{
					$output .= $titleElement
						->attr('id', 'article-' . $value->alias)
						->html($lastTable === 'categories' ? $linkElement
							->attr('href', $parameterRoute . $articleModel->getRouteById($value->id))
							->text($value->title) : $value->title
						);
				}
				$contentParser->process($value->text);
				$output .= $boxElement->html($contentParser->getOutput()) . Module\Hook::trigger('articleFragmentEnd', $value);

				/* admin dock */

				if ($loggedIn === $token && $firstParameter !== 'logout')
				{
					$output .= $this->_renderAdminDock($value->id);
				}
			}
		}
		$output .= Module\Hook::trigger('articleEnd');
		return $output;
	}

	/**
	 * render the admin dock
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string
	 */

	protected function _renderAdminDock(int $articleId = null) : string
	{
		$adminDock = new Admin\View\Helper\Dock($this->_registry, $this->_language);
		$adminDock->init();
		return $adminDock->render('articles', $articleId);
	}
}
