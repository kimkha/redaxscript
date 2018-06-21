<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;

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
		$route = 'admin';

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
			$route = 'admin/edit/settings';
			$updateArray =
			[
				'language' => $postArray['language'],
				'template' => $postArray['template'],
				'title' => $postArray['title'],
				'author' => $postArray['author'],
				'copyright' => $postArray['copyright'],
				'description' => $postArray['description'],
				'keywords' => $postArray['keywords'],
				'robots' => $postArray['robots'],
				'email' => $postArray['email'],
				'subject' => $postArray['subject'],
				'notification' => $postArray['notification'],
				'charset' => $postArray['charset'],
				'divider' => $postArray['divider'],
				'time' => $postArray['time'],
				'date' => $postArray['date'],
				'homepage' => $postArray['homepage'],
				'limit' => $postArray['limit'],
				'order' => $postArray['order'],
				'pagination' => $postArray['pagination'],
				'moderation' => $postArray['moderation'],
				'registration' => $postArray['registration'],
				'verification' => $postArray['verification'],
				'recovery' => $postArray['recovery'],
				'captcha' => $postArray['captcha']
			];
			if ($this->_update($updateArray))
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
		$validateArray = [];

		/* validate post */

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
