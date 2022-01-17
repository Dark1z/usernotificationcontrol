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
 * Migration stage 000 : Main
 */
class unc_000_main extends migration
{
	static public function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function update_data()
	{
		return [
			// Config
			['config.add', ['dark1_unc_enable', 0]],

			// Module
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_UNC_TITLE',
			]],
			['module.add', [
				'acp',
				'ACP_UNC_TITLE',
				[
					'module_basename'	=> '\dark1\usernotificationcontrol\acp\main_module',
					'modes'				=> ['main'],
				],
			]],
		];
	}
}
