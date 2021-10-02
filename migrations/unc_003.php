<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-2021, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\migrations;

/**
 * @ignore
 */
use phpbb\db\migration\migration;

/**
 * Migration stage 003 : Add Module & Config
 */
class unc_003 extends migration
{
	static public function depends_on()
	{
		return ['\dark1\usernotificationcontrol\migrations\unc_002'];
	}

	public function update_data()
	{
		return [
			// Add Config
			['config.add', ['dark1_unc_all_notify_expire_days', 365, true]],
			['config.add', ['dark1_unc_auto_prune_notify_enable', 0, true]],
			['config.add', ['dark1_unc_auto_prune_notify_gc', 864000, true]],
			['config.add', ['dark1_unc_auto_prune_notify_last_gc', 0, true]],

			// Remove Module
			['module.remove', [
				'acp',
				'ACP_UNC_TITLE',
				[
					'module_basename'	=> '\dark1\usernotificationcontrol\acp\main_module',
					'modes'				=> ['main'],
				],
			]],

			// Add Module
			['module.add', [
				'acp',
				'ACP_UNC_TITLE',
				[
					'module_basename'	=> '\dark1\usernotificationcontrol\acp\main_module',
					'modes'				=> ['main', 'prune'],
				],
			]],
		];
	}
}
