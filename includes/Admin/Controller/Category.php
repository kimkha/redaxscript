<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Validator;

/**
 * children class to process the admin category request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Category extends ControllerAbstract
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
		$route = 'admin/view/categories';

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

		if ($this->_request->getPost('Redaxscript\Admin\View\CategoryForm') === 'create')
		{
			$route = 'admin/new/categories';
			$createArray =
			[

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

		if ($this->_request->getPost('Redaxscript\Admin\View\CategoryForm') === 'update')
		{
			$route = 'admin/edit/categories/' . $postArray['category'];
			$updateArray =
			[

			];
			if ($this->_update($postArray['category'], $updateArray))
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
		$categoryModel = new Admin\Model\Category();
		$validateArray = [];

		/* validate post */

		if (!$postArray['title'])
		{
			$validateArray[] = $this->_language->get('title_empty');
		}
		if (!$postArray['alias'])
		{
			$validateArray[] = $this->_language->get('alias_empty');
		}
		else if ($aliasValidator->validate($postArray['alias'], Validator\Alias::MODE_GENERAL) || $aliasValidator->validate($postArray['alias'], Validator\Alias::MODE_DEFAULT))
		{
			$validateArray[] = $this->_language->get('alias_incorrect');
		}
		else if ($categoryModel->getByAlias($postArray['alias'])->count())
		{
			$validateArray[] = $this->_language->get('alias_exists');
		}
		return $validateArray;
	}

	/**
	 * create the category
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$categoryModel = new Admin\Model\Category();
		return $categoryModel->createByArray($createArray);
	}

	/**
	 * update the category
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId identifier of the category
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $categoryId = null, array $updateArray = []) : bool
	{
		$categoryModel = new Admin\Model\Category();
		return $categoryModel->updateByIdAndArray($categoryId, $updateArray);
	}
}
