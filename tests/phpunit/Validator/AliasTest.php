<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * AliasTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Validator\Alias
 */

class AliasTest extends TestCaseAbstract
{
	/**
	 * testAlias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias
	 * @param int $mode
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testAlias(string $alias = null, int $mode = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Alias();

		/* actual */

		$actual = $validator->validate($alias, $mode);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
