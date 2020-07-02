<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use dark1\usernotificationcontrol\core\unc_table;
use phpbb\config\config;
use phpbb\language\language;

/**
 * User Notification Control [UNC] Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \dark1\usernotificationcontrol\core\unc_table */
	protected $unc_table;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\language\language */
	protected $language;

	/**
	 * Constructor
	 *
	 * @param unc_table					$unc_table
	 * @param \phpbb\config\config		$config		phpBB config
	 * @param \phpbb\language\language	$language	Language object
	 */
	public function __construct(unc_table $unc_table, config $config, language $language)
	{
		$this->unc_table	= $unc_table;
		$this->config		= $config;
		$this->language		= $language;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @access public
	*/
	public static function getSubscribedEvents()
	{
		return array(
			'core.ucp_display_module_before'										=> 'ucp_display_module_before',
			'core.user_add_modify_notifications_data'								=> 'user_add_modify_notifications_data',
			'core.ucp_notifications_submit_notification_is_set'						=> 'ucp_notifications_submit_notification_is_set',
			'core.ucp_notifications_output_notification_types_modify_template_vars'	=> 'ucp_notifications_output_notification_types_modify_template_vars',
			'core.notification_manager_add_notifications_for_users_modify_data'		=> 'notification_manager_add_notifications_for_users_modify_data',
		);
	}



	/**
	 * Load language files in UCP
	 *
	 * @param \phpbb\event\data	$event	Event object
	 *
	 * @return void
	 * @access public
	 */
	public function ucp_display_module_before($event)
	{
		$mode = $event['mode'];

		if ($this->config['dark1_unc_enable'] == 1 && $mode == 'notification_options')
		{
			$this->language->add_lang('lang_unc', 'dark1/usernotificationcontrol');
		}
	}



	/**
	* User add modify notifications data
	*
	* @param object $event The event object
	*
	* @return void
	* @access public
	*/
	public function user_add_modify_notifications_data($event)
	{
		$notifications_data = $event['notifications_data'];

		if ($this->config['dark1_unc_enable'] == 1 && !empty($notifications_data))
		{
			$notify_matrix = $this->unc_table->get_notify_method_type_matrix();

			foreach ($notifications_data as $key => $subscription)
			{
				if (isset($notify_matrix[$subscription['method']][$subscription['item_type']]) && !$notify_matrix[$subscription['method']][$subscription['item_type']])
				{
					unset($notifications_data[$key]);
				}
			}
		}

		$event['notifications_data'] = $notifications_data;
	}



	/**
	* UCP on notifications submit check $is_set_notify
	*
	* @param object $event The event object
	*
	* @return void
	* @access public
	*/
	public function ucp_notifications_submit_notification_is_set($event)
	{
		$type_data = $event['type_data'];
		$method_data = $event['method_data'];
		$is_set_notify = $event['is_set_notify'];

		if ($this->config['dark1_unc_enable'] == 1)
		{
			$value = $this->unc_table->get_notify_method_type_value($method_data['id'], $type_data['id']);

			$is_set_notify = (isset($value)) ? $value : $is_set_notify ;
		}

		$event['is_set_notify'] = $is_set_notify;
	}



	/**
	* UCP notifications modify 'notification_types' template vars
	*
	* @param object $event The event object
	*
	* @return void
	* @access public
	*/
	public function ucp_notifications_output_notification_types_modify_template_vars($event)
	{
		$type_data = $event['type_data'];
		$method_data = $event['method_data'];
		$tpl_ary = $event['tpl_ary'];

		if ($this->config['dark1_unc_enable'] == 1)
		{
			$value = $this->unc_table->get_notify_method_type_value($method_data['id'], $type_data['id']);

			$tpl_ary['AVAILABLE'] = (isset($value)) ? false : $tpl_ary['AVAILABLE'] ;
		}

		$event['tpl_ary'] = $tpl_ary;
	}



	/**
	* Modify data in 'notification_manager' at 'add_notifications' for users
	*
	* @param object $event The event object
	*
	* @return void
	* @access public
	*/
	public function notification_manager_add_notifications_for_users_modify_data($event)
	{
		$notification_type_name = $event['notification_type_name'];
		$notify_users = $event['notify_users'];

		if ($this->config['dark1_unc_enable'] == 1)
		{
			$notify_matrix = $this->unc_table->get_notify_method_type_matrix();

			// Go through each user
			foreach ($notify_users as $user => $methods)
			{
				foreach ($methods as $key => $method)
				{
					// unset the notification method
					if (isset($notify_matrix[$method][$notification_type_name]) && !$notify_matrix[$method][$notification_type_name])
					{
						unset($notify_users[$user][$key]);
					}
				}
			}
		}

		$event['notify_users'] = $notify_users;
	}
}
