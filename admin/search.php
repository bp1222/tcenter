<?
/**
  * search.php
  *
  * Search form for old tickets.  Search by username or ticket number
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

// Needed for IE to display titles on the ticket page.
// Including "inc/header.inc" later, but not using
// it's includes
require "basepath.inc";
require "inc/connection.inc";
include_once "inc/lookup.inc";
include_once "inc/ticket.inc";
include_once "inc/style.inc";

// Needs to be before our main include, since we may be passing a Header
// IE is so broken :(
if ($_REQUEST['submit'] || $_REQUEST['tnumber'])
{
	$ticket = trim($_REQUEST['tnumber']);

	// If it's JUST a username ...
	if (preg_match("/^[A-Za-z]{3}[0-9]{4}$/", $ticket) ||
		preg_match("/^[A-Za-z]{6,7}$/", $ticket))
	{
		$username = $ticket;
		$sql = "SELECT ticket.t_id, ticket.last_time, ticket.status, ticket.summary, ticket.u_id, user.u_id, user.username FROM ticket, user WHERE user.username = '$username' AND ticket.u_id = user.u_id ORDER BY t_id DESC";
		$result = mysql_query($sql);

		if (mysql_num_rows($result) == 0)
		{
			header("Location: ticket.php?t_id=0");
		}
		else if (mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_assoc($result);
			header("Location: ticket.php?t_id=".$row['t_id']);
		}
		else
		{
			$multiple = true;	
		}
	}
	else if (preg_match("/[0-9]{4}$/", $ticket, $matches))
	{
		header("Location: ticket.php?t_id=".$matches[0]);
	}
	else
	{
		$errval['input'] = true;
	}
}

// Need to include everything we need up top, but need to do the header output
// here, since there will be headers passed above.
$noinc = true;
$title = "Search Tickets";
include "inc/header.inc";
if ($multiple)
{
	echo "<div class=\"box\" style=\"width: 45%\">";
		topCorner();
		echo "<div class=\"boxHeader\">Search Results</div>";
		while ($row = mysql_fetch_assoc($result))
		{?>
			<div class="boxContent">
				Ticket: <a href="ticket.php?t_id=<?=$row['t_id']?>">
							<?=$row['username']."-".$row['t_id']?>
						</a>
				&nbsp;&nbsp;&nbsp;&nbsp;Last Date : <?=makeDate($row['last_time'])?>
				&nbsp;&nbsp;&nbsp;&nbsp;Status : <?=array_search($row['status'], $l_status)?>
				<hr/>
			</div>
		<?}
		bottomCorner();
	echo "</div>";
	return;
}
?>

<form method="post" action="<?=$PHP_SELF?>">
	<div class="box" style="width: 35%">
		<?topCorner();?>
		<div class="boxHeader">
			Search For Ticket
		</div>

		<div class="boxContent">
			<fieldset class="noborder">
				<label class="fLblRequest">Ticket Number<br/>or Username</label>
					<input type="text" name="tnumber" 
						value="<?=$errval['input'] ? "Syntax Error" : ""?>" 
						size="15" maxlength="12" onfocus="clearField(this)"/><br/>
			</fieldset>
			<center>
			<input type="submit" name="submit" value="Search" tabindex="9"/>
			</center>
		</div>
		<?bottomCorner();?>
	</div>
</form>
