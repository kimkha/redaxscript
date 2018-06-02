<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the article model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Article extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'articles';

	/**
	 * get the article id by alias
	 *
	 * @since 3.3.0
	 *
	 * @param string $articleAlias alias of the article
	 *
	 * @return int|null
	 */

	public function getIdByAlias(string $articleAlias = null) : ?int
	{
		return $this->_query()->select('id')->where('alias', $articleAlias)->findOne()->id;
	}

	/**
	 * get the article title by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string|null
	 */

	public function getTitleById(int $articleId = null) : ?string
	{
		return $this->_query()->select('title')->whereIdIs($articleId)->findOne()->title;
	}

	/**
	 * get the article route by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $articleId identifier of the article
	 *
	 * @return string|null
	 */

	public function getRouteById(int $articleId = null) : ?string
	{
		$route = null;
		$articleArray = $this->_query()
			->tableAlias('a')
			->leftJoinPrefix('categories', 'a.category = c.id', 'c')
			->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
			->select('p.alias', 'parent_alias')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('a.id', $articleId)
			->findArray();

		/* build route */

		if (is_array($articleArray[0]))
		{
			$route = implode('/', array_filter($articleArray[0]));
		}
		return $route;
	}

	/**
	 * get the articles by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 *
	 * @return object
	 */

	public function getManyByLanguage(string $language = null)
	{
		return $this->_query()
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
	}

	/**
	 * get the article by category id and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param string $language
	 *
	 * @return object
	 */

	public function getManyByCategoryIdAndLanguage(int $categoryId = null, string $language = null)
	{
		return $this->_query()
			->where('category', $categoryId)
			->whereLanguageIs($language)
			->findMany();
	}

	/**
	 * publish each article by date
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 *
	 * @return int
	 */

	public function publishByDate(string $date = null) : int
	{
		return $this->_query()
			->where('status', 2)
			->whereLt('date', $date)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}
}
