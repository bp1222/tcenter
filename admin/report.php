<?
/**
  * report.php
  *
  * reporting functionality for statistical information
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

$title = "Reports";
include "setup.inc";
include "inc/header.inc";

function numTicketsByVar ($var = null, $date = null)
{
    $db = Database::getInstance();
	$sql = "SELECT count(t_id) FROM ticket ".($var ? " WHERE $var >= '$date'" : "");
	$result = $db->query($sql)->fetch_assoc();
	return $result['count(t_id)'];
}

function numTicketsBoundByVar ($var = null, $start_date, $end_date)
{
    $db = Database::getInstance();
	$sql = "SELECT count(t_id) FROM ticket WHERE $var BETWEEN '$start_date' AND '$end_date'";
	$result = $db->query($sql)->fetch_assoc();
	return $result['count(t_id)'];
}

function getReopenedCount ($start_date, $end_date = null)
{
    $db = Database::getInstance();
	if (!is_null($end_date))
		$sql = "SELECT reopen_count FROM ticket WHERE close_date BETWEEN '$start_date' AND '$end_date'";
	else
		$sql = "SELECT reopen_count FROM ticket WHERE close_date >= $start_date";

	$result = $db->query($sql);
	$reopen = 0;
	while ($row = $db->fetch_assoc($result))
	{
		$reopen += $row['reopen_count'];
	}
	return $reopen;
}

// Generate report statistics
$today = date("Y-m-d");
$last_week =  date("Y-m-d", strtotime("-1 week"));
$this_month =  date("Y-m-1");

// Weekly Stats
$weekly_opened = numTicketsByVar("open_date", $last_week);
$weekly_closed = numTicketsByVar("close_date", $last_week);
$weekly_reopened = getReopenedCount($last_week);

// Monthly Stats
$monthly_opened = numTicketsByVar("open_date", $this_month);
$monthly_closed = numTicketsByVar("close_date", $this_month);
$monthly_reopened = getReopenedCount($this_month);

// All-Time Stats
$total_tickets = numTicketsByVar();

if ($_REQUEST['start_date'])
{
	$start = explode("-", $_REQUEST['start_date']);
	$end = explode("-", $_REQUEST['end_date']);

	$start_date = date ("Y-m-d", mktime(0, 0, 0, $start[0], $start[1], $start[2]));
	$end_date = date ("Y-m-d", mktime(0, 0, 0, $end[0], $end[1], $end[2]));

	$open_report = numTicketsBoundByVar ("open_date", $start_date, $end_date);
	$close_report = numTicketsBoundByVar ("close_date", $start_date, $end_date);
	$reopen_report = getReopenedCount ($start_date, $end_date);
?>
<div class="box reportResult"> 
		<div class="boxHeader noFold">
			Report Results
		</div>

		<div class="boxContent">
			<table width="100%">
				<tr>
					<td class="fLblTicket">Tickets opened in this period:</td>
				    <td><?=$open_report?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Tickets closed in this period:</td>
				    <td><?=$close_report?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Tickets re-opened in this period:</td>
				    <td><?=$reopen_report?></td>
				</tr>
				<tr>
			</table>
		</div>
</div>
<?}?>

<div class="box reportDate">
	<div class="boxHeader noFold">
		Manual Date Bounds
	</div>

	<div class="boxContent">
		<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
		<table>
			<tr>
				<td>Start Date:</td>
				<td><input type="text" size="15" value="<?=$_REQUEST['start_date'] ? $_REQUEST['start_date'] : date("m-d-y")?>" name="start_date" tabindex="1"/></td>
			</tr>
			<tr>
				<td>End Date:</td>
				<td><input type="text" size="15" value="<?=$_REQUEST['end_date'] ? $_REQUEST['end_date'] : date("m-d-y")?>" name="end_date" tabindex="2"/></td>
			</tr>
			<tr>
				<td/>
				<td><input type="submit" value="Build Report" name="submit"/></td>
			</tr>
		</table>
		</form>
	</div>
</div>

<div style="float:left; width: 300px; padding-left: 40px; margin-right:20px;">
	<div class="box">
		<div class="boxHeader noFold">
			TCenter Weekly Report
		</div>
	
		<div class="boxContent">
			<table width="100%">
				<tr>
					<td class="fLblTicket">Tickets opened this past week:</td>
				    <td><?=$weekly_opened?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Tickets closed this past week:</td>
				    <td><?=$weekly_closed?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Tickets re-opened this past week:</td>
				    <td><?=$weekly_reopened?></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div style="float:left; width: 300px; margin-right:20px;">
	<div class="box">
		<div class="boxHeader noFold">
			TCenter Monthly Report
		</div>
	
		<div class="boxContent">
			<table width="100%">
				<tr>
					<td class="fLblTicket">Computers admitted this month:</td>
					<td class="flblTicket"><?=$monthly_opened?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Computers closed this month:</td>
					<td><?=$monthly_closed?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Tickets re-opened this month:</td>
				    <td><?=$monthly_reopened?></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div style="float:left; width: 300px; margin-right:20px;">
	<div class="box">
		<div class="boxHeader noFold">
			TCenter All-Time Report
		</div>
	
		<div class="boxContent">
			# of computers accepted : <?=$total_tickets?><br/>
		</div>
	</div>
</div>
