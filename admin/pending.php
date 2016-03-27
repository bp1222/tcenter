<? 
	include("../inc/connection.inc"); 
  include("../inc/header2.php");	
?>

<?
	$query = "SELECT * from tickets WHERE status = 'Pending Customer';";
	$result = pg_query($query);
	if ( pg_num_rows($result) == 0 ) {
		echo "There are currently no tickets that are Pending Customer.";
	}
	# data was found, show it
	else { 
	?>
		<h3>Tickets That Are Pending Customer</h3><hr width="35%"><br>
		<table width="80%" valign="top">
	<?
	while ($row = pg_fetch_assoc($result)) {

	$username = trim($row['username']);
	$tcnumber = trim($row['tcnumber']);
	$date = trim($row['date']);
	$summary = trim($row['summary']);
	$description = trim($row['description']);
	$lastupdate = stripslashes(trim($row['lastupdate']));
	$lastupdatedby = trim($row['lastupdatedby']);
	$lastupdateddate = trim($row['lastupdateddate']);

	$contactquery = "SELECT * from users WHERE username = '$username';";
	$contactresult = pg_query($contactquery);
	while ($contactrow = pg_fetch_assoc($contactresult)) {
		$name = trim($contactrow['name']);
		$phone = trim($contactrow['phone']);
		$email = trim($contactrow['email']);
	}
	
	?>
			<tr>
				<td valign="top" width="150px"><b>Ticket #:&nbsp; <a href="tickets.php?tcnumber=<? echo $tcnumber; ?>&referrer=tickets"><? echo $tcnumber; ?></a></b></td>
			</tr>
			<tr>
				<td></td>
				<td valign="top" width="200px">Name:&nbsp; <? echo $name; ?></td>
				<td valign="top" width="155px">Username:&nbsp; <? echo $username; ?></td>
				<td valign="top" width="140px">Phone:&nbsp; <? echo $phone; ?></td>
				<td valign="top" width="350px">Email:&nbsp; <? echo $email; ?></td>
			</tr>
			<tr>
				<td></td>
				<td valign="top">Date Of Creation:&nbsp; <? echo $date; ?></td>
				<td valign="top">Last Updated By:&nbsp; <? echo $lastupdatedby; ?></td>
				<td valign="top" colspan="2">Date Last Updated:&nbsp; <? echo $lastupdateddate; ?></td>
			</tr>
			<tr>
				<td></td>
				<td valign="top" colspan="4">Summary:&nbsp; <?echo $summary; ?></td>
			</tr>
			<tr>
				<td></td>
				<td valign="top" align="right"><b>Last Update:</b></td><td valign="top" colspan="4"><? echo $lastupdate; ?></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="4">
					<hr width="55%">
				</td>
			</tr>
	<? }} ?>
	
</table>
<br>
<br>

<? 
# close the database connection
pg_close($db_connect) ?>
