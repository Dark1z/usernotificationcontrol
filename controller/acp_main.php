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
use phpbb\event\dispatcher_interface as dispatcher;
use dark1\usernotificationcontrol\core\unc_table;
use dark1\usernotificationcontrol\core\unc_helper;

/**
 * User Notification Control [UNC] ACP controller Main.
 */
class acp_main extends acp_base
{
	/** @var config */
	protected $config;

	/** @var dispatcher */
	protected $dispatcher;

	/** @var unc_table */
	protected $unc_table;

	/** @var unc_helper */
	protected $unc_helper;

	/**
	 * Constructor.
	 *
	 * @param language		$language				Language object
	 * @param log			$log					Log object
	 * @param request		$request				Request object
	 * @param template		$template				Template object
	 * @param user			$user					User object
	 * @param config		config					Config object
	 * @param dispatcher	$dispatcher				Dispatcher object
	 * @param unc_table		$unc_table				UNC Table object
	 * @param unc_helper	$unc_helper				UNC Helper object
	 */
	public function __construct(language $language, log $log, request $request, template $template, user $user, config $config, dispatcher $dispatcher, unc_table $unc_table, unc_helper $unc_helper)
	{
		parent::__construct($language, $log, $request, $template, $user);

		$this->config				= $config;
		$this->dispatcher			= $dispatcher;
		$this->unc_table			= $unc_table;
		$this->unc_helper			= $unc_helper;
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
			$this->config->set('dark1_unc_enable', $this->request->variable('dark1_unc_enable', 0));

			// Get No Notify Matrix
			$notify_matrix = $this->request_notify_method_type_matrix();

			// Set No Notify Matrix
			$this->unc_table->set_notify_method_type_matrix($notify_matrix);

			// Reflect in other tables
			$this->unc_table->update_user_notifications_table($notify_matrix, (bool) $this->config['dark1_unc_enable']);

			$this->success_form_on_submit();
		}

		// Get No Notify Matrix & display the Options
		$notify_matrix = $this->unc_table->get_notify_method_type_matrix();
		$this->output_notification_methods_types($notify_matrix);

		// Set output variables for display in the template
		$this->template->assign_vars([
			'UNC_ENABLE'	=> $this->config['dark1_unc_enable'],
			'UNC_NOTICE'	=> $this->language->lang('ACP_UNC_NO_LANG_KEY_NOTICE', $this->get_lang_key('')),
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
		$notification_methods = $this->unc_helper->get_subscription_methods();
		$notification_types_groups = $this->unc_helper->get_subscription_types();

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
		$add_ext_lang = $this->unc_helper->get_lang_unc_custom();

		/**
		 * Event to modify the similar topics template block
		 *
		 * @event dark1.usernotificationcontrol.add_ext_lang
		 *
		 * @var array add_ext_lang		Array with [(string) '<vendor>/<extension>' => (string|array) '<lang>' | ['<lang1>', '<lang2>']]
		 *
		 * @since 1.0.2
		 */
		$vars = ['add_ext_lang'];
		extract($this->dispatcher->trigger_event('dark1.usernotificationcontrol.add_ext_lang', compact($vars)));

		// Add Language File(s) that are Required
		$this->language->add_lang('ucp');
		if (!empty($add_ext_lang))
		{
			foreach ($add_ext_lang as $ext => $langs)
			{
				$langs = is_string($langs) ? [$langs] : $langs;

				if (is_array($langs))
				{
					foreach ($langs as $lang)
					{
						$this->language->add_lang((string) $lang, (string) $ext);
					}
				}
			}
		}

		// Get phpBB Notification
		$notification_methods = $this->unc_helper->get_subscription_methods();
		$notification_types_groups = $this->unc_helper->get_subscription_types();

		$block_method = 'notification_methods';
		$block_type = 'notification_types';

		foreach ($notification_methods as $method => $method_data)
		{
			$this->template->assign_block_vars($block_method, [
				'METHOD'	=> $method_data['id'],
				'NAME'		=> $this->get_lang_key($method_data['lang'], true, $method_data['id']),
			]);
		}

		foreach ($notification_types_groups as $group => $notification_types)
		{
			$this->template->assign_block_vars($block_type, [
				'GROUP_NAME'	=> $this->get_lang_key($group, true, $group),
			]);

			foreach ($notification_types as $type => $type_data)
			{
				$this->template->assign_block_vars($block_type, [
					'TYPE'		=> $type_data['id'],
					'NAME'		=> $this->get_lang_key($type_data['lang']),
					'EXPLAIN'	=> $this->get_lang_key($type_data['lang'] . '_EXPLAIN', false),
				]);

				foreach ($notification_methods as $method => $method_data)
				{
					$this->template->assign_block_vars($block_type . '.' . $block_method, [
						'METHOD'		=> $method_data['id'],
						'NAME'			=> $this->language->lang($method_data['lang']),
						'SUBSCRIBED'	=> isset($notify_matrix[$method_data['id']][$type_data['id']]) ? ($notify_matrix[$method_data['id']][$type_data['id']] ? 1 : -1) : 0 ,
					]);
				}
			}
		}

		$this->template->assign_vars([
			strtoupper($block_method) . '_COLS'	=> 3,
			strtoupper($block_type) . '_COLS'	=> (count($notification_methods) * 3) + 1,
		]);
	}

	/**
	 * Get all of the subscription methods
	 *
	 * @param string $lang_key
	 * @param bool $warn
	 * @param string $sub
	 *
	 * @return string Array of methods
	 * @access private
	 */
	private function get_lang_key($lang_key, $warn = true, $sub = '')
	{
		$warn = $warn ? ' ⚠️ ' . $this->language->lang('ACP_UNC_NO_LANG_KEY') : '';

		return $this->language->is_set($lang_key) ? $this->language->lang($lang_key) : $warn . (!empty($sub) ? ' : ' . $sub : '');
	}
}
