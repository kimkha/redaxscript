<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Db;
use Redaxscript\Model as BaseModel;
use Redaxscript\Filter;
use Redaxscript\Registry;

/**
 * parent class to provide the admin setting model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

class Setting extends BaseModel\Setting
{
	protected function update() : bool
	{
		return admin_update();
	}
}

/**
 * admin update
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Admin
 * @author Henry Ruhs
 */

function admin_update()
{
	$registry = Registry::getInstance();
	$tableParameter = $registry->get('tableParameter');
	if ($tableParameter == 'settings')
	{
		$specialFilter = new Filter\Special();
		$emailFilter = new Filter\Email();

		/* clean post */

		$r['language'] = $specialFilter->sanitize($_POST['language']);
		$r['template'] = $specialFilter->sanitize($_POST['template']);
		$r['title'] = $_POST['title'];
		$r['author'] = $_POST['author'];
		$r['copyright'] = $_POST['copyright'];
		$r['description'] = $_POST['description'];
		$r['keywords'] = $_POST['keywords'];
		$r['robots'] = $specialFilter->sanitize($_POST['robots']);
		$r['email'] = $emailFilter->sanitize($_POST['email']);
		$r['subject'] = $_POST['subject'];
		$r['notification'] = $specialFilter->sanitize($_POST['notification']);
		$r['charset'] = !$r['charset'] ? 'utf-8' : $r['charset'];
		$r['divider'] = $_POST['divider'];
		$r['time'] = $_POST['time'];
		$r['date'] = $_POST['date'];
		$r['homepage'] = $specialFilter->sanitize($_POST['homepage']);
		$r['limit'] = !$specialFilter->sanitize($_POST['limit']) ? 10 : $specialFilter->sanitize($_POST['limit']);
		$r['order'] = $specialFilter->sanitize($_POST['order']);
		$r['pagination'] = $specialFilter->sanitize($_POST['pagination']);
		$r['moderation'] = $specialFilter->sanitize($_POST['moderation']);
		$r['registration'] = $specialFilter->sanitize($_POST['registration']);
		$r['verification'] = $specialFilter->sanitize($_POST['verification']);
		$r['recovery'] = $specialFilter->sanitize($_POST['recovery']);
		$r['captcha'] = $specialFilter->sanitize($_POST['captcha']);

		/* update settings */

		foreach ($r as $key => $value)
		{
			if ($value == 'select')
			{
				$value = null;
			}
			return Db::forTablePrefix($tableParameter)
				->where('name', $key)
				->findOne()
				->set('value', $value)
				->save();
		}
	}
	return false;
}