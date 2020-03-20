<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
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
use dark1\usernotificationcontrol\core\unc_table;
use Symfony\Component\DependencyInjection\ContainerInterface;
use phpbb\config\config;

/**
 * User Notification Control [UNC] ACP controller Main.
 */
class acp_main extends acp_base
{
	/** @var \dark1\usernotificationcontrol\core\unc_table */
	protected $unc_table;

	/** @var ContainerInterface */
	protected $phpbb_container;

	/** @var \phpbb\config\config */
	protected $config;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\language\language				$language		Language object
	 * @param \phpbb\log\log						$log			Log object
	 * @param \phpbb\request\request				$request		Request object
	 * @param \phpbb\template\template				$template		Template object
	 * @param \phpbb\user							$user			User object
	 * @param unc_table								$unc_table
	 * @param ContainerInterface					$phpbb_container
	 * @param \phpbb\config\config					$config			Config object
	 */
	public function __construct(language $language, log $log, request $request, template $template, user $user, unc_table $unc_table, ContainerInterface $phpbb_container, config $config)
	{
		parent::__construct($language, $log, $request, $template, $user);

		$this->phpbb_container	= $phpbb_container;
		$this->config			= $config;
		$this->unc_table		= $unc_table;
	}

	/**
	 * Display the options a user can configure for Main Mode.
	 *
	 * @return void
	 * @access public
	 */
	public function handle()
	{
		$notify_matrix = [];

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			$this->check_form_on_submit();

			// Set the options the user configured
			$this->config->set('dark1_unc_enable', $this->request->variable('dark1_unc_enable', 0));

			// Get No Notify Matrix
			$notify_matrix = $this->request_notify_method_type_matrix();

			// Set No Notify Matrix
			$this->unc_table->set_notify_method_type_matrix($notify_matrix);

			// Reflect in other tables
			$this->unc_table->update_user_notifications_table($notify_matrix, $this->config['dark1_unc_enable']);

			$this->success_form_on_submit();
		}

		// Get No Notify Matrix & display the Options
		$notify_matrix = $this->unc_table->get_notify_method_type_matrix();
		$this->output_notification_methods_types($notify_matrix);

		// Set output variables for display in the template
		$this->template->assign_vars([
			'UNC_ENABLE'		=> $this->config['dark1_unc_enable'],
		]);
	}

	/**
	 * Request the Notification Methods and Types Matrix.
	 *
	 * @return array $notify_matrix
	 * @access private
	 */
	private function request_notify_method_type_matrix()
	{
		// Get phpBB Notification
		$phpbb_notifications = $this->phpbb_container->get('notification_manager');
		$notification_methods = $phpbb_notifications->get_subscription_methods();
		$notification_types_groups = $phpbb_notifications->get_subscription_types();
		$this->language->add_lang('ucp');

		$notify_matrix = [];
		foreach ($notification_types_groups as $group => $notification_types)
		{
			foreach ($notification_types as $type => $type_data)
			{
				foreach ($notification_methods as $method => $method_data)
				{
					$notify_value = $this->request->variable(str_replace('.', '_', $type_data['id'] . '_' . $method_data['id']), 0);
					if ($notify_value == 1)
					{
						$notify_matrix[$method_data['id']][$type_data['id']] = true;
					}
					else if ($notify_value == -1)
					{
						$notify_matrix[$method_data['id']][$type_data['id']] = false;
					}
				}
			}
		}

		return $notify_matrix;
	}

	/**
	 * Display the Notification Methods and Types with their options.
	 *
	 * @param array $notify_matrix
	 *
	 * @return void
	 * @access private
	 */
	private function output_notification_methods_types($notify_matrix)
	{
		// Get phpBB Notification
		$phpbb_notifications = $this->phpbb_container->get('notification_manager');
		$notification_methods = $phpbb_notifications->get_subscription_methods();
		$notification_types_groups = $phpbb_notifications->get_subscription_types();
		$block_method = 'notification_methods';
		$block_type = 'notification_types';

		foreach ($notification_methods as $method => $method_data)
		{
			$this->template->assign_block_vars($block_method, array(
				'METHOD'			=> $method_data['id'],
				'NAME'				=> $this->language->lang($method_data['lang']),
			));
		}

		foreach ($notification_types_groups as $group => $notification_types)
		{
			$this->template->assign_block_vars($block_type, array(
				'GROUP_NAME'	=> $this->language->lang($group),
			));

			foreach ($notification_types as $type => $type_data)
			{
				$this->template->assign_block_vars($block_type, array(
					'TYPE'				=> $type_data['id'],
					'NAME'				=> $this->language->lang($type_data['lang']),
					'EXPLAIN'			=> (isset($this->language->lang[$type_data['lang'] . '_EXPLAIN'])) ? $this->language->lang($type_data['lang'] . '_EXPLAIN') : '',
				));

				foreach ($notification_methods as $method => $method_data)
				{
					$this->template->assign_block_vars($block_type . '.' . $block_method, array(
						'METHOD'			=> $method_data['id'],
						'NAME'				=> $this->language->lang($method_data['lang']),
						'AVAILABLE'			=> $method_data['method']->is_available($type_data['type']),
						'SUBSCRIBED'		=> isset($notify_matrix[$method_data['id']][$type_data['id']]) ? ($notify_matrix[$method_data['id']][$type_data['id']] ? 1 : -1) : 0 ,
					));
				}
			}
		}

		$this->template->assign_vars(array(
			strtoupper($block_method) . '_COLS' => 3,
			strtoupper($block_type) . '_COLS' => (count($notification_methods) * 3) + 1,
		));
	}
}
