<?php
namespace Redaxscript\View;

use Redaxscript\Config;
use Redaxscript\Content;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Request;
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
	 * render the view
	 *
	 * @param string $extraAlias alias of the extra
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render(string $extraAlias = null) : string
	{
		//TODO: refactor
		$registry = Registry::getInstance();
		$request = Request::getInstance();
		$language = Language::getInstance();
		$config = Config::getInstance();
		if (!$filter)
		{
			$output .= Module\Hook::trigger('extraStart');
		}
		$categoryId = $registry->get('categoryId');
		$articleId = $registry->get('articleId');
		$firstParameter = $registry->get('firstParameter');

		/* query extras */

		$extras = Db::forTablePrefix('extras')
			->whereLanguageIs($registry->get('language'));

		/* has filter */

		//TODO: move this stuff into the extra model
		if ($filter)
		{
			$id = Db::forTablePrefix('extras')->where('alias', $filter)->findOne()->id;

			/* handle sibling */

			$sibling = Db::forTablePrefix('extras')->where('id', $id)->findOne()->sibling;

			/* query sibling collection */

			$sibling_array = Db::forTablePrefix('extras')->whereIn('sibling',
				[
					$id,
					$sibling > 0 ? $sibling : null
				])
				->where('language', $registry->get('language'))->select('id')->findFlatArray();

			/* process sibling array */

			foreach ($sibling_array as $value)
			{
				$id_array[] = $value;
			}
			$id_array[] = $sibling;
			$id_array[] = $id;
		}
		else
		{
			$id_array = $extras->where('status', 1)->orderByAsc('rank')->select('id')->findFlatArray();
		}

		/* query result */

		if ($id_array)
		{
			$result = $extras->whereIn('id', $id_array)->findArray();
		}

		/* collect output */

		if ($result)
		{
			$accessValidator = new Validator\Access();
			foreach ($result as $r)
			{
				$access = $r['access'];

				/* access granted */

				if ($accessValidator->validate($access, $registry->get('myGroups')) === Validator\ValidatorInterface::PASSED)
				{
					if ($r)
					{
						foreach ($r as $key => $value)
						{
							if ($key !== 'language')
							{
								$$key = stripslashes($value);
							}
						}
					}

					/* show if category or article matched */

					if ($category === $categoryId || $article === $articleId || (!$category && !$article))
					{
						/* parser */

						$parser = new Content\Parser($registry, $request, $language, $config);
						$parser->process($text, $route);

						/* collect headline output */

						$output .= Module\Hook::trigger('extraFragmentStart', $r);
						if ($headline == 1)
						{
							$output .= '<h3 class="rs-title-extra" id="extra-' . $alias . '">' . $title . '</h3>';
						}

						/* collect box output */

						$output .= '<div class="rs-box-extra">' . $parser->getOutput() . '</div>' . Module\Hook::trigger('extraFragmentEnd', $r);

						/* prepend admin dock */

						if ($registry->get('loggedIn') == $registry->get('token') && $firstParameter != 'logout')
						{
							//$output .= admin_dock('extras', $id);
						}
					}
				}
			}
		}
		if (!$filter)
		{
			$output .= Module\Hook::trigger('extraEnd');
		}
		return $output;
	}
}
