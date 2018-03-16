<?php
namespace Redaxscript\Admin\View;

use function Redaxscript\Admin\View\Helper\admin_control;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;
use Redaxscript\Validator;

/**
 * children class to create the admin content table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class ContentTable extends ViewAbstract implements ViewInterface
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
		admin_contents_list();
		return ob_get_clean();
	}
}

/**
 * admin contents list
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_contents_list()
{
	$registry = Registry::getInstance();
	$language = Language::getInstance();
	$output = Module\Hook::trigger('adminContentListStart');

	/* define access variables */

	$tableParameter = $registry->get('tableParameter');
	$table_new = $registry->get('tableNew');
	if ($tableParameter == 'comments')
	{
		$articles_total = Db::forTablePrefix('articles')->count();
		$articles_comments_disable = Db::forTablePrefix('articles')->where('comments', 0)->count();
		if ($articles_total == $articles_comments_disable)
		{
			$table_new = 0;
		}
	}

	/* switch table */

	switch ($tableParameter)
	{
		case 'categories':
			$wording_single = 'category';
			$wording_parent = 'category_parent';
			break;
		case 'articles':
			$wording_single = 'article';
			$wording_parent = 'category';
			break;
		case 'extras':
			$wording_single = 'extra';
			break;
		case 'comments':
			$wording_single = 'comment';
			$wording_parent = 'article';
			break;
	}

	/* query contents */

	$result = Db::forTablePrefix($tableParameter)->orderByAsc('rank')->findArray();
	$num_rows = count($result);

	/* collect listing output */

	$output .= '<h2 class="rs-admin-title-content">' . $language->get($tableParameter) . '</h2>';
	$output .= '<div class="rs-admin-wrapper-button">';
	if ($table_new == 1)
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/new/' . $registry->get('tableParameter') . '" class="rs-admin-button-default rs-admin-button-create">' . $language->get($wording_single . '_new') . '</a>';
	}
	if ($registry->get('tableEdit') == 1 && $num_rows)
	{
		$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/sort/' . $registry->get('tableParameter') . '/' . $registry->get('token') . '" class="rs-admin-button-default">' . $language->get('sort') . '</a>';
	}
	$output .= '</div><div class="rs-admin-wrapper-table"><table class="rs-admin-table-default rs-admin-table-' . $wording_single . '">';

	/* collect thead */

	$output .= '<thead><tr><th class="rs-admin-col-title">' . $language->get('title') . '</th><th class="rs-admin-col-alias">';
	if ($tableParameter == 'comments')
	{
		$output .= $language->get('identifier');
	}
	else
	{
		$output .= $language->get('alias');
	}
	$output .= '</th>';
	if ($tableParameter != 'extras')
	{
		$output .= '<th class="rs-admin-col-parent">' . $language->get($wording_parent) . '</th>';
	}
	$output .= '<th class="rs-admin-col-rank">' . $language->get('rank') . '</th></tr></thead>';

	/* collect tfoot */

	$output .= '<tfoot><tr><td>' . $language->get('title') . '</td><td>';
	if ($tableParameter == 'comments')
	{
		$output .= $language->get('identifier');
	}
	else
	{
		$output .= $language->get('alias');
	}
	$output .= '</td>';
	if ($tableParameter != 'extras')
	{
		$output .= '<td>' . $language->get($wording_parent) . '</td>';
	}
	$output .= '<td class="rs-admin-col-rank">' . $language->get('rank') . '</td></tr></tfoot>';
	if (!$result || !$num_rows)
	{
		$error = $language->get($wording_single . '_no') . $language->get('point');
	}
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
							if ($key !== 'language')
							{
								$$key = stripslashes($value);
							}
						}
					}
				}

				/* prepare name */

				if ($tableParameter == 'comments')
				{
					$name = $author . $language->get('colon') . ' ' . strip_tags($text);
				}
				else
				{
					$name = $title;
				}

				/* build class string */

				if ($status == 1)
				{
					$class_status = null;
				}
				else
				{
					$class_status = 'rs-admin-is-disabled';
				}

				/* build route */

				if ($tableParameter != 'extras' && $status == 1)
				{
					if ($tableParameter == 'categories' && $parent == 0 || $tableParameter == 'articles' && $category == 0)
					{
						$route = $alias;
					}
					else
					{
						$contentModel = new Admin\Model\Content();
						$route = $contentModel->getRouteByTableAndId($tableParameter, $id);
					}
				}
				else
				{
					$route = null;
				}

				/* collect tbody output */

				if ($tableParameter == 'categories')
				{
					if ($before != $parent)
					{
						$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="4">';
						if ($parent)
						{
							$output .= Db::forTablePrefix('categories')->where('id', $parent)->findOne()->title;
						}
						else
						{
							$output .= $language->get('none');
						}
						$output .= '</td></tr>';
					}
					$before = $parent;
				}
				if ($tableParameter == 'articles')
				{
					if ($before != $category)
					{
						$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="4">';
						if ($category)
						{
							$output .= Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
						}
						else
						{
							$output .= $language->get('uncategorized');
						}
						$output .= '</td></tr>';
					}
					$before = $category;
				}
				if ($tableParameter == 'comments')
				{
					if ($before != $article)
					{
						$output .= '<tbody><tr class="rs-admin-row-group"><td colspan="4">';
						if ($article)
						{
							$output .= Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
						}
						else
						{
							$output .= $language->get('none');
						}
						$output .= '</td></tr>';
					}
					$before = $article;
				}

				/* collect table row */

				$output .= '<tr';
				if ($alias)
				{
					$output .= ' id="' . $alias . '"';
				}
				if ($class_status)
				{
					$output .= ' class="' . $class_status . '"';
				}
				$output .= '><td>';

				if ($status == 1)
				{
					$output .= '<a href="' . $registry->get('parameterRoute') . $route . '" class="rs-admin-link-view';
					if ($r['language'])
					{
						$output .= ' rs-admin-has-language" data-language="' . $r['language'];
					}
					$output .= '">' . $name . '</a>';
				}
				else
				{
					$output .= $name;
				}

				/* collect control output */

				$output .= admin_control('contents', $tableParameter, $id, $alias, $status, $registry->get('tableNew'), $registry->get('tableEdit'), $registry->get('tableDelete'));

				/* collect alias and id output */

				$output .= '</td><td>';
				if ($tableParameter == 'comments')
				{
					$output .= $id;
				}
				else
				{
					$output .= $alias;
				}
				$output .= '</td>';

				/* collect parent output */

				if ($tableParameter != 'extras')
				{
					$output .= '<td>';
					if ($tableParameter == 'categories')
					{
						if ($parent)
						{
							$parent_title = Db::forTablePrefix('categories')->where('id', $parent)->findOne()->title;
							$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/categories/' . $parent . '" class="rs-admin-link-parent">' . $parent_title . '</a>';
						}
						else
						{
							$output .= $language->get('none');
						}
					}
					if ($tableParameter == 'articles')
					{
						if ($category)
						{
							$category_title = Db::forTablePrefix('categories')->where('id', $category)->findOne()->title;
							$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/categories/' . $category . '" class="rs-admin-link-parent">' . $category_title . '</a>';
						}
						else
						{
							$output .= $language->get('uncategorized');
						}
					}
					if ($tableParameter == 'comments')
					{
						if ($article)
						{
							$article_title = Db::forTablePrefix('articles')->where('id', $article)->findOne()->title;
							$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/edit/articles/' . $article . '" class="rs-admin-link-parent">' . $article_title . '</a>';
						}
						else
						{
							$output .= $language->get('none');
						}
					}
					$output .= '</td>';
				}
				$output .= '<td class="rs-admin-col-rank">';

				/* collect control output */

				if ($registry->get('tableEdit') == 1)
				{
					$rank_desc = Db::forTablePrefix($tableParameter)->max('rank');
					if ($rank > 1)
					{
						$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/up/' . $registry->get('tableParameter') . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-button-moveup">' . $language->get('up') . '</a>';
					}
					else
					{
						$output .= '<a class="rs-admin-button-moveup rs-admin-is-disabled">' . $language->get('up') . '</a>';
					}
					if ($rank < $rank_desc)
					{
						$output .= '<a href="' . $registry->get('parameterRoute') . 'admin/down/' . $registry->get('tableParameter') . '/' . $id . '/' . $registry->get('token') . '" class="rs-admin-button-movedown">' . $language->get('down') . '</a>';
					}
					else
					{
						$output .= '<a class="rs-admin-button-movedown rs-admin-is-disabled">' . $language->get('down') . '</a>';
					}
					$output .= '</td>';
				}
				$output .= '</tr>';

				/* collect tbody output */

				if ($tableParameter == 'categories')
				{
					if ($before != $parent)
					{
						$output .= '</tbody>';
					}
				}
				if ($tableParameter == 'articles')
				{
					if ($before != $category)
					{
						$output .= '</tbody>';
					}
				}
				if ($tableParameter == 'comments')
				{
					if ($before != $article)
					{
						$output .= '</tbody>';
					}
				}
			}
			else
			{
				$counter++;
			}
		}

		/* handle access */

		if ($num_rows == $counter)
		{
			$error = $language->get('access_no') . $language->get('point');
		}
	}

	/* handle error */

	if ($error)
	{
		$output .= '<tbody><tr><td colspan="4">' . $error . '</td></tr></tbody>';
	}
	$output .= '</table></div>';
	$output .= Module\Hook::trigger('adminContentListEnd');
	echo $output;
}
