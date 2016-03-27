<?
/**
  * print.php
  *
  * Displays a printer - friendly version of a ticket
  *
  * @author Jeremy Wozniak	(jpwrcc@rit.edu)
  * @author David Walker	(azrail@csh.rit.edu)
  */

$nonav=true;
$title="Printable Ticket";
include "inc/header.inc";

// Parse out ticket number
// Scrub out SQL injection -- David
$t_id = $_REQUEST["t_id"];

$ticket_number = longTicketNumber($t_id);

// Get information we need for the printed receipt.
$sql = "SELECT open_date, close_date, summary, updates FROM ticket WHERE t_id = $t_id";
$result = mysql_query($sql);

$row = mysql_fetch_assoc($result);

// Set variables from the record
$open_date = $row["open_date"];
$close_date = $row["close_date"];
$summary = $row["summary"];
$updates = $row["updates"];
?>

<h1 align="center">Resnet Service Receipt</h1><br/>
<h3 align="center">Your new password: <?=$ticket_number?></h3><br/>
<table align="left">
	<tr>
		<td><b>Ticket open date:</b></td>
		<td><?=$open_date?></td>
	</tr>
	<tr>
		<td><b>Ticket close date:</b></td>
		<td><?=$close_date?></td>
	</tr>
	<tr>
		<td><b>Problem:</b></td>
		<td><?=$summary?></td>
	</tr>
</table>
<table align="center" width="65%">
	<tr valign="top">
		<td><b>Work Summary:</b></td>
		<td><?=$updates?></td>
	</tr>
</table>
