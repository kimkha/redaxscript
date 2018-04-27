<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Db;
use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin user model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class User extends BaseModel\User
{
	/**
	 * update last by id
	 *
	 * @since 4.0.0
	 *
	 * @param string $userId id of the user
	 * @param string $date
	 *
	 * @return bool
	 */

	public function updateLastById(string $userId = null, string $date = null)
	{
		return Db::forTablePrefix('users')
			->where('id', $userId)
			->findOne()
			->set('last', $date)
			->save();
	}
}
