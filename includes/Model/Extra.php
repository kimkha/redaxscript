<?php
namespace Redaxscript\Model;

use Redaxscript\Db;

/**
 * parent class to provide the extra model
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Model
 * @author Henry Ruhs
 */

class Extra
{
	/**
	 * get the extra array by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 *
	 * @return array
	 */

	public function getArrayByLanguage(string $language = null) : array
	{
		return Db::forTablePrefix('extras')
			->whereLanguageIs($language)
			->findArray();
	}

	/**
	 * get the extra array by alias and language
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias alias of the extra
	 * @param string $language
	 *
	 * @return array
	 */

	public function getArrayByAliasAndLanguage(string $extraAlias = null, string $language = null) : array
	{
		return Db::forTablePrefix('extras')
			->where('alias', $extraAlias)
			->whereLanguageIs($language)
			->findArray();
	}

	/**
	 * publish each extra by date
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 *
	 * @return int
	 */

	public function publishByDate(string $date = null) : int
	{
		return Db::forTablePrefix('extras')
			->where('status', 2)
			->whereLt('date', $date)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}
}
