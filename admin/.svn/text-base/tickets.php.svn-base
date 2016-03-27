<? 
	include("../inc/connection.inc"); 
  #include("../inc/header2.php");	
?>


<?

	# set values to null to clear url injection
	$reopen = "";
	$close = "";

	$resuser = trim($PHP_AUTH_USER);

	# extract the post data
	@extract($_POST);

	$thedate = date("F d, Y");
	# if the referrer is tickets
	if ($referrer == "tickets") {

		# if they are looking to reopen a ticket
		if ($reopen == "yes") {
			# set the status to "Reopen"
			$reopen = "Reopen";
			$status = $reopen;

			# reset the common tasks that need to be done again
			$query = "UPDATE link SET date = NULL WHERE ticket = '$tcnumber' AND task IN (20, 13, 14, 15);";
			$result = pg_query($query);

			# set the status to reopen in the database
			$query = "UPDATE tickets SET status = '$reopen' WHERE tcnumber = '$tcnumber';";
			$result = pg_query($query);
			# if we get an affected row, the update worked
			if (pg_affected_rows($result) == 1) {
				$theupdates = "Ticket $title has Been ReOpened<br>";
				$title = " - ReOpened";
				# generate cumulative statistics for the day
				$statquery = "SELECT statid, value FROM stats WHERE name = 'open' AND date = '$thedate';";
				$result = pg_query($statquery);
				# get the current value and add one to it
				while ($row = pg_fetch_assoc($result)) {
						$statid = trim($row['statid']);
						$value = trim($row['value']);
						$value++;

						$statupdate = "UPDATE stats SET value = '$value' WHERE statid = '$statid';";
						$statresult = pg_query($statupdate);

				}
				$closeearly = "UPDATE link SET date = NULL WHERE ticket = '$tcnumber' AND task = 20;";
				$result = pg_query($closeearly);
			
			}
			# error reopening the ticket if no rows were found
			else {
				$theupdates = "An error occured while reopening the ticket<br>";
				$title = " - Error";
			}
		}
		# they are looking to close a ticket
		if ($close == "yes") {
			# check if machine has a pickupdate set. if it doesn't, set it to
			# 'thedate'
			$query = "SELECT pickupdate, progress FROM tickets WHERE tcnumber = '$tcnumber';";
			$result = pg_query($query);
			while($row = pg_fetch_assoc($result)) {
				$pickupdate = trim($row['pickupdate']);
				$curprog = trim($row['progress']);
			}
			# set the status to closed
			$closed = "Closed";
			$updateprogress = "<font color=\"#ff0000\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Status Changed To: Closed</font><br>$curprog";
			$updateprogress = addslashes($updateprogress);
			if ($pickupdate != "") {
				$query = "UPDATE tickets SET status = '$closed', rescon = '', closeddate = '$thedate', progress = '$updateprogress' WHERE tcnumber = '$tcnumber';";
			}
			else {
				$query = "UPDATE tickets SET status = '$closed', rescon = '', closeddate = '$thedate', pickupdate = '$thedate', progress = '$updateprogress' WHERE tcnumber = '$tcnumber';";
			}		
			$result = pg_query($query);
			# an affected row means we have closed a ticket
			if (pg_affected_rows($result) == 1) {
				$theupdates = "Ticket $title Has Been Closed<br>";
				$title = " - Closed";
				# for security purposes, the password is cleared in the database
				# when a ticket is closed
				$query = "UPDATE machines SET password = '' WHERE tcnumber = '$tcnumber';";
				$result = pg_query($query);

				# generate cumulative statistics for the day
				$statquery = "SELECT statid, value FROM stats WHERE name = 'closed' AND date = '$thedate';";
				$result = pg_query($statquery);
				# get the current value and add one to it
					while ($row = pg_fetch_assoc($result)) {
						$statid = trim($row['statid']);
						$value = trim($row['value']);
						$value++;

						$statupdate = "UPDATE stats SET value = '$value' WHERE statid = '$statid';";
						$statresult = pg_query($statupdate);

					}

			}
			# no row was affected, there was an error
			else {
				$theupdates = "An error occured while closing the ticket<br>";
				$title = " - Error";
			}
		}

		# accepting the ticket is required before being able to update
		# if the ticket was accepted, and we are updating the ticket:
		if ($update == "yes" && $dateaccepted != "" && $acceptedby != "") {

			# update the ticket
			
			# initial prep-work
			# we put together updates for the progress first
			
			# get the current progress
			$query = "SELECT progress, status, accepteddate, rescon FROM tickets WHERE tcnumber = '$tcnumber';";
			$accepteddate = "";
			$result = pg_query($query);
			while ($row = pg_fetch_assoc($result)) {
				$oldprogress = trim($row['progress']);
				$oldstatus = trim($row['status']);
				$oldrescon = trim($row['rescon']);
				$accepteddate = trim($row['accepteddate']);
			}
			# if the ticket has never been accepted, we need to add it to the stats
			if ($accepteddate == "") {

				# generate cumulative statistics for the day
				$statquery = "SELECT statid, value FROM stats WHERE name = 'open' AND date = '$thedate';";
				$result = pg_query($statquery);
				# get the current value and add one to it
					while ($row = pg_fetch_assoc($result)) {
						$statid = trim($row['statid']);
						$value = trim($row['value']);
						$value++;

						$statupdate = "UPDATE stats SET value = '$value' WHERE statid = '$statid';";
						$statresult = pg_query($statupdate);

					}
			}

			# update the repair/full install
			if ($repairinstall != "") {
				$repairupdate = "UPDATE tickets SET repairinstall = 'TRUE' WHERE tcnumber = '$tcnumber';";
				$repairresult = pg_query($repairupdate);
			}
			if ($fullinstall != "") {
				$fullupdate = "UPDATE tickets SET fullinstall = 'TRUE' WHERE tcnumber = '$tcnumber';";
				$fullresult = pg_query($fullupdate);
			}
			
			# this checks to see if the progress update field on the ticket was
			# changed at all and that it is not blank
			if ($progress != "" && !preg_match("/^\s*(To Do:)\s*$/", $progress)) {
				# they have changed the progress. Append the old progress to the new
				# progress to keep the latest updates at the top
				# if the status has changed, add an update for that as well

				if ($status != $oldstatus || $status == "Reopen") {
					$statuschange = "<font color=\"#ff0000\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Status Changed To: $status</font><br>";
					# check to see if the ticket was changed to Awaiting Pickup
					# if it was, set pickupdate to 'thedate'
					if ($status == "Awaiting Pickup") {
						$statusquery = "UPDATE tickets SET pickupdate = '$thedate' WHERE tcnumber = '$tcnumber';";
						$statusresult = pg_query($statusquery);
					}
				}
				else {
					$statuschange = "";
				}
				if ($rescon != $oldrescon) {
					if ($status == "Awaiting Pickup") {
						$resconchange = "<font color=\"#0000ff\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Rescon Status Has Been Removed</font><br>";
					}
					else {
							if ($rescon == "green") {
								$therescon = "Low";	
							}
							elseif ($rescon == "blue") {
								$therescon = "Medium";	
							}
							elseif ($rescon == "yellow") {
								$therescon = "High";	
							}
							elseif ($rescon == "orange") {
								$therescon = "Critical";	
							}
							elseif ($rescon == "red") {
								$therescon = "Severe";	
							}
							elseif ($rescon == "") {
								$therescon = "Uncategorized";
							}
						
						$resconchange = "<font color=\"#0000ff\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Rescon Changed To: $therescon</font><br>";
					}
				}
				else {
					$resconchange = "";
				}

				# update the lastupdate so we can use for pending customers
				$lastupdate = $resconchange . $statuschange . "<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b><br>" . $progress;

				$lastupdatequery = "UPDATE tickets SET lastupdate = '$lastupdate' WHERE tcnumber = '$tcnumber';";
				$lastupdateresult = pg_query($lastupdatequery);

				$progressupdate = $resconchange . $statuschange . "<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b><br>" . $progress . "<br>" . $oldprogress;
			}

			# they didn't update the progress, so don't change it
			else {
				if ($status != $oldstatus || $status == "Reopen") {
					$statuschange = "<font color=\"#ff0000\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Status Changed To: $status</font><br>";
					# check to see if the ticket was changed to Awaiting Pickup
					# if it was, set pickupdate to 'thedate'
					if ($status == "Awaiting Pickup") {
						$statusquery = "UPDATE tickets SET pickupdate = '$thedate' WHERE tcnumber = '$tcnumber';";
						$statusresult = pg_query($statusquery);
					}
				}
				else {
					$statuschange = "";
				}
				if ($rescon != $oldrescon) {
					if ($status == "Awaiting Pickup") {
						$resconchange = "<font color=\"#0000ff\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Rescon Status Has Been Removed</font><br>";
					}
					else {
							if ($rescon == "green") {
								$therescon = "Low";	
							}
							elseif ($rescon == "blue") {
								$therescon = "Medium";	
							}
							elseif ($rescon == "yellow") {
								$therescon = "High";	
							}
							elseif ($rescon == "orange") {
								$therescon = "Critical";	
							}
							elseif ($rescon == "red") {
								$therescon = "Severe";	
							}
							elseif ($rescon == "") {
								$therescon = "Uncategorized";
							}
						$resconchange = "<font color=\"#0000ff\"<b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Rescon Changed To: $therescon</font><br>";
					}
				}
				else {
					$resconchange = "";
				}

				$progressupdate = $resconchange . $statuschange . $oldprogress;
			}

			# automatically change the values of lastupdatedby and lastupdateddate with the
			# currently logged in user and the current date
			$lastupdatedby = $PHP_AUTH_USER;
			$lastupdateddate = date("m/d/y");
				
			# if we found inventory that the user did not write down, add it here
			if ($inventoryupdate != "") {
				$query = "SELECT inventory FROM machines WHERE tcnumber = '$tcnumber';";
				$result = pg_query($query);
				while ($row = pg_fetch_assoc($result)) {
					$inventory = trim($row['inventory']);
				}
				$inventory = $inventory . ", " . $inventoryupdate;

				# update the inventory
				$query = "UPDATE machines SET inventory = '" . addslashes($inventory) . "' WHERE tcnumber = '$tcnumber';";
				$result = pg_query($query);
				if (pg_affected_rows($result) == 1) {
					$theupdates = "Inventory Has Been Updated<br>";
				}

			}


			# replace all of the line breaks in the progress update with <br> tags so it
			# is html friendly upon being viewed later
			$progressupdate = preg_replace('/[\n]/', '<br>', $progressupdate);

			#query based on being able to close or not

			if ($status == "Awaiting Pickup") {
				$rescon = "\#00CCFF";
			}

			if ($status == "Re-Queued") {
				$rescon = "\#990000";
			}

			# if someone tries to manually close the ticket, don't update the status, 
			# just update everything else
			if ($close != "yes" && $status == "Closed") {
				$query = "UPDATE tickets SET tnumber = '$tnumber', acceptedby = '$acceptedby', accepteddate = '$dateaccepted', summary = '$summary', queue = '$queue', progress = '" . addslashes($progressupdate) . "', lastupdatedby = '$lastupdatedby', hdbackuplocation = '" . addslashes($hdbackuplocation) . "', lastupdateddate = '$lastupdateddate', rescon = '$rescon', signoff = '$signoff' WHERE tcnumber = '$tcnumber';";
				
			}

			# update everything if it is all okay
			else {
				$query = "UPDATE tickets SET tnumber = '$tnumber', acceptedby = '$acceptedby', accepteddate = '$dateaccepted', summary = '$summary', queue = '$queue', status = '$status', progress = '" . addslashes($progressupdate) . "', lastupdatedby = '$lastupdatedby', hdbackuplocation = '" . addslashes($hdbackuplocation) . "', lastupdateddate = '$lastupdateddate', rescon = '$rescon', signoff = '$signoff' WHERE tcnumber = '$tcnumber';";
				
			}

			$result = pg_query($query);
			# an affected row was returned, it was updated
			if (pg_affected_rows($result) == 1) {
				$theupdates = "$theupdates Ticket Has Been Updated<br>";
				$title = " - Updated";
			}
			# error updating
			else {
				$theupdates = "$theupdates Error Updating Ticket<br>";
				$title = " - Error";
			}

			$xpquery = "SELECT username FROM experience WHERE username = '$lastupdatedby';";
			$xpresult = pg_query($xpquery);
			if (pg_num_rows($xpresult) == 0) {
				$adduser = "INSERT INTO experience (username, points) VALUES('$lastupdatedby', 0);";
				$addresult = pg_query($adduser);
			}

			if ($rescon == "green") {
				$xp = 2;
			}
			elseif ($rescon == "blue") {
				$xp = 4;
			}
			elseif ($rescon == "yellow") {
				$xp = 6;
			}
			elseif ($rescon == "orange") {
				$xp = 8;
			}
			elseif ($rescon == "red") {
				$xp = 10;
			}
			else {
				$xp = 1;
			}

			$xpquery = "SELECT username, points FROM experience WHERE username = '$lastupdatedby';";
			$xpresult = pg_query($xpquery);
			while ($row = pg_fetch_assoc($xpresult)) {
				$employee = trim($row['username']);
				$points = trim($row['points']);
			}

			$points = $points + $xp;
			$updatexp = "UPDATE experience SET points = '$points' WHERE username = '$employee';";
			$updateresult = pg_query($updatexp);

			#update current tasks
			
			# the tasks are stored as an array. HTML only passes in the ones that have a
			# true value in the form (they are checked). Go through and update all of the
			# returned tasks with the latest dates of completion.
			if (isset($task)) {
				foreach ($task as $taskid) {

					$setdate = date("m/d/y");
					$query = "UPDATE link SET date = '$setdate' WHERE ticket = '$tcnumber' AND task = '$taskid';";
					$result = pg_query($query);
				}
			}

		# update user's update stats
	
		$udquery = "SELECT updates FROM experience WHERE username = '$resuser' AND updates <> '';";
		$udresult = pg_query($udquery);
		# if there is a row for the user and there is no updates yet, just add it
		if (pg_num_rows($udresult) == 0) {
			$updateud = "UPDATE experience SET updates = '$tcnumber!1' WHERE username = '$resuser';";
			pg_query($updateud);
		}
		# there are updates already, which means we have work to do
		else {
			while ($row = pg_fetch_assoc($udresult)) {
				$updates = trim($row['updates']);
				
				# first we need to see if the ticket has been updated by this user
				# or not. If it hasn't, then just append the ticket to the end.
				# Otherwise, we need to loop through the array to find the ticket,
				# then add one to it's amount of updates for the day

				# strpos finds and returns the position of the found string
				# if it does not find it, it returns a boolean representation
				# of false
				$found = strpos($updates, $tcnumber);

				# if the string wasn't found, just append the information
				if ($found === false) {
					$theupdateud = "$updates,$tcnumber!1";
				}
				# the string was found, now we have to extract it
				else {
					$i = 0;
					$udarray = explode(',', $updates);
					foreach ($udarray as $theupdate) {
						# this one isn't the ticket we want
						if (strpos($theupdate, $tcnumber) === false) {
							$i++;
						}
						# this is the one we want, explode it and increment
						else {
							list($exticket, $exnum) = explode('!', $theupdate);
							$exnum++;
						  #impode the two strings
							$imptwo = "$exticket!$exnum";
							$udarray[$i] = $imptwo;
							$i++;
						}
					}
					$theupdateud = implode(',',$udarray);
				}

			$udreturnud = "UPDATE experience SET updates = '$theupdateud' WHERE username = '$resuser';";
			pg_query($udreturnud);

			}
		}
		}
		# they have not accepted the ticket but asked for the ticket to be updated
		else {
			if ($update == "yes" && $dateaccepted == "" && $acceptedby == "") {
				$theupdates = "Please ensure you have accepted this ticket, filled in the date, and your initials<br>";
				$title = " - Error";
			}
		}

		# check to see if the ticket # was entered
		if ($tcnumber != "") {
			# get information about the machine at hand
			$query = "SELECT mid, inventory, description, username, password, warranty FROM machines WHERE lower(tcnumber) = lower('$tcnumber');";		
			$result = pg_query($query);
			while ($row = pg_fetch_assoc($result)) {
				$mid = trim($row['mid']);
				$inventory = trim($row['inventory']);
				$machinedesc = trim($row['description']);
				$accountname = trim($row['username']);
				$passwd = trim($row['password']);
				$warranty = trim($row['warranty']);
			}
			# get information about the ticket at hand
			$query = "SELECT * FROM tickets WHERE lower(tcnumber) = lower('$tcnumber') ORDER BY date ASC;";
		}
	# if the ticket # was not supplied, error
	if ($tcnumber == "") {
		echo "Please enter a ticket to search for."; 
	}
	# the ticket # was supplied, show the ticket
	else {
	if ($tcnumber != "") {
		$queryresult = "Ticket #: $tcnumber"; 

	}
	$result = pg_query($query);
	# if no data was returned, show an error
	if ( pg_num_rows($result) == 0 ) {
		echo "There are no results for your query.";
	}
	# the ticket was found, let's format and show it!
	else { 

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
	$pickupdate = trim($row['pickupdate']);
	$rescon = trim($row['rescon']);
	$signoff = trim($row['signoff']);
	$repairinstall = trim($row['repairinstall']);
	$fullinstall = trim($row['fullinstall']);
	
	$title = "$tcnumber$title";
  include("../inc/header2.php");	

	?>
<!-- Two fields a the top of the page to query by -->
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
	<? if ($theupdates !="") {?>
		<center><div style="width:200px; height:auto; background-color:#eeeeee;color:#ff0000; border: 1px solid #000000;"><b><?echo $theupdates;?></b> </div></center><br>
	<? }?>
<!-- End Query Fields -->
		<h3><? echo $queryresult; ?></h3><hr width="35%"><br>
		<center><div style="width:50%; height:auto; background-color:#eeeeee;color:#ff0000;"><b>REMEMBER:</b> users will receive a receipt of work for their problem. They will be able to read everything you write. Please be professional when updating tickets, but remember to document <b>everything</b></div></center><br>
		<table width="70%" valign="top">
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
					<form enctype="multipart/form-data" method="post" action="queryuser.php" target="new">
						<input type="hidden" name="referrer" value="queryuser">
						<input type="hidden" name="uname" value="<?echo $username;?>">
						<input type="submit" name="submit" value="User Information">
					</form>
					<form enctype="multipart/form-data" method="post" action="editmachines.php">
						<input type="hidden" name="referrer" value="admin">
						<input type="hidden" name="midnumber" value="<?echo $mid;?>">
						<input type="submit" name="submit" value="Machine Information">
					</form>
					<br>
				</td>
			</tr>
			<form enctype="multipart/form-data" method="post" action="tickets.php">
			<input type="hidden" name="update" value="yes">
			<tr>
				<td valign="bottom">Username: </td><td valign="top"><? echo "<a href=\"https://ipedit.rit.edu/search.php?whichsearch=dhcp&username=$username&method=detail\">$username</a>"; ?></td>
			</tr>
			<tr>
				<td valign="top">Ticket #: </td><td valign="top"><? echo $tcnumber; ?>&nbsp; <a href="../barcode/barcode.php?code=<?echo $tcnumber;?>&scale=1&bar=ANY"><img src="../images/bc.jpg" border="0"></a></td>
			</tr>
			<tr>
				<td valign="top">RIT Ticket #: </td><td valign="top"><input type="text" name="tnumber" size = "50" value="<? echo $tnumber; ?>"></td>
			</tr>
			<tr>
				<td valign="top">Date Of Creation: </td><td valign="top"><? echo $date; ?></td>
			</tr>
			<tr>
				<td valign="top">Date Accepted: </td><td valign="top"><input type="text" name="dateaccepted" size="50" value="<? 
					if ($accepteddate != "") {echo $accepteddate;} else { echo date('m/d/y'); } ?>"> [mm/dd/yy]</td>
			</tr>
			<tr>
				<td valign="top">Accepted By: </td><td valign="top"><input type="text" name="acceptedby" size="50" maxlength="3" value="<? echo $acceptedby; ?>"> [initials]</td>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<td valign="top">Machine Description:</td><td valign="top"><? echo $machinedesc; ?></td>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<td valign="top">Machine Under Warranty:</td><td valign="top"><? echo $warranty; ?></td>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<td valign="top">Repair / Full Install:</td><td valign="top">
				<? if ($repairinstall == "t") { echo "Yes";} else {echo "No";}?>&nbsp;/&nbsp;<?if ($fullinstall == "t") { echo "Yes";} else {echo "No";}?><br>
				<? if ($repairinstall == "f") {echo "Repair Permission: ";?> <input type="checkbox" name="repairinstall"><br><? } 
				if ($fullinstall == "f") { echo "Full Install Permission: ";?> <input type="checkbox" name="fullinstall"><br><? } ?>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<td valign="top">Login Account:</td><td valign="top"><? echo $accountname; ?></td>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<td valign="top">Account Password:</td><td valign="top"><? echo $passwd; ?></td>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<td valign="top">Problem Description:</td><td valign="top"><? echo $description; ?></td>
			</tr>
			<tr>
				<td colspan="2"><br></td>
			</tr>
			<tr>
				<!--<td valign="top">Summary: </td><td valign="top"><input type="text" name="summary" size="50" maxlength="200" value="<? #echo preg_replace('@<[\/\!]*?[^<>]*?>@si', '', $summary); ?>"> [brief]</td> -->
				<td valign="top">Summary: </td><td valign="top"><input type="text" name="summary" size="50" maxlength="200" value="<? echo $summary; ?>"> [brief]</td>
			</tr>
			<tr>
				<td valign="top">Rescon Scale: </td><td valign="top">
					<table>
						<tr>
							<td bgcolor="#000000" align="center">
								<input type="radio" name="rescon" value=""
								<? if ( $rescon == "" ) { echo " checked"; }?>><font color="#ffffff"> Uncategorized</font>
								<input type="radio" name="rescon" value="green"
 								<? if ( $rescon == "green" ) { echo " checked"; }?>> <font color="green">Low</font>
								<input type="radio" name="rescon" value="blue"
 								<? if ( $rescon == "blue" ) { echo " checked"; }?>> <font color="blue">Medium</font>
								<input type="radio" name="rescon" value="yellow"
 								<? if ( $rescon == "yellow" ) { echo " checked"; }?>> <font color="yellow">High</font><br />
								<input type="radio" name="rescon" value="orange"
 								<? if ( $rescon == "orange" ) { echo " checked"; }?>> <font color="orange">Critical</font>
								<input type="radio" name="rescon" value="red"
 								<? if ( $rescon == "red" ) { echo " checked"; }?>> <font color="red">Severe</font>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top">Inventory: </td><td valign="top"><? echo stripslashes($inventory); ?></td>
			</tr>
			<tr>
				<td valign="top" align="right">Inventory Updates: </td><td valign="top">				<input type="text" name="inventoryupdate" size="50" value="">
				</td>
			</tr>
			<tr>
				<td valign="top">Queue: </td><td valign="top"><select name="queue" size="1"><? 

				# get all of the queues and put them in a select box
				$query = "SELECT name, enabled from queues ORDER BY name ASC;";
				$result = pg_query($query);
				while ($row = pg_fetch_assoc($result)) {
					$queuename = trim($row['name']);
					$enabled = trim($row['enabled']);

					# if the queue is the one the ticket is in, show it by default
					if ($queue == $queuename) {
						echo "<option selected>$queuename</option>\n";
					}
					else {
						echo "<option>$queuename</option>\n";
					}
				}
			?>
				</select>
				</td>
			</tr>
			<tr>
				<td valign="top">Status: </td><td valign="top"><select name="status" size="1"><? 

				# get a list of statuses and put them in a select box
				$query = "SELECT name from status WHERE enabled = 'true' ORDER BY name ASC;";
				$result = pg_query($query);
				while ($row = pg_fetch_assoc($result)) {
					$statusname = trim($row['name']);

					# do not show "Closed" status as an option unless the ticket
					# is closed
					if ( $status != "Closed" && $statusname != "Closed") {
						# if the current status is the same as the one the ticket currently
						# is, show it by default
						if ($status == $statusname) {
							echo "<option selected>$statusname</option>\n";
						}
						else {
							echo "<option>$statusname</option>\n";
						}
					}
					if ( $status == "Closed" && $statusname == "Closed") {
							echo "<option selected>$statusname</option>\n";
					}
				}
			?>
				</select></td>
			</tr>
			<? if ($pickupdate != "") {
			?>
			<tr>
				<td valign="top">Awaiting Pickup Since: </td><td valign="top"><? echo $pickupdate; ?></td>
			</tr>
			<? } ?>
			<? if ($closeddate != "") {
			?>
			<tr>
				<td valign="top">Date Closed: </td><td valign="top"><? echo $closeddate; ?></td>
			</tr>
			<? } ?>
			<tr>
				<td valign="top">Senior Signoff: </td><td valign="top"><input type="text" name="signoff" maxlength="3" value="<?echo $signoff; ?>">&nbsp; [Initials of Who Signed Off]</td>
			</tr>
			<tr>
				<td valign="top"><b>Update Progress: </td><td><textarea rows="10" cols="67" name="progress" wrap="hard"><? echo "\n\n\nTo Do:\n"; ?></textarea></td>
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
				<td valign="top">Hard Drive Backup Location: </td><td valign="top"><input type="text" name="hdbackuplocation" size="50" value="<? echo $hdbackuplocation; ?>"></td>
			</tr>
			<tr>
				<td valign="top" colspan="2">
				<hr width="80%">
				<br>
				<b><u>Common Tasks:</u></b><br><br>
					"<font color="#ff0000">*</font>" = Required<br><br>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table width="600px" cellpadding="0px" cellspacing="0px">
				<?
					# get a list of the tasks for this ticket. The checkboxes are not checked
					# if they have been done already to allow for re-updating upon 
					# completing a task more than once. Also, show the dates that the tasks
					# were completed
					$query = "SELECT tasks.name AS name, tasks.required AS required, link.task AS task, link.date AS date FROM tasks, link WHERE tasks.enabled = 'TRUE' AND link.ticket = '$tcnumber' AND tasks.taskid = link.task ORDER BY tasks.required DESC, taskid ASC;";
					$result = pg_query($query);
					# the color alternates the background so it is easier to read
					$color = 2;
					while ($row = pg_fetch_assoc($result)) {

						$taskname = trim($row['name']);
						$taskrequired = trim($row['required']);
						$taskid = trim($row['task']);
						$datecompleted = trim($row['date']);

						# change the background color
						if ($color %2 == 0) {
							echo "<tr bgcolor=\"#cccccc\">";
							$color = 1;
						}
						else {
							echo "<tr bgcolor=\"#eeeeee\">";
							$color = 2;
						}
						?>
							<td valign="center" width="300px">
						<?
						if ($taskrequired == "t") {?>
							<font color="#ff0000">*</font>
						<? } 
							echo $taskname;
						?>
							</td>
							<td valign="center" width="50px" padding="0px">
								<input type="checkbox" name="task[]" value="<? echo $taskid; ?>">&nbsp;</td>
							<td valign="center" align="right" width="250px">Date Completed:&nbsp;<? echo $datecompleted; ?>
							</td>
						</tr>
					<?
					}
					?></table><?
	 		} 
		}	
	}
	?>
	<tr>
		<td colspan="2" align="right">
		<hr width="80%"><br>
		<table>
			<tr>
			<?
				# if the status isn't closed, then only show the update button right now
				if ($status != "Closed") {
				?>
				<td>
					<input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
					<input type="hidden" name="referrer" value="tickets">
					<input type="hidden" name="update" value="yes">
					<input type="submit" name="submit" value="Update">
			</form>
			</td>
			<?
				}
				# if the status is closed, show a ReOpen, and a Print Receipt button 
				if ($status == "Closed") { ?>
				<td>
					<form enctype="multipart/form-data" method="post" action="tickets.php">
					<input type="hidden" name="reopen" value="yes">
					<input type="hidden" name="referrer" value="tickets">
					<input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
					<input type="submit" name="submit" value="ReOpen Ticket">
					</form></td>
				<td>
					<form enctype="multipart/form-data" method="post" action="receipt.php">
					<input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
					<input type="submit" name="submit" value="Print Receipt">
					</form>
				</td>
				<?
				}
				# this checks all of the tasks that are required to close a ticket.
				# if all of them are not fulfilled, it will not allow the "Close Ticket" 
				# button to be displayed. Once all of the required tasks are met, the
				# Close Ticket button will be shown
				$isrequired = "TRUE";
				$query = "SELECT link.date AS date FROM link, tasks WHERE link.ticket = '$tcnumber' AND tasks.required = '$isrequired' AND link.task = tasks.taskid;";
				$result = pg_query($query);
				$closable = "yes";
				while ($row = pg_fetch_assoc($result)) {

					$hasdate = trim($row['date']);
				
					if ($hasdate == "") {
						$closable = "no";
					}
				}
				$query = "SELECT link.date AS date FROM link, tasks WHERE link.ticket = '$tcnumber' AND tasks.name = 'Close Ticket With Machine Unfinished' AND link.task = tasks.taskid;";
				$result = pg_query($query);
				while ($row = pg_fetch_assoc($result)) {
					$hasdate = trim($row['date']);

					if ($hasdate != "") {
						$closable = "yes";
					}
				}
				# if we made it out of the check and it can be closed, show the button
				if ($closable == "yes" && $status != "Closed") { ?>
				<td>
					<form enctype="multipart/form-data" method="post" action="tickets.php">
					<input type="hidden" name="close" value="yes">
					<input type="hidden" name="referrer" value="tickets">
					<input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
					<input type="submit" name="submit" value="Close Ticket">
					</form></td>
				<?
				}
				?>
			</tr>
		</table>
			</td>
		</tr>
	</table><?
	}
	?>

<? 
# close the database connection
pg_close($db_connect) ?>
