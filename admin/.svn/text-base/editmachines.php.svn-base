<?
	include("../inc/connection.inc");
 	include("../inc/header2.php"); ?>

<? 

	# instantiation of key variables
	# set certain ones to null to overwrite url injections
	$queryuser = $PHP_AUTH_USER; 
	$submittal = "";
	$delete = "";
	$olddescription = "";
	$referrer = "";

	# set global veriables for access inside the functions
	global $do_submit;
	global $successful;

	# extract the data sent to this page
	@extract($_POST);

	# check to see if the individual using this page is an admin
	if ($referrer == "admin") {

		# set the type of field to query to be the machine ID
		$type = "mid";
		# set the value to query for to the MID of the machine being edited
		$queryvalue = $midnumber;

	}
	# if they are not an admin
	else {
		
		# set the type to "owner" and the value to be the current logged in user
		$type = "owner";
		$queryvalue = $queryuser;

	}

	# if they have edited a machine and submitted for modification
	if ($submittal == "yes") {
		
?>	
	<table width="80%"
	<tr>
		<td align="center">
			<div style="background-color: eee;
								  border-style: solid;
									border-width: 1px;
									width: 50%;
									height: auto;
									font-size: 10pt;
									color: #ff0000;
									padding: 10px;
									text-align: left;"><center><font size="2"><b>Results:</b></font><hr>
<?

		#first let us check to see if they want the machine deleted

		if ($delete != "") {
			
			#delete the machine entry

				#set the query to delete the machine based on the machine MID
				$query = "DELETE FROM machines WHERE mid = '$mid';";

				$result = pg_query($query);
				
				#check for an affected row from the query
				if (pg_affected_rows($result) == 1) {
					
					?>The machine has been deleted. Click <a href="request.php">here</a> to return to the request page for computer assistance.</center></div></td></tr></table>
					<?
				
				#set successful to yes so we know this has worked
				$successful = "yes";
				}

				#there was an error with the query because no rows were affected
				else {
					?>No machine was found to delete. Click <a href="request.php">here</a> to return to the request page for computer assistance.</center></div></td></tr></table>
					<?
				}
		}

		#the machine was not slated to be deleted, so let us update it
		else {

			# throw the information into the verify function to ensure proper values
			verify($snumber, $mac, $description, $accountname, $passwd, $olddescription, $queryuser, $mid);

		}	

}
	
?>


<!-- Main Page -->
	<table width="80%" border="0" align="center">
		<tr>
			<td align="center"><b>Edit Machines</b>
				<hr width="60%"></td>
		</tr>
	</table>
	<br>
	<table width="80%" border="0" align="center">
		<tr>
			<td colspan="2">
					<?
				
					# if they have not submitted information, show them the initial page
					if ($submittal != "yes") {
					 ?>
				Please select, from the list of machines below, the one that you would like to modify (this will most likely be the machine you wish to be serviced by Resnet, or a machine that you would like deleted from your machine list):
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br><br>
			</td>
		</tr>
				<?

					# there is a chance that the user has more than one machine on record
					# run through the database and print out information for each machine

					if ($referrer == 'admin') {
						$query = "SELECT * FROM machines WHERE $type = $queryvalue;";
					}
					else {
						$query = "SELECT * FROM machines WHERE $type = '$queryvalue';";
					}
					
					$result = pg_query($query);

					if (pg_num_rows($result) > 0) {
						while ($row = pg_fetch_assoc($result)) {

							$mid = trim($row['mid']);
							$snumber = trim($row['snumber']);
							$mac = trim($row['mac']);
							$description = trim($row['description']);
							$accountname = trim($row['username']);
							$passwd = trim($row['password']);
							
							?>
							
							<!-- The Form For A Machine -->
							<form enctype="multipart/form-data" method="post" action="editmachines.php">
							<tr>
								<td>
									<b>Description:</b>
								</td>
								<td align="right">
									<input type="text" name="description" size="50" maxlength="200" value="<? echo $description; ?>">
									<input type="hidden" name="olddescription" value="<? echo $description; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Account To Log In With:
								</td>
								<td align="right">
									<input type="text" name="accountname" size="50" maxlength="50" value="<? echo $accountname; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Password To Log In With:
								</td>
								<td align="right">
									<input type="text" name="passwd" size="50" maxlength="50" value="<? echo $passwd; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Serial/Service Tag #:
								</td>
								<td align="right">
									<input type="text" name="snumber" size="50" maxlength="50" value="<? echo $snumber; ?>">
								</td>
							</tr>
							<tr>
								<td>
									MAC Address:
								</td>
								<td align="right">
									<input type="text" name="mac" size="50" maxlength="50" value="<? echo $mac; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Would you like to delete this entry?
								</td>
								<td align="right">
									Yes:&nbsp;<input type="checkbox" name="delete">
								</td>									
							</tr>
							<tr>
								<td>
									<br><br>
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right">
									<input type="hidden" name="mid" value="<? echo $mid; ?>">
									<input type="hidden" name="submittal" value="yes">
									<input type="submit" name="Submit" value="Submit">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<hr width="100%">
								</td>
							</tr>
							</form>
							<!-- End Form For Machine -->
				<?
						}
					}

					# no machines were found when the query was attempted for this user
					else {
					?>
						<tr>
							<td>
								We were unable to find any machines stored in your name
							</td>
						</tr>
					<?
					}

				}

				# the user submitted data, and these are the results
				else {
				?>
				The following changes were submitted:
			</td>
		</tr>

							<form enctype="multipart/form-data" method="post" action="editmachines.php">
							<tr>
								<td>
									<b>Description:</b>
								</td>
								<td align="right">
									<input type="text" name="description" size="50" maxlength="200" value="<? echo $description; ?>">
									<input type="hidden" name="olddescription" value="<? echo $olddescription; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Account To Log In With:
								</td>
								<td align="right">
									<input type="text" name="accountname" size="50" maxlength="50" value="<? echo $accountname; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Password To Log In With:
								</td>
								<td align="right">
									<input type="text" name="passwd" size="50" maxlength="50" value="<? echo $passwd; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Serial/Service Tag #:
								</td>
								<td align="right">
									<input type="text" name="snumber" size="50" maxlength="50" value="<? echo $snumber; ?>">
								</td>
							</tr>
							<tr>
								<td>
									MAC Address:
								</td>
								<td align="right">
									<input type="text" name="mac" size="50" maxlength="50" value="<? echo $mac; ?>">
								</td>
							</tr>
							<tr>
								<td>
									Would you like to delete this entry?
								</td>
								<td align="right">
									Yes:&nbsp;<input type="checkbox" name="delete">
								</td>									
							</tr>
							<tr>
								<td>
									<br><br>
								</td>
							</tr>
							<tr>
								<td><? echo $successful; ?></td>
							<?

							?>
								<td align="right">
									<input type="hidden" name="mid" value="<? echo $mid; ?>">
									<input type="hidden" name="submittal" value="yes">
									<input type="submit" name="Submit" value="Submit">
								</td>
							</tr>
							</form>
				<?
				}
					?>

