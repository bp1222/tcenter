<? 
	include("../inc/connection.inc");
	include("../inc/header2.php");
?>

<table width="60%">
	<tr>
		<td colspan="2" align="center"><h3>Admin Tools</h3>
		<hr width="40%">
		<br>
		</td>
	</tr>
	<tr>
		<td><a href="generate.php">Generate A Ticket For A User</a></td>
		<td>Generate a ticket for a user by using their username. If they are already in the database, their information will be filled in for you. If not, you'll have the opportunity to fill it in.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="../barcode/">Generate A Barcode For A Username</a></td>
		<td>Generate a barcode for a user by using their username.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="queryuser.php">Query A User's Information</a></td>
		<td>Query for a user's stored information based on their username or part of their real name. You can also generate a barcode for the user via this search.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="querymachine.php">Query For Machine Information</a></td>
		<td>Query for machine information based on MAC Address, Owner's Username, Service Tag/Serial #, or currently assigned Ticket #.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="queryticket.php">Query The Ticket Queue</a></td>
		<td>Query for a specific queue, specific status, specific user, or a specific ticket.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="pending.php">Pending Customers</a></td>
		<td>A quick list of tickets that are Pending Customer. Includes contact information for the user so that they may be called to speed up the response.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="stats/chart.php">Daily Open/Closed Statistics Chart</a></td>
		<td>Check out the latest up-to-date statistics of Open/Closed tickets per day. It is a png image, so if your browser does not support opening images correctly, you might need to open it with other imaging software.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="changewait.php">Resnet Wait Time</a></td>
		<td>Change the current wait time that customers should expect when bringing their computer to Resnet. This wait time is a prompt the users will see when they go to request assistance.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="experience.php">Resnet Experience Chart</a></td>
		<td>Check out the latest up-to-date statistics of our Resnet employees as they attempt to fight the evils of Spyware and Viruses. Watch as they level up and become supreme Pirates!</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="report/index.php">Shift Supervisor Reports</a></td>
		<td>Supervisor Reports for all Shift Supervisors. Entries can be made and read as the convenience of Resnet Supervisors.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="dbmod/updates.php">Resnet Daily Workflow</a></td>
		<td>Workflow for the day. Logs usernames of people who work during the day, the tickets they worked on, and the amount of updates they made per ticket.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="dbmod/moddatabase.php">Add/Modify Common Tasks, Stati, and Queues</a></td>
		<td>Add or modify the Common Tasks that we perform for each machine, the different stati the machines can have, and the different queues this software supports. This allows you to add new items, or set items to be enabled/disabled depending on the needs of ITS.</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
	<tr>
		<td><a href="dbmod/deleteticket.php">Delete A Ticket From The Queue</a></td>
		<td>Delete a ticket from the queue completely. This will NOT remove the references to the ticket number for the Machine (this ensures proper referencing to an existing ticket upon the next update).
		</td>
	</tr>
	<tr>
		<td><hr></td>
	</tr>
</table>

</body>
</html>

<? pg_close($db_connect); ?>
