<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * abstract class to create a model class
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

abstract class ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table;

	/**
	 * get by id
	 *
	 * @since 4.0.0
	 *
	 * @param int $id
	 *
	 * @return object
	 */

	public function getById(int $id = null)
	{
		return $this->_query()->whereIdIs($id)->findMany();
	}

	/**
	 * get all
	 *
	 * @since 4.0.0
	 *
	 * @return object
	 */

	public function getAll()
	{
		return $this->_query()->findMany();
	}

	/**
	 * query the table
	 *
	 * @since 4.0.0
	 *
	 * @return object
	 */

	protected function _query()
	{
		return Db::forTablePrefix($this->_table);
	}
}