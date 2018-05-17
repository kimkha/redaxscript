<?php
namespace Redaxscript\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Html;
use Redaxscript\Module;

/**
 * children class to create the admin comment table
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class CommentTable extends ViewAbstract
{
	/**
	 * render the view
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render() : string
	{
		$output = Module\Hook::trigger('adminCommentTableStart');
		$parameterRoute = $this->_registry->get('parameterRoute');
		$commentsNew = $this->_registry->get('commentsNew');

		/* html element */

		$element = new Html\Element();
		$titleElement = $element
			->copy()
			->init('h2',
			[
				'class' => 'rs-admin-title-content',
			])
			->text($this->_language->get('comments'));
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-button'
			]);
		$linkElement = $element
			->copy()
			->init('a',
			[
				'class' => 'rs-admin-button-default rs-admin-button-create',
				'href' => $parameterRoute . 'admin/new/comments'
			])
			->text($this->_language->get('comment_new'));

		/* collect output */

		$output .= $titleElement;
		if ($commentsNew)
		{
			$output .= $wrapperElement->html($linkElement);
		}
		$output .= $this->_renderTable();
		$output .= Module\Hook::trigger('adminCommentTableEnd');
		return $output;
	}

	/**
	 * render the table
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	protected function _renderTable() : string
	{
		$output = null;
		$outputHead = null;
		$outputBody = null;
		$outputFoot = null;
		$tableArray =
		[
			'author' => $this->_language->get('author'),
			'language' => $this->_language->get('language'),
			'article' => $this->_language->get('article'),
			'rank' => $this->_language->get('rank')
		];
		$adminControl = new Helper\Control($this->_registry, $this->_language);
		$articleModel = new Admin\Model\Article();
		$commentModel = new Admin\Model\Comment();
		$comments = $commentModel->getAll();
		$commentsTotal = $comments->count();

		/* html element */

		$element = new Html\Element();
		$wrapperElement = $element
			->copy()
			->init('div',
			[
				'class' => 'rs-admin-wrapper-table'
			]);
		$tableElement = $element
			->copy()
			->init('table',
			[
				'class' => 'rs-admin-table-default'
			]);
		$theadElement = $element->copy()->init('thead');
		$tbodyElement = $element->copy()->init('tbody');
		$tfootElement = $element->copy()->init('tfoot');
		$trElement = $element->copy()->init('tr');
		$thElement = $element->copy()->init('th');
		$tdElement = $element->copy()->init('td');

		/* process table */

		foreach ($tableArray as $key => $value)
		{
			$outputHead .= $thElement->copy()->text($value);
			$outputFoot .= $tdElement->copy()->text($value);
		}

		/* process categories */

		if ($commentsTotal)
		{
			foreach ($comments as $key => $value)
			{
				$outputBody .= $trElement
					->copy()
					->addClass(!$value->status ? 'rs-admin-is-disabled' : null)
					->html(
						$tdElement->copy()->html($value->author . $adminControl->render('comments', $value->id, $value->alias, $value->status)) .
						$tdElement->copy()->text($value->language ? $this->_language->get($value->language, '_index') : $this->_language->get('all')) .
						$tdElement->copy()->text($value->article ? $articleModel->getTitleById($value->article) : $this->_language->get('none')) .
						$tdElement
							->copy()
							->addClass('rs-admin-col-move')
							->addClass($commentsTotal > 1 ? 'rs-admin-is-active' : null)
							->text($value->rank)
				);
			}
		}
		else
		{
			$outputBody .= $trElement
				->copy()
				->html(
					$tdElement
						->copy()
						->attr('colspan', count($tableArray))
						->text($this->_language->get('article_no'))
				);
		}

		/* collect output */

		$outputHead = $theadElement->html(
			$trElement->html($outputHead)
		);
		$outputBody = $tbodyElement->html($outputBody);
		$outputFoot = $tfootElement->html(
			$trElement->html($outputFoot)
		);
		$output .= $wrapperElement->copy()->html(
			$tableElement->html($outputHead . $outputBody . $outputFoot)
		);
		return $output;
	}
}
