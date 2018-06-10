<?php
namespace Redaxscript\Admin\Model;

use Redaxscript\Model as BaseModel;

/**
 * parent class to provide the admin module model
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Module extends BaseModel\Module
{
	/**
	 * update the module by id and array
	 *
	 * @since 4.0.0
	 *
	 * @param int $moduleId identifier of the module
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function updateByIdAndArray(int $moduleId = null, array $updateArray = []) : bool
	{
		return $this->query()
			->whereIdIs($moduleId)
			->set(
			[
				'name' => $updateArray['name'],
				'description' => $updateArray['description'],
				'status' => $updateArray['status'],
				'access' => $updateArray['access']
			])
			->save();
	}
}
