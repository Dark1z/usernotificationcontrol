<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-2021, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\cron;

/**
 * @ignore
 */
use phpbb\cron\task\base;
use phpbb\config\config;
use phpbb\notification\manager as notification_manager;
use phpbb\log\log;

/**
 * Prune All Notifications Cron Task.
 */
class auto_prune_notify extends base
{
	/** @var config */
	protected $config;

	/** @var notification_manager */
	protected $notification_manager;

	/** @var log */
	protected $phpbb_log;

	/** Time Format */
	const TIME_FORMAT	= 'Y-m-d h:i:s A P';

	/**
	* Constructor for cron task
	*
	* @param config					$config					phpBB config
	* @param notification_manager	$notification_manager	phpBB notification manager
	* @param log					$phpbb_log				phpBB log
	* @access public
	*/
	public function __construct(config $config, notification_manager $notification_manager, log $phpbb_log)
	{
		$this->config					= $config;
		$this->notification_manager		= $notification_manager;
		$this->phpbb_log				= $phpbb_log;
	}

	/**
	* Returns whether this cron task can run, given current board configuration.
	*
	* @return bool
	*/
	public function is_runnable()
	{
		return (bool) $this->config['dark1_unc_auto_prune_notify_enable'];
	}

	/**
	* Returns whether this cron task should run now, because enough time has passed since it was last run.
	*
	* @return bool
	*/
	public function should_run()
	{
		return (bool) ($this->config['dark1_unc_auto_prune_notify_last_gc'] < (time() - $this->config['dark1_unc_auto_prune_notify_gc']));
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		// time minus expire days in seconds
		$days = $this->config['read_notification_expire_days'] + $this->config['dark1_unc_all_notify_expire_days'];
		$timestamp = time() - ($days * 86400);
		$this->notification_manager->prune_notifications((int) $timestamp, false);

		// Log the cron task run
		$before_date = date(self::TIME_FORMAT, (int) $timestamp);
		$this->phpbb_log->add('admin', ANONYMOUS, '127.0.0.1', 'UNC_AUTO_PRUNE_LOG', time(), [$days, $before_date]);

		// Update the last run time
		$this->config->set('dark1_unc_auto_prune_notify_last_gc', time(), false);
	}
}
