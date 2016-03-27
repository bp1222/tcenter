<? include("inc/header.php"); ?>
<? include("inc/connection.inc"); ?>

<html>
<head>
<title>Resnet Shift Report</title>

</head>
<body>
<table width="100%">
	<tr>
		<td align="right"><a href="index.php">Shift Report Entry</a></td>
	</tr>
</table>
<h2>Resnet Shift Supervisor Report Reports</h2>
</center>
				<center>
					<table width="95%">
						<tr>
							<td colspan="6" align="right">
<?
	
		$userdudemanz0r = $PHP_AUTH_USER;

		if (!isset($start)) $start = 0;

				$insertquery = "SELECT * FROM shifts ORDER BY sid DESC OFFSET " . $start . "LIMIT 20;";
				$result = pg_query($insertquery);

				$countquery = "SELECT sid FROM shifts;";
				$counter = pg_query($countquery);
				$numrows = pg_num_rows($counter);
	
				if ($start >= 20) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start - 20) . "\">Next 20</a> ]";
				}	
				if ($numrows > ($start + 20)) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start + 20) . "\">Previous 20</a> ]";
				}
?>
							</td>
						</tr>
						<tr>
							<td>Supervisor:</td><td>Date:</td></td><td>Time:</td><td>Overview:</td>
							<td>Problems:</td><td>Other:</td>
						</tr>
<?
					$i = 1;
				while ($row = pg_fetch_assoc($result)) {

					$nid = trim($row['nid']);
					$date = trim($row['date']);
					$time = trim($row['time']);
					$overview = trim($row['overview']);
					$problems = trim($row['problems']);
					$other = trim($row['other']);

					$query = "SELECT name FROM peoples WHERE nid = '$nid';";

					$newresult = pg_query($query);
					while ($newrows = pg_fetch_assoc($newresult)) {
						$name = trim($newrows['name']);
					}

					if ( $i % 2 == 0 ) {
						echo "<tr style=\"background-color:#cccccc;\">";
					}
					else {
						echo "<tr style=\"background-color:#eeeeee;\">";
					}

					?>
				
						<td><?echo $name; ?></td><td><?echo $date; ?></td><td><?echo $time; ?></td><td><?echo $overview; ?></td><td><?echo $problems; ?></td><td><?echo $other; ?></td>
					</tr>
				
				<?
				$i++;
				}
	
?>
				<tr>
					<td colspan="6" align="right">
<?
				if ($start >= 20) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start - 20) . "\">Next 20</a> ]";
				}	
				if ($numrows > ($start + 20)) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start + 20) . "\">Previous 20</a> ]";
				}
?>
			</td>
		</tr>
	</table>
	</center>
<? pg_close($db_connect);?>
