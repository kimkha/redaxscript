<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SettingTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Setting
 */

class SettingTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
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
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testGetAndSet
	 *
	 * @since 3.3.0
	 */

	public function testGetAndSet()
	{
		/* setup */

		$settingModel = new Model\Setting();
		$settingModel->set('charset', 'utf-16');

		/* actual */

		$actual = $settingModel->get('charset');

		/* compare */

		$this->assertEquals('utf-16', $actual);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 3.3.0
	 */

	public function testGetInvalid()
	{
		/* setup */

		$settingModel = new Model\Setting();

		/* actual */

		$actual = $settingModel->get('invalidKey');

		/* compare */

		$this->assertFalse($actual);
	}
}
