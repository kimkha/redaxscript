<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ConfigTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @runTestsInSeparateProcesses
 */

class ConfigTest extends TestCaseAbstract
{
	/**
	 * testConfig
	 *
	 * @since 4.0.0
	 */

	public function testConfig()
	{
		/* setup */

		new Bootstrap\Config();

		/* expect and actual */

		$expectArray =
		[
			'default_charset' => 'UTF-8',
			'display_startup_errors' => '',
			'display_errors' => 'stderr',
			'mbstring.substitute_character' => '',
			'session.use_trans_sid' => '0',
			'url_rewriter.tags' => 'form='
		];
		$actualArray =
		[
			'default_charset' => ini_get('default_charset'),
			'display_startup_errors' => ini_get('display_startup_errors'),
			'display_errors' => ini_get('display_errors'),
			'mbstring.substitute_character' => ini_get('mbstring.substitute_character'),
			'session.use_trans_sid' => ini_get('session.use_trans_sid'),
			'url_rewriter.tags' => ini_get('url_rewriter.tags')
		];

		/* compare */

		function_exists('ini_set') ? $this->assertEquals($expectArray, $actualArray) : $this->markTestSkipped();
	}
}
