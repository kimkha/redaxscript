<?php
namespace Redaxscript\Tests;

/**
 * RequestTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Request
 */

class RequestTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$this->_request->init();

		/* actual */

		$actual = $this->_request;

		/* compare */

		$this->assertInstanceOf('Redaxscript\Request', $actual);
	}

	/**
	 * testAll
	 *
	 * @since 2.2.0
	 */

	public function testAll()
	{
		/* actual */

		$actualArray = $this->_request->get();

		/* compare */

		$this->assertArrayHasKey('server', $actualArray);
	}

	/**
	 * testGlobal
	 *
	 * @since 2.2.0
	 */

	public function testGlobal()
	{
		/* setup */

		$this->_request->set('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->get('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testServer
	 *
	 * @since 2.2.0
	 */

	public function testServer()
	{
		/* setup */

		$this->_request->setServer('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getServer('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testQuery
	 *
	 * @since 2.2.0
	 */

	public function testQuery()
	{
		/* setup */

		$this->_request->setQuery('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getQuery('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testPost
	 *
	 * @since 2.2.0
	 */

	public function testPost()
	{
		/* setup */

		$this->_request->setPost('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getPost('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testFiles
	 *
	 * @since 3.9.0
	 */

	public function testFiles()
	{
		/* setup */

		$this->_request->setFiles('testKey', 'testValue');

		/* actual */

		$actual = $this->_request->getFiles('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testSession
	 *
	 * @since 2.6.2
	 */

	public function testSession()
	{
		/* setup */

		$this->_request->setSession('testKey', 'testValue');
		$this->_request->refreshSession();

		/* actual */

		$actual = $this->_request->getSession('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testCookie
	 *
	 * @since 2.6.2
	 */

	public function testCookie()
	{
		/* setup */

		$this->_request->setCookie('testKey', 'testValue');
		$this->_request->refreshCookie();

		/* actual */

		$actual = $this->_request->getCookie('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 3.0.0
	 */

	public function testGetInvalid()
	{
		/* actual */

		$actual = $this->_request->get('invalidKey');

		/* compare */

		$this->assertFalse($actual);
	}
}
