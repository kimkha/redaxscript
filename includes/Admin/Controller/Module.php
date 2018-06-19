<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;

/**
 * children class to process the admin module request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Module extends ControllerAbstract
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
		$route = 'admin/view/modules';

		/* validate post */

		if ($validateArray)
		{
			return $this->_error(
			[
				'route' => $route,
				'message' => $validateArray
			]);
		}

		/* handle update */

		if ($this->_request->getPost('Redaxscript\Admin\View\ModuleForm') === 'update')
		{
			$route = 'admin/edit/modules/' . $postArray['module'];
			$updateArray =
			[
			];
			if ($this->_update($postArray['module'], $updateArray))
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

		if (!$postArray['name'])
		{
			$validateArray[] = $this->_language->get('name_empty');
		}
		return $validateArray;
	}

	/**
	 * update the module
	 *
	 * @since 4.0.0
	 *
	 * @param int $moduleId identifier of the module
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $moduleId = null, array $updateArray = []) : bool
	{
		$moduleModel = new Admin\Model\Module();
		return $moduleModel->updateByIdAndArray($moduleId, $updateArray);
	}
}
