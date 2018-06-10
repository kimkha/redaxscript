<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin group model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Group extends BaseModel\Group
{
	/**
	 * create the group by array
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
				'name' => $createArray['name'],
				'alias' => $createArray['alias'],
				'description' => $createArray['description'],
				'categories' => $createArray['categories'],
				'articles' => $createArray['articles'],
				'extras' => $createArray['extras'],
				'comments' => $createArray['comments'],
				'groups' => $createArray['groups'],
				'users' => $createArray['users'],
				'modules' => $createArray['modules'],
				'settings' => $createArray['settings'],
				'filter' => $createArray['filter'],
				'status' => $createArray['status']
			])
			->save();
	}
}
