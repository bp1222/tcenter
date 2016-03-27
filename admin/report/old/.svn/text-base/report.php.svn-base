<? include("inc/header.php"); ?>

<html>
<head>
<title>Resnet Shift Report</title>

</head>

	<h2>Resnet Shift Supervisor Report Reports</h2>

					<table width="100%">
						<tr>
							<td>Supervisor:</td><td>Date:</td></td><td>Time:</td><td>Overview:</td>
							<td>Problems:</td><td>Other:</td>
						</tr>
<?php
	
		/* MYSQL CONNECTION */
		
		$link = mysql_connect("localhost", "sreport", "d3vnu11")
			or die("Could not connect : " . mysql_error());
		$datab = "shiftreport";
		mysql_select_db("$datab") or die("Could not select database");
		
		$userdudemanz0r = $PHP_AUTH_USER;

				$insertquery = "SELECT * FROM shifts";

				$result = mysql_query($insertquery);

					$i = 1;
				while ($row = mysql_fetch_assoc($result)) {

					$nid = trim($row['NID']);
					$date = trim($row['date']);
					$time = trim($row['time']);
					$overview = trim($row['overview']);
					$problems = trim($row['problems']);
					$other = trim($row['other']);

					$query = "SELECT name FROM peoples WHERE NID = '$nid';";

					$newresult = mysql_query($query);
					while ($newrows = mysql_fetch_assoc($newresult)) {
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

