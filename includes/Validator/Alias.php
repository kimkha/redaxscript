<?php
namespace Redaxscript\Validator;

/**
 * children class to validate general and default alias
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class Alias implements ValidatorInterface
{
	/**
	 * array of system alias
	 *
	 * @var array
	 */

	protected $_systemArray =
	[
		'admin',
		'login',
		'logout',
		'search',
		'recover',
		'register',
		'reset'
	];

	/**
	 * validate the alias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias alias for routes and users
	 * @param int $mode switch between general and default validation
	 *
	 * @return bool
	 */

	public function validate($alias = null, $mode = 'general')
	{
		$output = false;

		/* validate general */

		if ($mode === 'general')
		{
			if (preg_match('/[^a-z0-9-]/i', $alias) || is_numeric($alias))
			{
				$output = true;
			}
		}

		/* validate system */

		if ($mode === 'system')
		{
			if (in_array($alias, $this->_systemArray))
			{
				$output = true;
			}
		}
		return $output;
	}
}
