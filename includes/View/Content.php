<?php
namespace Redaxscript\View;

use function Redaxscript\Admin\View\Helper\admin_dock;
use Redaxscript\Config;
use Redaxscript\Content as BaseContent;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Messenger;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Validator;

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
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		ob_start();
		contents();
		return ob_get_clean();
	}
}

/**
 * contents
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 */

function contents()
{
	$registry = Registry::getInstance();
	$request = Request::getInstance();
	$language = Language::getInstance();
	$config = Config::getInstance();
	$output = Module\Hook::trigger('contentStart');
	$aliasValidator = new Validator\Alias();
	$settingModel = new Model\Setting();
	$lastId = $registry->get('lastId');
	$lastTable = $registry->get('lastTable');
	$categoryId = $registry->get('categoryId');
	$articleId = $registry->get('articleId');
	$firstParameter = $registry->get('firstParameter');

	/* query articles */

	$articles = Db::forTablePrefix('articles')->where('status', 1);
	$articles->whereLanguageIs($registry->get('language'));

	/* handle sibling */

	if ($lastId)
	{
		$sibling = Db::forTablePrefix($lastTable)->where('id', $lastId)->findOne()->sibling;

		/* query sibling collection */

		$sibling_array = Db::forTablePrefix($lastTable)->whereIn('sibling',
			[
				$lastId,
				$sibling > 0 ? $sibling : null
			])
			->where('language', $registry->get('language'))->select('id')->findFlatArray();

		/* process sibling array */

		foreach ($sibling_array as $value)
		{
			$id_array[] = $value;
		}
	}

	/* handle article */

	if ($articleId)
	{
		$id_array[] = $sibling;
		$id_array[] = $articleId;
		$articles->whereIn('id', $id_array);
	}

	/* else handle category */

	else if ($categoryId)
	{
		if (!$id_array)
		{
			if ($sibling > 0)
			{
				$id_array[] = $sibling;
			}
			else
			{
				$id_array[] = $categoryId;
			}
		}
		$articles->whereIn('category', $id_array)->orderGlobal('rank');

		/* handle sub parameter */

		$result = $articles->findArray();
		if ($result)
		{
			$num_rows = count($result);
			$sub_maximum = ceil($num_rows / $settingModel->get('limit'));
			$sub_active = $registry->get('lastSubParameter');

			/* sub parameter */

			if ($registry->get('lastSubParameter') > $sub_maximum || !$registry->get('lastSubParameter'))
			{
				$sub_active = 1;
			}
			else
			{
				$offset_string = ($sub_active - 1) * $settingModel->get('limit') . ', ';
			}
		}
		$articles->limit($offset_string . $settingModel->get('limit'));
	}
	else
	{
		$articles->limit(0);
	}

	/* query result */

	$result = $articles->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if ($categoryId && !$num_rows)
	{
		$error = $language->get('article_no');
	}
	else if (!$result || !$num_rows_active || $registry->get('contentError'))
	{
		$error = $language->get('content_not_found');
	}

	/* collect output */

	else if ($result)
	{
		$articleModel = new Model\Article();
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
							$$key = $value;
						}
					}
				}
				if ($lastTable == 'categories' || !$registry->get('fullRoute') || $aliasValidator->validate($firstParameter, Validator\Alias::MODE_DEFAULT) == Validator\ValidatorInterface::PASSED)
				{
					$route = $articleModel->getRouteById($id);
				}

				/* parser */

				$parser = new BaseContent\Parser($registry, $request, $language, $config);
				$parser->process($text, $route);

				/* collect headline output */

				$output .= Module\Hook::trigger('contentFragmentStart', $r);
				if ($headline == 1)
				{
					$output .= '<h2 class="rs-title-content" id="article-' . $alias . '">';
					if ($lastTable == 'categories' || !$registry->get('fullRoute') || $aliasValidator->validate($firstParameter, Validator\Alias::MODE_DEFAULT) == Validator\ValidatorInterface::PASSED
					)
					{
						$output .= '<a href="' . $registry->get('parameterRoute') . $route . '">' . $title . '</a>';
					}
					else
					{
						$output .= $title;
					}
					$output .= '</h2>';
				}

				/* collect box output */

				$output .= '<div class="rs-box-content">' . $parser->getOutput() . '</div>';
				if ($byline == 1)
				{
					$bylineHelper = new Helper\Byline();
					$output .= $bylineHelper->render(
					[
						'table' => 'articles',
						'id' => $id,
						'author' => $author,
						'date' => $date
					]);
				}
				$output .= Module\Hook::trigger('contentFragmentEnd', $r);

				/* admin dock */

				if ($registry->get('loggedIn') == $registry->get('token') && $firstParameter != 'logout')
				{
					$output .= admin_dock('articles', $id);
				}
			}
			else
			{
				$counter++;
			}
		}
		echo 'test';
		/* handle access */

		if ($lastTable == 'categories')
		{
			if ($num_rows_active == $counter)
			{
				$error = $language->get('access_no');
			}
		}
		else if ($lastTable == 'articles' && $counter == 1)
		{
			$error = $language->get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		/* show error */

		$messenger = new Messenger($registry);
		echo $messenger->error($error, $language->get('something_wrong'));
	}
	else
	{
		$output .= Module\Hook::trigger('contentEnd');
		echo $output;

		/* call comments as needed */

		if ($articleId)
		{
			/* comments replace */

			if ($comments == 1 && $registry->get('commentReplace'))
			{
				Module\Hook::trigger('commentReplace');
			}

			/* else native comments */

			else if ($comments > 0)
			{
				$articleModel = new Model\Article();
				$comment = new Comment($registry, $language);
				$comment->render(
				[
					'articleId' => $articleId,
					'route' => $articleModel->getRouteById($articleId)
				]);

				/* comment form */

				if ($comments == 1 || ($registry->get('commentNew') && $comments == 3))
				{
					$commentForm = new CommentForm($registry, $language);
					echo $commentForm->render($articleId);
				}
			}
		}
	}

	/* call pagination as needed */

	if ($sub_maximum > 1 && $settingModel->get('pagination') == 1)
	{
		$categoryModel = new Model\Category();
		$route = $categoryModel->getRouteById($categoryId);
		pagination($sub_active, $sub_maximum, $route);
	}
}
