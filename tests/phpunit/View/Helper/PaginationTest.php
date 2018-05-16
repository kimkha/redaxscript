<?php
namespace Redaxscript\Tests\View\Helper;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View\Helper\Pagination;

/**
 * PaginationTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class PaginationTest extends TestCaseAbstract
{
    /**
     * providerRender
     *
     * @since 4.0.0
     *
     * @return array
     */

    public function providerRender() : array
    {
        return $this->getProvider('tests/provider/View/Helper/pagination_render.json');
    }

	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param string $route
	 * @param int $current
	 * @param int $total
	 * @param int $range
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(string $route = null, int $current = null, int $total = null, int $range = null, array $optionArray = [], string $expect = null)
	{
		/* setup */

		$pagination = new Pagination($this->_registry, $this->_language);
		$pagination->init($optionArray);

		/* actual */

		$actual = $pagination->render($route, $current, $total, $range);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
