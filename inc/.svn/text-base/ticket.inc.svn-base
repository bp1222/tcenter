<?php
/**
  * ticket.inc
  *
  * Class to manage a given ticket.
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

class Ticket extends TCenterDataObject
{
	public $owner;
	public $machine;

	protected function loadObject($t_id)
	{
		$sql = "SELECT * FROM ticket WHERE t_id = '$t_id'";

		if ($this->db->query($sql)->num_rows() != 1)
			throw new TCenterException("Ticket Information is Bogus");

		$row = $this->db->fetch_assoc();
		
		foreach ($row as $k=>$v)
		{
			$this->objInfo[$k] = $v;
		}

		$this->owner = new Owner($this->objInfo['o_id']);
		$this->machine = new Machine($this->objInfo['m_id']);
	}

/**************************
* Public STATIC Functions *
**************************/
	public static function getTicketsByStatus ($status)
	{
		$db = Database::getInstance();
		$sql = "SELECT t.t_id, t.summary, t.open_date, m.description, o.username FROM ticket as t, machine as m, owner as o WHERE t.status = '".$status."' AND t.o_id = o.o_id AND t.m_id = m.m_id";

		if ($db->query($sql)->num_rows() == 0)
			return null;

		$ret = array();

		while ($row = $db->fetch_assoc())
		{
			array_push($ret, $row);
		}

		return $ret;
	}
}
