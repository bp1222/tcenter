<? 
	include("../../inc/connection.inc"); 
  include("../../inc/header3.php");	
?>

<?

	# create the current date
	$date = date('l, M d Y');

	# query for username and update information
	$query = "SELECT username, updates FROM experience WHERE updates <> '' ORDER BY username ASC;";
	$result = pg_query($query);
	?>
		<h3>Resnet TechCenter Daily Progess For: <? echo $date; ?></h3><hr width="35%"><br><br>
		<table width="40%" valign="top">
			<tr>
				<td valign="top" align="center"><u><b>Username</b></u></td>
				<td valign="top" align="center"><u><b>Ticket #</b></u></td>
				<td valign="top" align="right"><u><b># of Updates Today</b></u></td>
			</tr>
		<tr>
			<td colspan="3"><hr width="100%">
			</td>
		</tr>
	<?
	if (pg_num_rows($result) > 0) {
	while ($row = pg_fetch_assoc($result)) {

		$username = trim($row['username']);
		$updates = trim($row['updates']);

		# split the updates string by ',' and populate and array of strings
		# each string will be in the form "username-ticket#!updates"
		$udarray = explode(',', $updates);
	
		# create the table to contain this user's data
		?>
						<tr>
							<td valign="top" align="center"><b><?echo $username;?></b></td>
							<td></td><td></td>
						</tr>
		<?
						
		# create a loop that explodes each string in the udarray, which
		# gives us the ticket# and the amount of updates for that ticket
		# which we will use to populate the table and add for the total
		
		$totalud = 0;

		foreach ($udarray as $singleud) {

			# make two variables to contain the ticket# and #updates
			list($tcnumber, $numupdates) = explode('!', $singleud);
		
			# add to total
			$totalud+=$numupdates;

			?>
			<tr>
				<td valign="top" align="center"></td>
				<td valign="top" align="center"><a href="http://tcenter.rit.edu/admin/tickets.php?tcnumber=<? echo $tcnumber; ?>&referrer=tickets"><? echo $tcnumber; ?></td>
				<td valign="top" align="right"><? echo $numupdates; ?></td>
			</tr>
			<?
		}
			?>

			<tr>
				<td></td><td></td>
				<td valign="top" align="right"><? echo "<b>Total Updates:</b> $totalud";?></td>
			</tr>
		<tr>
			<td colspan="3"><hr width="100%">
			</td>
		</tr>
		
		<?

	}
	}

		?>

	</table>

<? 
# close database connection
pg_close($db_connect); ?>
					
