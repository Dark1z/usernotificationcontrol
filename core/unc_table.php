<?php
/**
 *
 * User Notification Control [UNC]. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2020, Dark❶, https://dark1.tech
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace dark1\usernotificationcontrol\core;

/**
 * @ignore
 */
use phpbb\db\driver\driver_interface;

/**
 * User Notification Control Core Table Class.
 */
class unc_table
{
	/** @var string Table Name */
	const TABLE_NAME = 'dark1_unc';

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var string */
	protected $table_prefix;

	/**
	 * Constructor for User Notification Control Core Table Class.
	 *
	 * @param \phpbb\db\driver\driver_interface		$db				Database object
	 * @param string								$table_prefix
	 */
	public function __construct(driver_interface $db, $table_prefix)
	{
		$this->db				= $db;
		$this->table_prefix		= $table_prefix;
	}



	/**
	 * Get Notification Method & Type is Enabled {true} OR Disabled {false}.
	 *
	 * @param string $notification_method
	 * @param string $notification_type
	 *
	 * @return bool|null Enabled {true} OR Disabled {false}
	 * @access public
	 */
	public function get_notify_method_type_value($notification_method, $notification_type)
	{
		$sql = 'SELECT notification_value'. PHP_EOL .
				'FROM ' . $this->table_prefix . self::TABLE_NAME . PHP_EOL .
				'WHERE notification_method = "' . $this->db->sql_escape($notification_method) . '" AND notification_type = "' . $this->db->sql_escape($notification_type) . '"';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$value = (isset($row['notification_value'])) ? $row['notification_value'] : null;
		$this->db->sql_freeresult($result);

		return $value;
	}



	/**
	 * Get Notification Methods & Types is Enabled {true} OR Disabled {false} in Matrix.
	 *
	 * @return array in form ary['notification_method']['notification_type'] = Enabled {true} OR Disabled {false}
	 * @access public
	 */
	public function get_notify_method_type_matrix()
	{
		$ary = [];

		$sql = 'SELECT * FROM ' . $this->table_prefix . self::TABLE_NAME;
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$ary[$row['notification_method']][$row['notification_type']] = $row['notification_value'];
		}
		$this->db->sql_freeresult($result);

		return $ary;
	}



	/**
	 * Set Notification Methods & Types is Disabled {true}in Matrix.
	 *
	 * @param array $ary in form ary['notification_method']['notification_type'] = Enabled {true} OR Disabled {false}
	 *
	 * @return void
	 * @access public
	 */
	public function set_notify_method_type_matrix($ary)
	{
		// Truncate the Table
		$sql = 'DELETE FROM ' . $this->table_prefix . self::TABLE_NAME;
		$this->db->sql_query($sql);

		$sr = 0;
		$sql_ary = [];

		foreach ($ary as $notification_method => $ary_type)
		{
			foreach ($ary_type as $notification_type => $notification_value)
			{
				if (isset($notification_value))
				{
					$sql_ary[] = [
						'notification_sr_no' 	=> $sr,
						'notification_method' 	=> $notification_method,
						'notification_type' 	=> $notification_type,
						'notification_value' 	=> $notification_value,
					];
					$sr++;
				}
			}
		}

		$this->db->sql_multi_insert($this->table_prefix . self::TABLE_NAME, $sql_ary);
	}



	/**
	 * Update User Notification Table using No Notify Matrix.
	 *
	 * @param array $notify_matrix in form notify_matrix['notification_method']['notification_type'] = Enabled {true} OR Disabled {false}
	 * @param bool $update to Update or not
	 *
	 * @return void
	 * @access public
	 */
	public function update_user_notifications_table($notify_matrix, $update)
	{
		if ($update)
		{
			foreach ($notify_matrix as $notification_method => $ary_type)
			{
				$sql0 = $sql1 = '';
				$flag0_1st = $flag1_1st = true;
				foreach ($ary_type as $notification_type => $value)
				{
					if ($value)
					{
						$sql1 .= (($flag1_1st) ? '' : 'OR ' ) . 'item_type = "' . $this->db->sql_escape($notification_type) . '"' . PHP_EOL;
						$flag1_1st = false;
					}
					else
					{
						$sql0 .= (($flag0_1st) ? '' : 'OR ' ) . 'item_type = "' . $this->db->sql_escape($notification_type) . '"' . PHP_EOL;
						$flag0_1st = false;
					}
				}

				for ($i=0; $i < 2; $i++)
				{
					$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE . PHP_EOL .
							'SET notify = ' . (int) $i . PHP_EOL .
							'WHERE method = "' . $this->db->sql_escape($notification_method) . '"' . PHP_EOL .
							'AND (' . PHP_EOL . (string) (($i === 1) ? $sql1 : $sql0) . ')';
					$this->db->sql_query($sql);
				}
			}
		}
	}

}
