<? 
	include("../inc/connection.inc"); 
  include("../inc/header2.php");	
?>

<?

	# extract the data that was submitted

	@extract($_POST);

	# if the referrer is set correctly
	if ($referrer == "querymachine") {

	# they have chosen to search for a machine based on MAC address
	# check to see if the MAC of the machine is in fact properly formatted
	if ($macaddr != "") {

		# the mac must have 6 sets of 2 numbers and/or letters divided by ':' or '-'
		$okay = preg_match("/^([A-Fa-f0-9]{1,2}[:-]){5}[A-fa-f0-9]{1,2}$/", $macaddr);
		# if the MAC passed, query based upon it
		if ($okay != "FALSE"){
			$query = "SELECT * FROM machines WHERE mac = '$macaddr';";
		}
		# need a valid mac address
		else {
			echo "Please enter a valid MAC address";
			$query = "no";
		}
	}

	# they have decided to search by owner of the machine
	elseif ($owner != "") {
		$query = "SELECT * FROM machines WHERE lower(owner) LIKE lower('%$owner%');";
	}
	# they have decided to search for a machine by service tag
	elseif ($stag != "") {
		$query = "SELECT * FROM machines WHERE lower(snumber) = lower('$stag');";
	}
	# they have decided to search for a machine by ticket # it is connected to
	elseif ($tcnumber != "") {
		$query = "SELECT * FROM machines WHERE lower(tcnumber) = lower('$tcnumber');";
	}
	?>
	<?

	# they haven't decided to search by anything
	if ($macaddr == "" & $owner == "" & $stag == "" & $tcnumber == "") {
			?>	<h3>Query For A Machine:</h3><hr width="40%"> <?
		echo "Please enter correct information in the field of your choosing below";
		
	?>	
	<br><br>
	<!-- The search fields -->
	<table>
		<tr>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				 MAC Address to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="macaddr">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		</tr>
		<tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				 Owner's Username to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="owner">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				 Service Tag/Serial # to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="stag">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				Ticket # to query: 
			</td>
			<td>
				<input type="text" size="20" value="" name="tcnumber">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		</tr>
	</table>
	
	<? }

	# they have submitted data already and we must show it to them
	else {

	# set up the banner to match what they searched for
	if ($macaddr != "") {
		$queryresult = "Query Result For MAC Address: $mac"; 
	}
	elseif ($owner != "") {
		$queryresult = "Query Result For Owner Name: $owner"; 
	}
	elseif ($stag != "") {
		$queryresult = "Query Result For Service Tag/Serial # Of: $snumber"; 
	}
	elseif ($tcnumber != "") {
		$queryresult = "Query Result For Machine In Ticket #: $tcnumber"; 
	}
	?>	
		<h3><? echo $queryresult; ?></h3><br><table>
	<?
	# if we are supposed to query, then query
	if ($query != "no") {
		$result = pg_query($query);
		# if we get results from the database
		if ( pg_num_rows($result) == 0 ) {
			echo "There are no results for your query.";
		}

	while ($row = pg_fetch_assoc($result)) {

	$mid = trim($row['mid']);
	$snumber = trim($row['snumber']);
	$inventory = trim($row['inventory']);
	$mac = trim($row['mac']);
	$description = trim($row['description']);
	$username = trim($row['username']);
	$password = trim($row['password']);
	$owner = trim($row['owner']);
	$tcnumber = trim($row['tcnumber']);

	?>
			<tr>
				<td>Machine Identification Number:</td><td><? echo $mid; ?></td>
			</tr>
			<tr>
				<td>Service Tag/Serial #:</td><td><? echo $snumber; ?></td>
			</tr>
			<tr>
				<td>Inventory:</td><td><? echo $inventory; ?></td>
			</tr>
			<tr>
				<td>MAC Address:</td><td><? echo $mac; ?></td>
			</tr>
			<tr>
				<td>Description:</td><td><? echo $description; ?></td>
			</tr>
				<td>Machine account username:</td><td><? echo $username; ?></td>
			</tr>
			<tr>
				<td>Machine account password:</td><td><? echo $password; ?></td>
			</tr>
			<tr>
				<td>Machine Owner's username:</td><td><? echo $owner; ?>&nbsp;<a href="../barcode/barcode.php?code=<?echo $owner;?>&scale=1&bar=ANY"><img src="../images/bc.jpg" border="0"></a></td>
			</tr>
			<tr>
				<td>Machine's current Ticket #:</td><td><? echo $tcnumber; ?></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
				<!-- Form To Edit The Machine If They Want -->
				<form enctype="multipart/form-data" method="post" action="editmachines.php"><input type="hidden" name="referrer" value="admin"><input type="hidden" name="midnumber" value="<? echo $mid; ?>"><input type="submit" name="Submit" value="Edit Machine"></form></td>
			</tr>
			<tr>
				<td colspan="2"><br><hr><br></td>
			</tr>
	<? } 
	}	
	}
	}
	
	# show the form to query with
	else { 
	
	?>
				<h3>Query For A Machine:</h3><hr width="40%">
	<table>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				 MAC Address to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="macaddr">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		</tr>
		<tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				 Owner's Username to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="owner">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				 Service Tag/Serial # to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="stag">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="querymachine.php" method="post">
				Ticket # to query: 
			</td>
			<td>
				<input type="text" size="20" value="" name="tcnumber">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="querymachine">
				</form>
			</td>
		</tr>
	</table>
	
	<? } ?>

	</table>

<? 
# close the database connection
pg_close($db_connect) ?>
