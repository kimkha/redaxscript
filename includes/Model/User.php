<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the user model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class User extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'users';

	/**
	 * create the user by array
	 *
	 * @since 3.3.0
	 *
	 * @param array $createArray
	 *
	 * @return bool
	 */

	public function createByArray(array $createArray = []) : bool
	{
		return $this->_query()
			->create()
			->set(
			[
				'name' => $createArray['name'],
				'user' => $createArray['user'],
				'email' => $createArray['email'],
				'password' => $createArray['password'],
				'language' => $createArray['language'],
				'groups' => $createArray['groups'],
				'status' => $createArray['status']
			])
			->save();
	}

	/**
	 * reset the password by id
	 *
	 * @since 3.3.0
	 *
	 * @param int $userId identifier of the user
	 * @param string $password
	 *
	 * @return bool
	 */

	public function resetPasswordById(int $userId = null, string $password = null) : bool
	{
		return $this->_query()
			->whereIdIs($userId)
			->where('status', 1)
			->findOne()
			->set('password', $password)
			->save();
	}
}
