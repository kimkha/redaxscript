<?php
namespace Redaxscript\Validator;

use Redaxscript\Hash;

/**
 * children class to validate captcha
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Validator
 * @author Henry Ruhs
 */

class Captcha implements ValidatorInterface
{
	/**
	 * validate the captcha
	 *
	 * @since 2.2.0
	 *
	 * @param string $task plain task
	 * @param string $hash hashed solution
	 *
	 * @return bool
	 */

	public function validate($task = null, $hash = null)
	{
		$output = false;
		$captchaHash = new Hash();

		/* validate captcha */

		if ($task && $captchaHash->validate($task, $hash))
		{
			$output = true;
		}
		return $output;
	}
}
