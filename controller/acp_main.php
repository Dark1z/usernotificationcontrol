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
use phpbb\event\dispatcher_interface;
use dark1\usernotificationcontrol\core\unc_table;
use phpbb\notification\type\type_interface;
use phpbb\notification\method\method_interface;

/**
 * User Notification Control [UNC] ACP controller Main.
 */
class acp_main extends acp_base
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\event\dispatcher_interface */
	protected $dispatcher;

	/** @var \dark1\usernotificationcontrol\core\unc_table */
	protected $unc_table;

	/** @var array */
	protected $notification_types;

	/** @var array */
	protected $notification_methods;

	/**
	 * Constructor.
	 *
	 * @param \phpbb\language\language							$language		Language object
	 * @param \phpbb\log\log									$log			Log object
	 * @param \phpbb\request\request							$request		Request object
	 * @param \phpbb\template\template							$template		Template object
	 * @param \phpbb\user										$user			User object
	 * @param \phpbb\config\config								$config			Config object
	 * @param \phpbb\event\dispatcher_interface					$dispatcher		Dispatcher object
	 * @param \dark1\usernotificationcontrol\core\unc_table		$unc_table		UNC Table object
	 * @param array												$notification_types
	 * @param array												$notification_methods
	 */
	public function __construct(language $language, log $log, request $request, template $template, user $user, config $config, dispatcher_interface $dispatcher, unc_table $unc_table, $notification_types, $notification_methods)
	{
		parent::__construct($language, $log, $request, $template, $user);

		$this->config				= $config;
		$this->dispatcher			= $dispatcher;
		$this->unc_table			= $unc_table;
		$this->notification_types	= $notification_types;
		$this->notification_methods	= $notification_methods;
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
		$notification_methods = $this->get_subscription_methods();
		$notification_types_groups = $this->get_subscription_types();

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
		$add_ext_lang = [];
		/**
		 * Event to modify the similar topics template block
		 *
		 * @event dark1.usernotificationcontrol.add_ext_lang
		 *
		 * @var array add_ext_lang		Array with [(string) '<vendor>/<extension>' => (string) '<lang>']
		 *
		 * @since 1.0.2
		 */
		$vars = ['add_ext_lang'];
		extract($this->dispatcher->trigger_event('dark1.usernotificationcontrol.add_ext_lang', compact($vars)));

		// Add Language File(s) that are Required
		$this->language->add_lang('ucp');
		if (!empty($add_ext_lang))
		{
			foreach ($add_ext_lang as $ext => $lang)
			{
				$this->language->add_lang((string) $lang, (string) $ext);
			}
		}

		// Get phpBB Notification
		$notification_methods = $this->get_subscription_methods();
		$notification_types_groups = $this->get_subscription_types();

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

	/**
	 * Get all of the subscription methods
	 *
	 * @return array Array of methods
	 * @access private
	 */
	private function get_subscription_methods()
	{
		$subscription_methods = [];

		/** @var method_interface $method */
		foreach ($this->notification_methods as $method_name => $method)
		{
			$subscription_methods[$method_name] = [
				'id'		=> $method->get_type(),
				'lang'		=> str_replace('.', '_', strtoupper($method->get_type())),
			];
		}

		return $subscription_methods;
	}

	/**
	 * Get all of the subscription types
	 *
	 * @return array Array of item types
	 * @access private
	 */
	private function get_subscription_types()
	{
		$subscription_types = [];

		/** @var type_interface $type */
		foreach ($this->notification_types as $type_name => $type)
		{
			$type_ary = [
				'id'	=> $type->get_type(),
				'lang'	=> 'NOTIFICATION_TYPE_' . strtoupper($type->get_type()),
				'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
			];

			if ($type::$notification_option !== false)
			{
				$type_ary = array_merge($type_ary, $type::$notification_option);
			}

			$subscription_types[$type_ary['group']][$type_ary['id']] = $type_ary;
		}

		// Move miscellaneous group to last section
		if (isset($subscription_types['NOTIFICATION_GROUP_MISCELLANEOUS']))
		{
			$miscellaneous = $subscription_types['NOTIFICATION_GROUP_MISCELLANEOUS'];
			unset($subscription_types['NOTIFICATION_GROUP_MISCELLANEOUS']);
			$subscription_types['NOTIFICATION_GROUP_MISCELLANEOUS'] = $miscellaneous;
		}

		return $subscription_types;
	}
}
