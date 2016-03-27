<? 
	include("../../inc/connection.inc"); 
	include("../../inc/header3.php");
?>

<?

	# set the variable to null to overwrite url injection
	$delete = "";

	# extract the post data
	@extract($_POST);

	# if the submission is set for deletion, check it out
	if ($delete == "yes") {

		$query = "DELETE FROM tickets where tcnumber = '$tcnumber';";
		$result = pg_query($query);

		# if the ticket was deleted, prompt
		if (pg_affected_rows($result) == 1) {
			echo "The ticket $tcnumber has been deleted from the database.<br>";
		}
		# if the ticket was not deleted, prompt
		else {
			echo "There was an error deleting the ticket from the database.<br>";
		}

		# find the last ticket owned by the user and reference it. This will
		# set the machine's "Current Ticket" to the last known ticket for the user

		# strip the username off of the ticket #
		$uname = explode("-", $tcnumber);
		$username = $uname[0];

		$query = "SELECT tcnumber FROM tickets WHERE username = '$username' ORDER BY tid DESC LIMIT 1;";
		$result = pg_query($query);
		while ($row = pg_fetch_assoc($result)) {
			$tcnumber = trim($row['tcnumber']);
		}

		$query = "UPDATE machines SET tcnumber = '$tcnumber' WHERE owner = '$username';";
		$result = pg_query($query);
		if (pg_affected_rows($result) == 1) {
			echo "Machine has been referenced to the user's last known ticket.";
		}

	}

?>
	<br><br>
	<table width="80%">
		<tr>
			<td align="center">Delete Ticket # &nbsp;
			<form enctype="multipart/form-data" method="post" action="deleteticket.php">
			<input type="hidden" name="delete" value="yes">
				<select name="tcnumber">
					<?
						# find every current non-closed ticket and put them in a dropdown
						$query = "SELECT tcnumber FROM tickets WHERE status != 'Closed' ORDER BY tcnumber ASC;";
						$result = pg_query($query);
						while ($row = pg_fetch_assoc($result)) {
							$tcnumber = trim($row['tcnumber']);
							echo "<option>$tcnumber</option>";
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<br><br><br><br>
				<!-- Prompt them to ensure they understand that this ticket will no longer
						 exist if they hit the button -->
				<font color="#ff0000">BEFORE HITTING THIS BUTTON, PLEASE BE SURE YOU MEAN TO DELETE THIS TICKET!!!!!!!!!</font><br><br>
				<!-- The Big Red Button -->
				<input type="image" src="../../images/bigbutt.gif" name="submit">
			</td>
		</tr>
		</form>
	</table>

<? 
# close database connection
pg_close($db_connect); ?>
					
