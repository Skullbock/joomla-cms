<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

require_once JPATH_PLATFORM . '/joomla/table/user.php';

/**
 * Test class for JTableUser.
 * Generated by PHPUnit on 2011-12-06 at 03:44:10.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Table
 * @since       11.1
 */
class JTableUserTest extends TestCaseDatabase
{
	/**
	 * Gets the data set to be loaded into the database during setup
	 *
	 * @return  PHPUnit_Extensions_Database_DataSet_CsvDataSet
	 *
	 * @since   11.1
	 */
	protected function getDataSet()
	{
		$dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', "'", '\\');

		$dataSet->addTable('jos_users', JPATH_TEST_DATABASE . '/jos_users.csv');

		return $dataSet;
	}

	/**
	 * Test...
	 *
	 * @covers JTableUser::store
	 *
	 * @return void
	 */
	public function testStoreNewUser()
	{
		$user = new JTableUser(self::$driver);

		$user->name = 'Neil Armstrong';
		$user->username = 'neil.armstrong';
		$user->email = 'neil.armstrong@example.com';
		$user->groups = array(
			'Astronauts' => 1,
			'Moon walkers' => 2,
		);

		$this->assertThat(
			$user->store(),
			$this->isTrue(),
			'Checks that the new user stored correctly.'
		);

		self::$driver->setQuery('SELECT * FROM #__users WHERE id = ' . (int) $user->id);
		$stored = self::$driver->loadObject();

		$this->assertThat(
			$stored->name,
			$this->equalTo('Neil Armstrong'),
			'Checks that name was stored correctly.'
		);

		$this->assertThat(
			$stored->username,
			$this->equalTo('neil.armstrong'),
			'Checks that username was stored correctly.'
		);

		$this->assertThat(
			$stored->email,
			$this->equalTo('neil.armstrong@example.com'),
			'Checks that email was stored correctly.'
		);

		self::$driver->setQuery('SELECT group_id FROM #__user_usergroup_map WHERE user_id = ' . (int) $user->id);
		$this->assertThat(
			self::$driver->loadColumn(),
			$this->equalTo(array(1, 2)),
			'Checks that the user group mapping was stored correctly.'
		);
	}
}
