<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * parent class to provide the comment model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Comment
{
	/**
	 * get the extras by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 *
	 * @return object
	 */

	public function getManyByLanguage(string $language = null)
	{
		return Db::forTablePrefix('comments')
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
	}

	/**
	 * get the extras by alias and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param string $language
	 *
	 * @return object
	 */

	public function getManyByIdAndLanguage(int $articleId = null, string $language = null)
	{
		return Db::forTablePrefix('comments')
			->where('article', $articleId)
			->whereLanguageIs($language)
			->findMany();
	}

	/**
	 * get the comment route by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $commentId identifier of the comment
	 *
	 * @return string|null
	 */

	public function getRouteById(int $commentId = null) : ?string
	{
		$route = null;
		$commentArray = Db::forTablePrefix('comments')
			->tableAlias('d')
			->leftJoinPrefix('articles', 'd.article = a.id', 'a')
			->leftJoinPrefix('categories', 'a.category = c.id', 'c')
			->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
			->select('p.alias', 'parent_alias')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('d.id', $commentId)
			->findArray();

		/* build route */

		if (is_array($commentArray[0]))
		{
			$route = implode('/', array_filter($commentArray[0])) . '#comment-' . $commentId;
		}
		return $route;
	}

	/**
	 * get all comments
	 *
	 * @since 4.0.0
	 *
	 * @return object
	 */

	public function getAll()
	{
		return Db::forTablePrefix('comments')->findMany();
	}

	/**
	 * publish each comment by date
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 *
	 * @return int
	 */

	public function publishByDate(string $date = null) : int
	{
		return Db::forTablePrefix('comments')
			->where('status', 2)
			->whereLt('date', $date)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}

	/**
	 * create the comment by array
	 *
	 * @since 3.3.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => $createArray['author'],
				'email' => $createArray['email'],
				'url' => $createArray['url'],
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'article' => $createArray['article'],
				'access' => $createArray['access']
			])
			->save();
	}
}
