<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-forever, Dark❶, https://dark1.tech
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
	'ACP_UNC_TITLE'	=> 'User Notification Control',

	// phpBB Log
	'ACP_UNC_LOG_SET_SAV'	=> '<strong>User Notification Control [UNC]</strong><br>» %s saved successfully!',
	'ACP_UNC_LOG_CRON'		=> '<strong>User Notification Control [UNC]</strong><br>» Cron ran successfully!%s',
	'UNC_AUTO_PRUNE_LOG'	=> '<strong>User Notification Control [UNC]</strong><br>» Auto prune all notification(s) completed.<br>» Pruned notification(s) before “%1$s” Day(s) from “%2$s”',

	// ACP Modes
	'ACP_UNC_MAIN'	=> 'Main Settings',
	'ACP_UNC_PRUNE'	=> 'Prune Settings',
]);
