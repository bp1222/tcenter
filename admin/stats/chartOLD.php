<?
	include("../../inc/header3.php");
	include("../../inc/connection.inc");

	@extract($_POST);

	$query = "SELECT uid FROM users;";
	$result = pg_query($query);
	$numusers = pg_num_rows($result);

	$query = "SELECT tid FROM tickets WHERE status <> 'Closed' AND status <> 'Awaiting Pickup' AND status <> 'Pending Customer';";
	$result = pg_query($query);
	$numopen = pg_num_rows($result);

	$query = "SELECT tid FROM tickets WHERE status = 'Awaiting Pickup';";
	$result = pg_query($query);
	$numwaiting = pg_num_rows($result);

	$query = "SELECT tid FROM tickets WHERE status = 'Pending Customer';";
	$result = pg_query($query);
	$numpending = pg_num_rows($result);

	$query = "SELECT tid FROM tickets WHERE status = 'Closed';";
	$result = pg_query($query);
	$numclosed = pg_num_rows($result);

	$query = "SELECT tid FROM tickets WHERE status = 'Queued';";
	$result = pg_query($query);
	$numqueued = pg_num_rows($result);

	# attempts to find current average time of tickets open

	if ($startdate != "" && $enddate != "") {
		$query = "SELECT accepteddate, pickupdate FROM tickets WHERE accepteddate >= '$startdate' AND pickupdate <= '$enddate';";
	}
	else {
		$query = "SELECT accepteddate, pickupdate FROM tickets WHERE accepteddate LIKE '%-%-%' AND pickupdate LIKE '%-%-%';";
	}
	$result = pg_query($query);
	$numrows = pg_num_rows($result);
	$total = 0;
  
	while ($row = pg_fetch_assoc($result)) {

		$accepted = trim($row['accepteddate']);
		$pickup = trim($row['pickupdate']);

		$total = $total + ( round((strtotime($pickup) - strtotime($accepted)) / (60*60*24) ));

	}

    $averagetime_numrows = $numrows;  // Number of pickup-open tickets
	if ($numrows != 0) {
		$averagetime = $total / $numrows;
	}
	else {
		$averagetime = 0;
	}

	# attempts to find current average time of pickup 

	if ($startdate != "" && $enddate != "") {
		$query = "SELECT closeddate, pickupdate FROM tickets WHERE closeddate LIKE '%-%-%' AND pickupdate LIKE '%-%-%' AND pickupdate >= '$startdate' AND closeddate <= '$enddate';";
	}
	else {
		$query = "SELECT closeddate, pickupdate FROM tickets WHERE closeddate LIKE '%-%-%' AND pickupdate LIKE '%-%-%';";
	}
	$result = pg_query($query);
	$numrows = pg_num_rows($result);
	$total = 0;
	while ($row = pg_fetch_assoc($result)) {

		$closed = trim($row['closeddate']);
		$pickup = trim($row['pickupdate']);

		#$total = $total + ( round((strtotime($closed) - strtotime($pickup)) / (60*60*24) -1));
		$total = $total + ( round((strtotime($closed) - strtotime($pickup)) / (60*60*24) ));
	
	}

    $averagepickuptime_numrows = $numrows;  // Number of pickup-closed tickets
	if ($numrows != 0) {
		$averagepickuptime = $total / $numrows;
	}
	else {
		$averagepickuptime = 0;
	}

?>
<!-- Two fields a the top of the page to query by -->
<table width="100%">
	<tr>
		<td align="right" colspan="2">
			<form enctype="multipart/form-data" method="post" action="chart.php">
			Date Range:&nbsp;<input type="text" name="startdate" value="" size="20">&nbsp;to&nbsp;<input type="text" name="enddate" value="" size="20">
		</td>
	</tr>
	<tr>
		<td align="right">
			Date Format:&nbsp;[mm/dd/yyyy]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Get Stats">
			</form>
		</td>
	</tr>
</table>
<br><br>
</center>

<table>
	<tr>
		<td colspan="2">
			<u><b>Up-To-Date Individual Stats for:</b> <? echo date('F d, Y'); ?></u>
		</td>
	</tr>
	<tr>
		<td width="200px"><b>Current Unique Customer Count:</td><td><?echo $numusers; ?></b></td>
	</tr>
	<tr>
		<td><b>Total Tickets Currently Closed:</td><td><?echo $numclosed; ?></b></td>
	</tr>
	<tr>
		<td><b>Total Tickets Currently Open:</td><td><?echo $numopen; ?></b></td>
	</tr>
	<tr>
		<td><b>Total Tickets Currently Pending Customer:</td><td><?echo $numpending; ?></b></td>
	</tr>
	<tr>
		<td><b>Total Tickets Currently Awaiting Pickup:</td><td><?echo $numwaiting; ?></b></td>
	</tr>
	<tr>
		<td><b>Total Tickets Currently Queued:</td><td><?echo $numqueued; ?></b></td>
	</tr>
	<?
		if ($startdate != "" && $enddate != "") { ?>
		    <tr><td colspan="2"><b><u>Averages From <? echo "$startdate To $enddate"; ?></u></b></td></tr>
			
			<tr>
			    <td><b>Average Life Of Ticket:</b></td>
		        <td><?echo round($averagetime, 2); ?> days (<?echo $averagetime_numrows; ?> tickets)</td>
			</tr>
			<tr>
			    <td><b>Average Pickup Time:</b></td>
		        <td><?echo round($averagepickuptime, 2); ?> days (<?echo $averagepickuptime_numrows; ?> tickets)</td>
			</tr>
        <?} else {?>
            <tr>
		        <td><b>Average Life Of Ticket:</b></td>
		        <td><?echo round($averagetime, 2); ?> days</td>
	        </tr>
	        <tr>
		        <td><b>Average Pickup Time:</b></td>
		        <td><?echo round($averagepickuptime, 2); ?> days</td>
	        </tr>
	    <?}?>
</table>
<br>

<br><br>
<center>
<b>Week-long Report:</b>
<br>
<img src="weekgraph.php">
<br><br>
<b>Month-long Report:</b>
<br>
<img src="monthgraph.php">
<br><br>
<b>Year-long Report:</b>
<br>
<img src="yeargraph.php">
<br><br>
