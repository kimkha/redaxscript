<?php
namespace Redaxscript\Tests\Admin\Html;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * FormTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class FormTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerCreate
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerCreate() : array
	{
		return $this->getProvider('tests/provider/Admin/Html/form_create.json');
	}

	/**
	 * testCreate
	 *
	 * @since 3.0.0
	 *
	 * @param array $attributeArray
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerCreate
	 */

	public function testCreate(array $attributeArray = [], array $optionArray = [], string $expect = null)
	{
		/* setup */

		$form = new Admin\Html\Form($this->_registry, $this->_language);
		$form->init($attributeArray, $optionArray);

		/* actual */

		$actual = $form;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
