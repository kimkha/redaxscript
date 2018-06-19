<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin setting request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Setting extends ControllerAbstract
{
	/**
	 * process the class
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function process() : string
	{
		$postArray = $this->_sanitizePost();
		$validateArray = $this->_validatePost($postArray);
		$now = $this->_registry->get('now');

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => 'admin',
				'message' => $validateArray
			]);
		}

		/* handle update */

		if ($this->_request->getPost('Redaxscript\Admin\View\SettingForm') === 'update')
		{
			$updateArray =
			[

			];
			if ($this->_update($postArray['setting'], $updateArray))
			{
				return $this->_success(
				[
					'route' => 'admin/edit/settings',
					'timeout' => 2
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => 'admin',
			'message' => $this->_language->get('something_wrong')
		]);
	}

	/**
	 * sanitize the post
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	protected function _sanitizePost() : array
	{
		$aliasFilter = new Filter\Alias();
		$specialFilter = new Filter\Special();

		/* sanitize post */

		return
		[
		];
	}

	/**
	 * validate the post
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validatePost(array $postArray = []) : array
	{
		$aliasValidator = new Validator\Alias();
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$settingModel = new Admin\Model\Setting();
		$validateArray = [];

		/* validate post */

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['alias'])
		{
			$validateArray[] = $this->_language->get('alias_empty');
		}
		else if ($aliasValidator->validate($postArray['alias'], Validator\Alias::MODE_GENERAL) === Validator\ValidatorInterface::PASSED)
		{
			$validateArray[] = $this->_language->get('alias_incorrect');
		}
		else if ($settingModel->getByAlias($postArray['alias'])->count())
		{
			$validateArray[] = $this->_language->get('alias_exists');
		}
		if (!$postArray['password'])
		{
			$validateArray[] = $this->_language->get('password_empty');
		}
		else if ($loginValidator->validate($postArray['password']) === Validator\ValidatorInterface::FAILED)
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if ($emailValidator->validate($postArray['email']) === Validator\ValidatorInterface::FAILED)
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		return $validateArray;
	}

	/**
	 * update the setting
	 *
	 * @since 4.0.0
	 *
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(array $updateArray = []) : bool
	{
		$settingModel = new Admin\Model\Setting();
		return $settingModel->updateByArray($updateArray);
	}
}
