<?php
namespace Redaxscript\View\Helper;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Module;

/**
 * helper class to create the byline
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Byline
{
	/**
	 * render the view
	 *
	 * @param array $optionArray options of the form
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */

	public function render(array $optionArray = []) : string
	{
		//TODO: refactor -> constructor with language instance
		$language = Language::getInstance();
		$output = Module\Hook::trigger('bylineStart');
		$settingModel = new Model\Setting();
		$time = date($settingModel->get('time'), strtotime($optionArray['date']));
		$date = date($settingModel->get('date'), strtotime($optionArray['date']));
		if ($optionArray['table'] == 'articles')
		{
			//TODO: use model here instead of Db
			$comments_total = Db::forTablePrefix('comments')->where('article', $optionArray['id'])->count();
		}

		/* collect output */

		//TODO: use Html builder and move classes to local property like in Breadcrumb className => ...
		$output .= '<div class="rs-box-byline">';

		/* collect author output */

		if ($optionArray['table'] == 'articles')
		{
			$output .= '<span class="rs-text-by">' . $language->get('posted_by') . ' ' . $optionArray['author'] . '</span>';
			$output .= '<span class="rs-text-on"> ' . $language->get('on') . ' </span>';
		}

		/* collect date and time output */

		$output .= '<span class="rs-text-date">' . $date . '</span>';
		$output .= '<span class="rs-text-at"> ' . $language->get('at') . ' </span>';
		$output .= '<span class="rs-text-time">' . $time . '</span>';

		/* collect comment output */

		if ($comments_total)
		{
			$output .= '<span class="rs-text-divider">' . $settingModel->get('divider') . '</span><span class="rs-text-total">' . $comments_total . ' ';
			if ($comments_total == 1)
			{
				$output .= $language->get('comment');
			}
			else
			{
				$output .= $language->get('comments');
			}
			$output .= '</span>';
		}
		$output .= '</div>';
		$output .= Module\Hook::trigger('bylineEnd');
		return $output;
	}
}
