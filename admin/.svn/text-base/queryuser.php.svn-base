<? 
	include("../inc/connection.inc"); 
	include("../inc/header2.php");
?>

<?

	# extract the post data
	@extract($_POST);

	# check to make sure the referrer is correct and that they 
	# provided either a username or part of the user's full name
	# to search by
	if ($referrer == "queryuser" && ($uname != "" || $fullname != "")) {

	# if they provided a username, search for it
	if ($uname != "") {
		$query = "SELECT * FROM users WHERE lower(username) LIKE lower('%$uname%');";
	}
	# they provided part of a full name, search for it
	elseif ($fullname != "") {
		$query = "SELECT * FROM users WHERE lower(name) LIKE lower('%$fullname%');";
	}
	$result = pg_query($query);

	# if there are no resuls to return
	if ( pg_num_rows($result) == 0 ) {
		echo "There are no results for your query.";
	}

	# there are results to display
	else { 
		?><h3>Query Result For User: <? echo $queryresult; ?></h3><hr width="40%"><br>
		<table>
		<?
	while ($row = pg_fetch_assoc($result)) {

		$uid = trim($row['uid']);
		$name = trim($row['name']);
		$username = trim($row['username']);
		$phone = trim($row['phone']);
		$address = trim($row['address']);
		$email = trim($row['email']);

	# queryresult contains the value that was searched by
	if ($uname != "") {
		$queryresult = $username; 
	}
	elseif ($fullname != "") {
		$queryresult = $name; 
	}
	?>
			<tr>
				<td>User Identification Number:</td><td><? echo $uid; ?></td>
			</tr>
			<tr>
				<td>Full Name:</td><td><? echo $name; ?></td>
			</tr>
			<tr>
				<td valign="center">Username:</td><td valign="center"><? echo "<a href=\"https://ipedit.rit.edu/search.php?whichsearch=dhcp&username=$username&method=detail\">$username</a>"; ?>&nbsp;<a href="../barcode/barcode.php?code=<?echo $username;?>&scale=1&bar=ANY"><img src="../images/bc.jpg" border="0"></a></td>
			</tr>
			<tr>
				<td>Phone #:</td><td><? echo $phone; ?></td>
			</tr>
			<tr>
				<td>Address:</td><td><? echo $address; ?></td>
			</tr>
				<td>Email Address:</td><td><? echo $email; ?></td>
			</tr>
			<tr>
				<td><br></td>
			</tr>
			<tr>
				<td colspan="2" align="right">
					<form enctype="multipart/form-data" method="post" action="editusers.php">
					<input type="hidden" name="referrer" value="admin">
					<input type="hidden" name="username" value="<?echo $username; ?>">
					<input type="submit" name="submit" value="Edit User Info">
					</form>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr width="60%"></td>
			</tr>
	<? } 
	
	}
		?></table><?
	}
	# nothing was provided, so they need to query by somehing. Show the form
	else { ?>
		<h3>Query For A User</h3><hr width="40%"><br>
		<?
	if ($referrer == "queryuser" && $uname == "" && $fullname == "") {
		echo "Please fill in the form properly.";
	}
	?>
	<br>
	<table>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="queryuser.php" method="post">
				Username to query:
			</td>
			<td>
				<input type="text" size="20" value="" name="uname">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="queryuser">
				</form>
			</td>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="queryuser.php" method="post">
				Name to query (full or part): 
			</td>
			<td>
				<input type="text" size="20" value="" name="fullname">
				<input type="submit" name="submit" value="Submit">
				<input type="hidden" name="referrer" value="queryuser">
				</form>
			</td>
		</tr>
	</table>
	
	<? } ?>

<? 
# close the database connection
pg_close($db_connect) ?>
