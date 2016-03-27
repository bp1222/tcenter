<? 
	include("../../inc/connection.inc"); 
	include("../../inc/header3.php");
?>

<?

	# set the variable to null to overwrite url injection
	$changing = "";

	# extract the post data
	@extract($_POST);

	# if the commontasks are slated to be changed
	if($changing == "commontasks") {
		# for each task that has been changed to required, update it
		if(isset($required)) {
			foreach ($required as $taskid) {
				$query = "UPDATE tasks SET required = 'TRUE' WHERE taskid = '$taskid';";
				$result = pg_query($query);
			}
		}
		# for each task that is set to be not required, update it
		if(isset($notrequired)) {
			foreach ($notrequired as $taskid) {
				$query = "UPDATE tasks SET required = 'FALSE' WHERE taskid = '$taskid';";
				$result = pg_query($query);
			}
		}
		# for each task that is set to be enabled, update it
		if(isset($enable)) {
			foreach ($enable as $taskid) {
				$query = "UPDATE tasks SET enabled = 'TRUE' WHERE taskid = '$taskid';";
				$result = pg_query($query);
			}
		}
		# for each task that is set to be disabled, update it
		if(isset($disable)) {
			foreach ($disable as $taskid) {
				$query = "UPDATE tasks SET enabled = 'FALSE' WHERE taskid = '$taskid';";
				$result = pg_query($query);
			}
		}
		# for each task that is set to be deleted, delete it
		if(isset($delete)) {
			foreach($delete as $taskid) {
				$query = "DELETE FROM tasks WHERE taskid = '$taskid';";
				$result = pg_query($query);
			}
		}
		# if there is a new task to be added, add it
		if($newtaskname != "") {
			# if it is to be enabled by default, enable it
			if(isset($newtaskenable)) {
				foreach($newtaskenable as $enabled) {
					$enable = "TRUE";
				}
			}
			else {
				$enable = "FALSE";
			}
			if(isset($newtaskrequired)) {
				foreach($newtaskrequired as $required) {
					$require = "TRUE";
				}
			}
			else {
					$require = "FALSE"; 
			}
			$query = "INSERT INTO tasks (\"name\", \"enabled\", \"required\") values('$newtaskname', '$enable', '$require');";
			$result = pg_query($query);
		}

	}

	# if the queues are slated to be changed
	if($changing == "queues") {

		# for each queue set to be enabled, enable it
		if(isset($enable)) {
			foreach ($enable as $qid) {
				$query = "UPDATE queues SET enabled = 'TRUE' WHERE qid = '$qid';";
				$result = pg_query($query);
			}
		}
		# for each queue set to be disabled, disable it
		if(isset($disable)) {
			foreach ($disable as $qid) {
				$query = "UPDATE queues SET enabled = 'FALSE' WHERE qid = '$qid';";
				$result = pg_query($query);
			}
		}
		# for each queue set to be deleted, delete it
		if(isset($delete)) {
			foreach($delete as $qid) {
				$query = "DELETE FROM queues WHERE qid = '$qid';";
				$result = pg_query($query);
			}
		}
		# for each queue set to be added, add it
		if($newqueuename != "") {
			if(isset($newqueueenable)) {
				foreach($newqueueenable as $enabled) {
					$enable = "TRUE";
				}
			}
			else {
				$enable = "FALSE";
			}
			$query = "INSERT INTO queues (\"name\", \"enabled\") values('$newqueuename', '$enable');";
			$result = pg_query($query);
		}

	}

	# if the statuses are set to be changed
	if($changing == "status") {
		
		# for each status set to be enabled, enable them
		if(isset($enable)) {
			foreach ($enable as $sid) {
				$query = "UPDATE status SET enabled = 'TRUE' WHERE sid = '$sid';";
				$result = pg_query($query);
			}
		}
		# for each status set to be disabled, disable them
		if(isset($disable)) {
			foreach ($disable as $sid) {
				$query = "UPDATE status SET enabled = 'FALSE' WHERE sid = '$sid';";
				$result = pg_query($query);
			}
		}
		# for each status set to be deleted, delete it
		if(isset($delete)) {
			foreach($delete as $sid) {
				$query = "DELETE FROM status WHERE sid = '$sid';";
				$result = pg_query($query);
			}
		}
		# for each new status, add it
		if($newstatusname != "") {
			if(isset($newstatusenable)) {
				foreach($newstatusenable as $enabled) {
					$enable = "TRUE";
				}
			}
			else {
				$enable = "FALSE";
			}
			$query = "INSERT INTO status (\"name\", \"enabled\") values('$newstatusname', '$enable');";
			$result = pg_query($query);
		}

	}

	if ($changing == "usermod") {

		# for each await/close set to be enabled, enable them
		if(isset($grantac)) {
			foreach ($grantac as $pid) {
				$query = "UPDATE privileged SET await_close = 'TRUE' WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each await/close set to be disabled, disable them
		if(isset($ungrantac)) {
			foreach ($ungrantac as $pid) {
				$query = "UPDATE privileged SET await_close = 'FALSE' WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each edit set to be enabled, enable them
		if(isset($grantedit)) {
			foreach ($grantedit as $pid) {
				$query = "UPDATE privileged SET edit = 'TRUE' WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each edit set to be disabled, disable them
		if(isset($ungrantedit)) {
			foreach ($ungrantedit as $pid) {
				$query = "UPDATE privileged SET edit = 'FALSE' WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each db set to be enabled, enable them
		if(isset($grantdb)) {
			foreach ($grantdb as $pid) {
				$query = "UPDATE privileged SET db = 'TRUE' WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each db set to be disabled, disable them
		if(isset($ungrantdb)) {
			foreach ($ungrantdb as $pid) {
				$query = "UPDATE privileged SET db = 'FALSE' WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each user set to be deleted, delete it
		if(isset($delete)) {
			foreach($delete as $pid) {
				$query = "DELETE FROM privileged WHERE pid = '$pid';";
				$result = pg_query($query);
			}
		}

		# for each new user, add it
		if($newuser != "") {
			if(isset($newgrantac)) {
				foreach($newgrantac as $grantac) {
					$grantac = "TRUE";
				}
			}
			else {
				$grantac = "FALSE";
			}
			if(isset($newgrantedit)) {
				foreach($newgrantedit as $grantedit) {
					$grantedit = "TRUE";
				}
			}
			else {
				$grantedit = "FALSE";
			}
			if(isset($newgrantdb)) {
				foreach($newgrantdb as $grantdb) {
					$grantdb = "TRUE";
				}
			}
			else {
				$grantdb = "FALSE";
			}
			$query = "INSERT INTO privileged (\"username\", \"await_close\", \"edit\", \"db\") values('$newuser', '$grantac', '$grantedit', '$grantdb');";
			$result = pg_query($query);
		}

	}
?>

<h3><b>Common Tasks</b><h3>

<table width="75%">
	<tr>
		<td><b>Name</b></td><td><b>Required/Not Required</b></td><td><b>Enable/Disable</b></td><td><b>Delete</b></td>
	</tr>
	<form enctype="multipart/form-data" method="post" action="moddatabase.php">
	<?

		# find all of the tasks
		$query = "SELECT taskid, name, enabled, required FROM tasks ORDER BY taskid;";
		$result = pg_query($query);
		while ($row = pg_fetch_assoc($result)) {
			$taskid = trim($row['taskid']);
			$name = trim($row['name']);
			$enabled = trim($row['enabled']);
			$required = trim($row['required']);

			?>
			<tr>
				<td>
					<? echo $name; ?>
				</td>
				<td>
					<?
					# if it is required already, set the text to show a not required value
					if($required == 't') {
						echo "Do Not Require&nbsp;";
					?>
					<input type="checkbox" name="notrequired[]" value="<?echo $taskid; ?>">
					<?}
					# if it is not required already, set the text to show a required value
					elseif ($required == 'f') {
						echo "Require&nbsp;";
					?>
					<input type="checkbox" name="required[]" value="<?echo $taskid; ?>">
					<?
					}
					?>
				</td>
				<td>  
					<? 
					# if it is enabled, show a disabled value	
					if($enabled == 't') {
						?> Disable&nbsp;<input type="checkbox" name="disable[]" value="<? echo "$taskid"; ?>"> <?
						}
						# if it is not enabled, so an enabled value
						elseif($enabled == 'f') {
						?> Enable&nbsp;<input type="checkbox" name="enable[]" value="<? echo "$taskid"; ?>"> <?
						}?>
				</td>
				<td>
					<input type="checkbox" name="delete[]" value="<? echo $taskid; ?>">
				</td>
			</tr>
		<?
		}
		?>
			<tr>
				<td>
					<br>
					New Task:&nbsp;<input type="text" size="30" name="newtaskname" value="">
				</td>
				<td>
					Require&nbsp;<input type="checkbox" name="newtaskrequired[]" value="newtask">
				</td>
				<td>
					Enable&nbsp;<input type="checkbox" name="newtaskenable[]" value="enable">
				</td>
			</tr>
			<tr>
				<td colspan="4" align="right">
				<br>
					<input type="hidden" name="changing" value="commontasks">
					<input type="submit" name="submit" value="Update Common Tasks">
				</td>
			</tr>
			</form>
		</table>
		<br>
		<hr width="70%">
		<br>
					
<h3><b>Queues</b><h3>

<table width="75%">
	<tr>
		<td><b>Name</b></td><td><b>Enable/Disable</b></td><td><b>Delete</b></td>
	</tr>
	<form enctype="multipart/form-data" method="post" action="moddatabase.php">
	<?

		# get all of the queues
		$query = "SELECT qid, name, enabled FROM queues ORDER BY qid;";
		$result = pg_query($query);
		while ($row = pg_fetch_assoc($result)) {
			$qid = trim($row['qid']);
			$name = trim($row['name']);
			$enabled = trim($row['enabled']);

			?>
			<tr>
				<td>
					<? echo $name; ?>
				</td>
				<td>
					<? 
						# if it is enabled, show a disabled
						if($enabled == 't') {
						?> Disable&nbsp;<input type="checkbox" name="disable[]" value="<? echo "$qid"; ?>"> <?
						}
						# if it is disabled, show an enabled
						elseif($enabled == 'f') {
						?> Enable&nbsp;<input type="checkbox" name="enable[]" value="<? echo "$qid"; ?>"> <?
						}?>
				</td>
				<td>
					<input type="checkbox" name="delete[]" value="<? echo $qid; ?>">
				</td>
			</tr>
		<?
		}
		?>
			<tr>
				<td>
					<br>
					New Queue:&nbsp;<input type="text" size="30" name="newqueuename" value="">
				</td>
				<td>
					Enable&nbsp;<input type="checkbox" name="newqueueenable[]" value="enable">
				</td>
			</tr>
			<tr>
				<td colspan="4" align="right">
				<br>
					<input type="hidden" name="changing" value="queues">
					<input type="submit" name="submit" value="Update Queues">
				</td>
			</tr>
			</form>
		</table>
		<br>
		<hr width="70%">
		<br>
		
<h3><b>Stati</b><h3>

<table width="75%">
	<tr>
		<td><b>Name</b></td><td><b>Enable/Disable</b></td><td><b>Delete</b></td>
	</tr>
	<form enctype="multipart/form-data" method="post" action="moddatabase.php">
	<?

		# get all of the statuses
		$query = "SELECT sid, name, enabled FROM status ORDER BY sid;";
		$result = pg_query($query);
		while ($row = pg_fetch_assoc($result)) {
			$sid = trim($row['sid']);
			$name = trim($row['name']);
			$enabled = trim($row['enabled']);

			?>
			<tr>
				<td>
					<? echo $name; ?>
				</td>
				<td>
					<? 
						# if it is enabled, show a disabled
						if($enabled == 't') {
						?> Disable&nbsp;<input type="checkbox" name="disable[]" value="<? echo "$sid"; ?>"> <?
						}
						# if it is disabled, show an enabled
						elseif($enabled == 'f') {
						?> Enable&nbsp;<input type="checkbox" name="enable[]" value="<? echo "$sid"; ?>"> <?
						}?>
				</td>
				<td>
					<input type="checkbox" name="delete[]" value="<? echo $sid; ?>">
				</td>
			</tr>
		<?
		}
		?>
			<tr>
				<td>
					<br>
					New Status:&nbsp;<input type="text" size="30" name="newstatusname" value="">
				</td>
				<td>
					Enable&nbsp;<input type="checkbox" name="newstatusenable[]" value="enable">
				</td>
			</tr>
			<tr>
				<td colspan="4" align="right">
				<br>
					<input type="hidden" name="changing" value="status">
					<input type="submit" name="submit" value="Update Stati">
				</td>
			</tr>
			</form>
		</table>
		<br>
		<hr width="70%">
		<br>
		
<h3><b>User Privileges</b><h3>

<table width="75%">
	<tr>
		<td><b>Username</b></td><td><b>Awaiting/Closed</b></td><td><b>Edit Updates</b></td><td><b>Edit Database</b></td><td><b>Delete User</b></td>
	</tr>
	<form enctype="multipart/form-data" method="post" action="moddatabase.php">
	<?

		# get all of the statuses
		$query = "SELECT * FROM privileged ORDER BY pid;";
		$result = pg_query($query);
		while ($row = pg_fetch_assoc($result)) {
			$pid = trim($row['pid']);
			$username = trim($row['username']);
			$await_close = trim($row['await_close']);
			$edit = trim($row['edit']);
			$dbase = trim($row['db']);

			?>
			<tr>
				<td>
					<? echo $username; ?>
				</td>
				<td>
					<? 
						# if it is Granted, show an Ungrant
						if($await_close == 't') {
						?> Ungrant&nbsp;<input type="checkbox" name="ungrantac[]" value="<? echo "$pid"; ?>"> <?
						}
						# if it is Ungranted, show a Grant
						elseif($await_close == 'f') {
						?> Grant&nbsp;<input type="checkbox" name="grantac[]" value="<? echo "$pid"; ?>"> <?
						}?>
				</td>
				<td>
					<? 
						# if it is Granted, show an Ungrant
						if($edit == 't') {
						?> Ungrant&nbsp;<input type="checkbox" name="ungrantedit[]" value="<? echo "$pid"; ?>"> <?
						}
						# if it is Ungranted, show a Grant
						elseif($edit == 'f') {
						?> Grant&nbsp;<input type="checkbox" name="grantedit[]" value="<? echo "$pid"; ?>"> <?
						}?>
				</td>
				<td>
					<? 
						# if it is Granted, show an Ungrant
						if($dbase == 't') {
						?> Ungrant&nbsp;<input type="checkbox" name="ungrantdb[]" value="<? echo "$pid"; ?>"> <?
						}
						# if it is Ungranted, show a Grant
						elseif($dbase == 'f') {
						?> Grant&nbsp;<input type="checkbox" name="grantdb[]" value="<? echo "$pid"; ?>"> <?
						}?>
				</td>
				<td>
					<input type="checkbox" name="delete[]" value="<? echo $pid; ?>">
				</td>
			</tr>
		<?
		}
		?>
			<tr>
				<td>
					<br>
					New User:&nbsp;<input type="text" size="30" name="newuser" value="">
				</td>
				<td>
					Grant Await/Close&nbsp;<input type="checkbox" name="newgrantac[]" value="enable">
				</td>
				<td>
					Grant Edit Updates&nbsp;<input type="checkbox" name="newgrantedit[]" value="enable">
				</td>
				<td>
					Grant Database Mod&nbsp;<input type="checkbox" name="newgrantdb[]" value="enable">
				</td>
			</tr>
			<tr>
				<td colspan="4" align="right">
				<br>
					<input type="hidden" name="changing" value="usermod">
					<input type="submit" name="submit" value="Update Users">
				</td>
			</tr>
			</form>
		</table>
		
					
<? 
# close the database connection
pg_close($db_connect); ?>
