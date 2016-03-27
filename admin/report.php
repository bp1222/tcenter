<?
/**
  * report.php
  *
  * Reporting functionality for statistical information
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

include "inc/header.inc";

// Generate report statistics
$today = date("Y-m-d");
$last_week =  date("Y-m-d", strtotime("-1 week"));
$this_month =  date("Y-m-1");

function numTicketsByVar ($var = null, $date = null)
{
	$sql = "SELECT count(t_id) FROM ticket ".($var ? " WHERE $var >= '$date'" : "");
	$result = mysql_fetch_assoc(mysql_query($sql));
	return $result['count(t_id)'];
}

function numTicketsBoundByVar ($var = null, $start_date, $end_date)
{
	$sql = "SELECT count(t_id) FROM ticket WHERE $var BETWEEN '$start_date' AND '$end_date'";
	$result = mysql_fetch_assoc(mysql_query($sql));
	return $result['count(t_id)'];
}

function getReopenedCount ($start_date, $end_date = null)
{
	if (!is_null($end_date))
		$sql = "SELECT reopen_count FROM ticket WHERE close_date BETWEEN '$start_date' AND '$end_date'";
	else
		$sql = "SELECT reopen_count FROM ticket WHERE close_date >= $start_date";

	$result = mysql_query($sql);
	$reopen = 0;
	while ($row = mysql_fetch_assoc($result))
	{
		$reopen += $row['reopen_count'];
	}
	return $reopen;
}

function getTimeInStateOnDate ($start_date, $end_date = null)
{
	if (is_null($end_date))
		$sql = "SELECT time_working, time_waiting FROM ticket WHERE open_date >= '$start_date'";
	else
		$sql = "SELECT time_working, time_waiting FROM ticket WHERE open_date BETWEEN '$start_date' AND '$end_date'";

	var_dump($sql);
	$result = mysql_query($sql);
	$total_working = 0;
	$total_waiting = 0;
	$num_row = mysql_num_rows($result);
	
	if ($num_row > 0)
	{
		while ($row = mysql_fetch_assoc($result))
		{
			$total_working += $row['time_working'];
			$total_waiting += $row['time_waiting'];
		}
		$return['working'] = ($total_working / $num_row) / (60*60*24);
		$return['waiting'] = ($total_waiting / $num_row) / (60*60*24);
	
		// Returns time in DAYS!
		return $return;
	}
	return null;
}

// Weekly Stats
$weekly_opened = numTicketsByVar("open_date", $last_week);
$weekly_closed = numTicketsByVar("close_date", $last_week);
$weekly_reopened = getReopenedCount($last_week);
$times = getTimeInStateOnDate($last_week);
$weekly_working = $times['working'];
$weekly_waiting = $times['waiting'];

// Monthly Stats
$monthly_opened = numTicketsByVar("open_date", $this_month);
$monthly_closed = numTicketsByVar("close_date", $this_month);
$monthly_reopened = getReopenedCount($this_month);
$times = getTimeInStateOnDate($this_month);
$monthly_working = $times['working'];
$monthly_waiting = $times['waiting'];

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

	$times = getTimeInStateOnDate($start_date, $end_date);
	$working_report = $times['working'];
	$waiting_report = $times['waiting'];
?>
<div class="box reportResult"> 
	<?topCorner();?>
		<div class="boxHeader">
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
					<td class="fLblTicket">Average time ticket was<br/>In Progress/Queued:</td>
				    <td><?printf("%.2f Days", $working_report)?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Average time ticket was<br/>Pending/Awaiting Pickup:</td>
				    <td><?printf("%.2f Days", $waiting_report)?></td>
				</tr>
			</table>
		</div>
	<?bottomCorner();?>
</div>
<?}?>

<div class="box reportDate">
	<?topCorner();?>
	<div class="boxHeader">
		Manual Date Bounds
	</div>

	<div class="boxContent">
		<form method="post" action="<?=$PHP_SELF?>">
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
	<?bottomCorner();?>
	</div>

<div class="column1">
	<div class="box">
		<?topCorner();?>
		<div class="boxHeader">
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
				<tr>
					<td class="fLblTicket">Average time ticket was<br/>In Progress/Queued:</td>
				    <td><?printf("%.2f Days", $weekly_working)?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Average time ticket was<br/>Pending/Awaiting Pickup:</td>
				    <td><?printf("%.2f Days", $weekly_waiting)?></td>
				</tr>
			</table>
		</div>
		<?bottomcorner();?>
	</div>
</div>

<div class="column2">
	<div class="box">
		<?topCorner();?>
		<div class="boxHeader">
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
				<tr>
					<td class="fLblTicket">Average time ticket was<br/>In Progress/Queued:</td>
				    <td><?printf("%.2f Days", $monthly_working)?></td>
				</tr>
				<tr>
					<td class="fLblTicket">Average time ticket was<br/>Pending/Awaiting Pickup:</td>
				    <td><?printf("%.2f Days", $monthly_waiting)?></td>
				</tr>
			</table>
		</div>
		<?bottomcorner();?>
	</div>
</div>

<div class="column3">
	<div class="box">
		<?topCorner();?>
		<div class="boxHeader">
			TCenter All-Time Report
		</div>
	
		<div class="boxContent">
			# of computers accepted : <?=$total_tickets?><br/>
		</div>
		<?bottomcorner();?>
	</div>
</div>
