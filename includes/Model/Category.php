<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the category model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Category extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'categories';

	/**
	 * get the category id by alias
	 *
	 * @since 3.3.0
	 *
	 * @param string $categoryAlias alias of the category
	 *
	 * @return int|null
	 */

	public function getIdByAlias(string $categoryAlias = null) : ?int
	{
		return $this->_query()->select('id')->where('alias', $categoryAlias)->findOne()->id;
	}

	/**
	 *
	 * get the category title by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return string|null
	 */

	public function getTitleById(int $categoryId = null) : ?string
	{
		return $this->_query()->select('title')->whereIdIs($categoryId)->findOne()->title;
	}

	/**
	 * get the category route by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $categoryId identifier of the category
	 *
	 * @return string|null
	 */

	public function getRouteById(int $categoryId = null) : ?string
	{
		$route = null;
		$categoryArray = $this->_query()
			->tableAlias('c')
			->leftJoinPrefix('categories', 'c.parent = p.id', 'p')
			->select('p.alias', 'parent_alias')
			->select('c.alias', 'category_alias')
			->where('c.id', $categoryId)
			->findArray();

		/* build route */

		if (is_array($categoryArray[0]))
		{
			$route = implode('/', array_filter($categoryArray[0]));
		}
		return $route;
	}

	/**
	 * publish each category by date
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
