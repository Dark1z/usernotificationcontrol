<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-forever, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\migrations;

/**
 * @ignore
 */
use phpbb\db\migration\migration;

/**
 * Migration stage 001 : Install
 */
class unc_001_install extends migration
{
	static public function depends_on()
	{
		return ['\dark1\usernotificationcontrol\migrations\unc_000_main'];
	}

	public function update_schema()
	{
		return [
			'add_tables'		=> [
				$this->table_prefix . 'dark1_unc'	=> [
					'COLUMNS'		=> [
						'notification_sr_no'	=> ['UINT', null],
						'notification_method'	=> ['VCHAR:255', ''],
						'notification_type'		=> ['VCHAR:255', ''],
						'notification_value'	=> ['BOOL', 0],
					],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_tables'		=> [
				$this->table_prefix . 'dark1_unc',
			],
		];
	}
}
