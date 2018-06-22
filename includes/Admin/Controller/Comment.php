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
		$postArray = $this->_normalizePost($this->_sanitizePost());
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
				'author' => $postArray['author'],
				'email' => $postArray['email'],
				'url' => $postArray['url'],
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'article' => $postArray['article'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date']
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
			$route = 'admin/edit/comments/' . $postArray['id'];
			$updateArray =
			[
				'author' => $postArray['author'],
				'email' => $postArray['email'],
				'url' => $postArray['url'],
				'text' => $postArray['text'],
				'language' => $postArray['language'],
				'article' => $postArray['article'],
				'status' => $postArray['date'] > $now ? 2 : $postArray['status'],
				'rank' => $postArray['rank'],
				'access' => $postArray['access'],
				'date' => $postArray['date']
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
		$emailFilter = new Filter\Email();
		$urlFilter = new Filter\Url();
		$htmlFilter = new Filter\Html();

		/* sanitize post */

		return
		[
			'id' => $specialFilter->sanitize($this->_request->getPost('id')),
			'email' => $emailFilter->sanitize($this->_request->getPost('email')),
			'url' => $urlFilter->sanitize($this->_request->getPost('url')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text'), $this->_registry->get('filter')),
			'language' => $specialFilter->sanitize($this->_request->getPost('language')),
			'article' => $specialFilter->sanitize($this->_request->getPost('article')),
			'status' => $specialFilter->sanitize($this->_request->getPost('date')),
			'rank' => $specialFilter->sanitize($this->_request->getPost('rank')),
			'access' => $specialFilter->sanitize($this->_request->getPost('access')),
			'date' => $this->_request->getPost('date')
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
