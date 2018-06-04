<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the extra model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Extra extends ContentAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'extras';

	/**
	 * get the extra by alias
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias alias of the extra
	 *
	 * @return object
	 */

	public function getByAlias(string $extraAlias = null)
	{
		return $this->_query()->where('alias', $extraAlias)->findMany();
	}
}
