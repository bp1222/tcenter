<? include("inc/header.php"); ?>

<?php
	if(!empty($_POST)) {
	
		/* MYSQL CONNECTION */
		
		$link = mysql_connect("localhost", "sreport", "d3vnu11")
			or die("Could not connect : " . mysql_error());
		$datab = "shiftreport";
		mysql_select_db("$datab") or die("Could not select database");
		
		/* Do stuff here */
		$userdudemanz0r = $PHP_AUTH_USER;
		$dateofentry = date("Y-m-d");
		$timeofentry = date("H:i:s");
		$overviews = $_POST[generaloverview];
		$problemss = $_POST[problems];
		$others = $_POST[other];
		$peeps = mysql_query( "SELECT NID, name from peoples" );
		$flagger = "no";
		
		/* do this stuff if the person exists */
		while ($row = (mysql_fetch_array( $peeps )))
		{
			$namegoeshere = $row['name'];
			$thenidder = $row['NID'];
			if($userdudemanz0r == $namegoeshere) {
				$insertquery = "INSERT INTO shifts ( NID, date, time, overview, problems, other ) values ( '$thenidder', '$dateofentry', '$timeofentry', '$overviews', '$problemss', '$others' )";
				mysql_query($insertquery);
				$flagger = "yes";
				}
		}
		
		/* Do this stuff it is a person that hasn't entered stuff before */
		if($flagger == "no") {
			$insertname = "INSERT INTO peoples ( name ) values ( '$userdudemanz0r' )";
			mysql_query($insertname);
			$getnidquery = "SELECT NID from peoples WHERE name = '$userdudemanz0r'";
			$newdudenid = mysql_query($getnidquery);
			$insertquery = "INSERT INTO shifts ( NID, date, time, overview, problems, other ) values ( '$newdudenid', '$dateofentry', '$timeofentry', '$overviews', '$problemss', '$others' )";
			while ($extrarow = (mysql_fetch_array( $newdudenid )))
			{
				$stupidstuburnnum = $extrarow['NID'];
				$insertquery = "INSERT INTO shifts ( NID, date, time, overview, problems, other ) values ( '$stupidstuburnnum', '$dateofentry', '$timeofentry', '$overviews', '$problemss', '$others' )";
			}
			mysql_query($insertquery);
		}
	
		/* Print out some feedback of what was entered */
		print "Stuff Entered:<br/><br/>\n";
		print "Supervsor: $userdudemanz0r <br/>\n";
		print "Date: $dateofentry <br/>\n";
		print "Time: $timeofentry <br/>\n";
		print "Overview: $overviews <br/>\n";
		print "Problems: $problemss <br/>\n";
		print "Other: $others <br/>\n";
		print "<br/>Your submission has been recorded.  Thank you\n";
	
		exit();
	}
?>


<html>
<head>
<title>Resnet Shift Report</title>

</head>
<table width="100%">
	<tr>
		<td align="right"><a href="report.php">Shift Report Reports</a></td>
	</tr>
</table>

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
</table>
<br/>
<input type="submit"/>
</form>
