<?php
namespace Redaxscript\Tests\Admin\View\Helper;

use Redaxscript\Admin\View\Helper;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ControlTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ControlTest extends TestCaseAbstract
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
		return $this->getProvider('tests/provider/Admin/View/Helper/control_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param array $registryArray
	 * @param array $renderArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], array $renderArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$adminControl = new Helper\Control($this->_registry, $this->_language);

		/* actual */

		$actual = $adminControl->render($renderArray['table'], $renderArray['id'], $renderArray['alias'], $renderArray['status']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
