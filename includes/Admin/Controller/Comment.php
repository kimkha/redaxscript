<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;

/**
 * children class to process the admin comment request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Comment extends ControllerAbstract
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
		$route = 'admin/view/comments';

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

		if ($this->_request->getPost('Redaxscript\Admin\View\CommentForm') === 'create')
		{
			$route = 'admin/new/comments';
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

		if ($this->_request->getPost('Redaxscript\Admin\View\CommentForm') === 'update')
		{
			$route = 'admin/edit/comments/' . $postArray['comment'];
			$updateArray =
			[

			];
			if ($this->_update($postArray['comment'], $updateArray))
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

		if (!$postArray['author'])
		{
			$validateArray[] = $this->_language->get('author_empty');
		}
		if (!$postArray['text'])
		{
			$validateArray[] = $this->_language->get('text_empty');
		}
		return $validateArray;
	}

	/**
	 * create the comment
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$commentModel = new Admin\Model\Comment();
		return $commentModel->createByArray($createArray);
	}

	/**
	 * update the comment
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId identifier of the comment
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $commentId = null, array $updateArray = []) : bool
	{
		$commentModel = new Admin\Model\Comment();
		return $commentModel->updateByIdAndArray($commentId, $updateArray);
	}
}
