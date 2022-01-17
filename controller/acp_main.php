<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-forever, Dark❶, https://dark1.tech
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
use dark1\usernotificationcontrol\core\unc_table;
use dark1\usernotificationcontrol\core\unc_helper;

/**
 * User Notification Control [UNC] ACP controller Main.
 */
class acp_main extends acp_base
{
	/** @var config */
	protected $config;

	/** @var unc_table */
	protected $unc_table;

	/** @var unc_helper */
	protected $unc_helper;

	/** @var array */
	protected $notification_methods;

	/** @var array */
	protected $notification_types_groups;

	/** @var array */
	protected $notify_matrix;

	/**
	 * Constructor.
	 *
	 * @param language		$language				Language object
	 * @param log			$log					Log object
	 * @param request		$request				Request object
	 * @param template		$template				Template object
	 * @param user			$user					User object
	 * @param config		config					Config object
	 * @param unc_table		$unc_table				UNC Table object
	 * @param unc_helper	$unc_helper				UNC Helper object
	 */
	public function __construct(language $language, log $log, request $request, template $template, user $user, config $config, unc_table $unc_table, unc_helper $unc_helper)
	{
		parent::__construct($language, $log, $request, $template, $user);

		$this->config			= $config;
		$this->unc_table		= $unc_table;
		$this->unc_helper		= $unc_helper;
		$this->notify_matrix	= [];

		// Get phpBB Notification Collection Arrays
		$this->notification_methods = $this->unc_helper->get_subscription_methods();
		$this->notification_types_groups = $this->unc_helper->get_subscription_types();
	}



	/**
	 * Display the options a user can configure for Main Mode.
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
			$dark1_unc_enable = $this->request->variable('dark1_unc_enable', 0);
			$this->config->set('dark1_unc_enable', $dark1_unc_enable);

			// Get No Notify Matrix
			$this->request_notify_method_type_matrix();

			// Set No Notify Matrix
			$this->unc_table->set_notify_method_type_matrix($this->notify_matrix);

			// Reflect in other tables if enabled
			if ($dark1_unc_enable)
			{
				$this->unc_table->update_user_notifications_table($this->notify_matrix);
			}

			$this->success_form_on_submit();
		}

		// Add Required Lang File(s)
		$this->unc_helper->add_lang();

		// Get Not Notify Matrix & display the Options
		$this->notify_matrix = $this->unc_table->get_notify_method_type_matrix();
		$this->display_notification_methods_types();

		// Set output variables for display in the template
		$this->template->assign_vars([
			'UNC_ENABLE'	=> $this->config['dark1_unc_enable'],
			'UNC_NOTICE'	=> $this->unc_helper->get_lang_key(''),
		]);
	}



	/**
	 * Request the Notification Methods and Types Matrix.
	 *
	 * @access private
	 */
	private function request_notify_method_type_matrix()
	{
		$this->notify_matrix = [];
		foreach ($this->notification_types_groups as $group => $notification_types)
		{
			foreach ($notification_types as $type => $type_data)
			{
				foreach ($this->notification_methods as $method => $method_data)
				{
					$notify_value = $this->request->variable(str_replace('.', '_', $type_data['id'] . '_' . $method_data['id']), 0);
					if ($notify_value == 1)
					{
						$this->notify_matrix[$method_data['id']][$type_data['id']] = true;
					}
					else if ($notify_value == -1)
					{
						$this->notify_matrix[$method_data['id']][$type_data['id']] = false;
					}
				}
			}
		}
	}



	/**
	 * Display the Notification Methods and Types with their options.
	 *
	 * @return void
	 * @access private
	 */
	private function display_notification_methods_types()
	{
		$block_method = 'notification_methods';
		$block_type = 'notification_types';

		$this->display_notification_methods($block_method);
		$this->display_notification_types($block_type, $block_method);

		$this->template->assign_vars([
			strtoupper($block_method) . '_COLS'	=> 3,
			strtoupper($block_type) . '_COLS'	=> (count($this->notification_methods) * 3) + 1,
		]);
	}



	/**
	 * Display the Notification Types.
	 *
	 * @param string $block_type
	 * @param string $block_method
	 *
	 * @return void
	 * @access private
	 */
	private function display_notification_types($block_type, $block_method)
	{
		foreach ($this->notification_types_groups as $group => $notification_types)
		{
			$this->template->assign_block_vars($block_type, [
				'GROUP_NAME'	=> $this->unc_helper->get_lang_key($group, true, $group),
			]);

			foreach ($notification_types as $type => $type_data)
			{
				$this->template->assign_block_vars($block_type, [
					'TYPE'		=> $type_data['id'],
					'TEXT'		=> implode(' ',array_unique(explode(' ', str_replace(['notification.type', '.', '_'], ' ', $type_data['id'])))),
					'NAME'		=> $this->unc_helper->get_lang_key($type_data['lang']),
					'LANG_KEY'	=> $type_data['lang'],
					'EXPLAIN'	=> $this->unc_helper->get_lang_key($type_data['lang'] . '_EXPLAIN', false),
				]);

				$this->display_notification_methods($block_type . '.' . $block_method, $type_data['id']);
			}
		}
	}



	/**
	 * Display the Notification Methods.
	 *
	 * @param string		$block_var
	 * @param string|bool	$type_id
	 *
	 * @return void
	 * @access private
	 */
	private function display_notification_methods($block_var, $type_id = false)
	{
		foreach ($this->notification_methods as $method => $method_data)
		{
			$this->template->assign_block_vars($block_var, array_merge(
				['METHOD'	=> $method_data['id']],
				($type_id === false)
				? ['NAME' => $this->unc_helper->get_lang_key($method_data['lang'], true, $method_data['id'])]
				: ['SUBSCRIBED'	=> isset($this->notify_matrix[$method_data['id']][$type_id]) ? ($this->notify_matrix[$method_data['id']][$type_id] ? 1 : -1) : 0]
			));
		}
	}
}
