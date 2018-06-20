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
	 * general validate mode
	 *
	 * @const integer
	 */

	const MODE_GENERAL = 0;

	/**
	 * default validate mode
	 *
	 * @const integer
	 */

	const MODE_DEFAULT = 1;

	/**
	 * array of default alias
	 *
	 * @var array
	 */

	protected $_defaultArray =
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

	public function validate($alias = null, $mode = 0)
	{
		$output = false;

		/* validate general alias */

		if ($mode === self::MODE_GENERAL)
		{
			if (preg_match('/[^a-z0-9-]/i', $alias) || is_numeric($alias))
			{
				$output = true;
			}
		}

		/* validate default alias */

		else if ($mode === self::MODE_DEFAULT)
		{
			if (in_array($alias, $this->_defaultArray))
			{
				$output = true;
			}
		}
		return $output;
	}
}
