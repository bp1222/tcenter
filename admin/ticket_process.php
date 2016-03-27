<?
/**
  * ticket_process.php
  *
  * This file will process requests from the ticket page, then bounce the user back
  * Purpose is to get rid of that pesky post-data
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

require "inc/connection.inc";
include_once "inc/lookup.inc";
include_once "inc/ticket.inc";

header("Cache-Control: private;");
$orig_ticket = getAllTicketById($_REQUEST['t_id']);

if ($_REQUEST['submit'] || $_REQUEST['close'])
{
	// Input all variables from _REQUEST
	$t_id = $_REQUEST['t_id'];
	$addInv = trim($_REQUEST['addInv']);
	$location = trim($_REQUEST['r_location']);
	$status = trim($_REQUEST['s_status']);
	$senior = trim($_REQUEST['t_senior']);
	$repair = trim($_REQUEST['c_repair']);
	$full = trim($_REQUEST['c_full']);
	$faculty = trim($_REQUEST['c_faculty']);
	$passwords = trim($_REQUEST['c_passwords']);
	$media = trim($_REQUEST['c_media']);
	$contacted = trim($_REQUEST['c_contacted']);
	$softupdate = trim($_REQUEST['c_updates']);
	$malware = trim($_REQUEST['c_malware']);
	$unfinished = trim($_REQUEST['c_unfinished']);
	$update = $_REQUEST['t_update'];
	$old_update = $_REQUEST['old_update'];
	$actions = "";

	$ticket_update = "UPDATE ticket SET last_time = '".time()."', ";

	// Time accounting update time.
	// We check first, if the "old" ticket status is Queued or In-Progress.  If it is
	// get the difference in time between now, and last_time and add it to time_working.
	// If the status of the "old" ticket is Pending Customer or Awaiting Pickup, take
	// the difference from the last_time and now, and add it to time_waiting.
	{
		$last_time = $orig_ticket['last_time'];
		$now = time();
		$time_diff = $now - $last_time;
		$old_status = $orig_ticket['status'];

		if (($old_status == "Queued") 
		  || $old_status == "In Progress")
		{
			$old_working = $orig_ticket['time_working'];
			$new_working = $old_working + $time_diff;
			$ticket_update .= "time_working = $new_working, ";
		} 
		else if (($old_status == "Pending Customer") 
			   || $old_status == "Awaiting Pickup")
		{
			$old_waiting = $orig_ticket['time_waiting'];
			$new_waiting = $old_waiting + $time_diff;
			$ticket_update .= "time_waiting = $new_waiting, ";
		}
	}
	
	// Status
	{
		if ($_REQUEST['close'])
		{
			$status = "Closed";
			$actions .= "<font color='red'>[Ticket Closed]</font><br/>";
			$ticket_update .= "close_date = NOW(), ";
		}
		else if ($orig_ticket['status'] != $status)
			$actions .= "<font color='red'>[Updated Status :: $status]<br/></font>";

		$ticket_update .= "status = '".$l_status[$status]."', ";
	}

	// Senior
	{
		if ($orig_ticket['senior'] != $senior)
		{
			$actions .= "[Senior Sign-off :: $senior]<br/>";
			$ticket_update .= "senior = '$senior', ";
		}
	}

	// Inventory
	{
		if ($addInv)
		{
			// Get old Inventory
			$sql = "SELECT * FROM machine WHERE m_id = '".$orig_ticket['m_id']."'";
			$row = mysql_fetch_assoc(mysql_query($sql));
			
			// Make new Inventory
			$old_inv = mysql_real_escape_string($row['inventory']);
			$new = mysql_real_escape_string($addInv);
			$new_inv = $new.", ".$old_inv;

			$machine_update = "UPDATE machine SET inventory = '$new_inv'  
						       WHERE m_id = '".$orig_ticket['m_id']."'"; 
			mysql_query($machine_update);

			$actions .= "[Updated Inventory :: $new]";
		}
	}

	// Location
	{
		if ($orig_ticket['location'] != $location)
			$actions .= "[Updated Location]<br/>";

		$ticket_update .= "location = '$location', ";
	}

	// Repair
	{
		if ($repair == "on")
			$num = 1;
		else
			$num = 0;

		if ($orig_ticket['repair'] != $num)
			$actions .= "[Got Repair Install Permission]<br/>";

		$ticket_update .= "repair = '$num', ";
	}

	// Full
	{
		if ($full == "on")
			$num = 1;
		else
			$num = 0;

		if ($orig_ticket['full'] != $num)
			$actions .= "[Got Full Install Permission]<br/>";

		$ticket_update .= "full = '$num', ";
	}

	// Faculty
	{
		if ($faculty == "on")
			$num = 1;
		else
			$num = 0;

		if ($orig_ticket['faculty'] != $num)
			$actions .= "[Faculty Machine]<br/>";

		$ticket_update .= "faculty = '0', ";
	}

	// Tasks 
	// Bitwise anding!  The checkboxes go up by x2, and get anded
	// 000001 = reset passwords
	// 000010 = checked for media
	// 000100 = Customer Contacted
	// 001000 = Software Updates
	// 010000 = Removed Malware
	// 100000 = Unfinished
	// Therefore
	// 000101 = Reset Passwords, and Contacted Customer
	{
		$tasks = 0;
		$tasks ^= $passwords;
		$tasks ^= $media;
		$tasks ^= $contacted;
		$tasks ^= $softupdate;
		$tasks ^= $malware;
		$tasks ^= $unfinished;

		$ticket_update .= "tasks = '$tasks', ";

		// Cause it's stored as a String in the DB, we convert it
		$orig_task = intval($orig_ticket['tasks']);

		// Need to check for differences in each task :(
		if (($orig_task & $passwords) != ($tasks & $passwords))
			$actions .= "[Reset Passwords]<br/>";

		if (($orig_task & $media) != ($tasks & $media))
			$actions .= "[Checked for Media]<br/>";

		if (($orig_task & $contacted) != ($tasks & $contacted))
			$actions .= "[Customer Contacted]<br/>";
		
		if (($orig_task & $softupdate) != ($tasks & $softupdate))
			$actions .= "[Software Updated]<br/>";

		if (($orig_task & $malware) != ($tasks & $malware))
			$actions .= "[Removed Malware]<br/>";

		if (($orig_task & $unfinished) != ($tasks & $unfinished))
			$actions .= "[Closed Machine Unfinished]<br/>";

	}

	// Update
	{
		// Formatting Update
		if ($actions)
			$actions = "<br/>".mysql_real_escape_string($actions);

		$old_update = $orig_ticket['updates'];

		if ($update != "" && !preg_match("/^\s*(To Do:)\s*$/", $update)) 
		{
			$update = mysql_real_escape_string(nl2br($update))."<br/><br/><update/>";
			$update .= mysql_real_escape_string($old_update);
			$update = "<b>".$PHP_AUTH_USER." [".date("m/d/y @ g:iA")."]".$actions."</b><br/>".$update;
			$ticket_update .= "updates = '$update' ";
		} else if (!empty($actions)) {
			$update = "<b>".$PHP_AUTH_USER." [".date("m/d/y @ g:iA")."]".$actions."</b><br/>";
			$update .= mysql_real_escape_string($old_update);
			$ticket_update .= "updates = '$update' ";
		}
	}

	$ticket_update .= "WHERE t_id = '$t_id'";
	mysql_query($ticket_update);

	// Send them back!
	header ("Location: ticket.php?t_id=$t_id");
}
else if ($_REQUEST['reopen'])
{
	$t_id = $_REQUEST['t_id'];

	$old_update = $orig_ticket['updates']."<br/>";
	$old_reopen = $orig_ticket['reopen_count'];

	$update = "<b>".$PHP_AUTH_USER." [".date("m/d/y @ g:iA")."]<br/><font color='red'>[Ticket ReOpened]</font></b><br/><br/>$old_update";

	$ticket_update = "UPDATE ticket SET last_time = ".time().", reopen_count = ".($old_reopen+1).", tasks = '0', status = '".$l_status["Queued"]."', updates = '".mysql_real_escape_string($update)."' WHERE t_id = $t_id";

	mysql_query($ticket_update);
	// Send them back!
	header ("Location: ticket.php?t_id=$t_id");
}
else if ($_REQUEST['accept'])
{
	// Accept the ticket!
	$summary = mysql_real_escape_string(trim($_REQUEST['summary']));
	$t_id = trim($_REQUEST['t_id']);

	if (empty($summary) || $summary == "Summary of Users Problem : Be Brief!") 
	{
		$error['summary'];
	}
	else
	{
		// We set our summary, set the last updated time, and make it queued.
		$accept_sql = "UPDATE ticket SET summary = '$summary', last_time = ".time().", status = 1 WHERE t_id = '$t_id'";
		mysql_query($accept_sql);
	}
	// Send them back!
	header ("Location: ticket.php?t_id=$t_id");
}

?>
