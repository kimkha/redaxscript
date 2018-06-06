<?php
namespace Redaxscript\Tests\Admin\View;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ModuleFormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\View\ModuleForm
 */

class ModuleFormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
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
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}


	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/Admin/View/module_form_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param int $moduleId
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], int $moduleId = null, array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$moduleForm = new Admin\View\ModuleForm($this->_registry, $this->_language);

		/* actual */

		$actual = $moduleForm->render($moduleId);

		/* compare */

		$this->assertStringStartsWith($expectArray['start'], $actual);
		$this->assertStringEndsWith($expectArray['end'], $actual);
	}
}
