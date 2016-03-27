<?
/**
  * recentClose.php
  *
  * Display recently closed tickets
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
$title = "Recently Closed Tickets";
include "setup.inc";
include "inc/header.inc";

$db =& Database::getInstance();

if ($_REQUEST['submit'])
{
	$back = date("Y-m-d", strtotime("-".$_REQUEST['back']));
	$sql = "SELECT * FROM ticket, owner WHERE close_date >= '$back' AND status = '7' AND ticket.o_id = owner.o_id";
	$db->query($sql);

	echo "<div class=\"box\" style=\"width: 400px\">";
	echo "<div class=\"boxHeader noFold\">Closed in the last ".$_REQUEST['back']."</div>";
	while ($row = $db->fetch_assoc($result))
	{?>
		<div class="boxContent">
			Ticket: <a href="ticket.php?t_id=<?=$row['t_id']?>">
						<?=$row['username']."-".$row['t_id']?>
					</a>
			&nbsp;&nbsp;&nbsp;&nbsp;Close Date : <?=$row['close_date']?>
			<?if ($row['rel'] != 0) {?>&nbsp;&nbsp;&nbsp;&nbsp;Relevance: <?=round($row['rel'], 2);}?>
			<hr/>
		</div>
	<?}
	echo "</div>";
	exit;
}
?>

<div class="box" style="width: 25%">
	<div class="boxHeader noFold">
		Recently Closed Tickets
	</div>

	<div class="boxContent">
		<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
			<center>
			Back Time:
			<select name="back">
				<option value="3 days">3 Days</option>
				<option value="1 week">1 Week</option>
				<option value="2 weeks">2 Weeks</option>
				<option value="1 month">1 Month</option>
			</select>
			<br/>
			<br/>
			<input type="submit" name="submit" value="Search"/>
			</center>
		</form>
	</div>
</div>
