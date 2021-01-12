<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020-2021, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\acp;

/**
 * User Notification Control [UNC] ACP module.
 */
class main_module
{
	public $page_title;
	public $tpl_name;
	public $u_action;

	/**
	 * Main ACP module
	 *
	 * @param int    $id   The module ID
	 * @param string $mode The module mode (for example: manage or settings)
	 * @throws \Exception
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		// Normalize the Mode to Lowercase
		$mode = strtolower($mode);

		// check for valid Mode
		if (class_exists('dark1\usernotificationcontrol\controller\acp_' . $mode))
		{
			// Get ACP controller for Mode
			$acp_controller = $phpbb_container->get('dark1.usernotificationcontrol.controller.acp.' . $mode);

			// Load the display handle in our ACP controller
			$acp_controller->set_data($id, $mode, $this->u_action);

			// Get data from our ACP controller
			$acp_get_data = $acp_controller->get_data();

			// Load a template from adm/style for our ACP page
			$this->tpl_name = $acp_get_data['tpl_name'];

			// Set the page title for our ACP page
			$this->page_title = $acp_get_data['page_title'];

			// Load the setup in our ACP controller
			$acp_controller->setup();

			// Load the handle in our ACP controller
			$acp_controller->handle();
		}
		else
		{
			trigger_error('FORM_INVALID', E_USER_WARNING);
		}
	}
}
