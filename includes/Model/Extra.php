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

class Extra extends ModelAbstract
{
	/**
	 * name of the table
	 *
	 * @var string
	 */

	protected $_table = 'extras';

	/**
	 * get the extras by language
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 *
	 * @return object
	 */

	public function getManyByLanguage(string $language = null)
	{
		return $this->_query()
			->whereLanguageIs($language)
			->where('status', 1)
			->findMany();
	}

	/**
	 * get the extras by alias and language
	 *
	 * @since 4.0.0
	 *
	 * @param int $extraId identifier of the extra
	 * @param string $language
	 *
	 * @return object
	 */

	public function getManyByIdAndLanguage(int $extraId = null, string $language = null)
	{
		return $this->_query()
			->whereIdIs($extraId)
			->whereLanguageIs($language)
			->findMany();
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
		return $this->_query()
			->where('status', 2)
			->whereLt('date', $date)
			->findMany()
			->set('status', 1)
			->save()
			->count();
	}
}
