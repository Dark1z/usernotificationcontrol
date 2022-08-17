<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-forever, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 *
 * Language : Turkish [tr]
 * Translators :
 * 1. Tksharmely
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
	'ACP_UNC_TITLE'	=> 'Kullanıcı Bildirim Kontrolü',

	// phpBB Log
	'ACP_UNC_LOG_SET_SAV'	=> '<strong>Kullanıcı Bildirim Kontrolü [UNC]</strong><br>» %s başarıyla kaydedildi!',
	'ACP_UNC_LOG_CRON'		=> '<strong>Kullanıcı Bildirim Kontrolü [UNC]</strong><br>» Otomatik süpürme tamamlandı!%s',
	'UNC_AUTO_PRUNE_LOG'	=> '<strong>Kullanıcı Bildirim Kontrolü [UNC]</strong><br>» Otomatik toplu süpürme başarıyla tamamlandı.<br>» “%1$s” gün ile “%2$s” arasındaki bildirimler silindi.',

	// ACP Modes
	'ACP_UNC_MAIN'	=> 'Ana Ayarlar',
	'ACP_UNC_PRUNE'	=> 'Süpürme Seçenekleri',
]);
