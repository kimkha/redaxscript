<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CronjobTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\Cronjob
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 *
 * @runTestsInSeparateProcesses
 */

class CronjobTest extends TestCaseAbstract
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
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * providerCronjob
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerCronjob() : array
	{
		return $this->getProvider('tests/provider/Bootstrap/cronjob.json');
	}

	/**
	 * testCronjob
	 *
	 * @since 3.1.0
	 *
	 * @param array $registryArray
	 * @param array $sessionArray
	 * @param bool $expect
	 *
	 * @dataProvider providerCronjob
	 */

	public function testCronjob(array $registryArray = [], array $sessionArray = [], bool $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->setSession('nextUpdate', $sessionArray['nextUpdate']);
		new Bootstrap\Cronjob($this->_registry, $this->_request);

		/* actual */

		$actual = $this->_registry->get('cronUpdate');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
