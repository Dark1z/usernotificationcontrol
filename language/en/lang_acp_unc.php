<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
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
	$lang = array();
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

$lang = array_merge($lang, array(
	// Common
	'ACP_UNC_SET'	=> 'Settings',
	'ACP_UNC_BY'	=> 'By',

	// Main
	'ACP_UNC_ENABLE'					=> 'User Notification Control Enable',
	'ACP_UNC_ENABLE_EXPLAIN'			=> 'Enables the User Notification Control.<br>Default : No',
	'ACP_UNC_NOTIFY'					=> 'User Notification Disable',
	'ACP_UNC_NOTIFY_EXPLAIN'			=> 'Select the Option to force control the User Notification.<br>Default : <b>None</b><br>Following are Options',
	'ACP_UNC_NOTIFY_ENABLE'				=> 'Enable',
	'ACP_UNC_NOTIFY_ENABLE_EXPLAIN'		=> 'Force enable the User Notification.',
	'ACP_UNC_NOTIFY_NONE'				=> 'None',
	'ACP_UNC_NOTIFY_NONE_EXPLAIN'		=> 'User select the Notification',
	'ACP_UNC_NOTIFY_DISABLE'			=> 'Disable',
	'ACP_UNC_NOTIFY_DISABLE_EXPLAIN'	=> 'Force disable the User Notification.',
	'ACP_UNC_NO_LANG_KEY'				=> 'No Language Key',
	'ACP_UNC_NOTIFY_NAME'				=> 'Name',
	'ACP_UNC_NOTIFY_EXPLAIN'			=> 'Explain',
	'ACP_UNC_NO_LANG_KEY_NOTICE'		=> '<b>Notice</b> : The “<b>%1$s </b>” is to indicate that neither core nor other extension(s) has not provided the required language key for the <b>Notification’s Name</b>.',
));
