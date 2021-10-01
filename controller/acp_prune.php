<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-2021, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\controller;

/**
 * @ignore
 */
use phpbb\language\language;
use phpbb\log\log;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\config\config;
use phpbb\cron\manager as cron_manager;
use phpbb\db\driver\driver_interface as db_driver;

/**
 * User Notification Control [UNC] ACP controller Prune.
 */
class acp_prune extends acp_base
{
	/** @var config */
	protected $config;

	/** @var cron_manager */
	protected $cron_manager;

	/** @var db_driver */
	protected $db;

	/** Time Format */
	const TIME_FORMAT	= 'Y-m-d h:i:s A P';

	/**
	 * Constructor.
	 *
	 * @param language			$language		Language object
	 * @param log				$log			Log object
	 * @param request			$request		Request object
	 * @param template			$template		Template object
	 * @param user				$user			User object
	 * @param config			$config			Config object
	 * @param cron_manager		$cron_manager	Cron manager
	 * @param db_driver			$db				Database object
	 */
	public function __construct(language $language, log $log, request $request, template $template, user $user, config $config, cron_manager $cron_manager, db_driver $db)
	{
		parent::__construct($language, $log, $request, $template, $user);

		$this->config			= $config;
		$this->cron_manager		= $cron_manager;
		$this->db				= $db;
	}

	/**
	 * Display the options a user can configure for Cron Mode.
	 *
	 * @return void
	 * @access public
	 */
	public function handle()
	{
		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			$this->check_form_on_submit();

			// Set the options the user configured
			$this->config->set('dark1_unc_all_notify_expire_days', $this->request->variable('dark1_unc_all_notify_expire_days', 0));
			$this->config->set('dark1_unc_auto_prune_notify_enable', $this->request->variable('dark1_unc_auto_prune_notify_enable', 0));
			$this->config->set('dark1_unc_auto_prune_notify_gc', ($this->request->variable('dark1_unc_auto_prune_notify_gc', 0)) * 86400);

			$this->success_form_on_submit();
		}

		// Run Cron Task
		if ($this->request->is_set_post('runcrontask'))
		{
			$this->check_form_on_submit();

			$cron_task = $this->cron_manager->find_task('dark1.usernotificationcontrol.cron.auto_prune_notify');
			$cron_task->run();

			$this->success_form_on_submit();
		}

		// Set output variables for display in the template
		$this->template->assign_vars([
			'UNC_READ_EXPIRE'		=> $this->config['read_notification_expire_days'],
			'UNC_ALL_EXPIRE'		=> $this->config['dark1_unc_all_notify_expire_days'],
			'UNC_ENABLE_CRON'		=> $this->config['dark1_unc_auto_prune_notify_enable'],
			'UNC_CRON_INTERVAL'		=> ($this->config['dark1_unc_auto_prune_notify_gc'] / 86400),
			'UNC_CRON_LAST_RUN'		=> $this->user->format_date($this->config['dark1_unc_auto_prune_notify_last_gc'], self::TIME_FORMAT, true),
			'UNC_CRON_NEXT_RUN'		=> $this->user->format_date($this->config['dark1_unc_auto_prune_notify_last_gc'] + $this->config['dark1_unc_auto_prune_notify_gc'], self::TIME_FORMAT, true),
		]);
	}
}
