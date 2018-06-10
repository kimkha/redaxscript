<?php
namespace Redaxscript\Admin\Model;

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
	 * create the user by array
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
				'user' => $createArray['user'],
				'description' => $createArray['description'],
				'password' => $createArray['password'],
				'email' => $createArray['email'],
				'language' => $createArray['language'],
				'status' => $createArray['status'],
				'groups' => $createArray['groups'],
				'first' => $createArray['first'],
				'last' => $createArray['last']
			])
			->save();
	}

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
		return $this->query()
			->where('id', $userId)
			->findOne()
			->set('last', $date)
			->save();
	}
}
