<?
/**
  * search.php
  *
  * Search form for old tickets.  Search by username or ticket number
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

$title = "Search Tickets";
include "setup.inc";
include "inc/header.inc";

$db = Database::getInstance();

if ($_REQUEST['submit'])
{
	$search = trim($_REQUEST['search']);

	// Searching based on content
	if ($_REQUEST['content'])
	{
		$search = trim($_REQUEST['content']);
		$sql = "SELECT ticket.*, owner.o_id, owner.username, MATCH(summary,problem,updates) AGAINST ('$search') AS rel FROM ticket, owner WHERE MATCH(summary,problem,updates) AGAINST ('$search') HAVING rel > 0.02 AND ticket.o_id = owner.o_id ORDER BY rel DESC";

		if ($db->query($sql)->num_rows() == 0)
		{
			header("Location: ticket.php?t_id=-1");
		}
		else if ($db->num_rows() == 1)
		{
			$row = $db->fetch_assoc();
			header("Location: ticket.php?t_id=".$row['t_id']);
		}
		else
		{
			$multiple = true;
		}
	}
	// If it's JUST a username ...
	else if (preg_match("/^[A-Za-z]{3}[0-9]{4,4}$/", $search) ||
		preg_match("/^[A-Za-z]{6,7}$/", $search))
	{
		$username = $search;
		$sql = "SELECT ticket.t_id, ticket.status, ticket.summary, ticket.o_id, owner.o_id, owner.username FROM ticket, owner WHERE owner.username = '$username' AND ticket.o_id = owner.o_id ORDER BY t_id DESC";

		if ($db->query($sql)->num_rows() == 0)
		{
			header("Location: ticket.php?t_id=0");
		}
		else if ($db->num_rows() == 1)
		{
			$row = $db->fetch_assoc();
			header("Location: ticket.php?t_id=".$row['t_id']);
		}
		else
		{
			$multiple = true;
		}
	}
	// It's a ticket number
	else if (preg_match("/[0-9]{4}$/", $search, $matches))
	{
		header("Location: ticket.php?t_id=".$matches[0]);
	}
	$errval['search'] = true;
}

if ($multiple)
{
	echo "<div class=\"box\" style=\"width: 500px\">";
		echo "<div class=\"boxHeader noFold\">Search Results</div>";
		while ($row = $db->fetch_assoc())
		{?>
			<div class="boxContent">
				Ticket: <a href="ticket.php?t_id=<?=$row['t_id']?>">
							<?=$row['username']."-".$row['t_id']?>
						</a>
				&nbsp;&nbsp;&nbsp;&nbsp;Last Date : <?=makeDate($row['last_time'])?>
				&nbsp;&nbsp;&nbsp;&nbsp;Status : <?=$l_status[$row['status']]?>
				<?if ($row['rel'] != 0) {?>&nbsp;&nbsp;&nbsp;&nbsp;Relevance: <?=round($row['rel'], 2);}?>
				<hr/>
			</div>
		<?}
	echo "</div>";
	return;
}
?>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="box" style="width: 400px">
		<div class="boxHeader noFold">
			Search For Ticket
		</div>

		<div class="boxContent">
			<label class="fLblRequest">Ticket Number,<br/>Username</label>
				<input type="text" name="search" 
					value="<?=$errval['search'] ? "Syntax Error" : ""?>" 
					size="15" maxlength="12" onfocus="clearField(this)"/><br/>
			<center>
			<input type="submit" name="submit" value="Search" tabindex="9"/>
			</center>
		</div>
	</div>
</form>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="box" style="width: 400px">
		<div class="boxHeader noFold">
			Search On Content
		</div>

		<div class="boxContent">
			<label class="fLblRequest">Content</label>
				<input type="text" name="content"
					value="<?=$errval['content'] ? "Syntax Error" : ""?>"
					size="15" maxlength="12" onfocus="clearField(this)"/><br/>
			<center>
			<input type="submit" name="submit" value="Search" tabindex="9"/>
			</center>
		</div>
	</div>
</form>
