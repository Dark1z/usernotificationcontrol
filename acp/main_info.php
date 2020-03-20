<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\acp;

/**
 * User Notification Control [UNC] ACP module info.
 */
class main_info
{
	public function module()
	{
		return array(
			'filename'	=> '\dark1\usernotificationcontrol\acp\main_module',
			'title'		=> 'ACP_UNC_TITLE',
			'modes'		=> array(
				'main'	=> array(
					'title'	=> 'ACP_UNC_MAIN',
					'auth'	=> 'ext_dark1/usernotificationcontrol && acl_a_board',
					'cat'	=> array('ACP_UNC_TITLE')
				),
			),
		);
	}
}
