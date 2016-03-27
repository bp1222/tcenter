<?php
/**
  * tcenterDataObject.inc
  *
  * Abstract class that defines a "DataObject" or one of the 3 parts of a 'ticket'
  *
  * TCenter 3 - Ticket Management
  * Copyright (C) 2007-2008		Rochester Institute of Technology
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
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

abstract class TCenterDataObject
{
	protected $db = null;
	protected $objInfo = array();
	private $o_id;

	public function __construct ($o_id = null)
	{
		$this->db =& Database::getInstance();

		if (is_null($o_id))
		{
			// This is a new ticket
			if (strtolower(get_class($this)) == "ticket")
			{
				$this->owner = new Owner();
				$this->machine = new Machine();
			}
			$this->o_id = "NULL";
			return;
		}

		$this->o_id = $o_id;
		$this->loadObject($o_id);
	}

	abstract protected function loadObject ($o_id);

/********************
  Public Functions
 ********************/
	/*
	 * getAttrib
	 *
	 * return some user attribute
	 */
	public function getAttrib ($attrib)
	{
		if (!is_array($attrib))
		{
			if (!isset($this->objInfo[$attrib]))
				throw new TCenterException("Invalid ".get_class($this)."Attribute - $attrib Does Not Exist");

			return $this->objInfo[$attrib];
		}
		else
		{
			$ret = array();

			foreach ($attrib as $a)
			{
				if (!isset($this->objInfo[$a]))
					throw new TCenterException("Invalid ".get_class($this)." Attribute Array - $a Does Not Exist");

				$ret[] = $this->objInfo[$a];
			}

			return $ret;
		}
	}

	public function updateAttrib ($attrib, $val = null)
	{
		if (!is_array($attrib))
		{
			$this->objInfo[$attrib] = $val;
		}
		else
		{
			foreach ($attrib as $k=>$v)
			{
				$this->objInfo[$k] = $v;
			}
		}
	}

	public function create ()
	{
		$class = strtolower(get_class($this));
		$sql = "INSERT INTO $class (";

		$keys = "";
		$values = "";

		foreach ($this->objInfo as $k=>$v)
		{
			$v = mysql_real_escape_string($v);
			$keys .= "$k, ";
			if ($k == "open_date")
				$values .= "NOW(), ";
			else
				$values .= "'$v', ";
		}

		$keys = substr($keys, 0, strlen($keys)-2);
		$values = substr($values, 0, strlen($values)-2);

		$sql .= "$keys) VALUES ($values)";

		$this->db->query($sql);
		return $this->db->get_id();
	}

	public function save ()
	{
		$class = strtolower(get_class($this));
		$sql = "UPDATE $class SET ";

		foreach ($this->objInfo as $k=>$v)
		{
			$v = mysql_real_escape_string($v);
			if ($k == "close_date" && $v == "NOW")
				$sql .= "$k = NOW(), ";
			else
				$sql .= "$k = '$v', ";
		}
		$sql = substr($sql, 0, strlen($sql)-2);
		$sql .= " WHERE ".substr($class, 0, 1)."_id = '".$this->o_id."'";

		$this->db->query($sql);
	}

	public function toJSON ()
	{
		$class = strtolower(get_class($this));
		$json = '"'.$class.'":[{';
		
		foreach ($this->objInfo as $k=>$v)
		{
			// JSON in JSON
			if ($k == "todo")
				$json .= "\"$k\":$v,";
			else 
				$json .= "\"$k\":\"".$v."\",";
		}
		$json = substr($json, 0, strlen($json)-1)."}],";

		return $json;
	}
}
