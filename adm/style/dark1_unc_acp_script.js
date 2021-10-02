/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-2021, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

$('#toggleuncdebug').click(function() {
	$('span[data-uncdebug]').toggleClass('show');
});

$('input[name="dark1_unc_all_notify_expire_days"]').change(function() {
	$('#unc_total_expire').val(parseInt($('#unc_read_expire').val()) + parseInt($(this).val()));
});
