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

class unc_000_main extends migration
{
	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_data()
	{
		return array(
			// Config
			array('config.add', array('dark1_unc_enable', 0)),

			// Module
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_UNC_TITLE',
			)),
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
