<?php
/**
  * tcenter.inc
  *
  * API - Core class that more-or-less manages the TCenter connection
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

include_once "tcenterDataObject.inc";
include_once "ticket.inc";
include_once "owner.inc";
include_once "machine.inc";
include_once "user.inc";
include_once "auth.inc";
include_once "exceptions.inc";

class TCenter
{ 
	public $auth = null;
	public $user = null;
	public $sysPrivs = array();

	public function __construct()
	{
		$this->auth = new LDAP();
		$this->loadSysPrivs();
	}

	public function hasAuth()
	{
		$this->auth->hasAuth();
		$this->user =& User::getInstance();
	}

	public function loadSysPrivs()
	{
		$xml = simplexml_load_file("inc/privs.xml");

		foreach ($xml->children() as $child)
		{
			$group = (string)$child->attributes()->name;

			foreach ($child->privs->children() as $priv)
			{
				$this->privs[$group][] = (string)$priv;
			}
		}
	}
}
