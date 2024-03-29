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
use phpbb\db\driver\driver_interface as db_driver;
use phpbb\cache\driver\driver_interface as cache_driver;

/**
 * User Notification Control Core Table Class.
 */
class unc_table
{
	/** @var string Table Name */
	const TABLE_NAME = 'dark1_unc';

	/** @var string Cache Key */
	const CACHE_KEY = '_dark1_unc_notify_matrix';

	/** @var db_driver */
	protected $db;

	/** @var cache_driver */
	protected $cache;

	/** @var string */
	protected $table_prefix;

	/**
	 * Constructor for User Notification Control Core Table Class.
	 *
	 * @param db_driver		$db				Database object
	 * @param cache_driver	$cache			Cache object
	 * @param string		$table_prefix	phpBB Table Prefix
	 */
	public function __construct(db_driver $db, cache_driver $cache, $table_prefix)
	{
		$this->db			= $db;
		$this->cache		= $cache;
		$this->table_prefix	= $table_prefix;
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
		// Get notify matrix
		$notify_matrix = $this->get_notify_method_type_matrix();

		return isset($notify_matrix[$notification_method][$notification_type]) ? $notify_matrix[$notification_method][$notification_type] : null;
	}



	/**
	 * Get Notification Methods & Types is Enabled {true} OR Disabled {false} in Matrix.
	 *
	 * @return array in form notify_matrix['notification_method']['notification_type'] = Enabled {true} OR Disabled {false}
	 * @access public
	 */
	public function get_notify_method_type_matrix()
	{
		// Get notify matrix data from the cache
		$notify_matrix = $this->cache->get(self::CACHE_KEY);

		if ($notify_matrix === false)
		{
			$sql = 'SELECT * FROM ' . $this->table_prefix . self::TABLE_NAME;
			$result = $this->db->sql_query($sql);
			$unc_rows = $this->db->sql_fetchrowset($result);
			$this->db->sql_freeresult($result);
			foreach ($unc_rows as $row)
			{
				$notify_matrix[$row['notification_method']][$row['notification_type']] = $row['notification_value'];
			}

			// Cache notify matrix data
			$this->cache->put(self::CACHE_KEY, $notify_matrix);
		}

		return $notify_matrix;
	}



	/**
	 * Set Notification Methods & Types is Disabled {true}in Matrix.
	 *
	 * @param array $notify_matrix in form notify_matrix['notification_method']['notification_type'] = Enabled {true} OR Disabled {false}
	 *
	 * @return void
	 * @access public
	 */
	public function set_notify_method_type_matrix($notify_matrix)
	{
		// Truncate the Table
		$sql = 'DELETE FROM ' . $this->table_prefix . self::TABLE_NAME;
		$this->db->sql_query($sql);

		// Multi-Insert the
		$sr = 0;
		$sql_ary = [];
		foreach ($notify_matrix as $notification_method => $ary_type)
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

		// Cache notify matrix data
		$this->cache->put(self::CACHE_KEY, $notify_matrix);
	}



	/**
	 * Update User Notification Table using No Notify Matrix.
	 *
	 * @param array $notify_matrix in form notify_matrix['notification_method']['notification_type'] = Enabled {true} OR Disabled {false}
	 *
	 * @return void
	 * @access public
	 */
	public function update_user_notifications_table($notify_matrix)
	{
		foreach ($notify_matrix as $notification_method => $notification_type_ary)
		{
			$sql_ary = [];

			foreach ($notification_type_ary as $notification_type => $notify)
			{
				$sql_ary[$notify][] = $this->db->sql_escape($notification_type);
			}

			foreach ($sql_ary as $notify => $item_type_ary)
			{
				if (!empty($item_type_ary))
				{
					$sql = 'UPDATE ' . USER_NOTIFICATIONS_TABLE .
							' SET notify = ' . (int) $notify .
							" WHERE method = '" . $this->db->sql_escape($notification_method) . "'" .
							' AND ' . $this->db->sql_in_set('item_type', $item_type_ary);
					$this->db->sql_query($sql);
				}
			}
		}
	}
}
