<?php
namespace Redaxscript\Tests\Filesystem;

use Redaxscript\Filesystem;
use org\bovigo\vfs\vfsStream as Stream;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * FileTest
 *
 * @since 3.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filesystem\File
 */

class FileTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.2.0
	 */

	public function setUp()
	{
		Stream::setup('root', 0777, $this->getProvider('tests' . DIRECTORY_SEPARATOR. 'provider' . DIRECTORY_SEPARATOR. 'Filesystem' . DIRECTORY_SEPARATOR. 'FilesystemTest_setUp.json'));
	}

	/**
	 * testCreate
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $file
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreate(string $root = null, bool $recursive = null, string $file = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\File();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->createFile($file);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testReadAndWrite
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $file
	 * @param string $content
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testReadAndWrite(string $root = null, bool $recursive = null, string $file = null, string $content = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\File();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->writeFile($file, $content);

		/* actual */

		$actualArray =
		[
			$filesystem->readFile($file),
			$filesystem->renderFile($file)
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testRemove
	 *
	 * @since 3.2.0
	 *
	 * @param string $root
	 * @param bool $recursive
	 * @param string $file
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRemove(string $root = null, bool $recursive = null, string $file = null, array $expectArray = [])
	{
		/* setup */

		$filesystem = new Filesystem\File();
		$filesystem->init(Stream::url($root), $recursive);
		$filesystem->removeFile($file);

		/* actual */

		$actualArray = $filesystem->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
