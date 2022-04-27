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

/**
 * User Notification Control [UNC] ACP controller Base.
 */
class acp_base
{
	/** @var language */
	protected $language;

	/** @var log */
	protected $log;

	/** @var request */
	protected $request;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var string The module ID */
	protected $id;

	/** @var string The module mode */
	protected $mode;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor.
	 *
	 * @param language	$language	Language object
	 * @param log		$log		Log object
	 * @param request	$request	Request object
	 * @param template	$template	Template object
	 * @param user		$user		User object
	 */
	public function __construct(language $language, log $log, request $request, template $template, user $user)
	{
		$this->language		= $language;
		$this->log			= $log;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
	}

	/**
	 * Set Data form.
	 *
	 * @param int		$id			The module ID
	 * @param string	$mode		The module mode
	 * @param string	$u_action	Custom form action
	 *
	 * @return void
	 * @access public
	 */
	public function set_data($id, $mode, $u_action)
	{
		$this->id = $id;
		$this->mode = $mode;
		$this->u_action = $u_action;
	}

	/**
	 * Get Data form.
	 *
	 * @return array Having keys 'tpl_name' & 'page_title'
	 * @access public
	 */
	public function get_data()
	{
		return [
			'tpl_name' => 'dark1_unc_acp_' . $this->mode,
			'page_title' => $this->language->lang('ACP_UNC_TITLE') . ' - ' . $this->language->lang('ACP_UNC_' . strtoupper($this->mode)),
		];
	}

	/**
	 * Set Display form.
	 *
	 * @return void
	 * @access public
	 */
	public function setup()
	{
		$ext_name_unc = 'User Notification Control [UNC]';
		$ext_by_dark1 = 'Dark❶ [dark1]';

		// Add our common language file
		$this->language->add_lang('lang_unc', 'dark1/usernotificationcontrol');
		$this->language->add_lang('lang_acp_unc', 'dark1/usernotificationcontrol');

		// Create a form key for preventing CSRF attacks
		add_form_key('dark1_unc_acp_' . $this->mode);

		// Set u_action in the template
		$this->template->assign_vars([
			'U_ACTION'		=> $this->u_action,
			'UNC_EXT_MODE'	=> $this->language->lang('ACP_UNC_' . strtoupper($this->mode)),
			'UNC_EXT_NAME'	=> $ext_name_unc,
			'UNC_EXT_DEV'	=> $ext_by_dark1,
		]);
	}

	/**
	 * Check Form On Submit .
	 *
	 * @return void
	 * @access protected
	 */
	protected function check_form_on_submit()
	{
		// Test if the submitted form is valid
		if (!check_form_key('dark1_unc_acp_' . $this->mode))
		{
			trigger_error('FORM_INVALID', E_USER_WARNING);
		}
	}

	/**
	 * Success Form On Submit.
	 * Used to Log & Trigger Success Err0r.
	 *
	 * @param string	$lang_key	Lang Key
	 * @param string	$lang_str	Lang String
	 *
	 * @return void
	 * @access protected
	 */
	protected function success_form_on_submit($lang_key = 'ACP_UNC_LOG_SET_SAV', $lang_str = null)
	{
		$lang_str = !isset($lang_str) ? $this->language->lang('ACP_UNC_' . strtoupper($this->mode)) : $lang_str;

		// Add option settings change action to the admin log
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $lang_key, time(), [$lang_str]);

		// Option settings have been updated and logged
		// Confirm this to the user and provide link back to previous page
		trigger_error($this->language->lang($lang_key, $lang_str) . adm_back_link($this->u_action), E_USER_NOTICE);
	}
}
