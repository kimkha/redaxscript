<?php
namespace Redaxscript\Tests;

use PHPUnitProviderAutoloader;
use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Installer;
use Redaxscript\Language;
use Redaxscript\Model;
use Redaxscript\Modules\TestDummy;
use Redaxscript\Registry;
use Redaxscript\Request;
use ReflectionClass;

/**
 * TestCaseAbstract
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

abstract class TestCaseAbstract extends PHPUnitProviderAutoloader\TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var Registry
	 */

	protected $_registry;

	/**
	 * instance of the request class
	 *
	 * @var Request
	 */

	protected $_request;

	/**
	 * instance of the language class
	 *
	 * @var Language
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var Config
	 */

	protected $_config;

	/**
	 * namespace of the testing suite
	 *
	 * @var string
	 */

	protected $_testNamespace = __NAMESPACE__;

	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		Db::clearCache();
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
		$this->_language = Language::getInstance();
		$this->_config = Config::getInstance();
	}

	/**
	 * installerFactory
	 *
	 * @since 3.1.0
	 *
	 * @return Installer
	 */

	public function installerFactory() : Installer
	{
		return new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
	}

	/**
	 * settingFactory
	 *
	 * @since 3.3.0
	 *
	 * @return Model\Setting
	 */

	public function settingFactory() : Model\Setting
	{
		return new Model\Setting();
	}

	/**
	 * createDatabase
	 *
	 * @since 4.0.0
	 */

	public function createDatabase()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
	}

	/**
	 * dropDatabase
	 *
	 * @since 4.0.0
	 */

	public function dropDatabase()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * installTestDummy
	 *
	 * @since 4.0.0
	 */

	public function installTestDummy()
	{
		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->install();
	}

	/**
	 * uninstallTestDummy
	 *
	 * @since 4.0.0
	 */

	public function uninstallTestDummy()
	{
		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->clearNotification('success');
		$testDummy->clearNotification('warning');
		$testDummy->clearNotification('error');
		$testDummy->clearNotification('info');
		$testDummy->uninstall();
	}

	/**
	 * getProvider
	 *
	 * @since 2.2.0
	 *
	 * @param string $file
	 *
	 * @return array
	 */

	public function getProvider(string $file = null) : array
	{
		$content = file_get_contents($file);
		return json_decode($content, true);
	}

	/**
	 * getProperty
	 *
	 * @since 3.0.0
	 *
	 * @param object $object
	 * @param string $property
	 *
	 * @return mixed
	 */

	public function getProperty($object = null, string $property = null)
	{
		$reflection = new ReflectionClass($object);
		$reflectionProperty = $reflection->getProperty($property);
		$reflectionProperty->setAccessible(true);
		return $reflectionProperty->getValue($object);
	}

	/**
	 * callMethod
	 *
	 * @since 3.0.0
	 *
	 * @param object $object
	 * @param string $method
	 * @param array $argumentArray
	 *
	 * @return mixed
	 */

	public function callMethod($object = null, string $method = null, array $argumentArray = [])
	{
		$reflection = new ReflectionClass($object);
		$reflectionMethod = $reflection->getMethod($method);
		$reflectionMethod->setAccessible(true);
		return $reflectionMethod->invokeArgs($object, $argumentArray);
	}

	/**
	 * assertString
	 *
	 * @since 3.0.0
	 *
	 * @param mixed $actual
	 */

	public function assertString($actual = null)
	{
		$this->assertTrue(is_string($actual));
	}

	/**
	 * assertNumber
	 *
	 * @since 3.1.0
	 *
	 * @param mixed $actual
	 */

	public function assertNumber($actual = null)
	{
		$this->assertTrue(is_numeric($actual));
	}

	/**
	 * assertObject
	 *
	 * @since 3.1.0
	 *
	 * @param mixed $actual
	 */

	public function assertObject($actual = null)
	{
		$this->assertTrue(is_object($actual));
	}

	/**
	 * normalizeSeparator
	 *
	 * @since 3.2.0
	 *
	 * @param string $actual
	 *
	 * @return string
	 */

	public function normalizeSeparator(string $actual = null) : string
	{
		return str_replace(DIRECTORY_SEPARATOR, chr(47), $actual);
	}

	/**
	 * normalizeNewline
	 *
	 * @since 3.0.0
	 *
	 * @param string $actual
	 *
	 * @return string
	 */

	public function normalizeNewline(string $actual = null) : string
	{
		return str_replace(PHP_EOL, chr(10), $actual);
	}

	/**
	 * getHeaderArray
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function getHeaderArray()
	{
		return function_exists('xdebug_get_headers') ? xdebug_get_headers() : $this->markTestSkipped();
	}
}
