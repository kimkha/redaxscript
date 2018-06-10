<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin article model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Article extends BaseModel\Article
{
	/**
	 * create the article by array
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this->query()
			->create()
			->set(
			[
				'title' => $createArray['title'],
				'alias' => $createArray['alias'],
				'author' => $createArray['author'],
				'description' => $createArray['description'],
				'keywords' => $createArray['keywords'],
				'robots' => $createArray['robots'],
				'text' => $createArray['text'],
				'language' => $createArray['language'],
				'template' => $createArray['template'],
				'sibling' => $createArray['sibling'],
				'category' => $createArray['category'],
				'headline' => $createArray['headline'],
				'byline' => $createArray['byline'],
				'comments' => $createArray['comments'],
				'status' => $createArray['status'],
				'rank' => $createArray['rank'],
				'access' => $createArray['access'],
				'date' => $createArray['date']
			])
			->save();
	}
}
