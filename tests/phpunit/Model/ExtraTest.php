<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ExtraTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ExtraTest extends TestCaseAbstract
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
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra One',
				'alias' => 'extra-one',
				'rank' => 1,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Two',
				'alias' => 'extra-two',
				'rank' => 2,
				'status' => 1
			])
			->save();
		$extraThree = Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Three',
				'alias' => 'extra-three',
				'language' => 'en',
				'rank' => 3,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Four',
				'alias' => 'extra-four',
				'language' => 'de',
				'sibling' => $extraThree->id,
				'rank' => 4,
				'status' => 2,
				'date' => '2036-01-01 00:00:00'
			])
			->save();
		Db::forTablePrefix('extras')
			->create()
			->set(
			[
				'title' => 'Extra Five',
				'alias' => 'extra-five',
				'language' => 'fr',
				'sibling' => $extraThree->id,
				'rank' => 5,
				'status' => 2,
				'date' => '2037-01-01 00:00:00'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerExtraGetArray
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function providerExtraGetArray() : array
	{
		return $this->getProvider('tests/provider/Model/extra_get_array.json');
	}

	/**
	 * providerExtraGetArrayAlias
	 *
	 * @since 4.0.0
	 *
	 * @return array
	 */

	public function providerExtraGetArrayAlias() : array
	{
		return $this->getProvider('tests/provider/Model/extra_get_array_alias.json');
	}

	/**
	 * providerExtraPublishDate
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerExtraPublishDate() : array
	{
		return $this->getProvider('tests/provider/Model/extra_publish_date.json');
	}

	/**
	 * testGetArrayByLanguage
	 *
	 * @since 4.0.0
	 *
	 * @param string $language
	 * @param array $expectArray
	 *
	 * @dataProvider providerExtraGetArray
	 */

	public function testGetArrayByLanguage(string $language = null, array $expectArray = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$extraArray = $extraModel->getArrayByLanguage($language);
		$actualArray = [];

		/* process extra */

		foreach ($extraArray as $valueArray)
		{
			foreach ($valueArray as $key => $value)
			{
				if ($key === 'alias')
				{
					$actualArray[] = $value;
				}
			}
		}
		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetArrayByLanguageAndAlias
	 *
	 * @since 4.0.0
	 *
	 * @param string $extraAlias
	 * @param string $language
	 * @param string $expect
	 *
	 * @dataProvider providerExtraGetArrayAlias
	 */

	public function testGetArrayByLanguageAndAlias(string $extraAlias = null, string $language = null, string $expect = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$actualArray = $extraModel->getArrayByAliasAndLanguage($extraAlias, $language);

		/* compare */

		$this->markTestSkipped('implement sibling handling in model!');
		$this->assertEquals($expect, $actualArray['alias']);
	}

	/**
	 * testPublishByDate
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 * @param int $expect
	 *
	 * @dataProvider providerExtraPublishDate
	 */

	public function testPublishByDate(string $date = null, int $expect = null)
	{
		/* setup */

		$extraModel = new Model\Extra();

		/* actual */

		$actual = $extraModel->publishByDate($date);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
