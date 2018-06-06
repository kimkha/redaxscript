<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ModuleTableTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\ModuleTable
 */

class ModuleTableTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp()
	{
		parent::setUp();
		$this->createDatabase();
		$this->installTestDummy();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown()
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}

	/**
	 * providerRender
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/Admin/View/module_table_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$moduleTable = new Admin\View\ModuleTable($this->_registry, $this->_language);

		/* actual */

		$actual = $moduleTable->render();

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
