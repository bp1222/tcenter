<?
	include("../inc/connection.inc");
 	include("../inc/header2.php"); ?>

<? 

	# instantiation of key variables
	# set certain ones to null to overwrite url injections
	$submittal = "";
	$referrer = "";

	# set global veriables for access inside the functions
	global $do_submit;
	global $successful;

	# extract the data sent to this page
	@extract($_POST);

	# check to see if the individual using this page is an admin
	if ($referrer == "admin") {

		# set the type of field to query to be the username 
		$type = "username";
		# set the value to query for to the MID of the machine being edited
		$queryvalue = $username;

	}

	# if they have edited the user info  and submitted for modification
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

			# throw the information into the verify function to ensure proper values
			verify($name, $username, $phone, $address, $email);

		}	

	
?>


<!-- Main Page -->
	<table width="80%" border="0" align="center">
		<tr>
			<td align="center"><b>Edit User Information</b>
				<hr width="60%"></td>
		</tr>
	</table>
	<br>
	<table width="60%" border="0" align="center">
		<tr>
			<td colspan="2" align="center">
					<?
				
					# if they have not submitted information, show them the initial page
					if ($submittal != "yes") {
					 ?>
					 Modify the user information below and click "submit" when you are finished.
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br><br>
			</td>
		</tr>
				<?

					$query = "SELECT * FROM users WHERE $type = '$queryvalue';";
					
					$result = pg_query($query);

					if (pg_num_rows($result) > 0) {
						while ($row = pg_fetch_assoc($result)) {

							$name = trim($row['name']);
							$username = trim($row['username']);
							$phone = trim($row['phone']);
							$address = trim($row['address']);
							$email = trim($row['email']);
							
							?>
							
							<!-- The Form For The User Information -->
							<form enctype="multipart/form-data" method="post" action="editusers.php">
							<tr>
								<td>
									<b>Name:</b>
								</td>
								<td align="right">
									<input type="text" name="name" size="50" maxlength="200" value="<? echo $name; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Username:</b>	
								</td>
								<td align="right">
									<input disabled type="text" name="username" size="50" maxlength="50" value="<? echo $username; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Phone Number:</b>
								</td>
								<td align="right">
									<input type="text" name="phone" size="50" maxlength="50" value="<? echo $phone; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Address:</b>
								</td>
								<td align="right">
									<input type="text" name="address" size="50" maxlength="50" value="<? echo $address; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Email:</b>
								</td>
								<td align="right">
									<input type="text" name="email" size="50" maxlength="50" value="<? echo $email; ?>">
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right">
									<input type="hidden" name="username" value="<?echo $username; ?>">
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
							<!-- End Form For User Information -->
				<?
						}
					}

					# no entries were found when the query was attempted for this user
					else {
					?>
						<tr>
							<td>
								We were unable to find any user information for <? echo $username; ?> 
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

							<form enctype="multipart/form-data" method="post" action="editusers.php">
							<tr>
								<td>
									<b>Name:</b>
								</td>
								<td align="right">
									<input type="text" name="name" size="50" maxlength="200" value="<? echo $name; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Username:</b>	
								</td>
								<td align="right">
									<input disabled type="text" name="username" size="50" maxlength="50" value="<? echo $username; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Phone Number:</b>
								</td>
								<td align="right">
									<input type="text" name="phone" size="50" maxlength="50" value="<? echo $phone; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Address:</b>
								</td>
								<td align="right">
									<input type="text" name="address" size="50" maxlength="50" value="<? echo $address; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<b>Email:</b>
								</td>
								<td align="right">
									<input type="text" name="email" size="50" maxlength="50" value="<? echo $email; ?>">
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right">
									<input type="hidden" name="username" value="<?echo $username;?>">
									<input type="hidden" name="submittal" value="yes">
									<input type="submit" name="Submit" value="Submit">
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<hr width="100%">
								</td>
							</tr>
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

function verify($name, $username, $phone, $address, $email) {

	#initialize submit variable
	$do_submit = "yes";

	#verify name
	$okay = verifyname($name);
	if ($okay == "FALSE") {
		echo "Please provide a valid name<br>";
		$do_submit = "no";
	}
	#verify username
	$okay = verifyusername($username);
	if ($okay == "FALSE") {
		echo "Please provide a valid useraname<br>";
		$do_submit = "no";
	}
	#verify phone number
	$okay = verifyphone($phone);
	if ($okay == "FALSE" && $phone != "") {
		echo "Please provide a valid phone number<br>";
		$do_submit = "no";
	}
	#verify address
	$okay = verifyaddress($address);
	if ($okay == "FALSE") {
		echo "Please provide a valid address<br>";
		$do_submit = "no";
	}
	#verify email
	$okay = verifyemail($email);
	if ($okay == "FALSE") {
		echo "Please provide a valid email address<br>";
		$do_submit = "no";
	}

	#if the submit variable is still good, we can submit the verified data
	if ($do_submit == "yes") {

			# set up the query to update the machine information

			if ($phone != "") {
				$query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone', address = '$address' WHERE username = '$username';";
			}
			else {
				$query = "UPDATE users SET name = '$name', email = '$email', address = '$address' WHERE username = '$username';";
			}
			$result = pg_query($query);

			# if there was an affected row, we have updated the machine
			if (pg_affected_rows($result) == 1) {

					?><p>The user information has been updated.</p></center></div></td></tr></table>
					<?
			$successful = "yes";
			}
			# there was an error finding the machine to update
			else {
					?><p>No user was found to update.</p></center></div></td></tr></table>
					<?
			}
	}
	# if do_submit is no, this will close the error box that the errors are in
	else {
		echo "</div></td></tr></table>";
	}


}

#verify name
function verifyname($name) {

					# the name can only contain A-Z, a-z, ''' and '_' and must be First and Last name
					# and as many middle names as they have
					$pattern = "/^([A-z\'-]+ ){1,}([A-z\'-]+)$/";
					return preg_match($pattern,$name);

}

#verify username 
function verifyusername($username) {

					# the username can contain only letters A-Z, a-z, 0-9
					$pattern = "/^[^ ][A-z0-9]*$/";
					return preg_match($pattern,$username);

}

#verify phone
function verifyphone($phone) {

					# the phone must be in the form XXX-XXX-XXXX
					$pattern = "/^[^ ][0-9 \(\)\.-]*$/";
					return preg_match($pattern,$phone);

}

#verify account name
function verifyaddress($address) {

					# the address can only contain letters A-Z, a-z, 0-9, '#', '.', and '_'
					$pattern = "/^[^ ][A-z0-9 #\.-]*$/";
					return preg_match($pattern,$address);

}

#verify account password
function verifyemail($email) {


					# provide a valid email address of letters A-Z, a-z, '.', '_', and '-'
				  # followed by '@'. The next character must be a letter A-Z, a-z, or 0-9
				  # which can be followed by any amount of letters A-Z, a-z, 0-9, and '-'.
				  # there must be a '.' followed by any amount of letters A-Z, a-z, 0-9,
				  # '_' and '-', and have potentially 2-6 sets of those (that is to 
				  # simulated something like @rit.edu, @it.rit.edu, etc all being valid)
				  $pattern = "/^[A-z0-9\._-]+"
				           . "@"
				           . "[A-z0-9][A-z0-9-]*"
				           . "(\.[A-z0-9_-]+)*"
				           . "\.([A-z]{2,6})$/";
			    return preg_match ($pattern, $email);

}


?>

<? 
# close the database connection
pg_close($db_connect); ?>
