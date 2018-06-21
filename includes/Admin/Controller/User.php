<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin user request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class User extends ControllerAbstract
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
		$route = 'admin/view/users';

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => $route,
				'message' => $validateArray
			]);
		}

		/* handle create */

		if ($this->_request->getPost('Redaxscript\Admin\View\UserForm') === 'create')
		{
			$route = 'admin/new/users';
			$createArray =
			[
				'name' => $postArray['name'],
				'user' => $postArray['user'],
				'description' => $postArray['description'],
				'password' => $postArray['password'],
				'email' => $postArray['email'],
				'language' => $postArray['language'],
				'status' => $postArray['status'],
				'groups' => $postArray['groups']
			];
			if ($this->_create($createArray))
			{
				return $this->_success(
				[
					'route' => $route,
					'timeout' => 2
				]);
			}
		}

		/* handle update */

		if ($this->_request->getPost('Redaxscript\Admin\View\UserForm') === 'update')
		{
			$route = 'admin/new/users/' . $postArray['id'];
			$updateArray =
			[
				'name' => $postArray['name'],
				'user' => $postArray['user'],
				'description' => $postArray['description'],
				'password' => $postArray['password'],
				'email' => $postArray['email'],
				'language' => $postArray['language'],
				'status' => $postArray['status'],
				'groups' => $postArray['groups']
			];
			if ($this->_update($postArray['id'], $updateArray))
			{
				return $this->_success(
				[
					'route' => $route,
					'timeout' => 2
				]);
			}
		}

		/* handle error */

		return $this->_error(
		[
			'route' => $route,
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
		$specialFilter = new Filter\Special();

		/* sanitize post */

		return
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id'))
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
		$loginValidator = new Validator\Login();
		$emailValidator = new Validator\Email();
		$validateArray = [];

		/* validate post */

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		if (!$postArray['user'])
		{
			$validateArray[] = $this->_language->get('user_empty');
		}
		else if ($loginValidator->validate($postArray['user'], 'general'))
		{
			$validateArray[] = $this->_language->get('user_incorrect');
		}
		if (!$postArray['password'])
		{
			$validateArray[] = $this->_language->get('password_empty');
		}
		else if (!$loginValidator->validate($postArray['password']))
		{
			$validateArray[] = $this->_language->get('password_incorrect');
		}
		if (!$emailValidator->validate($postArray['email']))
		{
			$validateArray[] = $this->_language->get('email_incorrect');
		}
		return $validateArray;
	}

	/**
	 * create the user
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$userModel = new Admin\Model\User();
		return $userModel->createByArray($createArray);
	}

	/**
	 * update the user
	 *
	 * @since 4.0.0
	 *
	 * @param int $userId identifier of the user
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $userId = null, array $updateArray = []) : bool
	{
		$userModel = new Admin\Model\User();
		return $userModel->updateByIdAndArray($userId, $updateArray);
	}
}
