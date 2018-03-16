<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin user model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class User extends BaseModel\User
{
	public function updateLastSeen()
	{
		return admin_last_update();
	}
}

/**
 * admin last update
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_last_update()
{
	$registry = Registry::getInstance();
	if ($registry->get('myId'))
	{
		Db::forTablePrefix('users')
			->where('id', $registry->get('myId'))
			->findOne()
			->set('last', $registry->get('now'))
			->save();
	}
}
