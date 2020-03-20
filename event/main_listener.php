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

/**
 * User Notification Control [UNC] Event listener.
 */
class main_listener implements EventSubscriberInterface
{
	/** @var \dark1\usernotificationcontrol\core\unc_table */
	protected $unc_table;

	/** @var \phpbb\config\config */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param unc_table					$unc_table
	 * @param \phpbb\config\config		$config		phpBB config
	 */
	public function __construct(unc_table $unc_table, config $config)
	{
		$this->unc_table	= $unc_table;
		$this->config		= $config;
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
			'core.user_setup'														=> 'load_language_on_setup',
			'core.user_add_modify_notifications_data'								=> 'user_add_modify_notifications_data',
			'core.ucp_notifications_submit_notification_is_set'						=> 'ucp_notifications_submit_notification_is_set',
			'core.ucp_notifications_output_notification_types_modify_template_vars'	=> 'ucp_notifications_output_notification_types_modify_template_vars',
			'core.notification_manager_add_notifications_for_users_modify_data'		=> 'notification_manager_add_notifications_for_users_modify_data',
		);
	}



	/**
	 * Load common language files during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 *
	 * @return void
	 * @access public
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'dark1/usernotificationcontrol',
			'lang_set' => 'lang_unc',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}



	/**
	* user_add_modify_notifications_data setup
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
	* ucp_notifications_submit_notification_is_set setup
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
	* ucp_notifications_output_notification_types_modify_template_vars setup
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
	* notification_manager_add_notifications_for_users_modify_data setup
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
