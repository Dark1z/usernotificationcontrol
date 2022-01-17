<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-forever, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\core;

/**
 * @ignore
 */
use phpbb\user;
use phpbb\language\language;
use phpbb\extension\manager as ext_manager;
use phpbb\event\dispatcher_interface as dispatcher;
use phpbb\notification\type\type_interface;
use phpbb\notification\method\method_interface;
use phpbb\finder;

/**
 * User Notification Control Core Helper Class.
 */
class unc_helper
{
	/** @var user */
	protected $user;

	/** @var language */
	protected $language;

	/** @var ext_manager */
	protected $ext_manager;

	/** @var dispatcher */
	protected $dispatcher;

	/** @var array Notification Types */
	protected $notification_types;

	/** @var array Notification Methods */
	protected $notification_methods;

	/** @var string phpBB php ext */
	protected $php_ext;

	/**
	 * Constructor for User Notification Control Core Table Class.
	 *
	 * @param language		$language				Language object
	 * @param user			$user					User object
	 * @param ext_manager	$ext_manager			phpBB Extension Manager
	 * @param dispatcher	$dispatcher				Dispatcher object
	 * @param array			$notification_types		phpBB Notification Types
	 * @param array			$notification_methods	phpBB Notification Methods
	 * @param string		$php_ext				phpBB php ext
	 */
	public function __construct(user $user, language $language, ext_manager $ext_manager, dispatcher $dispatcher, $notification_types, $notification_methods, $php_ext)
	{
		$this->user					= $user;
		$this->language				= $language;
		$this->ext_manager			= $ext_manager;
		$this->dispatcher			= $dispatcher;
		$this->notification_types	= $notification_types;
		$this->notification_methods	= $notification_methods;
		$this->php_ext				= $php_ext;
	}



	/**
	 * Get all of the subscription methods
	 *
	 * @return array Array of methods
	 * @access public
	 */
	public function get_subscription_methods()
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
	 * @access public
	 */
	public function get_subscription_types()
	{
		$subscription_types = [];
		$prefix = 'NOTIFICATION_TYPE_';

		/** @var type_interface $type */
		foreach ($this->notification_types as $type_name => $type)
		{
			$lang = str_replace('.', '_', strtoupper($type->get_type()));
			if (substr($lang, 0, strlen($prefix)) == $prefix)
			{
				$lang = substr($lang, strlen($prefix));
			}

			$type_ary = [
				'id'	=> $type->get_type(),
				'lang'	=> $prefix . $lang,
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



	/**
	 * Get all of the subscription methods
	 *
	 * @return array Array of lang(s)
	 * @access public
	 */
	public function get_lang_unc_custom()
	{
		$ext_name = 'dark1/usernotificationcontrol';
		$ext_lang = 'lang_unc_custom';

		/** @var finder $finder */
		$finder = $this->ext_manager->get_finder();
		$lang_file_path = $finder
			->set_extensions([$ext_name])
			->prefix($ext_lang)
			->suffix('.'.$this->php_ext)
			->directory("language/".$this->user->lang_name)
			->get_files();

		// Check if exists
		$lang_ary = (current($lang_file_path)) ? [$ext_name => $ext_lang] : [];

		return $lang_ary;
	}



	/**
	 * Add Required Lang File(s).
	 *
	 * @return void
	 * @access public
	 */
	public function add_lang()
	{
		$add_ext_lang = $this->get_lang_unc_custom();

		/**
		 * Event to modify the similar topics template block
		 *
		 * @event dark1.usernotificationcontrol.add_ext_lang
		 *
		 * @var array add_ext_lang		Array with [(string) '<vendor>/<extension>' => (string|array) '<lang>' | ['<lang1>', '<lang2>']]
		 *
		 * @since 1.0.2
		 * @changed 1.0.4	The add_ext_lang now accepts array of lang(s)
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
				foreach ($langs as $lang)
				{
					$this->language->add_lang((string) $lang, (string) $ext);
				}
			}
		}
	}



	/**
	 * Get all of the subscription methods
	 *
	 * @param string $lang_key
	 * @param bool $warn
	 * @param string $sub
	 *
	 * @return string Array of methods
	 * @access public
	 */
	public function get_lang_key($lang_key, $warn = true, $sub = '')
	{
		$warn = $warn ? ' ⚠️ ' . $this->language->lang('ACP_UNC_NO_LANG_KEY') : '';
		return $this->language->is_set($lang_key) ? $this->language->lang($lang_key) : $warn . (!empty($sub) ? ' : ' . $sub : '');
	}

}