</body>
</html>



<?

# the verify function takes in the data to be submitted and make sure it is 
# properly formatted to be submitted to the database

# do_submit is the variable that will be checked before submitting. If it's
# value is "no" then the data will not be submitted and an error will be
# returned to the user.

function verify($snumber, $mac, $description, $accountname, $passwd, $olddescription, $queryuser, $mid) {

	#initialize submit variable
	$do_submit = "yes";

	#verify description
	$okay = verifydescription($description);
	if ($okay == "FALSE") {
		echo "Please provide a valid computer description<br>";
		$do_submit = "no";
	}
	#verify snumber
	$okay = verifysnumber($snumber);
	if ($okay == "FALSE" && $snumber != "n/a") {
		echo "Please provide a valid serial/service tag number<br>";
		$do_submit = "no";
	}
	#verify mac
	$okay = verifymac($mac);
	if ($okay == "FALSE" && $mac != "") {
		echo "Please provide a valid MAC address<br>";
		$do_submit = "no";
	}
	#verify account name 
	$okay = verifyaccountname($accountname);
	if ($okay == "FALSE" && $accountname != "n/a") {
		echo "Please provide a valid user account for your machine<br>";
		$do_submit = "no";
	}
	#verify account password 
	$okay = verifypasswd($passwd);
	if ($okay == "FALSE" && $passwd != "n/a") {
		echo "Please provide a valid password for your user account<br>";
		$do_submit = "no";
	}

	#if the submit variable is still good, we can submit the verified data
	if ($do_submit == "yes") {

			# set up the query to update the machine information

			if ($mac != "") {
				$query = "UPDATE machines SET snumber = '$snumber', mac = '$mac', description = '$description', username = '$accountname', password = '$passwd' WHERE mid = '$mid';";

			}
			else {
				$query = "UPDATE machines SET snumber = '$snumber', description = '$description', username = '$accountname', password = '$passwd' WHERE mid = '$mid';";

			}
			$result = pg_query($query);

			# if there was an affected row, we have updated the machine
			if (pg_affected_rows($result) == 1) {

					?><p>The machine has been updated. Click <a href="request.php">here</a> to return to the request page for computer assistance.</p></center></div></td></tr></table>
					<?
			$successful = "yes";
			}
			# there was an error finding the machine to update
			else {
					?><p>No machine was found to update. Click <a href="request.php">here</a> to return to the request page for computer assistance.</p></center></div></td></tr></table>
					<?
			}
	}
	# if do_submit is no, this will close the error box that the errors are in
	else {
		echo "</div></td></tr></table>";
	}


}

#verify description
function verifydescription($description) {

					# the description can contain only letters A-Z, a-z, 0-9, ',', and '-'
					$pattern = "/^[A-z0-9 \,-]*$/";
					return preg_match($pattern,$description);

}

#verify serial/service tag #
function verifysnumber($snumber) {

					# the snumber can contain only letters A-Z, a-z, 0-9, and '-'
					$pattern = "/^[A-z0-9-]*$/";
					return preg_match($pattern,$snumber);

}

#verify mac address 
function verifymac($mac) {

					# the mac must have 6 sets of 2 numbers and/or letters seperated by ':' or '-'
					$pattern = "/^([A-Fa-f0-9]{1,2}[:-]){5}[A-Fa-f0-9]{1,2}$/";
					return preg_match($pattern,$mac);

}

#verify account name
function verifyaccountname($accountname) {

					# the accountname can contain only letters A-Z, a-z, 0-9, and '-'
					$pattern = "/^[A-z0-9 -]*$/";
					return preg_match($pattern,$accountname);

}

#verify account password
function verifypasswd($passwd) {

					# the password match would go here. Currently, this is not checked
					#$pattern = "/^$/";
					#return preg_match($pattern,$accountname);

}


?>

<? 
# close the database connection
pg_close($db_connect); ?>
