<?
/**
  * print.php
  *
  * Display the printer-friendly version of the ticket
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
  * @author Jeremy Wozniak	(jpwrcc@rit.edu)
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$title="Printable Ticket";
include "setup.inc";
include "inc/header.inc";

// Parse out ticket number
// Scrub out SQL injection -- David
$ticket = new Ticket($_REQUEST["t_id"]);
?>

<h1 align="center">Resnet Service Receipt</h1><br/>
<h3 align="center">Your new password: <?=$ticket->owner->getAttrib("username")."-".$ticket->getAttrib("t_id")?></h3><br/>
<table align="left">
	<tr>
		<td><b>Ticket open date:</b></td>
		<td><?=$ticket->getAttrib("open_date")?></td>
	</tr>
	<tr>
		<td><b>Ticket close date:</b></td>
		<td><?=$ticket->getAttrib("close_date")?></td>
	</tr>
	<tr>
		<td><b>Problem:</b></td>
		<td><?=$ticket->getAttrib("summary")?></td>
	</tr>
</table>
<table align="center" width="65%">
	<tr valign="top">
		<td><b>Work Summary:</b></td>
		<td><?=$ticket->getAttrib("updates")?></td>
	</tr>
</table>
