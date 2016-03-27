
<?  include("connection.inc"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><? echo $tcnumber; ?></title>
<? include( "ITS-head.html" ); ?>
<link rel="stylesheet" type="text/css" href="tcenter.css">

</head>

<body>

<? include( "ITS-topbar.php" ); ?>
 
<?

  // Set values to null to clear url injection
  $reopen = "";
  $close = "";

  // Extract the post data
  @extract( $_POST );

  $resuser = trim( $PHP_AUTH_USER );
  $thedate = date( "F d, Y" );
  $status = $POST_status;

  $errorMessage = "";
  $ticketFound = false;  // Assume wrong ticket and check for correctness.
  $updateTicket = false;  // Used to handle a ticket that is being updated versus just viewed.

  // Check to see if a valid ticket number was given, if, try to get data from it.
  if( $tcnumber ) {

    $query = "SELECT 1 FROM tickets WHERE lower(tcnumber) = lower('$tcnumber') LIMIT 1;";
    $result = pg_query( $query );
 
  }

  if( !$tcnumber || pg_num_rows( $result ) != 1 )
      echo "<div class=error>Unable to find ticket from given ticket number.</div>\n";
  else {
    
    $ticketFound = true;

    // Get Machine Info
    $query = "SELECT mid, inventory, description, username, password, warranty, location FROM machines WHERE lower(tcnumber) = lower('$tcnumber');";		
    $result = pg_query( $query );
    if( $row = pg_fetch_assoc( $result ) ) {

      $DB_mid = trim( $row[ 'mid' ] );
      $DB_inventory = trim( $row[ 'inventory' ] );
      $DB_machinedesc = trim( $row[ 'description' ] );
      $DB_accountname = trim( $row[ 'username' ] );
      $DB_passwd = trim( $row[ 'password' ] );
      $DB_warranty = trim( $row[ 'warranty' ] );
      $DB_location = $row[ 'location' ];

    }

    //---- Updating a Ticket
    if( $POST_update == "yes" ) {

      // Run query to get current values
      $query = "SELECT * FROM tickets WHERE lower(tcnumber) = lower('$tcnumber') LIMIT 1;";
      $result = pg_query( $query );
      $ticket = pg_fetch_assoc( $result );


      // Get some current values
      $DB_accepteddate = "";
      $query = "SELECT summary, faculty, progress, status, priority, accepteddate FROM tickets WHERE tcnumber = '$tcnumber';";
      $result = pg_query( $query );
      if( $row = pg_fetch_assoc( $result ) ) {

        $DB_progress = trim( $row[ 'progress' ] );
        $DB_status = trim( $row[ 'status' ] );
        $DB_accepteddate = trim( $row[ 'accepteddate' ] );
        $DB_acceptedby = trim( $row[ 'acceptedby' ] );
				$DB_priority = trim( $row['priority'] );
        $DB_summary = trim( $row[ 'summary' ] );
        $DB_faculty = trim( $row[ 'faculty' ] );

      }

      // Make sure ticket has been accepted, or is being accepted now.
      if( $ticket[ 'accepteddate' ] == "" && !$POST_accept )
        echo "<div class=error>Tried to update a ticket that hasn't been accepted.</div>\n";
      else {

        echo "<div class=notice> Updating Ticket </div>\n";

        // Build a 'lastupdate'.
        $lastupdate = $statuschange;
        if( $POST_progress )
          $lastupdate .= "<b>" . 
                         $PHP_AUTH_USER . 
                         " [ " . 
                         date("m/d/y @ g:iA") .
                         " ]</b><br>";

        // Update tasks
        if( isset( $task ) ) {

          //
          // Update them in the database
          //
          
          $task_query = "UPDATE link SET date = '$thedate' WHERE ticket = '$tcnumber' AND task IN ( ";
          $taskName_query = "SELECT name FROM tasks WHERE taskid IN (";

          foreach( $task as $taskid ) {
            $task_query .= $taskid . ", "; 
            $taskName_query .= $taskid . ", ";
          }

          // End it with a dummy task so that the comma at the end of the list of tasks
          // has a number after it.
          $task_query .= "8346 );";
          $taskName_query .= "8346 );";

          $result = pg_query( $task_query );

          //
          // Make a progress message about the tasks
          //

          $result = pg_query( $taskName_query );
          while( $row = pg_fetch_assoc( $result ) ) {

            $lastupdate .= "[::] Completed task: " . $row[ 'name' ] . "<br>";

          }

        }

        // Has the status changed?
        $statuschange = "";
        if( $status != $ticket[ 'status' ] || $status == "Reopen" ) {

          $statuschange = "<font color=\"#ff0000\"><b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Status Changed To: $status</font><br>";

        }

        // A progress that has just a "To Do:" and nothing else is treated as blank.
        // Else, replace newlines with html breaks
        if( preg_match( "/^\s*(To Do:)\s*$/", $POST_progress ) )
          $progress = "";
        else {
          $progress = preg_replace( '/[\n]/', '<br>', $POST_progress );
          $progress .= "<br>";
          //$progress = addslashes( $progress );
        }
        
        // Set RCC Flags
        if (isset($POST_flag))
        {
           $p_query = "UPDATE tickets SET flag = '".$POST_flag."' WHERE tcnumber = '".$tcnumber."'";
           pg_query($p_query);
        }

        // Update Inventory (in machines table)
        if( $POST_inventoryupdate ) {

          // If there is an inventory, add a new one to it. otherwise, just make it the new one
          $query = "SELECT inventory FROM machines WHERE tcnumber = '$tcnumber';";
          $result = pg_query( $query );
          if( $row = pg_fetch_assoc( $result ) )
            $inventory = $row[ 'inventory' ] . ", " . trim( $POST_inventoryupdate );
          else
            $inventory = trim( $POST_inventoryupdate );

          // Update Inventory in database
          $query = "UPDATE machines SET inventory = '" . addslashes( $inventory ) . "' WHERE tcnumber = '$tcnumber';";
          $result = pg_query( $query );
          if( pg_affected_rows( $result ) <= 0 )
            echo "<div class=error>Unable to update inventory.</div>\n";

        }

        // Build Update Query
        $update_query = "UPDATE tickets SET lastupdatedby = '$PHP_AUTH_USER', lastupdateddate = '$thedate'";
        $needToUpdate = false;

        if( $POST_signoff ) {
          $update_query .= ", signoff = '$POST_signoff'";
          $needToUpdate = true;
        }

        if( $POST_repairinstall ) {
          $lastupdate .= "[::] Repair Install Permission Granted.<br>";
          $update_query .= ", repairinstall = 'TRUE'";
          $needToUpdate = true;
        }

        if( $POST_fullinstall ) {
          $lastupdate .= "[::] Full Install Permission Granted.<br>";
          $update_query .= ", fullinstall = 'TRUE'";
          $needToUpdate = true;
        }

        if( $POST_summary != $DB_summary ) {
          $update_query .= ", summary = '$POST_summary'";
          $needToUpdate = true;
        }

		if( $POST_faculty )
		{
			$update_query .= ", faculty = '1'";
			$needToUpdate = true;
		}

		unset ($update_location);
        
        // User should not manually set status to Closed.
        if( $status != $DB_status && ( $status != "Closed" || $POST_close == "yes" ) ) {

          // check to see if the ticket was changed to Awaiting Pickup
          // if it was, set pickupdate to 'thedate'
          if( $status == "Awaiting Pickup" )
					{
            $update_query .= ", pickupdate = '$thedate'";
						$update_location = "0";
					}

					if ($status == "Queued")
					{
						$update_location = "1";
					}
          $update_query .= ", status = '$status', statustime = 'now'";

          $needToUpdate = true;

        }

		if (($POST_priority != $DB_priority) && isset($POST_priority))
        {
          $query = "UPDATE tickets SET priority = '" . $POST_priority . "' WHERE tcnumber = '$tcnumber';";
          $result = pg_query( $query );
          if( pg_affected_rows( $result ) <= 0 )
            echo "<div class=error>Unable to update location.</div>\n";
        }

		if (($POST_faculty != $DB_faculty) && isset($POST_faculty))
        {
          $query = "UPDATE tickets SET faculty = '" . $POST_faculty . "' WHERE tcnumber = '$tcnumber';";
          $result = pg_query( $query );
          if( pg_affected_rows( $result ) <= 0 )
            echo "<div class=error>Unable to update location.</div>\n";
        }

        // Accepting a ticket
        if( $POST_accept ) {
          $update_query .= ", queue = 'Resnet', acceptedby = '$POST_acceptedby', accepteddate = 'now'";
					$update_location = "1";
          $needToUpdate = true;
        }

        if( $POST_hdbackuplocation ) {
          $update_query .= ", hdbackuplocation = '" . addslashes( $POST_hdbackuplocation ) . "'";
          $needToUpdate = true;
        }

        // Update location
        $new_location = $update_location;

        if (!isset($new_location))
          $new_location = $POST_location;

        if (isset($new_location))
        {
          $query = "UPDATE machines SET location = '" . $new_location . "' WHERE tcnumber = '$tcnumber';";
          $result = pg_query( $query );
          if( pg_affected_rows( $result ) <= 0 )
            echo "<div class=error>Unable to update location.</div>\n";
          $DB_location = $new_location;
        }

        // Update DB with last update
        if( $lastupdate ) {

          $lastupdate .= $progress;
          $update_query .= ", progress = '" .
                           addslashes( $lastupdate ) .
                           addslashes( $DB_progress ) .
                           "'";
          $needToUpdate = true;

          $lastupdatequery = "UPDATE tickets SET lastupdate = '" .
                             addslashes( $lastupdate ) .
                             "' WHERE tcnumber = '$tcnumber';";
          $lastupdateresult = pg_query( $lastupdatequery );

        }

        // Finish and send query
        $update_query .= " WHERE tcnumber = '$tcnumber';";

        if( $needToUpdate ) {

          $result = pg_query( $update_query );
          if( pg_affected_rows( $result ) <= 0 ) {

            echo "<div class=error>Unable to update ticket. Number of affected rows: ";
            echo pg_affected_rows( $result );
            echo "</div>\n";

          }

        }
        
		  }

    }

    //---- Closing a Ticket
    if( $POST_close ) {

      // Get current prrogress and pickupdate (date it went into Awaiting Pickup).
      $query = "SELECT progress, pickupdate FROM tickets WHERE tcnumber = '$tcnumber' LIMIT 1;";
      $result = pg_query( $query );
      if( $row = pg_fetch_assoc( $result ) ) {

        $pickupdate = $row[ 'pickupdate' ];
        $oldProgress = $row[ 'progress' ];
        
        // If the ticket didn't go to Awaiting Pickup before being closed, the pickupdate
        // field won't be set. That date is used for statistics, so set it to the current
        // date.
        if( !$pickupdate )
          $pickupdate = $thedate;
        
        $progress = "<font color=\"#ff0000\"><b>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ]</b> Status Changed To: Closed</font><br>" . $oldProgress;
        //$progress = "<div class=StatusChange>" . $PHP_AUTH_USER . " [ " . date("m/d/y @ g:iA") . " ] Status Changed To: Closed</div>" . $oldProgress;

        // Update the ticket to close it
        $query = "UPDATE tickets SET status = 'Closed', closeddate = '$thedate', progress = '" . addslashes( $progress ) . "', pickupdate = '$pickupdate' WHERE tcnumber = '$tcnumber';";
        $result = pg_query( $query );
        if( pg_affected_rows( $result ) < 1 ) {
          echo "<div class=error>Unable to close ticket.</div>\n";
          echo "<div class=error>$query</div>\n";
        }

        // Clear user's password for machine
        $query = "UPDATE machines SET password = '' WHERE tcnumber = '$tcnumber';";
        $result = pg_query( $query );
        if( pg_affected_rows( $result ) < 1 )
          echo "<div class=error>Unable to clear user's password.</div>\n";

        // Update stats
        $query = "SELECT statid, value FROM stats WHERE name = 'closed' AND date = '$thedate' LIMIT 1;";
        if( $row = pg_fetch_assoc( $result ) ) {

          $statid = $row[ 'statid' ];
          $value = $row[ 'value' ];

          $value++;

          $query = "UPDATE stats SET value = '$value' WHERE statid = '$statid';";
          $result = pg_query( $query );
          if( pg_affected_rows( $result ) < 1 )
            echo "<div class=error>Unable to update stats.</div>\n";

        }

      }
      else {

        echo "<div class=error>Unable to find ticket to close it.</div>\n";
        
      }

    }

    //---- Get Ticket Info again
    $query = "SELECT * FROM tickets WHERE lower(tcnumber) = lower('$tcnumber') ORDER BY date ASC;";
    $result = pg_query( $query );
    if( $row = pg_fetch_assoc( $result ) ) {
      
      $DB_username = $row[ 'username' ];
      $tcnumber = $row[ 'tcnumber' ];
      $DB_date = $row[ 'date' ];
      $DB_accepteddate = $row[ 'accepteddate' ];
      $DB_acceptedby = $row[ 'acceptedby' ];
      $DB_description = stripslashes( $row[ 'description' ] );
      $DB_summary = stripslashes( $row[ 'summary' ] );
      $DB_status = $row[ 'status' ];
      $DB_progress = stripslashes( $row[ 'progress' ] );
      $DB_hdbackuplocation = stripslashes( $row[ 'hdbackuplocation' ] );
      $DB_signoff = $row[ 'signoff' ];
	  $DB_priority = $row['priority'];
	  $DB_faculty = $row['faculty'];
      $DB_repairinstall = $row[ 'repairinstall' ];
      $DB_fullinstall = $row[ 'fullinstall' ];

    }

    // Get Machine Info
    $query = "SELECT inventory FROM machines WHERE lower(tcnumber) = lower('$tcnumber') LIMIT 1;";		
    $result = pg_query($query);
    if( $row = pg_fetch_assoc( $result ) )
      $DB_inventory = trim( $row[ 'inventory' ] );
    else
      $DB_inventory = "";

    // Get User Info
    $query = "SELECT name FROM users WHERE lower(username) LIKE lower('%$DB_username%') LIMIT 1;";
    $result = pg_query( $query );
    if( $row = pg_fetch_assoc( $result ) )
      $DB_fullname = trim( $row[ 'name' ] );
    else
      $DB_fullname = "(name not found)";

  }

?>

<? 

// Display page if it's a valid ticket

if( $ticketFound ) {

  //---- Reminder Message
  echo "<div class=notice>REMEMBER: Users will receive a receipt of work for their problem. They will be able to read everything you write. Please be professional when updating tickets, but remember to document everything.</div>\n\n";

?>
<form enctype=multipart/form-data method=post action=" <? echo $_SERVER[ 'REQUEST_URI' ] ?> ">
<input type=hidden name="POST_update" value="yes">

<?

  //---- Ticket Number
  echo "<div id=TicketNumber>\n";
  echo "  <div class=Title>$tcnumber</div>\n";
  if ($DB_status == "Closed") {
	  echo "<a href='/admin/receipt.php?tcnumber=$tcnumber'>Printer Friendly View</a>\n";
  }
	echo "</div>\n\n";

  //---- User

  echo "<div id=\"UserInfo\">\n\n";
  echo "  <h3 class=BoxLabel onclick=\"expand( this )\">User</h3>\n";
  echo "  <table class=Box>\n\n";

  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Name: </td>\n";
  echo "      <td> $DB_fullname </td>\n";
  echo "    </tr>\n\n";
  
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Username: </td>\n";
  echo "      <td> $DB_username </td>\n";
  echo "    </tr>\n\n";

  echo "  </table>\n\n";

  echo "  <div class=\"info\"> <a href=\"user.php?uname=$DB_username\">User Info</a> </div>\n";
  echo "  <div class=\"info\"> <a href=\"https://ipedit.rit.edu/search.php?whichsearch=dhcp&username=$DB_username&method=detail\">IPedit Info</a> </div>\n";
  
  echo "</div>\n\n";

  //---- Machine

  echo "<div id=\"MachineInfo\">\n\n";
  echo "  <h3 class=BoxLabel onclick=\"expand( this )\">Machine</h3>\n";
  echo "  <table class=Box>\n";

  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Description: </td>\n";
  echo "      <td> $DB_machinedesc </td>\n";
  echo "    </tr>\n\n";
  
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Login Account: </td>\n";
  echo "      <td> $DB_accountname </td>\n";
  echo "    </tr>\n\n";
  
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Login Password: </td>\n";
  echo "      <td> $DB_passwd </td>\n";
  echo "    </tr>\n\n";
  
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Warranty: </td>\n";
  echo "      <td> $DB_warranty </td>\n";
  echo "    </tr>\n\n";

	echo "    <tr class=\"ticket\">\n";
  echo "      <td> Location: </td>\n";

  if ($DB_location == 1)
  {
    echo "      <td><input type=\"radio\" name=\"POST_location\" value=\"1\" checked/>Tech Center<br/>\n";
    echo "        <input type=\"radio\" name=\"POST_location\" value=\"0\"/>Office</td>\n";
  }
  else
  {
    echo "      <td><input type=\"radio\" name=\"POST_location\" value=\"1\"/>Tech Center<br/>\n";
    echo "      <input type=\"radio\" name=\"POST_location\" value=\"0\" checked/>Office</td>\n";
  }
  echo "    </tr>\n\n";

  echo "  </table>\n";

  echo "  <div class=\"info\"> <a href=\"machine.php?mid=$DB_mid\">Edit This Machine</a> </div>\n";
  echo "  <div class=\"info\"> <a href=\"machine.php?uname=$DB_username\">Edit All User's Machines</a> </div>\n";

  echo "</div>\n\n";

  //---- Ticket

  echo "<div id=\"TicketInfo\">\n";
  echo "  <h3 class=BoxLabel onclick=\"expand( this )\">Ticket</h3>\n";
  echo "  <table class=Box>\n";

  // Barcode
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Barcode: </td>\n";
  echo "      <td> <a href=\"label.php?t=$tcnumber\"><img src=\"../images/bc.jpg\" border=\"0\"></a> </td>\n";
  echo "    </tr>\n\n";

  // Creation Date
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Created on: </td>\n";
  echo "      <td> $DB_date </td>\n";
  echo "    </tr>\n\n";

  // Accepted Date
  echo "    <tr class=\"ticket\">\n";
  echo "      <td> Accepted on: </td>\n";
  echo "      <td> $DB_accepteddate by $DB_acceptedby </td>\n";
  echo "    </tr>\n\n";

  // Senior Signoff
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Senior Signoff:</td>\n";
  echo "    <td> <input type=text maxlength=3 size=3 name=\"POST_signoff\" value=\"$DB_signoff\"> </td>\n";
  echo "  </tr>\n\n";

  // Status
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Status:</td>\n";
  echo "    <td> <select name=\"POST_status\" size=\"1\">\n";

    // Fill in Status Menu. The query is run now to avoid
    // storing arrays (maybe it's better to do it with arrays).
    
    $query = "SELECT name FROM status WHERE enabled = 'true' ORDER BY name ASC;";
    $result = pg_query( $query );
    while( $row = pg_fetch_assoc( $result ) ) {

      $DB_statusname = trim( $row[ 'name' ] );

      // Make the current status the selected one.
      if( $DB_status == $DB_statusname )
        echo "       <option selected>$DB_statusname</option>\n";
      // Filter out "Closed" if ticket isn't already closed.
      else if( ( $DB_statusname != "Closed" || $DB_status == "Closed" ) && $DB_statusname != "Re-Queued" && $DB_statusname != "New" && $DB_statusname != "Reopen" )
        echo "       <option>$DB_statusname</option>\n";

    }

    echo "    </select> ";

    // Display when status was changed to its current value.
    if( $DB_statustime )
      echo "since $DB_statustime ";

  echo "    </td>\n";
  echo "  </tr>\n\n";

if ($PHP_AUTH_USER == 'dmshelp' || $PHP_AUTH_USER == 'trdhelp' || $PHP_AUTH_USER == 'vxiacc'
    || $PHP_AUTH_USER == 'rltrcc' || $PHP_AUTH_USER == 'ammrcc' || $PHP_AUTH_USER == 'jdwrcc') {
  echo "  <tr class=\"ticket\"\n";
  echo "    <td>High Priority:</td>\n";
  echo "    <td>";
  echo "      <input type=checkbox name=\"POST_priority\" value=\"1\"";
    if ($DB_priority == 1)
      echo " checked";
  echo "/>\n";
  echo "    </td>";
  echo "  </tr>";
}

  echo "  <tr class=\"ticket\"\n";
  echo "    <td>Faculty Machine:</td>\n";
  echo "    <td>";
  echo "      <input type=checkbox name=\"POST_faculty\" value=\"1\"";
    if ($DB_faculty == 1)
      echo " checked";
  echo "/>\n";
  echo "    </td>";
  echo "  </tr>";

  // Problem Description
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>User's Problem Description:</td>\n";
  echo "    <td>$DB_description</td>\n";
  echo "  </tr>\n\n";

  // Summary
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Problem Summary:</td>\n";
  echo "    <td> <input type=\"text\" name=\"POST_summary\" size=40 maxlength=\"200\" value=\"$DB_summary\"> (be brief!) </td>\n";
  echo "  </tr>\n\n";

  echo "</table>\n\n";

  //****TODO: Past Tickets

  echo "</div>\n\n";

  //---- More Ticket Info

  echo "<div id=\"TicketInfo2\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">Ticket Info</h3>\n";
  echo "<table class=Box>\n";

  // Inventory
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Inventory:</td>\n";
  echo "    <td>"; 
    echo stripslashes( $DB_inventory );
  echo "</td>\n";
  echo "  </tr>\n\n";

  // Inventory Update Field
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Inventory Updates:</td>\n";
  echo "    <td> <input type=\"text\" name=\"POST_inventoryupdate\" size=40 value=\"\"> </td>\n";
  echo "  </tr>\n\n";

  // Backup Location
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Backup Location:</td>\n";
  echo "    <td> <input type=\"text\" name=\"POST_hdbackuplocation\" size=40 maxlength=100 value=\"$DB_hdbackuplocation\"> </td>\n";
  echo "  </tr>\n\n";

  // Repair Install Permission
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Repair Install Permission:</td>\n";
    if( $DB_repairinstall == "t" )
      echo "    <td> Yes </td>\n";
    else
      echo "    <td> No; Give permission: <input type=\"checkbox\" name=\"POST_repairinstall\"> </td>\n";
  echo "  </tr>\n\n";

  // Full Install Permission
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>Full Install Permission:</td>\n";
    if( $DB_fullinstall == "t" )
      echo "    <td> Yes ";
    else
      echo "    <td> No; Give permission: <input type=\"checkbox\" name=\"POST_fullinstall\"> </td>\n";
  echo "  </tr>\n\n";

  echo "</table>\n\n";

  echo "</div>\n\n";

  //---- Common Tasks

  echo "<div id=\"CommonTasks\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">Common Tasks</h3>\n";
  echo "<table class=Box>\n";

  // Table Heading
  echo "<tr class=\"ticket\"> 
          <th>Task</th>
          <th>Date Completed</th>
        </tr>\n\n";

  // get a list of the tasks for this ticket. The checkboxes are not checked
  // if they have been done already to allow for re-updating upon 
  // completing a task more than once. Also, show the dates that the tasks
  // were completed
  $query = "SELECT tasks.name AS name, tasks.required AS required, link.task AS task, link.date AS date FROM tasks, link WHERE tasks.enabled = 'TRUE' AND link.ticket = '$tcnumber' AND tasks.taskid = link.task ORDER BY tasks.required DESC, taskid ASC;";
  $result = pg_query( $query );
  while( $row = pg_fetch_assoc( $result ) ) {

    // Flag required tasks.
    if( $row[ 'required' ] == "t" )
      echo "  <tr class=required>\n";
    else
      echo "  <tr class=ticket>\n";

    echo "    <td class=taskname> " . $row[ 'name' ] . " </td>\n";
    echo "    <td class=taskdate> <input type=checkbox name=\"task[]\" value=\"";
    echo $row[ 'task' ] . "\">" . $row[ 'date' ] . "</td>\n";

    echo "  </tr>\n\n";

  }
  
  echo "</table>\n\n";

  echo "</div>\n\n";

  //---- Update Buttons

  echo "<div id=\"Buttons\">\n";

  // Show different buttons depending on the ticket's status.

  if( $DB_status == "Closed" ) {

    echo "<div class=notice> This ticket can be re-opened. However, the old ticket page needs to be used. </div>\n";

  }
  else {

    // Update Ticket Button
    ?>
    <input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
    <input type="hidden" name="POST_update" value="yes">
    <input type="submit" name="POST_submit" value="Update">
    <?

    // Only show Close Ticket Button if all the required criteria are met, or
    // the field used for closing an unfinished ticket is checked.

    $closable = false;

    // Check for override field.
    $query = "SELECT link.date AS date FROM link, tasks WHERE link.ticket = '$tcnumber' AND tasks.name = 'Close Ticket With Machine Unfinished' AND link.task = tasks.taskid;";
    $result = pg_query( $query );
    while( $row = pg_fetch_assoc( $result ) ) {

      if( trim( $row[ 'date' ] ) != "" )
        $closable = true;

    }

    // Check required tasks
    if( !$closable ) {

      // Assume true, and check for false
      $closable = true;

      $query = "SELECT link.date AS date FROM link, tasks WHERE link.ticket = '$tcnumber' AND tasks.required = 'TRUE' AND link.task = tasks.taskid;";
      $result = pg_query( $query );
      while( $row = pg_fetch_assoc( $result ) ) {

        // If it doesn't have a date, then it's not closable.
        if( trim( $row[ 'date' ] ) == "" )
          $closable = false;

      }

    }

    // Display message if it's a closable ticket.
    if( $closable ) {

      echo "  <input type=submit name='POST_close' value='Close'>\n";

    }

  }

  echo "</div>\n\n";

  //---- Updates

  echo "<div id=Updates>\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">Updates</h3>\n";
  echo "<table class=Box>\n";

  //---- Flag someone
  echo "<tr class=ticket>";
  echo "<td><div class=label>Flag an RCC</div></td>\n";
  echo "<td><input type=text size=7 name=\"POST_flag\"></td>";  
	echo "</tr>";

  // Update Progress
  echo "  <tr class=ticket>\n";
  echo "    <td class=label>Update Progress:</td>\n";
  echo "    <td class=content> <textarea rows=\"10\" cols=\"54\" name=\"POST_progress\" wrap=\"hard\">\n\n\nTo Do:\n</textarea> </td>\n";
  echo "  </tr>\n\n";

  // Display Progress
  echo "  <tr class=ticket>\n";
  echo "    <td class=label>Current Progress:</td>\n";
  echo "    <td class=content> <div id=Progress> $DB_progress </div> </td>\n";
  echo "  </tr>\n\n";

  echo "</table>\n\n";
  echo "</div>\n\n";

  echo "<div id=\"CannedResponse\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">Canned Responses</h3>\n";
  echo "<table class=Box>\n";

  // Table Heading
  echo "<tr class=\"ticket\">
    <th>Select Response</th>
    <th/>
    </tr>\n\n";

	echo "<tr class=\"ticket\">";
	echo "<td>Computer Complete</td>";
	echo "<td><div class=\"info\"> <a href=\"canned.php?value=done&amp;name=$DB_fullname&amp;tcnumber=$tcnumber\" target=\"_blank\" onclick=\"return!open(this.href, this.target, 'width=800,height=800,screenX=150,screenY=100')\">Inform User</a></div></td>\n";
	echo "</tr>";

	echo "<tr class=\"ticket\">";
	echo "<td>Need Windows / System CD</td>";
	echo "<td><div class=\"info\"> <a href=\"canned.php?value=cd&amp;name=$DB_fullname&amp;tcnumber=$tcnumber\" target=\"_blank\" onclick=\"return!open(this.href, this.target, 'width=800,height=800,screenX=150,screenY=100')\">Request CD</a></div></td>\n";
	echo "</tr>";

	if( $DB_repairinstall != "t" ) {
		echo "<tr class=\"ticket\">";
		echo "<td>Repair Install Permission</td>";
		echo "<td><div class=\"info\"> <a href=\"canned.php?value=repair&amp;name=$DB_fullname&amp;tcnumber=$tcnumber\" target=\"_blank\" onclick=\"return!open(this.href, this.target, 'width=800,height=800,screenX=150,screenY=100')\">Request Repair Install</a></div></td>\n";
		echo "</tr>";
	}

	if( $DB_fullinstall != "t" ) {
		echo "<tr class=\"ticket\">";
		echo "<td>Full Install Permission</td>";
		echo "<td><div class=\"info\"> <a href=\"canned.php?value=full&amp;name=$DB_fullname&amp;tcnumber=$tcnumber\" target=\"_blank\" onclick=\"return!open(this.href, this.target, 'width=800,height=800,screenX=150,screenY=100')\">Request Full Install</a></div></td>\n";
		echo "</tr>";
	}

	echo "<tr class=\"ticket\">";
	echo "<td>Create Blank Email</td>";
	echo "<td><div class=\"info\"> <a href=\"canned.php?value=&amp;name=$DB_fullname&amp;tcnumber=$tcnumber\" target=\"_blank\" onclick=\"return!open(this.href, this.target, 'width=800,height=800,screenX=150,screenY=100')\">Blank Email</a></div></td>\n";
	echo "</tr>";

	echo "</table>";

  echo "</form>\n\n";

}

// Close database connection
pg_close( $db_connect );

?>

</body>

</html>

