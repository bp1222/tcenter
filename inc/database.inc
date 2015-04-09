<?php
/**
  * database.inc
  *
  * Database layer for the API
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

// WebServices Weird Thing
if (defined(RIT_WEBSPACE) && RIT_WEBSPACE)
	require_once("SiteConfigVars.php");

class Database
{
	// Implement Singleton
	private static $database_instance;

	private $connection;
	private $result;

	private function __construct()
	{
		if (defined(RIT_WEBSPACE) && RIT_WEBSPACE)
		{
			$mysql_hostname = getConfigValue("dbHost_a_tcent");
			$mysql_password = getConfigValue("dbPass_a_tcent");
			$mysql_username = "a-tcent";
			$mysql_database = "a_tcent";
		} else {
			$mysql_hostname = 'localhost';
			$mysql_password = '1209qwpo';
			$mysql_username = "tcenter";
			$mysql_database = "tcenter";
		}

		if (!($this->connection = @mysql_connect($mysql_hostname, $mysql_username, $mysql_password)))
		{
			throw new TCenterDBException("Could not connect to Database");
		}

		mysql_select_db($mysql_database);
	}

	public static function &getInstance()
	{
		if (!isset(self::$database_instance))
		{
			$c = __CLASS__;
			self::$database_instance = new $c;
		}

		return self::$database_instance;
	}

	public function query ($sql)
	{
		$this->result = mysql_query($sql, $this->connection);
		return self::$database_instance;
	}

	public function num_rows ()
	{
		return mysql_num_rows($this->result);
	}

	public function fetch_assoc ()
	{
		return mysql_fetch_assoc($this->result);
	}

	public function get_id ()
	{
		return mysql_insert_id();
	}
}
?>
