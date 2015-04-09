<?php
/**
  * user.inc
  *
  * Manage auth uers settings, and privs
  *
  * TCenter 3 - Ticket Management
  * Copyright (C) 2007-2008		Rochester Institute of Technology
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 2 of the License, or
  * (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

class User
{
	// Implement Singleton
	private static $adminInstance = array();
	private static $defaultAdmin;

	public $username = null;

	private $db = null;
	private $privs = array();

	private function __construct($username)
	{
		$this->db =& Database::getInstance();

		// We already have an authenticated username
		$this->username = $username;
		$this->loadPrivs();
	}

	/*
	 * Implement the singleton.  One user instance
	 * Allow for multiple single-instances
	 */
	public function &getInstance ($username = null, $default = false)
	{
		if ($default)
			self::$defaultAdmin = $username;

		if (is_null($username))
			if (!isset(self::$defaultAdmin))
				throw new TCenterException("No instantiated user");
			else
				$username = self::$defaultAdmin;

		if (!isset(self::$adminInstance[$username]))
		{
			$c = __CLASS__;
			self::$adminInstance[$username] = new $c($username);
		}

		return self::$adminInstance[$username];
	}

/*********************
  Class Functions
 *********************/
	/*
	 * loadPrivs
	 *
	 * Load this users privs from SQL
	 */
	private function loadPrivs ()
	{
		$sql = "SELECT priv FROM privs WHERE admin_username = '{$this->username}'";
		
		if ($this->db->query($sql)->num_rows() <= 0)
			return;

		while ($row = $this->db->fetch_assoc())
		{
			$this->privs[] = $row['priv'];
		}
	}

	public function updatePrivs ($privs)
	{
		// First remove all the old privs, then add the new ones
		$sql = "DELETE FROM privs WHERE admin_username = '".$this->username."'";
		$this->db->query($sql);

		foreach ($privs as $k=>$v)
		{
			$sql = "INSERT INTO privs () VALUES ('".$this->username."', '$k')";
			$this->db->query($sql);
		}
	}

/********************
  Public Functions
 ********************/
	public function hasPriv ($priv)
	{
		if (in_array("ADMIN_TOTAL_ADMIN", $this->privs))
			return true;

		return in_array($priv, $this->privs);
	}
}
