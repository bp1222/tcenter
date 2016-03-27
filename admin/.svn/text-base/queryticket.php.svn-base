<? 
	include("../inc/connection.inc"); 
  include("../inc/header2.php");	
?>
<!-- Begin Rescon Summary -->
<table width="80" align="center">
	<tr>
		<td bgcolor="green">Low</td>
		<td bgcolor="blue">Medium</td>
		<td bgcolor="yellow">High</td>
		<td bgcolor="orange">Critical</td>
		<td bgcolor="red">Severe</td>
		<td bgcolor="#00CCFF">Awaiting Pickup</td>
		<td bgcolor="#990000">Re-Queued</td>
	</tr>
</table>
<!-- End Rescon Summary -->
<?

	# set variables to null to overwrite url injection
	$orderby = "";
	$global = "";

	# get the post data
	@extract($_POST);

	if (!isset($start)) $start = 0;
	
	# if the query is an openquery
	if ($openquery == "openquery") {
		# or orderby has not been set, default to the date the ticket was created
		if ($orderby == "") {
			$orderby = "date";
		}
				
		# if the username was not left blank we have to query for a specific username
		if ($username != "") {

		# check if it is an RCC
		if (preg_match("/(rcc|help)/i", $username)) {
			$goby = "lastupdatedby";
		}
		else {
			$goby = "username";
		}

			# search for specific status to query by
			if ($status != "Open") {
				$query = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status = '$status' AND queue = '$queue' AND $goby LIKE '%$username%' ORDER BY $orderby ASC, tid ASC OFFSET " . $start . " LIMIT 50;";
				$countquery = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status = '$status' AND queue = '$queue' AND $goby LIKE '%$username%' ORDER BY $orderby ASC, tid ASC;";
			}
			# search for any status that is not Closed
			elseif ($status == "Open") {
				$query = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status <> 'Closed' AND queue = '$queue' AND $goby LIKE '%$username%' ORDER BY $orderby ASC, tid ASC OFFSET " . $start . " LIMIT 50;";
				$countquery = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status <> 'Closed' AND queue = '$queue' AND $goby LIKE '%$username%' ORDER BY $orderby ASC, tid ASC;";

			}
		}
		# if a username was not supplied
		if ($username == "") {
			# search for specific status to query by
			if ($status != "Open") {
				$query = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status = '$status' AND queue = '$queue' ORDER BY $orderby ASC, tid ASC OFFSET " . $start . " LIMIT 50;";
				$countquery = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status = '$status' AND queue = '$queue' ORDER BY $orderby ASC;";
			}
			# search for any status that is not Closed
			elseif ($status == "Open") {
				$query = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status <> 'Closed' AND queue = '$queue' ORDER BY $orderby ASC, tid ASC OFFSET " . $start . " LIMIT 50;";
				$countquery = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status <> 'Closed' AND queue = '$queue' ORDER BY $orderby ASC, tid ASC;";

			}
		}
		
	# do a global search of all queues and statuses for the username
	if ($global == "global") {
				$query = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE username LIKE '%$username%' ORDER BY $orderby ASC, tid ASC OFFSET " . $start . " LIMIT 50;";
				$countquery = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE username LIKE '%$username%' ORDER BY $orderby ASC, tid ASC;";
	}
		?>
		<!-- Customized Header -->
		<center><h3>Tickets in the <?echo $queue;?> Queue that are <?echo $status; 
		if (preg_match("/(rcc|help)/i", $username)) {
			echo " Last Updated By $username";
		}
		elseif ($username != "") {
			echo " for user $username";
		}
	
		$countresult = pg_query($countquery);	
		$numrows = pg_num_rows($countresult);
		
		?></h3>
		<!--<table width="30px" align="right">-->
			<table width="80%" align="center">
			<tr>
				<td align="right" width="100%">
					<table align="right" width="280px">
						<tr>
							<td width="50px" align="right">
					<?
						if ($start > 0) {
							?>
							<form enctype="multipart/form-data" method="post" action="<? echo $PHP_SELF; ?>">
								<input type="hidden" name="start" value="0">
								<input type="hidden" name="openquery" value="<? echo $openquery;?>">
								<input type="hidden" name="orderby" value="<? echo $orderby;?>">
								<input type="hidden" name="username" value="<? echo $username;?>">
								<input type="hidden" name="status" value="<? echo $status;?>">
								<input type="hidden" name="queue" value="<? echo $queue;?>">
								<input type="submit" name="submit" value="Beginning">
							</form>
							</td>
							<td width="55px" align="left">
							<form enctype="multipart/form-data" method="post" action="<? echo $PHP_SELF; ?>">
								<input type="hidden" name="start" value="<? echo ($start - 50); ?>">
								<input type="hidden" name="openquery" value="<? echo $openquery;?>">
								<input type="hidden" name="orderby" value="<? echo $orderby;?>">
								<input type="hidden" name="username" value="<? echo $username;?>">
								<input type="hidden" name="status" value="<? echo $status;?>">
								<input type="hidden" name="queue" value="<? echo $queue;?>">
								<input type="submit" name="submit" value="Previous 50">
							</form>
							</td>
							<td width="30px" align="left">
						<?	
						}
						else {
						?></td><td width="55px"></td><td width="30px"><?
						}
						if ($numrows > ($start + 50)) {
							?>
							<form enctype="multipart/form-data" method="post" action="<? echo $PHP_SELF; ?>">
								<input type="hidden" name="start" value="<? echo ($start + 50); ?>">
								<input type="hidden" name="openquery" value="<? echo $openquery;?>">
								<input type="hidden" name="orderby" value="<? echo $orderby;?>">
								<input type="hidden" name="username" value="<? echo $username;?>">
								<input type="hidden" name="status" value="<? echo $status;?>">
								<input type="hidden" name="queue" value="<? echo $queue;?>">
								<input type="submit" name="submit" value="Next 50">
							</form>
						<?	
							
						}

						$end = $numrows - ($numrows % 50);	
						if ($start != $end) {
						?>
						</td>
						<td width="40px" align="left">
							<form enctype="multipart/form-data" method="post" action="<? echo $PHP_SELF; ?>">
								<input type="hidden" name="start" value="<? echo $end; ?>">
								<input type="hidden" name="openquery" value="<? echo $openquery;?>">
								<input type="hidden" name="orderby" value="<? echo $orderby;?>">
								<input type="hidden" name="username" value="<? echo $username;?>">
								<input type="hidden" name="status" value="<? echo $status;?>">
								<input type="hidden" name="queue" value="<? echo $queue;?>">
								<input type="submit" name="submit" value="End">
							</form>
							<? } ?>
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
		<br>
		<!-- Begin Massive Table
				 Each button is a form that will auto sort the returned data by the column
				 the button is in
		-->

			<table width="80%">
				<tr>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="username">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Username">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="tcnumber">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Ticket #" style="width:85px;">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="tnumber">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="RIT Ticket #">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="status">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Status">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="date">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Request Date">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="summary">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Summary" style="width:90px;">
						</form>
					</td>
					<td>
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="rescon">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Rescon" style="width:90px;">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="lastupdateddate">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Last Updated">
						</form>
					</td>
					<td align="center">
						<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="orderby" value="lastupdatedby">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="username" value="<?echo $username; ?>">
						<input type="hidden" name="status" value="<?echo $status; ?>">
						<input type="hidden" name="queue" value="<?echo $queue;?>">
						<input type="submit" name="submit" value="Updated By">
						</form>
					</td>
					<td></td>
				</tr>
				<?
		# this variable changes the background color of the returned item to alternate it
		# upon each iteration through the rows
		$result = pg_query($query);
		$i = 2;
		while ($row = pg_fetch_assoc($result)) {

			$username = trim($row['username']);
			$tcnumber = trim($row['tcnumber']);
			$date = trim($row['date']);
			$summary = stripslashes(trim($row['summary']));
			$lastupdateddate = trim($row['lastupdateddate']);
			$lastupdatedby = trim($row['lastupdatedby']);
			$tstatus = trim($row['status']);
			$tnumber = trim($row['tnumber']);
			$rescon = trim($row['rescon']);

			?>
			<tr style="height: auto;background-color: <? 
			# change the color based on the modulus	
			if($i % 2 == 0) { echo "#cccccc;";}else { echo "#eeeeee;";} ?>">
				<td valign="center" height="15px" width="35px"><?echo $username; ?></td><td valign="center" width="85px"><?echo $tcnumber;?></td>
				<td valign="center" height="15px" width="35px"><?
					if ($tnumber != "") {
						echo $tnumber;
					}
					else {
						?>
						<form enctype="multipart/form-data" method="post" action="addticketnum.php">
						<input type="hidden" name="tcnumber" value="<?echo $tcnumber;?>">
						<input type="submit" name="submit" value="Add Ticket #">
						</form>
						<?
					}
				?></td><td valign="center" width="85px"><?echo $tstatus;?></td>
				<!--<td valign="center" width="60px"><?#echo $date; ?></td><td onMouseOver="this.style.backgroundColor='black'" onMouseOut="this.style.backgroundColor='<? #echo $thecolor; ?>'" valign="center" width="330px"><?#echo $summary; ?></td>-->
				<td valign="center" width="60px"><?echo $date; ?></td><td colspan="2" bgcolor="<? echo $rescon; ?>" valign="center" width="330px"><?echo $summary; ?></td>
				<td valign="center" width="60px"><?echo $lastupdateddate; ?></td><td valign="center" width="60px"><?echo $lastupdatedby; ?></td>
				<td align="center" valign="center" width="20px" style="text-align:center; vertical-align:center;">
				<a href="queryticket.php?tcnumber=<? echo $tcnumber; ?>&referrer=queryticket"><img src="../images/eye.jpg" border="0"></a>
				<!--
				<form enctype="multipart/form-data" method="post" action="queryticket.php">
				<input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>"><input type="hidden" name="referrer" value="queryticket">
				<input type="image" src="../images/eye.jpg" name="submit">
				</form>
				-->
				</td>
				<td align="center" valign="center" style="text-align:center; vertical-align:center;">
				<a href="tickets.php?tcnumber=<? echo $tcnumber; ?>&referrer=tickets"><img src="../images/pencil.jpg" border="0"></a>
				<!--
				<form enctype="multipart/form-data" method="post" action="tickets.php"><input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>"><input type="hidden" name="referrer" value="tickets">
				<input type="image" src="../images/pencil.jpg" name="submit">
				</form>
				-->
				</td>
				<? if ($status == "Closed") {?>
				<td align="center" valign="center" width="20px" style="text-align:center; vertical-align:center;">
				<form enctype="multipart/form-data" method="post" action="receipt.php">
				<input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
				<input type="image" src="../images/printer.jpg" name="submit">
				</form></td>
				<? } ?>
			</tr>
		<?	
		# increment the cariable so the color will change
		$i++;
		}?>
		<tr>
			<td colspan="8" align="right" valign="center">Total Query Results:&nbsp;<?echo pg_num_rows($result); ?> Tickets
			</td>
		</tr>
	</table>
	<?
	}
	# if the query is specific
	else {

	if ($referrer == "queryticket") {

		# if the query is for a ticket number
		strtolower($tcnumber);
		if ($tcnumber != "") {
			$query = "SELECT * FROM tickets WHERE lower(tcnumber) LIKE '%$tcnumber%' ORDER BY date ASC;";
		}

		# if the query is for a username
		strtolower($username);
		if ($username != "") {
			$query = "SELECT * FROM tickets WHERE lower(username) LIKE '%$username%' ORDER BY date DESC;";
		}

	# if they did not enter anything, give them an error
	if ($tcnumber == "" && $username == "") {
		?><h3>Query For A Ticket:</h3><hr width="40%"><?
		echo "Please enter correct information in the field of your choosing below";
	?>
	<br><br>
	<table>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="queryticket.php" method="post">
				Ticket # to query: 
			</td>
			<td>
				<input type="text" size="20" value="" name="tcnumber">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="queryticket">
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="queryticket.php" method="post">
				Username to query: 
			</td>
			<td>
				<input type="text" size="20" value="" name="username">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="queryticket">
				</form>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<form enctype="multipart/form-data" action="queryticket.php" method="post">
				<b>Query For</b> &nbsp; <select size="1" name="status">
					<option selected>Open</option>
					<?
						# create a dropdown box of all of the current statuses
						$query = "SELECT name FROM status WHERE enabled = 'true' ORDER BY name ASC;";
						$result = pg_query($query);
						while ($row = pg_fetch_assoc($result)) {
							$statusname = trim($row['name']);
							echo "<option>$statusname</option>\n";
						}
					?></select>
				&nbsp;<b>Tickets in the</b>&nbsp; <select size="1" name="queue">
					<?
						# create a dropdown box of all of the current queues
						$query = "SELECT name FROM queues WHERE enabled = 'true' ORDER BY name ASC;";
						$result = pg_query($query);
						while ($row = pg_fetch_assoc($result)) {
							$queuename = trim($row['name']);
							if ($queuename == "Resnet") {
								echo "<option selected>$queuename</option>\n";
							}
							else {
								echo "<option>$queuename</option>\n";
							}
						}
					?>
					</select>&nbsp;<b>Queue</b> [for user]&nbsp;
				<input type="text" size="20" value="" name="username">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="openquery" value="openquery">
				</form>
			</td>
		</tr>
	</table>
	
	<? }

	# perform the query for the information they want
	else {
	if ($tcnumber != "") {
		$queryresult = "Query Result For Ticket #: $tcnumber"; 
		$setup = "SELECT username FROM tickets WHERE tcnumber = '$tcnumber';";
		$getname = pg_query($setup);
		while ($yeeaaa = pg_fetch_assoc($getname)) {
			$username = trim($yeeaaa['username']);
		}
	}
	if ($username != "") {
		$queryresult = "Query Result for Tickets For User: $username";
	}
	$result = pg_query($query);

	# no results were found
	if ( pg_num_rows($result) == 0 ) {
		echo "There are no results for your query.";
	}
	else { 
	#while ($whatrow = pg_fetch_assoc($result)) {
	#	$username = trim($whatrow['username']);
	#}
	?>
	<!-- Two query forms at the top -->
	<table width="100%">
		<tr>
			<td align="right">
				<form enctype="multipart/form-data" method="post" action="tickets.php">
				<input type="hidden" name="referrer" value="tickets">
				Ticket #:&nbsp;<input type="text" name="tcnumber" value="" size="20">&nbsp;<input type="submit" name="submit" value="Search">
				</form>
			</td>
		</tr>
		<tr>
			<td align="right">
				<form enctype="multipart/form-data" method="post" action="queryticket.php">
					<input type="hidden" name="referrer" value="queryticket">
					Username:&nbsp;<input type="text" name="username" value="" size="20" maxlength="10">&nbsp;<input type="submit" name="submit" value="Search">
				</form>
			</td>
		</tr>
	</table>
	<table width="70%">
			<tr>
				<td>
					<br>
					<form enctype="multipart/form-data" method="post" action="queryticket.php">
						<input type="hidden" name="openquery" value="openquery">
						<input type="hidden" name="global" value="global">
						<input type="hidden" name="orderby" value="date">
						<input type="hidden" name="username" value="<?echo $username;?>">
						<input type="submit" name="submit" value="Past Tickets">
					</form>
					<form enctype="multipart/form-data" method="post" action="queryuser.php">
						<input type="hidden" name="referrer" value="queryuser">
						<input type="hidden" name="uname" value="<?echo $username;?>">
						<input type="submit" name="submit" value="User Information">
					</form>
					<br>
				</td>
	</table>
	<!-- End Query Fields -->


		<h3><? echo $queryresult; ?></h3><hr width="35%"><br>
		<table width="70%" valign="top">
	<?
	# for each result found, print out the information related to it
	while ($row = pg_fetch_assoc($result)) {

	$tid = trim($row['tid']);
	$username = trim($row['username']);
	$tcnumber = trim($row['tcnumber']);
	$tnumber = trim($row['tnumber']);
	$date = trim($row['date']);
	$accepteddate = trim($row['accepteddate']);
	$acceptedby = trim($row['acceptedby']);
	$description = stripslashes(trim($row['description']));
	$summary = stripslashes(trim($row['summary']));
	$queue = trim($row['queue']);
	$status = trim($row['status']);
	$progress = stripslashes(trim($row['progress']));
	$lastupdatedby = trim($row['lastupdatedby']);
	$lastupdateddate = trim($row['lastupdateddate']);
	$hdbackuplocation = stripslashes(trim($row['hdbackuplocation']));
	$closeddate = trim($row['closeddate']);
	$rescon = trim($row['rescon']);
	$repairinstall = trim($row['repairinstall']);
	$fullinstall = trim($row['fullinstall']);
	
		# get the inventory for the machine involved in the ticket
		$anotherquery = "SELECT mid, inventory FROM machines WHERE tcnumber = '$tcnumber';";
		$anotherresult = pg_query($anotherquery);
		while ($anotherrow = pg_fetch_assoc($anotherresult)) {
			$inventory = stripslashes(trim($anotherrow['inventory']));
			$mid = trim($anotherrow['mid']);
		}
	?>
			<tr>
				<td colspan="2" align="center"><b>Ticket #: <? echo $tcnumber; ?></b>
				<br><br>
				</td>
			</tr>
			<tr>
				<td>
					<form enctype="multipart/form-data" method="post" action="../editmachines.php">
						<input type="hidden" name="referrer" value="admin">
						<input type="hidden" name="midnumber" value="<?echo $mid;?>">
						<input type="submit" name="submit" value="Machine Information">
					</form>
				</td>
			</tr>
			<tr>
				<td valign="bottom">Username: </td><td valign="top"><? echo $username; ?></td>
			</tr>
			<tr>
				<td valign="top">Ticket #: </td><td valign="top"><? echo $tcnumber; ?>&nbsp;<a href="../barcode/barcode.php?code=<?echo $tcnumber;?>&scale=1&bar=ANY"><img src="../images/bc.jpg" border="0"></a></td>
			</tr>
			<tr>
				<td valign="top">RIT Ticket #: </td><td valign="top"><? echo $tnumber; ?></td>
			</tr>
			<tr>
				<td valign="top">Date Of Creation: </td><td valign="top"><? echo $date; ?></td>
			</tr>
			<tr>
				<td valign="top">Date Accepted: </td><td valign="top"><? echo $accepteddate; ?></td>
			</tr>
			<tr>
				<td valign="top">Accepted By: </td><td valign="top"><? echo $acceptedby; ?></td>
			</tr>
			<tr>
				<td valign="top">Description:</td><td valign="top"><? echo $description; ?></td>
			</tr>
			<tr>
				<td valign="top">Summary: </td><td valign="top"><? echo $summary; ?></td>
			</tr>
			<tr>
				<td valign="top">Repair / Full Install: </td><td valign="top">
				<? if ($repairinstall == "t") { echo "Yes / ";}else{ echo "No / ";} ?>
				<? if ($fullinstall == "t") { echo "Yes";}else{ echo "No";} ?>
				</td>
			</tr>
			<tr>
				<td valign="top">Rescon Scale: </td><td valign="top"><? echo $rescon; ?>				</td>
			</tr>
			<tr>
				<td valign="top">Inventory: </td><td valign="top"><? echo $inventory; ?>				</td>
			</tr>
			<tr>
				<td valign="top">Queue: </td><td valign="top"><? echo $queue; ?></td>
			</tr>
			<tr>
				<td valign="top">Status: </td><td valign="top"><? echo $status; ?></td>
			</tr>
			<tr>
				<td valign="top">Closed Date: </td><td valign="top"><? echo $closeddate; ?></td>
			</tr>
			<tr>
				<td valign="top">Progress: </td><td valign="top"><? echo $progress; ?></td>
			</tr>
			<tr>
				<td valign="top">Last Updated By: </td><td valign="top"><? echo $lastupdatedby; ?></td>
			</tr>
			<tr>
				<td valign="top">Date Last Updated: </td><td valign="top"><? echo $lastupdateddate; ?></td>
			</tr>
			<tr>
				<td valign="top">Hard Drive Backup Location: </td><td valign="top"><? echo $hdbackuplocation; ?></td>
			</tr>
			<tr>
				<td colspan="2"><hr width="80%"></td>
			</tr>
			<tr>
				<td colspan="2"><b>Common Tasks Currently Completed:</b><br></td>
			</tr>
			<tr>
				<td colspan="2">
				<table width="80%" align="center">
			<? # list out the current tasks that have been completed for this ticket

				$ctaskquery = "SELECT tasks.name AS name, link.date AS date FROM tasks, link WHERE link.task = tasks.taskid AND link.ticket = '$tcnumber' AND link.date is not null;";
				$ctaskresult = pg_query($ctaskquery);
				while ($row = pg_fetch_assoc($ctaskresult)) {

					$name = trim($row['name']);
					$date = trim($row['date']);

					echo "<tr><td align=\"left\">$name</td><td align=\"center\">Date Completed: $date</td></tr>";
				
				}

			?>
			</table>
			</td>
			</tr>
			<tr>
				<td colspan="2" valign="top" align="right">
					<form encytype="multipart/form-data" method="post" action="tickets.php">
					<input type="hidden" name="tcnumber" value="<?echo $tcnumber; ?>">
					<input type="hidden" name="referrer" value="tickets">
					<input type="submit" name="submit" value="Edit Ticket">
				</form>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
	<? } 
	}	
	}
	?></table><?
	}

	# nothing to search for, show the search page
	else { ?>
		<h3>Query For A Ticket:</h3><hr width="40%">
	<table>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="queryticket.php" method="post">
				Ticket # to query: 
			</td>
			<td>
				<input type="text" size="20" value="" name="tcnumber">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="queryticket">
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="queryticket.php" method="post">
				Username to query: 
			</td>
			<td>
				<input type="text" size="20" value="" name="username">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="queryticket">
				</form>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<form enctype="multipart/form-data" action="queryticket.php" method="post">
				<b>Query For</b> &nbsp; <select size="1" name="status">
					<option selected>Open</option>
					<?
						$query = "SELECT name FROM status WHERE enabled = 'true' ORDER BY name ASC;";
						$result = pg_query($query);
						while ($row = pg_fetch_assoc($result)) {
							$statusname = trim($row['name']);
							echo "<option>$statusname</option>\n";
						}
					?></select>
				&nbsp;<b>Tickets in the</b>&nbsp; <select size="1" name="queue">
					<?
						$query = "SELECT name FROM queues WHERE enabled = 'true' ORDER BY name ASC;";
						$result = pg_query($query);
						while ($row = pg_fetch_assoc($result)) {
							$queuename = trim($row['name']);
							if ($queuename == "Resnet") {
								echo "<option selected>$queuename</option>\n";
							}
							else {
								echo "<option>$queuename</option>\n";
							}
						}
					?>
					</select>&nbsp;<b>Queue</b> [for user]&nbsp;
				<input type="text" size="20" value="" name="username">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="openquery" value="openquery">
				</form>
			</td>
		</tr>
	</table>
	
	<? }} ?>

<? 
# close the database connection
pg_close($db_connect) ?>
