<?
	include("../inc/connection.inc");
 	include("../inc/header2.php"); ?>

<? 

	# instantiation of key variables
	# set certain ones to null to overwrite url injections
	$submittal = "";

	# extract the data sent to this page
	@extract($_POST);

	# if they have edited the ticket #
	if ($submittal == "yes") {

		$query = "UPDATE tickets SET tnumber = '$tnumber' WHERE tcnumber = '$tcnumber';";
		$result = pg_query($query);
	
	}
		
?>	

<!-- Main Page -->
	<table width="80%" border="0" align="center">
		<tr>
			<td align="center"><b>Edit RIT Ticket Number for: <? echo $tcnumber; ?></b>
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
					 Modify the RIT Ticket # below and click "submit" when you are finished.
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br><br>
			</td>
		</tr>
							
							<!-- The Form For The Ticket #-->
							<form enctype="multipart/form-data" method="post" action="addticketnum.php">
							<tr>
								<td>
									<b>RIT Ticket Number:</b>
								</td>
								<td align="right">
									<input type="text" name="tnumber" size="20" maxlength="20">
								</td>
							</tr>
							<tr>
								<td></td>
								<td align="right">
									<input type="hidden" name="tcnumber" value="<?echo $tcnumber; ?>">
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
							<!-- End Form For Ticket # -->
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
									<b>RIT Ticket # for <? echo $tcnumber; ?> has been changed to: <? echo $tnumber; ?></b>
								</td>
							</tr>
				<?
				}
					?>

</body>
</html>
