<?php
/**
  * ticket.inc
  *
  * Functions for getting data from the database about tickets
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

function checkPhoneNumber ($number)
{
    if (empty($number))
        return true;

	$phone_pattern = "/^[0-9]{7,10}$/";
	
	// Replace the dashes
	$number = str_replace ('-', '', $number);
	$number = str_replace ('(', '', $number);
	$number = str_replace (')', '', $number);

	// See if the number is 11 characters, and the first is a 1.

	if ((strlen($number) == 11) && (substr($number, 0, 1) == "1"))
		$number = substr($number, 1, strlen($number));

	if ((strlen($number) != 7 && strlen($number) != 10)
 	  || preg_match($phone_pattern, $number) == 0)
		return false;

	return $number;
}

function todoComplete($ticket)
{
	$tasks = $ticket['todo'];

	if (is_array($tasks)) 
	{
		foreach ($tasks as $t=>$v)
		{
			if (!$v)
				return false;
		}
	}
	return true;
}

function addDashes ($number)
{
	// This will always be true, but it works with phonenumbers in the system with -'s
	$number = checkPhoneNumber($number);

	if (strlen($number) == 7)
		$retval = substr($number, 0, 3)."-".substr($number, 3, strlen($number));

	if (strlen($number) == 10)
		$retval = substr($number, 0, 3)."-".substr($number, 3, 3)."-".substr($number, 6, strlen($number));

	return $retval;
}

function makeDate ($timestamp)
{
	if (empty($timestamp))
		return "No Last Date";
	return date("Y-m-d", $timestamp);
}
