<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-2021, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 *
 * Language : English [en]
 * Translators :
 * 1. Dark❶ [dark1]
 *
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	// Common
	'ACP_UNC_SET'	=> 'Settings',
	'ACP_UNC_BY'	=> 'By',

	// Main
	'ACP_UNC_ENABLE'					=> 'User Notification Control Enable',
	'ACP_UNC_ENABLE_EXPLAIN'			=> 'Enables the User Notification Control.<br>Default : No',
	'ACP_UNC_NOTIFY'					=> 'User Notification Disable',
	'ACP_UNC_NOTIFY_EXPLAIN'			=> 'Select the Option to force control the User Notification of All Users.<br>Default : <b>None</b><br>Following are Options',
	'ACP_UNC_NOTIFY_ENABLE'				=> 'Enable',
	'ACP_UNC_NOTIFY_ENABLE_EXPLAIN'		=> 'Force enable the User Notification of All Users.',
	'ACP_UNC_NOTIFY_NONE'				=> 'None',
	'ACP_UNC_NOTIFY_NONE_EXPLAIN'		=> 'Let each User choose the User Notification in UCP.',
	'ACP_UNC_NOTIFY_DISABLE'			=> 'Disable',
	'ACP_UNC_NOTIFY_DISABLE_EXPLAIN'	=> 'Force disable the User Notification of All Users.',
	'ACP_UNC_NO_LANG_KEY'				=> 'No Language Key',
	'ACP_UNC_NOTIFY_NAME'				=> 'Name',
	'ACP_UNC_NOTIFY_EXPLAIN'			=> 'Explain',
	'ACP_UNC_NOTIFY_TYPE_ID'			=> 'Type ID',
	'ACP_UNC_NOTIFY_LANG_KEY'			=> 'Language Key',
	'ACP_UNC_NOTIFY_TOGGLE'				=> 'Toggle',
	'ACP_UNC_NOTIFY_TOGGLE_EXPLAIN'		=> 'To display additional Info',
	'ACP_UNC_NO_NOTICE_TITLE'			=> 'Notice',
	'ACP_UNC_NO_NOTICE_LANG_KEY'		=> 'The “<b>%1$s </b>” is to indicate that neither phpBB core nor other extension(s) has provided the required language key for the <b>Notification’s Name</b>.',
	'ACP_UNC_NO_NOTICE_FAQ_INFO'		=> 'For further details on this, read FAQ of this Extension on “phpBB Customisation Database” OR “GitHub Wiki”.',

	// Prune
	'ACP_UNC_ALL_NOTIFY_EXPIRE'			=> 'All Notification Expiration',
	'ACP_UNC_ALL_NOTIFY_EXPIRE_EXPLAIN'	=> 'Number of day(s) after “<b>%1$s</b>” that will elapse,<br>before All (Read and Un-Read) notification(s) will be deleted.<br>Default : 365 Days',
	'ACP_UNC_TOTAL_DAYS'				=> 'Total Day(s)',
	'ACP_UNC_TOTAL_DAYS_EXPLAIN'		=> 'All Notification delete before,<br>“<b>%1$s</b>” + “<b>%2$s</b>” total day(s).<br>This is “<b>Read-Only</b>”.',
	'ACP_UNC_READ_NOTIFICATION_NOTICE'	=> 'This is “<b>Read-Only</b>”, Change this setting here',
	'ACP_UNC_CRON_SET'					=> 'Cron Task Settings',
	'ACP_UNC_CRON_ENABLE'				=> 'Enable Auto Prune All Notification(s)',
	'ACP_UNC_CRON_INTERVAL'				=> 'Auto Prune Notification Interval',
	'ACP_UNC_CRON_LAST_RUN'				=> 'Prune Notification Last Run',
	'ACP_UNC_CRON_NEXT_RUN'				=> 'Prune Notification Next Run',
	'ACP_UNC_CRON_RUN'					=> 'Run Prune All Notification(s)',
	'ACP_UNC_CRON_RUN_NOW'				=> 'Run Now',
	'ACP_UNC_DAYS'						=> 'Day(s)',
	'ACP_UNC_STAT_TAB'					=> 'Notification Tabular Statistic',
	'ACP_UNC_STAT_NOTIFICATION'			=> 'Notification(s)',
	'ACP_UNC_STAT_UNREAD'				=> 'Un-Read',
	'ACP_UNC_STAT_READ'					=> 'Read',
	'ACP_UNC_STAT_ALL'					=> 'Current Total',
	'ACP_UNC_STAT_EXP'					=> 'Will Expire',
	'ACP_UNC_STAT_REM'					=> 'Will Remain',
]);
