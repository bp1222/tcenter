	include("../../inc/connection.inc");
	include("../../inc/header2.php");

<table width="60%">
	<tr><td><b>Accepted Date</b></td><td><b>Date Closed</b></td><td><b>Status (just to make sure)</b></td></tr>
	<?
	$total = 0;
	$query = "SELECT status, closeddate, accepteddate FROM tickets WHERE closeddate LIKE '2%' ORDER BY status, closeddate;";
	$result = pg_query($query);
	while ($row = pg_fetch_assoc($result)) {
		$status = trim($row['status']);
		$date = trim($row['closeddate']);
		$accepted = trim($row['accepteddate']);
		if ($date != $curdate) {
			print "<tr><td></td><td></td><td>$total</td></tr>";
			$newquery = "UPDATE stats SET value='$total' WHERE date='$date' AND name='closed' AND statid>125;";
			$newresult = pg_query($newquery);
			$curdate = $date;
			$total = 1;
		}
		else {
			$curdate = $date;
			$total++;
		}
		print "<tr><td>$accepted</td><td>$date</td><td>$status</td>\n";
	}
	?>
</table>

<table width="60%">
	<tr><td><b>Accepted Date</b></td><td><b>Date Closed</b></td><td><b>Status (just to make sure)</b></td></tr>
	<?
	$total = 0;
	$query = "SELECT status, closeddate, accepteddate FROM tickets ORDER BY status, accepteddate;";
	$result = pg_query($query);
	while ($row = pg_fetch_assoc($result)) {
		$status = trim($row['status']);
		$date = trim($row['closeddate']);
		$accepted = trim($row['accepteddate']);
		if ($accepted != $curdate) {
			print "<tr><td></td><td></td><td>$total</td></tr>";
			$newquery = "UPDATE stats SET value='$total' WHERE date='$accepted' AND name='open' AND statid>125;";
			$newresult = pg_query($newquery);
			$curdate = $accepted;
			$total = 1;
		}
		else {
			$curdate = $accepted;
			$total++;
		}
		print "<tr><td>$accepted</td><td>$date</td><td>$status</td>\n";
	}
	?>
</table>

</body>
</html>

<? pg_close($db_connect); ?>
