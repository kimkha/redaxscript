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
		return byline($optionArray['table'], $optionArray['id'], $optionArray['author'], $optionArray['date']);
	}
}

/**
 * byline
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 *
 * @param string $table
 * @param int $id
 * @param string $author
 * @param string $date
 *
 * @return string
 */

function byline($table, $id, $author, $date)
{
	$language = Language::getInstance();
	$output = Module\Hook::trigger('bylineStart');
	$settingModel = new Model\Setting();
	$time = date($settingModel->get('time'), strtotime($date));
	$date = date($settingModel->get('date'), strtotime($date));
	if ($table == 'articles')
	{
		$comments_total = Db::forTablePrefix('comments')->where('article', $id)->count();
	}

	/* collect output */

	$output .= '<div class="rs-box-byline">';

	/* collect author output */

	if ($table == 'articles')
	{
		$output .= '<span class="rs-text-by">' . $language->get('posted_by') . ' ' . $author . '</span>';
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
