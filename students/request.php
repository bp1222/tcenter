<? 
	include("inc/connection.inc");
	include("inc/header.php");

	$query = "SELECT time FROM waittime;";
	$result = pg_query($query);
	while ($row = pg_fetch_assoc($result)) {
		$waittime = trim($row['time']);
	}

?>
<?

	# set variables to null to overwrite url injection
	$success = "";
	$accept = "";
	$submittal = "";



	# set the user to the currently logged in user
	$queryuser = $PHP_AUTH_USER;

	# extract data that was sent to this page
	@extract($_POST);

 if ($problemdescription == "") {?>
<script type="text/javascript">
	<!--
	alert("Resnet Currently Has An Estimated Wait Time Of: <? echo $waittime; ?>")
	//-->
</script>
<?
	}

	# if they have submitted a request
	if ($submittal == "yes") {
?>	
	<tr>
		<td>
			<div style="background-color: eee;
								  border-style: solid;
									border-width: 1px;
									width: 50%;
									height: auto;
									font-size: 10pt;
									color: #ff0000;
									padding: 10px;
									text-align: left;"><center><font size="2"><b>Results:</b></font></center><hr>
<?

	#this area is to check that all of the data was filled in, 
	#and filled in properly. If it was filled in properly, 
	#we will add the entry into the database and the ticket will be created
	
	#if all fields are filled in (not including machine)
	if ($name != "" && $username != "" && $address != "" && $email != "" && $owner != "" && $passwd != "" && $inventory != "" && $problemdescription != "" && $warranty != "") {

		#they have chosen a saved machine
		if ($preload != "") {
		

			#query the database to bring out the data for the saved machine
			#we will call the function that will submit the ticket

			$query = "SELECT * FROM machines WHERE description = '$preload' AND owner = '$username';";
			$result = pg_query($query);
			if (pg_num_rows($result) > 0) {
				while ($row = pg_fetch_assoc($result)) {
					
					$snumber = trim($row['snumber']);
					$description = trim($row['description']);
					$mac = trim($row['mac']);
					$accountname = trim($row['username']);

				}
			}
			# no stored machine was found
			else {
				echo "We cannot find the stored computer you have chosen<br>";
				echo "</div></td></tr>";
				
			}

			# attempt to make all usernames lowercase
			$username = strtolower($username);

			# verify the data that is being submitted
			verify($name, $username, $phone, $address, $email, $owner, $description, $snumber, $mac, $accountname, $passwd, $inventory, $problemdescription, $preload, $queryuser, $warranty, $repairinstall, $fullinstall);

		}
		#if no data was submitted for the machine
		elseif ($preload == "" && $snumber == "" && $description == "" && $mac == "" && $accountname == "" && $passwd == "") {
				echo "Please make sure you have filled in all required fields for a machine, following the proper format.<br>";
				echo "</div></td></tr>";
				if ($description == "") {
					$xdescription = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
				}
				if ($accountname == "") {
					$xaccountname = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
				}
				if ($passwd == "") {
					$xpasswd = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
				}
		}
		#seems they haven't picked a saved machine
		elseif ($preload == "" && ($snumber != "" || $description != "" || $mac != "" || $accountname != "" || $passwd != "")) {

			#if the new machine was not entered properly
				if ($description == "" || $passwd == "") {
				echo "Please make sure you have filled in all required fields for a new machine, following the proper format.<br>";
				echo "</div></td></tr>";
				#if ($description == "") {
				#	$xdescription = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
				#}
				if ($accountname == "") {
					$xaccountname = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
				}
				if ($passwd == "") {
					$xpasswd = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
				}
				}
				else {
				# this variable will let us know that they have not chosen a stored machine
				$preload = "";

			# verify the data being submitted
			verify($name, $username, $phone, $address, $email, $owner, $description, $snumber, $mac, $accountname, $passwd, $inventory, $problemdescription, $preload, $queryuser, $warranty, $repairinstall, $fullinstall);
			}
		}
	}

	#if they haven't filled all the required fields in, tell them
	else {
				echo "Please make sure you have filled in all required fields following the proper format.<br>";
		echo "</div></td></tr>";
		if ($name == "") {
			$xname = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($address == "") {
			$xaddress = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($email == "") {
			$xemail = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($passwd == "") {
			$xpasswd = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($inventory == "") {
			$xinventory = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($problemdescription == "") {
			$xproblemdescription = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($warranty == "") {
			$xwarranty = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($description == "") {
			$xdescription = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
		if ($accountname == "") {
			$xaccountname = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		}
	}


	}	

		?>
	<!-- Begin Request Form -->	
	<table width="100%" align="right">
		<tr>
			<td align="right">
				<a href="index.php">Back To Customer Portal</a>
			</td>
		</tr>
	</table>

<?

	# check to see if they are allowed to request (incase they skip the waiver

		$requestip = $_SERVER["REMOTE_ADDR"];

		$query = "SELECT requestedfrom FROM tickets WHERE requestedfrom = '$requestip' AND status <> 'Closed';";
		$result = pg_query($query);

		if (pg_num_rows($result) >= 3 && $submittal != "yes") {
?>
		<table width="100%" align="center">
			<tr>
			<td align="center">
				At this time we only allow 3 open requests at a time. Thank you.
			</td>
			</tr>
		</table>
<?
		}
		else {

?>

		<h2>Resnet Technical Support Request Form:</h2>

		<br>
		<form enctype="multipart/form-data" method="post" action="request.php">
		<table width="60%" align="center">
			<tr>
				<td colspan="2">All of the information on this form is necessary to process your request. If you are not sure how to fill out a certain entry, click on the item to get a detailed description of what to fill in. If you do not fill in all of the data for this sheet, or you fill in the sheet improperly, you will be redirected back to the form until it is properly filled out. If any of the information that is auto-filled is incorrect, please correct it before submitting the request. <b>Quotes are not allowed unless specified.</b><br><br><b>Note:</b> A field denoted with a "<font color="#ff0000">*</font>" means the field is required.
				</td>
			</tr>
			<tr>
				<td colspan="2"><b><u>User Information</u></b></td>
			</tr>
			<tr>
				<td>
			
				<?
				# this is not the submitted form, check to see if the user has stored
				# user information in the database so we can retrieve it and use it
				if ($submittal != "yes") {

					$queryuser = trim($PHP_AUTH_USER);
					$query = "SELECT * FROM users where username = '$queryuser';";
					$result = pg_query($query);
					if (pg_num_rows($result) > 0) {
						while ($row = pg_fetch_assoc($result)) {
							
							$name = trim($row['name']);
							$username = trim($row['username']);
							$phone = trim($row['phone']);
							$address = trim($row['address']);
							$email = trim($row['email']);

						}
					}
					# there was no user data in the database, set them to nothing so the
					# user can fill this information in when the form is loaded
					else {

						$name = "";
						$username = "";
						$phone = "";
						$address = "";
						$email = "";

					}
				}

						?>
							<font color="#ff0000">*</font><?echo $xname;?>Name: 
						</td>
						<td style="text-align:right;">
							<input type="text" name="name" size="50" maxlength="50" value="<? echo $name; ?>">
						</td>
					</tr>
					<tr>
						<td></td><td style="text-align:center;">
							Full: [First Middle Last]
						</td>
					</tr>
					<tr>
						<td>
							<font color="#ff0000">*</font>Username:
						</td>
						<td style="text-align:right;">
							<input type="text" disabled size="50" maxlength="7" value="<? echo $queryuser; ?>">
							<input type="hidden" name="username" value="<?echo $queryuser; ?>">
						</td>
					</tr>
					<tr>
						<td>
							Phone/Pager #:
						</td>
						<td style="text-align:right;">
							<input type="text" name="phone" size="50" maxlength="12" value="<? echo $phone; ?>">
						</td>
					</tr>
					<tr>
						<td></td><td style="text-align:center;">
							[XXX-XXX-XXXX]
						</td>
					</tr>
					<tr>
						<td>
							<font color="#ff0000">*</font><?echo $xaddress;?>Address:
						</td>
						<td style="text-align:right;">
							<input type="text" name="address" size="50" maxlength="50" value="<? echo $address; ?>">
						</td>
					</tr>
					<tr>
						<td></td><td style="text-align:center;">
							[Room/Apt/House # Building/Road]
						</td>
					</tr>
					<tr>
						<td>
							<font color="#ff0000">*</font><?echo $xemail;?>Email:
						</td>
						<td style="text-align:right;">
							<input type="text" name="email" size="50" maxlength="50" value="<? echo $email; ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr width="85%">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b><u>Machine Information</u></b>
						</td>
					</tr>
					<tr>
								
		<?  

			# check to see if the user has brought a machine down already so they can 
			# choose to use the information already stored in the system
		
			$query = "SELECT description FROM machines WHERE owner = '$queryuser';";
			$result = pg_query($query);
			# we have found machines in our database
			if (pg_num_rows($result) > 0) {
					?><td colspan="2">If you have brought a machine to Resnet, please select it from the list below, or fill out the form below to add a new computer. If you are choosing a stored machine, please ensure that all field except "Owner" are empty and that you have entered your Machine account password.</td></tr>
					<tr>
						<td colspan="2">
						<!-- Select statement for the stored machines -->
							<select name="preload" width="90%">
								<option selected value=""></option>
				<? 
				# for each machine, put it's description in the select box for the user 
				# to choose from
				while ($row = pg_fetch_assoc($result)) {
	
					$storeddescription = trim($row['description']);
					?>
								<option value="<? echo $storeddescription . "\">" . $storeddescription ?></option>
				<? } ?>
							</select>
							<a href="editmachines.php">Edit Machine(s)</a>
					<? if ($submittal != "yes") { ?>
							<br> Or...
					<? } ?>
						</td>
					</tr>

			<? }
				?>
				
					<tr>
						<td colspan="2"><b>
					<? 
					# this is not a submitted form yet	
					if ($submittal != "yes") { ?>
						Please Add A Computer For Our Records:</b>
					<? }
						# the form is a submittal form, so show them what they submitted
						 else { ?>
						You have submitted with the following information:</b>
						<? } ?>
						</td>
					</tr>
					<tr>
						<td>
							<a href="requesthelp.php#owner" target="new"><font color="#ff0000">*</font>Owner of machine:</a>	
						</td>
						<td style="text-align:right;">
							<input type="text" size="50" disabled value="<? echo $queryuser ?>">
							<input type="hidden" name="owner" size="50" value="<? echo $queryuser ?>">
						</td>
					</tr>
					<tr>
						<td>
							<?echo $xdescription;?><a href="requesthelp.php#description" target="new"><font color="#ff0000">*</font>Description:</a>
						</td>
						<td style="text-align:right;">
							<input type="text" name="description" size="50" maxlength="200" value="<? echo $description ?>">
						</td>
					</tr>
					<tr>
						<td></td><td style="text-align:right;">
							[examples: Dell GX260, Thermaltake with Window]
						</td>
					</tr>
					<!--<tr>
						<td>
							<a href="requesthelp.php#serial" target="new">Serial # or Service Tag #:</a>
						</td>
						<td style="text-align:right;">
							<input type="text" name="snumber" size="50" maxlength="50" value="<? echo $snumber ?>">
						</td>
					</tr>
					<tr>
						<td>
							<a href="requesthelp.php#mac" target="new">MAC Address:</a>
						</td>
						<td style="text-align:right;">
							<input type="text" name="mac" size="50" maxlength="17" value="<? echo $mac ?>">
						</td>
					</tr> -->
					<tr>
						<td>
							<?echo $xaccountname;?><a href="requesthelp.php#username" target="new">Username to log in with:</a>
						</td>
						<td style="text-align:right;">
							<input type="text" name="accountname" size="50" maxlength="50" value="<? echo $accountname ?>">
						</td>
					</tr>
					<tr>
						<td colspan="2"><br>Passwords are required to be entered for existing and new machines. Passwords are cleared from our records for security purposes when you pick up your machine. If you do not have a password to log into the machine, please put "n/a".</td>
					</tr>
					<tr>
						<td valign="top">
							<?echo $xpasswd;?><a href="requesthelp.php#password" target="new"><font color="#ff0000">*</font>Machine account password:</a>
						</td>
						<td style="text-align:right;">
							<input type="text" name="passwd" size="50" maxlength="50" value="<? if(!isset($passwd)) { echo "n/a";} else {echo $passwd;} ?>"><br><b>Note:</b> password is viewable so you can confirm it
						</td>
					</tr>
					<tr>
						<td valign="top">
							<?echo $xwarranty;?>Is Your Computer Under Warranty?:
						</td>
						<td style="text-align:center;">
							Yes&nbsp;<input type="radio" name="warranty" value="Yes">&nbsp;No&nbsp;<input type="radio" name="warranty" value="No">
						</td>
					</tr>
					<tr>
						<td colspan="2"><br>Inventory is Mandatory for new machines and stored machines!</td>
					</tr>

				<?  ?>

					<tr>
						<td valign="top">
							<?echo $xinventory;?><a href="requesthelp.php#items" target="new"><font color="#ff0000">*</font>Inventory you are bringing with machine:</a>
						</td>
						<td style="text-align:right;">
							<input type="text" name="inventory" size="50" maxlength="120" value="<? echo $inventory ?>"><br><b>Note:</b> commas, -'s, ()'s allowed only
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr width="85%">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b><u>Repair and Full Installs</u></b>
						</td>
					</tr>
					<tr>
						<td valign="top" colspan="2">
							It is standard procedure for Resnet to exhaust all options in an attempt to fix a computer. In the event that the problem cannot be fixed, we will attempt the following steps in order to repair your computer:<br>
							1) <b>Repair Installation:</b> This will not remove any of the programs or data on your machine, but to be sure, we will attempt to back up all non-copyrighted data beforehand (we cannot backup your software, applications, or copyrighted data).<br> 
							2) <b>Full Installation:</b> This is only attempted if the Repair Installation fails. This removes all data and programs, but again your non-copyrighted data will be backed up beforehand.<br><br>
							In order to complete either a Repair or a Full Installation you will need to provide a <b>licensed</b> copy of the Operating System. Please be sure to provide us with the appropriate CD to expedite your service.<br><br>
						</td>
					</tr>
					<tr>
						<td> Do you grant permission for Resnet to attempt a Repair and/or Full install if it is needed?
						</td>
						<td style="text-align:center;">
							Repair Install:&nbsp;<input type="checkbox" name="repairinstall">&nbsp;Full Install:&nbsp;<input type="checkbox" name="fullinstall">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<hr width="85%">
						</td>
					</tr>
					<tr>
						<td>
							<?echo $xproblemdescription;?><font color="#ff0000">*</font><b><u>Problem Description</u></b>
						</td>
					</tr>
					<tr>
						<td>
							In <b>500</b> characters or less, please describe to us what the issue is you are having, when it started, what you did to produce the issue, and any error messages you may have encountered:<br><br><b>Note:</b> quotes, commas, periods, -'s, ()'s, ?'s and !'s allowed only for punctuation.
						</td>
						<td style="text-align:right;">
							<textarea name="problemdescription" cols="47" rows="15"><? echo stripslashes($problemdescription) ?></textarea>
						</td>
					</tr>
					<tr>
					<? 
					# if the form was not successful, or if they haven't submitted yet
					# give them a submit button. This is so after the user has submitted
					# their request, they cannot resubmit it immediately
					if ($success != "success") { ?>
						<td colspan="2" style="text-align:right;"><input type="hidden" name="submittal" value="yes"><input type="hidden" name="accept" value="Accept"> <input type="submit" name="submit" value="Submit">
						</td>
					<? } ?>
					</tr>
				</table>
				</form>
				</center>
				<br><p>Copyright &copy; 2004 - 2006 Michael Goffin
<? } ?>

</body>
</html>

<?
# verify the data that is being submitted. 
#
# the do_submit variable will let us know if all data has passed the test
function verify($name, $username, $phone, $address, $email, $owner, $description, $snumber, $mac, $accountname, $passwd, $inventory, $problemdescription, $preload, $queryuser, $warranty, $repairinstall, $fullinstall) {

	#initialize submit variable
	$do_submit = "yes";

	#verify name
	$okay = verifyname($name);
	if ($okay == "FALSE") {
		echo "Please provide a valid name (First Last)<br>";
		$xname = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify username
	$okay = verifyusername($username);
	if ($okay == "FALSE" || strlen($username) < '6' || strlen($username) > '7') {
		echo "Please provide a valid username (your RIT ID)<br>";
		$xusername = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify phone number
	$okay = verifyphone($phone);
	if ($okay == "FALSE" && $phone != "") {
		echo "Please provide a valid phone number (###-###-####)<br>";
		$xphone = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify address
	$okay = verifyaddress($address);
	if ($okay == "FALSE") {
		echo "Please provide a valid address (Room# Building/Apartment or House/Apt#, Street)<br>";
		$xaddress = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify email address
	$okay = verifyemail($email);
	if ($okay == "FALSE") {
		echo "Please provide a valid email address<br>";
		$xemail = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify description
	$okay = verifydescription($description);
	if ($okay == "FALSE") {
		echo "Please provide a valid computer description (refrain from using non-alphanumeric characters)<br>";
		$xdescription = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify snumber
	$okay = verifysnumber($snumber);
	if ($okay == "FALSE" && $snumber != "") {
		echo "Please provide a valid serial/service tag number<br>";
		$xsnumber = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify mac
	$okay = verifymac($mac);
	if ($okay == "FALSE" && $mac != "") {
		echo "Please provide a valid MAC address<br>";
		$xmac = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify account name 
	$okay = verifyaccountname($accountname);
	if ($okay == "FALSE" && $accountname != "") {
		echo "Please provide a valid user account for your machine<br>";
		$xaccountname = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify account password 
	$okay = verifypasswd($passwd);
	if ($okay == "FALSE" && $passwd != "n/a" && $passwd != "N/A") {
		echo "Please provide a valid password for your user account<br>";
		$xpasswd = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify inventory
	$okay = verifyinventory($inventory);
	if ($okay == "FALSE") {
		echo "Please provide valid information about the items you are bringing<br>";
		$xinventory = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	#verify problem description 
	$okay = verifyproblem($problemdescription);
	if ($okay == "FALSE" | strlen($problemdescription) > 500) {
		echo "Please provide a valid description of the problem you are having<br>";
		$xproblemdescription = "<font color=\"red\" size=\"5pt\"><b>!</b></font>";
		$do_submit = "no";
	}
	

	#if the submit variable is still good, we can submit the verified data
	if ($do_submit == "yes") {
		submit($name, $username, $phone, $address, $email, $owner, $description, $snumber, $mac, $accountname, $passwd, $inventory, $problemdescription, $preload, $queryuser, $warranty, $repairinstall, $fullinstall);

	}
	# the varification did not go so well, this closes the error messages received
	else {
		echo "</div></td></tr>";
	}


}

#verify name
function verifyname($name) {

					# they must provide a First and Last name minimally, with any amount
					# of middle names, all containing letters A-Z, a-z, '-' and '''
					trim($name);
					$pattern = "/^([A-z\'-]+ ){1,}([A-z\'-]+)$/";
					return !preg_match($pattern,$name);

}

#verify username
function verifyusername($username) {

					# the username must contain only letters A-Z, a-z, and 0-9
					trim($username);
					$pattern = "/^[^ ][A-z0-9]*$/";
					return !preg_match($pattern,$username);

}

#verify phone number
function verifyphone($phone) {

					# the phone number must be in the form XXX-XXX-XXXX
					trim($phone);
					$pattern = "/^[^ ][0-9 \(\)\.-]*$/";
					return !preg_match($pattern,$phone);

}

#verify address
function verifyaddress($address) {

					# the address can only contain letters A-Z, a-z, 09, '#', '.', and '-'
					trim($address);
					$pattern = "/^[^ ][A-z0-9 \s#\.-]*$/";
					return !preg_match($pattern,$address);

}

#verify email function
function verifyemail($email) {

					# provide a valid email address of letters A-Z, a-z, '.', '_', and '-'
					# followed by '@'. The next character must be a letter A-Z, a-z, or 0-9
					# which can be followed by any amount of letters A-Z, a-z, 0-9, and '-'.
					# there must be a '.' followed by any amount of letters A-Z, a-z, 0-9,
					# '_' and '-', and have potentially 2-6 sets of those (that is to 
					# simulated something like @rit.edu, @it.rit.edu, etc all being valid)
					trim($email);
					$pattern = "/^[A-z0-9\._-]+"
					         . "@"
							     . "[A-z0-9][A-z0-9-]*"
							     . "(\.[A-z0-9_-]+)*"
							     . "\.([A-z]{2,6})$/";
				  return preg_match ($pattern, $email);
}

#verify description
function verifydescription($description) {

					# the description can contain any letters A-Z, a-z, 0-9, '.', '!', '?',
					# ''', ',', and '()'
					trim($description);
					$pattern = "/^[A-z0-9 \s\.\!\?\'\,\(\)-]*$/";
					#return preg_match($pattern,$description);
					return "TRUE";

}

#verify serial/service tag #
function verifysnumber($snumber) {

					# the snumber can contain any letters A-Z, a-z, 0-9, and '-'
					trim($snumber);
					$pattern = "/^[A-z0-9\s-]*$/";
					return preg_match($pattern,$snumber);

}

#verify mac address 
function verifymac($mac) {

					# the mac must have 6 sets of 2 numbers and/or letters, divided by ':' or '-' 
					trim($mac);
					$pattern = "/^([A-Fa-f0-9]{1,2}[:-]){5}[A-Fa-f0-9]{1,2}$/";
					return preg_match($pattern,$mac);

}

#verify account name
function verifyaccountname($accountname) {

					# account on computer can only contain letters A-Z, a-z, 0-9, '.', '*', '~'
					# and '-'
					trim($accountname);
					$pattern = "/^[A-z0-9 \.\*~-]*$/";
					return preg_match($pattern,$accountname);

}

#verify account password
function verifypasswd($passwd) {

					# currently the password is not checked, but this is where it would go
					#$pattern = "/^$/";
					#return preg_match($pattern,$accountname);
					trim($passwd);

}

#verify inventory 
function verifyinventory($inventory) {

					# the inventory can contain only letters A-Z, a-z, 0-9, '.', ''', ',', '()'
					# and '-'
					trim($inventory);
					$pattern = "/^[A-z0-9 \s\.\'\,\(\)-]*$/";
					return preg_match($pattern,$inventory);

}

#verify problem description 
function verifyproblem($problemdescription) {

					# the problem description can contain only letters A-Z, a-z, 0-9, '.', 
					# ''', ',', '()', line breaks, and '-'
					trim($problemdescription);
					$pattern = "/^[A-z0-9 \s\.\!\?\@\*\#\&\'\"\,\(\)-]*$/";
					#return preg_match($pattern,$problemdescription);
					return "TRUE";

}

# replace all " with their HTML Ampersand Character Code of &quot;
# currently not used
function replacequote($replace) {

					# this will replace any qoutes with the html equivalent
					return preg_replace("\"", "&quot;", $replace);

}

# submit the data

function submit($name, $username, $phone, $address, $email, $owner, $description, $snumber, $mac, $accountname, $passwd, $inventory, $problemdescription, $preload, $queryuser, $warranty, $repairinstall, $fullinstall) {

	# need to check if they have changed any values for the user information
	# as well as update the inventory for the machine, or create a whole new
	# machine entry if necessary (can be checked if preload has a value).
	# once we know what to update, update it, and then create a new ticket.
	# the new ticket requires manipulation of the submitted data for our
	# purposes. that info will be created before doing any database updating
	# for the ticket.

	#global variable to notify us of sucessful creation so we can
	#remove submit button
	global $success;

	#The first line of business, believe it or not is to figure out what the TID
	#of the last ticket was that was entered into the system. That number + 1, along
	#with the username of this individual will be the ticket#

	$query = "SELECT tid FROM tickets ORDER BY tid DESC LIMIT 1;";
	$result = pg_query($query);
	if (pg_num_rows($result) == 1) {
		while ($row = pg_fetch_assoc($result)) {
			
			$tidnumber = trim($row['tid']) + 1;

		}
	}
	# pray this does not happen. This means the data in the table was completely erased
	# or that the table was scraped and rebuilt
	else {
		#echo "tid cannot be found (THIS IS NOT GOOD)<br>";
	}

		# ticket # is the following: username-TID
	
		$ticketid = $username . "-" . $tidnumber; 

	#first deal with the user information. If the information is new, add it
	#If the information is the same as the database, don't worry about it
	#If the information has changed, mofidy it

	$query = "SELECT * from users WHERE username = '$username';";
	$result = pg_query($query);
	if (pg_num_rows($result) > 0) {
	#there is an entry for the user
	while ($row = pg_fetch_assoc($result)) {

		$storedname = trim($row['name']);
		$storedusername = trim($row['username']);
		$storedphone = trim($row['phone']);
		$storedaddress = trim($row['address']);
		$storedemail = trim($row['email']);

	}	
		#they have changed their stored info, time to update it
		if ($name != $storedname || $username != $storedusername || $phone != $storedphone || $address != $storedaddress || $email != $storedemail) {

			# the query to update the user information
			$query = "UPDATE users SET name = '$name', username = '$username', phone = '$phone', address = '$address', email = '$email' WHERE username = '$queryuser';";

			$result = pg_query($query);
			if (pg_affected_rows($result) == 1) {
				#echo "User Information Has Been Updated<br>";
			}
			else {
				#echo "User Information Was Not Updated<br>";
			}
		}
		else {
			#echo "Username is already there with no changes needed<br>";
		}
	}
	else {
		#no rows have been found, so we must update the database with the user info
		# phone numbers are not required. If it was not filled in, don't add it in 
		# the query
		if ($phone != "") {
			$query = "INSERT INTO users (\"name\", \"username\", \"phone\", \"address\", \"email\") values('$name', '$username', '$phone', '$address', '$email');";
		}

		# the phone number was added, so add it to the query
		else {
			$query = "INSERT INTO users (\"name\", \"username\", \"address\", \"email\") values('$name', '$username', '$address', '$email');";
		}
		$result = pg_query($query);
		if (pg_affected_rows($result) == 1) {
			#echo "User Information Has Been Added To Database<br>";
		}
		else {
			#echo "User Information Has Not been Added<br>";
		}
	}

	#now we have to update the machine information
	#the TID we got before will be used to add a ticket # to this machine

	#if the machine exists, update the inventory and the ticket # it has

	if ($preload != "") {
		#they chose a preloaded machine, find its entry, and update
		#inventory, password, and ticket#


		$query = "UPDATE machines SET inventory = '$inventory', password = '$passwd', tcnumber = '$ticketid', warranty = '$warranty' WHERE description = '$description' AND owner = '$username';";
		$result = pg_query($query);
		if (pg_affected_rows($result) == 1) {
			#echo "Machine information updated successfully<br>";
		}
		else {
			#echo "Machine information not updated<br>";
		}
	}
	else {

		#they have a new machine, throw it in!

		# there are 4 cases, snumber and mac are empty, one or the other is empty, 
		# or both are full. the following queries reflect those 4 cases
		if ($snumber == "" && $mac == "") {
			$query = "INSERT INTO machines (\"inventory\", \"description\", \"username\", \"password\", \"owner\", \"tcnumber\", \"warranty\") values('$inventory', '$description', '$accountname', '$passwd', '$owner', '$ticketid', '$warranty');";
		}
		elseif ($snumber == "" && $mac != "") {
			$query = "INSERT INTO machines (\"inventory\", \"mac\", \"description\", \"username\", \"password\", \"owner\", \"tcnumber\", \"warranty\") values('$inventory', '$mac', '$description', '$accountname', '$passwd', '$owner', '$ticketid', '$warranty');";
		}
		elseif ($mac == "" && $snumber != "") {
			$query = "INSERT INTO machines (\"snumber\", \"inventory\", \"description\", \"username\", \"password\", \"owner\", \"tcnumber\", \"warranty\") values('$snumber', '$inventory', '$description', '$accountname', '$passwd', '$owner', '$ticketid', '$warranty');";
		}
		else {
			$query = "INSERT INTO machines (\"snumber\", \"inventory\", \"mac\", \"description\", \"username\", \"password\", \"owner\", \"tcnumber\", \"warranty\") values('$snumber', '$inventory', '$mac', '$description', '$accountname', '$passwd', '$owner', '$ticketid', '$warranty');";
		}
		$result = pg_query($query);
		if (pg_affected_rows($result) == 1) {
			#echo "Machine Has Been Added To Database<br>";
		}
		else {
			#echo "Machine Has Not been Added<br>";
		}
	}

	#and finally, we create the ticket. this should never be updated, but always
	#be new
	#we set the date, and the status of the ticket to be new, and the queue to be 	#unassigned

		# create the current date in the format xx/xx/xxxx
		$thedate = date("F d, Y");
		# set default status to New
		$status = "New";
		# put the ticket in the unassigned queue first
		$queue = "Unassigned";
		# initial entry in the progress to know when the ticket was created
		$progress = "<b>tcenter [ " . date("m/d/y @ g:iA") . " ]</b> ::ticket generated::";

		# check for repair install
		if ($repairinstall != "") {
			$repairinstall = "true";
		}
		else {
			$repairinstall = "false";
		}

		# check for full install
		if ($fullinstall != "") {
			$fullinstall = "true";
		}
		else {
			$fullinstall = "false";
		}

		$requestip = trim($_SERVER["REMOTE_ADDR"]);

		# insert the data into the ticket table
		$query = "INSERT INTO tickets (\"username\", \"tcnumber\", \"date\", \"description\", \"status\", \"progress\", \"queue\", \"repairinstall\", \"fullinstall\", \"requestedfrom\") values('$username', '$ticketid', '$thedate', '$problemdescription', '$status', '$progress', '$queue', '$repairinstall', '$fullinstall', '$requestip');";
		$result = pg_query($query);
		if (pg_affected_rows($result) == 1) {

			# look at the current tasks in the database
			$query = "SELECT taskid FROM tasks ORDER BY taskid DESC LIMIT 1";
			$result = pg_query($query);
			while ($row = pg_fetch_assoc($result)) {
				$index = trim($row['taskid']);
			}
			# put ticket-specific tasks in the link table
			for ($i = 1;$i <= $index;$i++) {
				$query = "INSERT INTO link (\"task\", \"ticket\") values('$i', '$ticketid');";
				$result = pg_query($query);
			}
			# let the user know the ticket was successfully submitted
			echo "<center><font color=\"#0000ff\">Your request has been logged. Please bring your machine to Resnet along with any items required to fix your issue.<br><br>Please close this window before leaving.</font></center>";
			$success = "success";

		}
		# the ticket was not submitted
		else {
			#echo "The Ticket Has Not Been Created";
		}
			echo "</div></td></tr>";
	}

?>

<?
# close the database
pg_close($db_connect); ?>
