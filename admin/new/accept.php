
<?  include("connection.inc"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title>Accept <? echo $tcnumber; ?></title>
<? include( "ITS-head.html" ); ?>
<link rel="stylesheet" type="text/css" href="tcenter.css">

<script type="text/javascript">

  function enableAccept() {
    
    if( document.ticket_form.POST_summary.value == '' || 
        document.ticket_form.POST_acceptedby.value == '' )
      document.ticket_form.POST_accept.disabled = true;
    else
      document.ticket_form.POST_accept.disabled = false;

  }

  if( document.all || document.getElementById )
    setInterval( "enableAccept()", 100 );

</script>

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

    //---- Get Ticket Info
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
      $DB_faculty = stripslashes( $row[ 'faculty' ] );
      $DB_status = $row[ 'status' ];
      $DB_progress = stripslashes( $row[ 'progress' ] );
      $DB_hdbackuplocation = stripslashes( $row[ 'hdbackuplocation' ] );
      $DB_signoff = $row[ 'signoff' ];
      $DB_repairinstall = $row[ 'repairinstall' ];
      $DB_fullinstall = $row[ 'fullinstall' ];

    }

    // Get Machine Info
    $query = "SELECT mid, inventory, description, username, password, warranty FROM machines WHERE lower(tcnumber) = lower('$tcnumber');";		
    $result = pg_query( $query );
    if( $row = pg_fetch_assoc( $result ) ) {

      $DB_mid = trim( $row[ 'mid' ] );
      $DB_inventory = trim( $row[ 'inventory' ] );
      $DB_machinedesc = trim( $row[ 'description' ] );
      $DB_accountname = trim( $row[ 'username' ] );
      $DB_passwd = trim( $row[ 'password' ] );
      $DB_warranty = trim( $row[ 'warranty' ] );

    }

    // Get User Info
    $query = "SELECT name, phone, email FROM users WHERE lower(username) LIKE lower('%$DB_username%') LIMIT 1;";
    $result = pg_query( $query );
    if( $row = pg_fetch_assoc( $result ) ) {
      $DB_fullname = trim( $row[ 'name' ] );
      $DB_phone = $row[ 'phone' ];
      $DB_email = $row[ 'email' ];
    }
    else
      $DB_fullname = "(not found)";

  }

?>

<? 

// Display page if it's a valid ticket

if( $ticketFound ) {

  //---- Reminder Message
  echo "<div class=notice>REMEMBER: Users will receive a receipt of work for their problem. They will be able to read everything you write. Please be professional when updating tickets, but remember to document everything.</div>\n\n";

?>
<form name="ticket_form" enctype=multipart/form-data method=post action="../../admin/new/ticket.php?tcnumber=<? echo $tcnumber ?>">
<input type=hidden name="POST_update" value="yes">

<?

  //---- Ticket Number
  echo "<div id=TicketNumber>\n";
  echo "  <div class=Title>$tcnumber</div>\n";
  echo "<a href='/admin/receipt.php?tcnumber=$tcnumber'>Printer Friendly View</a>\n";
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
  
  echo "    <tr class=ticket>\n";
  echo "      <td> Phone #: </td>\n";
  echo "      <td> $DB_phone </td>\n";
  echo "    </tr>\n\n";
  
  echo "    <tr class=ticket>\n";
  echo "      <td> Email: </td>\n";
  echo "      <td> $DB_email </td>\n";
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

  // Problem Description
  echo "  <tr class=\"ticket\">\n";
  echo "    <td>User's Problem Description:</td>\n";
  echo "    <td>$DB_description</td>\n";
  echo "  </tr>\n\n";

  // Summary
  echo "  <tr class=ticket>\n";
  echo "    <td>Problem Summary:</td>\n";
  echo "    <td> <input type=text name=\"POST_summary\" size=40 maxlength=\"200\" value=\"$DB_summary\"> (be brief!) </td>\n";
  echo "  </tr>\n\n";

  echo "  <tr class=ticket>\n";
  echo "    <td>Faculty Machine:</td>\n";
  echo "    <td> <input type=checkbox name=\"POST_faculty\"></td>\n";
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

  //---- Update Buttons

  echo "<div id=Buttons>\n";

  if( !$DB_accepteddate ) {

      ?>
      <input type="hidden" name="tcnumber" value="<? echo $tcnumber; ?>">
      <input type="hidden" name="POST_update" value="yes">
      <input type="hidden" name="POST_status" value="Queued">
      <input type="submit" name="POST_accept" value="Accept Ticket">
      by

<?php
	// For the accept field we only want the 3 initials of our staff
	// username
	$authedInitials = substr( $PHP_AUTH_USER, 0, 3 );
?>

      <input type=input maxlength=3 name="POST_acceptedby" value="<? echo $authedInitials; ?>" onclick='enableAccept()'>
      <?

  }

  echo "</div>\n\n";

  //---- Updates

  echo "<div id=Updates>\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">Updates</h3>\n";
  echo "<table class=Box>\n";

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

  echo "</form>\n\n";

}

// Close database connection
pg_close( $db_connect );

?>

</body>

</html>

