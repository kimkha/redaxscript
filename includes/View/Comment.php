<?php
namespace Redaxscript\View;

use function Redaxscript\Admin\View\Helper\admin_dock;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Model;
use Redaxscript\Module;
use Redaxscript\Validator;
use function Redaxscript\View\Helper\pagination;

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
	 * render the view
	 *
	 * @param array $optionArray options of the form
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		ob_start();
		comments($optionArray['articleId'], $optionArray['route']);
		return ob_get_clean();
	}
}

function comments($article, $route)
{
	$registry = Registry::getInstance();
	$language = Language::getInstance();
	$output = Module\Hook::trigger('commentStart');
	$settingModel = new Model\Setting();

	/* query comments */

	$comments = Db::forTablePrefix('comments')
		->where(
		[
			'status' => 1,
			'article' => $article
		])
		->whereLanguageIs($registry->get('language'))
		->orderGlobal('rank');

	/* query result */

	$result = $comments->findArray();
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
	$comments->limit($offset_string . $settingModel->get('limit'));

	/* query result */

	$result = $comments->findArray();
	$num_rows_active = count($result);

	/* handle error */

	if (!$result || !$num_rows)
	{
		$error = $language->get('comment_no');
	}

	/* collect output */

	else if ($result)
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

				/* collect headline output */

				$output .= Module\Hook::trigger('commentFragmentStart', $r) . '<h3 id="comment-' . $id . '" class="rs-title-comment">';
				if ($url)
				{
					$output .= '<a href="' . $url . '" rel="nofollow">' . $author . '</a>';
				}
				else
				{
					$output .= $author;
				}
				$output .= '</h3>';

				/* collect box output */

				$output .= '<div class="rs-box-comment">' . $text . '</div>';
				$bylineHelper = new Helper\Byline();
				$output .= $bylineHelper->render(
				[
					'table' => 'comments',
					'id' => $id,
					'author' => $author,
					'date' => $date
				]);
				$output .= Module\Hook::trigger('commentFragmentEnd', $r);

				/* admin dock */

				if ($registry->get('loggedIn') == $registry->get('token') && $registry->get('firstParameter') != 'logout')
				{
					$output .= admin_dock('comments', $id);
				}
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows_active == $counter)
		{
			$error = $language->get('access_no');
		}
	}

	/* handle error */

	if ($error)
	{
		$output = '<div class="rs-box-comment">' . $error . $language->get('point') . '</div>';
	}
	$output .= Module\Hook::trigger('commentEnd');
	echo $output;

	/* call pagination as needed */

	if ($sub_maximum > 1 && $settingModel->get('pagination') == 1)
	{
		pagination($sub_active, $sub_maximum, $route);
	}
}