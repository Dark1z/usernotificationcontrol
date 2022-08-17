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
	// Common
	'ACP_UNC_SET'	=> 'Ayarlar',
	'ACP_UNC_BY'	=> 'Geliştirici:',

	// Main
	'ACP_UNC_ENABLE'					=> 'Kullanıcı Bildirim Kontrolünü Aç',
	'ACP_UNC_ENABLE_EXPLAIN'			=> 'Kullanıcı Bildirim Kontrolünü açık hâle getirir.<br>Varsayılan : Hayır',
	'ACP_UNC_NOTIFY'					=> 'Kullanıcı Bildirim Kontrolü Devre Dışı Bırakma',
	'ACP_UNC_NOTIFY_EXPLAIN'			=> 'Tüm üyeler için varsayılan ayar yapmak için bir seçenek seçin.<br>Varsayılan : <b>Seçilmemiş</b><br>Aşağıdakiler seçeneklerdir;',
	'ACP_UNC_NOTIFY_ENABLE'				=> 'Açık',
	'ACP_UNC_NOTIFY_ENABLE_EXPLAIN'		=> 'Tüm üyeler için bildirimleri açık hâle getirir.',
	'ACP_UNC_NOTIFY_NONE'				=> 'Seçilmemiş',
	'ACP_UNC_NOTIFY_NONE_EXPLAIN'		=> 'UCP üzerinden her kullanıcıların bildirimleri seçmesine izin ver.',
	'ACP_UNC_NOTIFY_DISABLE'			=> 'Devredışı',
	'ACP_UNC_NOTIFY_DISABLE_EXPLAIN'	=> 'Tüm kullanıcıların bildirim almasını devre dışı bırak.',
	'ACP_UNC_NO_LANG_KEY'				=> 'Dil Anahtarı Yok',
	'ACP_UNC_NOTIFY_NAME'				=> 'İsim',
	'ACP_UNC_NOTIFY_EXPLAIN'			=> 'Açıklama',
	'ACP_UNC_NOTIFY_TYPE_ID'			=> 'Tip ID',
	'ACP_UNC_NOTIFY_LANG_KEY'			=> 'Dil Anahtarı',
	'ACP_UNC_NOTIFY_TOGGLE'				=> 'Aç/Kapat',
	'ACP_UNC_NOTIFY_TOGGLE_EXPLAIN'		=> 'Ek bilgi görüntüle',
	'ACP_UNC_NO_NOTICE_TITLE'			=> 'Dikkat',
	'ACP_UNC_NO_NOTICE_LANG_KEY'		=> ' “<b>%1$s </b>” phpBBnin <b>Bildirim Adı </b> için dil anahtarının olmadığı anlamına gelir.',
	'ACP_UNC_NO_NOTICE_FAQ_INFO'		=> 'Daha fazla detay için bu eklentinin S.S.S.nı “Github Wiki” ya da “phpBB Customisation Database” üzerinden okuyun.',

	// Prune
	'ACP_UNC_ALL_NOTIFY_EXPIRE'			=> 'Tüm Bildirimlerin Silinme Süresi',
	'ACP_UNC_ALL_NOTIFY_EXPIRE_EXPLAIN'	=> '“<b>%1$s</b>”nden hariç olarak,<br> Tüm (okunmuş ve okunmamış) bildirimler silinecek.<br>Varsayılan : 365 Gün',
	'ACP_UNC_TOTAL_DAYS'				=> 'Toplam Gün(ler)',
	'ACP_UNC_TOTAL_DAYS_EXPLAIN'		=> 'Tüm bildirimlerin silineceği zamanı ayarlar.<br>“<b>%1$s</b>” + “<b>%2$s</b>” <br>Bu ayar “<b>Sadece-Okunabilir</b>”dir.',
	'ACP_UNC_READ_NOTIFICATION_NOTICE'	=> 'Bu “<b>Sadece-Okunabilir</b>”, Bu ayarı buradan değiştirin',
	'ACP_UNC_CRON_SET'					=> 'Otomatik Görev Ayarları',
	'ACP_UNC_CRON_ENABLE'				=> 'Tüm bildirimleri otomatik süpürmeyi aç',
	'ACP_UNC_CRON_INTERVAL'				=> 'Otomatik Süpürme Zamanlaması',
	'ACP_UNC_CRON_LAST_RUN'				=> 'En son bildirim süpürme zamanı',
	'ACP_UNC_CRON_NEXT_RUN'				=> 'Bir sonraki bildirim süpürme zamanı',
	'ACP_UNC_CRON_RUN'					=> 'Tüm bildirimleri süpürmeyi şimdi aç',
	'ACP_UNC_CRON_RUN_NOW'				=> 'Şimdi Çalıştır',
	'ACP_UNC_DAYS'						=> 'Gün(ler)',
	'ACP_UNC_STAT_TAB'					=> 'Bildirim İstatistikleri',
	'ACP_UNC_STAT_NOTIFICATION'			=> 'Bildirim(ler)',
	'ACP_UNC_STAT_UNREAD'				=> 'Okunmadı',
	'ACP_UNC_STAT_READ'					=> 'Okundu',
	'ACP_UNC_STAT_ALL'					=> 'Toplam',
	'ACP_UNC_STAT_EXP'					=> 'Zamanı Dolacak',
	'ACP_UNC_STAT_REM'					=> 'Sabit Kalacak',
	'ACP_UNC_STAT_NOTICE'				=> 'Bu istatistikler “<b>%1$s</b>” (şu anki zamana) göredir.',
]);
