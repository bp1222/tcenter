<?
/**
  * ticketList.php
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$title = "Ticket Listing";
include "inc/header.inc";

function createTicketBox ($status, $open)
{
?>
<div class="box">
	<?topCorner(); //Pretty Rounded Corner?>
	<?if ($open){?>
		<div class="boxHeader expanded" onclick="expand(this)">
	<?} else {?>
		<div class="boxHeader collapsed" onclick="expand(this)">
	<?}?>
		<?=$status?>
	</div>

	<?if ($open){?>
		<div class="boxContent" style="display: block;">
	<?} else {?>
		<div class="boxContent" style="display: none;">
	<?}?>
<?
	$tickets = getTicketsByStatus($status);
	foreach ($tickets as $ticket) 
	{
		echo "<div class='boxEntry'>";
		echo "<span class='ticketNumber'>";
		echo "<a href='ticket.php?t_id=".$ticket['t_id']."'>".longTicketNumber($ticket['t_id'])."</a>";
		echo "</span> ";
		echo "<span class='ticketDescription'>( ".$ticket['description']." )</span><br/>";
		echo "<span class='ticketSummary'>Summary: ". $ticket['summary']."</span>";
		echo "</div>";
	}
?>
	</div>
	<?bottomCorner(); // Pretty Rounded Corners?>
</div>
<?
}
?>
<div class="column1">
<?
	createTicketBox("In Progress", true);
?>
</div>
<div class="column2">
<?
	createTicketBox("Queued", true);
	createTicketBox("Unaccepted", true);
?>
</div>
<div class="column3">
<?
	createTicketBox("Pending Customer", false);	
	createTicketBox("Awaiting Pickup", false);
	createTicketBox("Admin Hold", false);
?>
</div>
<?
return;
?>
