<? include("inc/header.php"); ?>
<? include("inc/connection.inc"); ?>

<html>
<head>
<title>Resnet Shift Report</title>

</head>
<table width="100%">
	<tr>
		<td align="right"><a href="report.php">Shift Report Reports</a></td>
	</tr>
</table>

<?php
	if(!empty($_POST)) {
	
		/* MYSQL CONNECTION */
		
		#$link = mysql_connect("localhost", "sreport", "d3vnu11")
		#	or die("Could not connect : " . mysql_error());
		#$datab = "shiftreport";
		#mysql_select_db("$datab") or die("Could not select database");
		
		/* Do stuff here */
		$userdudemanz0r = $PHP_AUTH_USER;
		$dateofentry = date("Y-m-d");
		$timeofentry = date("H:i:s");
		$overviews = $_POST[generaloverview];
		$problemss = $_POST[problems];
		$others = $_POST[other];
		$emailtom = $_POST[emailtom];
		$peeps = pg_query( "SELECT nid, name from peoples;" );
		$flagger = "no";
		
		/* do this stuff if the person exists */
		while ($row = pg_fetch_assoc( $peeps ))
		{
			$namegoeshere = trim($row['name']);
			$thenidder = trim($row['nid']);
			if($userdudemanz0r == $namegoeshere) {
				$insertquery = "INSERT INTO shifts ( nid, date, time, overview, problems, other ) values ( '$thenidder', '$dateofentry', '$timeofentry', '$overviews', '$problemss', '$others' );";
				pg_query($insertquery);
				$flagger = "yes";
				}
		}
		
		/* Do this stuff it is a person that hasn't entered stuff before */
		if($flagger == "no") {
			$insertname = "INSERT INTO peoples ( name ) values ( '$userdudemanz0r' );";
			pg_query($insertname);
			$getnidquery = "SELECT nid from peoples WHERE name = '$userdudemanz0r';";
			$newdudenid = pg_query($getnidquery);
			#$insertquery = "INSERT INTO shifts ( nid, date, time, overview, problems, other ) values ( '$newdudenid', '$dateofentry', '$timeofentry', '$overviews', '$problemss', '$others' )";
			while ($extrarow = pg_fetch_assoc( $newdudenid ))
			{
				$stupidstuburnnum = trim($extrarow['nid']);
				$insertquery = "INSERT INTO shifts ( nid, date, time, overview, problems, other ) values ( '$stupidstuburnnum', '$dateofentry', '$timeofentry', '$overviews', '$problemss', '$others' );";
			}
			pg_query($insertquery);
		}
	
		/* Print out some feedback of what was entered */
		print "Stuff Entered:<br/><br/>\n";
		print "Supervisor: $userdudemanz0r <br/>\n";
		print "Date: $dateofentry <br/>\n";
		print "Time: $timeofentry <br/>\n";
		print "Overview: $overviews <br/>\n";
		print "Problems: $problemss <br/>\n";
		print "Other: $others <br/>\n";
		print "<br/>Your submission has been recorded.  Thank you.<br>\n";
	
	}
	if (isset($emailtom)) {
		$to = "trdhelp@rit.edu";
    $from = "$userdudemanz0r@rit.edu";
    $subject = "Shift Report Urgent Message";
    $message = "There has been a Supervisor Update flagged as urgent for you. The contents of the Problems section is as follows:\n\n$problemss\n\nSupervisor Report System";

    $headers  = "From: $from\r\n";
    $success = mail($to, $subject, $message, $headers);
    if ($success) {
        echo "The email to $to from the Urgent Shift Report Email System was successfully sent.";
		}
    else {
        echo "An error occurred when sending the email to $to."; 
		}
	}
?>



	<h2>Resnet Shift Supervisor Report</h2>

<form action=index.php method="POST">

<table width="60%" border=0 cellspacing=10>
	<tr>
		<td colspan=2>This form is to be filled out by authorized, senior people only. Please fill this out at the end of every shift. If an issues comes up that you feel either Dan or the student team lead needs to be made aware of upon their return to the office, please check the respective checkbox to email them.
		</td>
	</tr>
	<tr>
		<td align=left valign=top>Overview of the shift</td>
		<td><textarea name="generaloverview" wrap="soft" rows=10 cols=50></textarea>
	</tr>
	<tr>
		<td align=left valign=top>Problems that came up</td>
		<td><textarea name="problems" wrap="soft" rows=10 cols=50></textarea>
	</tr>
	<tr>
		<td align=left valign=top>Other</td>
		<td><textarea name="other" wrap="soft" rows=10 cols=50></textarea>
	</tr>	
	<tr>
		<td align=left valign=top>It's Important, Email The Problems Section To Tom!:</td>
		<td><input type="checkbox" name="emailtom" unchecked></td>
	</tr>
</table>
<br/>
<input type="submit"/>
</form>

<? pg_close($db_connect);?>
