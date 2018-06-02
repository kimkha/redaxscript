<?php
namespace Redaxscript\Model;

/**
 * parent class to provide the group model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Group extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'groups';

	/**
	 * get the group id by alias
	 *
	 * @since 3.3.0
	 *
	 * @param string $groupAlias alias of the group
	 *
	 * @return int|null
	 */

	public function getIdByAlias(string $groupAlias = null) : ?int
	{
		return $this->_query()->select('id')->where('alias', $groupAlias)->findOne()->id;
	}
}
