<?php
namespace Redaxscript\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Filter;
use Redaxscript\Messenger;
use Redaxscript\Validator;

/**
 * children class to process the admin article request
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Controller
 * @author Henry Ruhs
 */

class Article extends ControllerAbstract
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
		$aliasFilter = new Filter\Alias();
		$htmlFilter = new Filter\Html();
		$route = 'admin/view/articles';

		/* process post */

		$postArray =
		[
			'article' => $this->_request->getPost('article'),
			'title' => $this->_request->getPost('title'),
			'alias' => $aliasFilter->sanitize($this->_request->getPost('alias')),
			'text' => $htmlFilter->sanitize($this->_request->getPost('text')),
			'status' => $this->_request->getPost('status')
		];

		/* validate post */

		$messageArray = $this->_validate($postArray);
		if ($messageArray)
		{
			return $this->_error(
			[
				'message' => $messageArray
			]);
		}

		/* handle create */

		if ($this->_request->getPost('Redaxscript\Admin\View\ArticleForm') === 'create')
		{
			$route = 'admin/new/articles';
			$createArray =
			[
				'title' => $postArray['title'],
				'alias' => $postArray['alias'],
				'text' => $postArray['text'],
				'status' => $postArray['status']
			];
			if ($this->_create($createArray))
			{
				return $this->_success();
			}
		}

		/* handle update */

		if ($this->_request->getPost('Redaxscript\Admin\View\ArticleForm') === 'update')
		{
			$route = 'admin/new/articles/' . $postArray['article'];
			$updateArray =
			[
				'title' => $postArray['title'],
				'alias' => $postArray['alias'],
				'text' => $postArray['text'],
				'status' => $postArray['status']
			];
			if ($this->_update($postArray['article'], $updateArray))
			{
				return $this->_success();
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
	 * show the success
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _success() : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('continue'), 'admin/view/articles')
			->doRedirect()
			->success($this->_language->get('operation_completed'));
	}

	/**
	 * show the error
	 *
	 * @since 4.0.0
	 *
	 * @param array $errorArray array of the error
	 *
	 * @return string
	 */

	protected function _error(array $errorArray = []) : string
	{
		$messenger = new Messenger($this->_registry);
		return $messenger
			->setRoute($this->_language->get('back'), $errorArray['route'])
			->error($errorArray['message'], $this->_language->get('error_occurred'));
	}

	/**
	 * validate
	 *
	 * @since 4.0.0
	 *
	 * @param array $postArray array of the post
	 *
	 * @return array
	 */

	protected function _validate(array $postArray = []) : array
	{
		$aliasValidator = new Validator\Alias();
		$articleModel = new Admin\Model\Article();

		/* validate post */

		$messageArray = [];
		if (!$postArray['alias'])
		{
			$messageArray[] = $this->_language->get('alias_empty');
		}
		else if ($articleModel->getByAlias($postArray['alias'])->count())
		{
			$messageArray[] = $this->_language->get('alias_exists');
		}
		else if ($aliasValidator->validate($postArray['alias'], Validator\Alias::MODE_GENERAL) == Validator\ValidatorInterface::PASSED || $aliasValidator->validate($postArray['alias'], Validator\Alias::MODE_DEFAULT) == Validator\ValidatorInterface::PASSED)
		{
			$messageArray[] = $this->_language->get('alias_incorrect');
		}
		return $messageArray;
	}

	/**
	 * create the article
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray array of the create
	 *
	 * @return bool
	 */

	protected function _create(array $createArray = []) : bool
	{
		$articleModel = new Admin\Model\Article();
		return $articleModel->createByArray($createArray);
	}

	/**
	 * update the article
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId identifier of the article
	 * @param array $updateArray
	 *
	 * @return bool
	 */

	public function _update(int $articleId = null, array $updateArray = []) : bool
	{
		$articleModel = new Admin\Model\Article();
		return $articleModel->updateByIdAndArray($articleId, $updateArray);
	}
}
