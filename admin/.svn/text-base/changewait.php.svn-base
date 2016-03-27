<?
	include("../inc/connection.inc");
 	include("../inc/header2.php"); ?>

<? 

	# instantiation of key variables
	# set certain ones to null to overwrite url injections
	$submittal = "";

	# extract the data sent to this page
	@extract($_POST);

	# if they have edited the time
	if ($submittal == "yes") {

		$query = "UPDATE waittime SET time = '$time';";
		$result = pg_query($query);
	
	}

	$query = "SELECT time FROM waittime;";
	$result = pg_query($query);
	while ($row = pg_fetch_assoc($result)) {
		$waittime = trim($row['time']);
	}
		
?>	

<!-- Main Page -->
	<table width="80%" border="0" align="center">
		<tr>
			<td align="center"><b>Resnet Current Wait Time: <? echo $waittime; ?></b>
				<hr width="60%"></td>
		</tr>
	</table>
	<br>
	<table width="35%" border="0" align="center">
		<tr>
			<td colspan="2" align="center">
					<?
				
					# if they have not submitted information, show them the initial page
					if ($submittal != "yes") {
					 ?>
					 Modify the Resnet Wait Time below and click "submit" when you are finished.
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br><br>
			</td>
		</tr>
							
							<!-- The Form For The Wait Time-->
							<form enctype="multipart/form-data" method="post" action="changewait.php">
							<tr>
								<td>
									<b>New Wait Time:</b>
								</td>
								<td align="right">
									<input type="text" name="time" size="40" maxlength="145">
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right">
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
							<!-- End Form For Wait Time -->
				<?
						}

				# the user submitted data, and these are the results
				else {
				?>
				The following changes were submitted:
			</td>
		</tr>

							<tr>
								<td align="center">
									<b>Resnet Wait Time has been changed to: <? echo $waittime; ?></b>
								</td>
							</tr>
				<?
				}
					?>

</body>
</html>
