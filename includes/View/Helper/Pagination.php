<?php
namespace Redaxscript\View\Helper;

use Redaxscript\Language;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * helper class to create the pagination
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category View
 * @author Henry Ruhs
 */

class Pagination
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
		ob_start();
		pagination($optionArray['subActive'], $optionArray['subMaximum'], $optionArray['route']);
		return ob_get_clean();
	}
}

/**
 * pagination
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Content
 * @author Henry Ruhs
 *
 * @param int $sub_active
 * @param int $sub_maximum
 * @param string $route
 */

function pagination($sub_active, $sub_maximum, $route)
{
	$registry = Registry::getInstance();
	$language = Language::getInstance();
	$output = Module\Hook::trigger('paginationStart');
	$output .= '<ul class="rs-list-pagination">';

	/* collect first and previous output */

	if ($sub_active > 1)
	{
		$first_route = $route;
		$previous_route = $route . '/' . ($sub_active - 1);
		$output .= '<li class="rs-item-first"><a href="' . $registry->get('parameterRoute') . $first_route . '">' . $language->get('first') . '</a></li>';
		$output .= '<li class="rs-item-previous"><a href="' . $registry->get('parameterRoute') . $previous_route . '" rel="prev">' . $language->get('previous') . '</a></li>';
	}

	/* collect center output */

	$j = 2;
	if ($sub_active == 2 || $sub_active == $sub_maximum - 1)
	{
		$j++;
	}
	if ($sub_active == 1 || $sub_active == $sub_maximum)
	{
		$j = $j + 2;
	}
	for ($i = $sub_active - $j; $i < $sub_active + $j; $i++)
	{
		if ($i == $sub_active)
		{
			$j++;
			$output .= '<li class="rs-item-number rs-item-active"><span>' . $i . '</span></li>';
		}
		else if ($i > 0 && $i < $sub_maximum + 1)
		{
			$output .= '<li class="rs-item-number"><a href="' . $registry->get('parameterRoute') . $route . '/' . $i . '">' . $i . '</a></li>';
		}
	}

	/* collect next and last output */

	if ($sub_active < $sub_maximum)
	{
		$next_route = $route . '/' . ($sub_active + 1);
		$last_route = $route . '/' . $sub_maximum;
		$output .= '<li class="rs-item-next"><a href="' . $registry->get('parameterRoute') . $next_route . '" rel="next">' . $language->get('next') . '</a></li>';
		$output .= '<li class="rs-item-last"><a href="' . $registry->get('parameterRoute') . $last_route . '">' . $language->get('last') . '</a></li>';
	}
	$output .= '</ul>';
	$output .= Module\Hook::trigger('paginationEnd');
	echo $output;
}
