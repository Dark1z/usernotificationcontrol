<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol;

/**
 * @ignore
 */
use phpbb\extension\base;

/**
 * User Notification Control [UNC] Extension base
 *
 * It is recommended to remove this file from
 * an extension if it is not going to be used.
 */
class ext extends base
{
	/** @var string Require phpBB v3.2.10 due to events. */
	const PHPBB_MIN_3_2_X = '3.2.10';

	/** @var string Require phpBB v3.3.1 due to events. */
	const PHPBB_MIN_3_3_X = '3.3.1';

	/**
	 * {@inheritdoc}
	 */
	public function is_enableable()
	{
		return $this->pbpbb_ver_chk();
	}

	/**
	 * phpBB Version Check.
	 *
	 * @return bool
	 * @access private
	 */
	private function pbpbb_ver_chk()
	{
		$config = $this->container->get('config');

		$phpbb_version = phpbb_version_compare(PHPBB_VERSION, $config['version'], '>=') ? PHPBB_VERSION : $config['version'] ;
		$min_version = strpos($phpbb_version, '3.2.') === 0 ? self::PHPBB_MIN_3_2_X : self::PHPBB_MIN_3_3_X;

		return phpbb_version_compare($phpbb_version, $min_version, '>=');
	}
}
