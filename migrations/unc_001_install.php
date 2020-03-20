<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\migrations;

use phpbb\db\migration\migration;

class unc_001_install extends migration
{

	static public function depends_on()
	{
		return array('\dark1\usernotificationcontrol\migrations\unc_000_main');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'dark1_unc'	=> array(
					'COLUMNS'		=> array(
						'notification_sr_no'	=> array('UINT', null),
						'notification_method'	=> array('VCHAR:255', ''),
						'notification_type'		=> array('VCHAR:255', ''),
						'notification_value'	=> array('BOOL', 0),
					),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'dark1_unc',
			),
		);
	}

	public function update_data()
	{
		return array(
			// Config
			array('config.add', array('dark1_unc_enable', 0)),

			// Module
			array('module.add', array(
				'acp',
				'ACP_UNC_TITLE',
				array(
					'module_basename'	=> '\dark1\usernotificationcontrol\acp\main_module',
					'modes'				=> array('main'),
				),
			)),
		);
	}
}
