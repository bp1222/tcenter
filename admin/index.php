<?
/**
  * index.php
  *
  * Administration Index Page
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

// Include main header
$title = "Admin Home";
include_once 'setup.inc';
include_once "inc/header.inc";
?>

<div class="box" style="width: 275px">
	<div class="boxHeader noFold">
		TCenter <?=$tcenterVersion?>
	</div>

	<div class="boxContent">
		<span class="boxEntry"><center><a href="ticketList.php">View Tickets</a></center></span>
		<span class="boxEntry"><center><a href="recentClose.php">Recently Closed Tickets</a></span></center>
	</div>
</div>

<div class="box" style="width: 275px">
	<div class="boxHeader expanded">
		Administration
	</div>

	<div class="boxContent" style="display: none;">
		<span class="boxEntry"><a href="search.php">Search Open/Closed Tickets</a></span>
		<span class="boxEntry"><a href="report.php">TCenter Reports</a></span>
		<?c_boxLink(ROOT."request.php?accept=Accept", "Manual Ticket Creation", "ADMIN_MANUAL")?>
		<?c_boxLink(ROOT."admin/config.php", "Configuration Options", "ADMIN_CONFIG")?>
		<?c_boxLink(ROOT."admin/usermod.php", "User Management", "ADMIN_USERMAN")?>
	</div>
</div>
