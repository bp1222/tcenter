<?  include( "connection.inc" ); ?>

<?

function showTickets( $result ) {

  while( $row = pg_fetch_assoc( $result ) ) {
		if ($row['priority'] == 1) {
    	echo "<tr class=tickethigh><td class=tickethigh>\n\n";
		} else if ($row['faculty']) {
    	echo "<tr class=tickethigh><td class=ticketfaculty>\n\n";
		} else {
    	echo "<tr class=ticket><td class=ticket>\n\n";
		}
    echo "  <span class=ticketnumber> ";
    
    if( $row[ 'accepteddate' ] )
      echo "<a class=ticketnumber href='ticket.php?";
    else
      echo "<a class=ticketnumber href='accept.php?";
      
    echo "tcnumber=" . $row[ 'tcnumber' ] . "'>" . $row[ 'tcnumber' ];
    echo "</a> </span>\n\n";

    ?>

      <span class=machine> (<?
        echo $row[ 'description' ];
      ?>) </span>

      <span class=lastupdate> Last Updated on <?
        echo $row[ 'lastupdateddate' ];
      ?> by <?
        echo $row[ 'lastupdatedby' ];
      ?> </span>

      <span class=summary> Summary: <?
        echo $row[ 'summary' ];
      ?> </span>

    </td></tr>

  <?

  }

}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title>ITS Resnet - Tickets</title>
<? include( "ITS-head.html" ); ?>
<link rel="stylesheet" type="text/css" href="tcenter.css">

</head>

<body>


<? include( "ITS-topbar.php" ); ?>

<?

echo "<div class=frame>\n\n";

// set variables to null to overwrite url injection
$global = "";

// get the post data
@extract($_POST);

//
// Show search forms
//

?>

<table class=tickets>
  <tr class=ticket>
    <form method=GET action="tickets.php">
      <td class=ticket> <input type=text name="search" value="<? echo $search; ?>"> </td>
      <td class=ticket> <input type=submit value="Find Tickets"> </td>
    </form>
  </tr>
</table>

<?
  
if( $search ) {

  //
  // Search in ticket number and username
  //

  $query = "SELECT tickets.accepteddate, tickets.tcnumber, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND ( tickets.status = 'In Progress' OR tickets.status = 'Queued' OR tickets.status = 'Re-Queued' OR tickets.status = 'Awaiting Pickup' or tickets.status = 'Pending Customer' ) AND ( tickets.tcnumber LIKE '%$search%' OR tickets.username LIKE '%$search%' ) ORDER BY tickets.statusoverride DESC, tickets.statustime ASC, tickets.tid ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">$numrows Tickets Found from User</h3>\n";
  echo "<table class=Box>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Search in rccs
  //

  $query = "SELECT tickets.accepteddate, tickets.tcnumber, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND ( tickets.status = 'In Progress' OR tickets.status = 'Queued' OR tickets.status = 'Re-Queued' OR tickets.status = 'Awaiting Pickup' or tickets.status = 'Pending Customer' ) AND ( tickets.acceptedby LIKE '%$search%' OR tickets.lastupdatedby LIKE '%$search%' OR tickets.signoff LIKE '%$search%' ) ORDER BY tickets.statusoverride DESC, tickets.statustime ASC, tickets.tid ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">$numrows Tickets Found from RCCs</h3>\n";
  echo "<table class=Box>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Search in ticket contents
  //

  $query = "SELECT tickets.accepteddate, tickets.tcnumber, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND ( tickets.status = 'In Progress' OR tickets.status = 'Queued' OR tickets.status = 'Re-Queued' OR tickets.status = 'Awaiting Pickup' or tickets.status = 'Pending Customer' ) AND ( tickets.progress LIKE '%$search%' OR tickets.summary LIKE '%$search%' OR tickets.description LIKE '%$search%' OR machines.description LIKE '%$search%' ) ORDER BY tickets.statusoverride DESC, tickets.statustime ASC, tickets.tid ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">$numrows Tickets from Contents</h3>\n";
  echo "<table class=Box>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

}
else {

  //
  // In Progress Tickets
  //

  $query = "SELECT DISTINCT ON (tickets.tid) tickets.accepteddate, tickets.tcnumber, tickets.faculty, tickets.priority, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND tickets.status = 'In Progress' AND tickets.queue = 'Resnet' ORDER BY tickets.tid ASC, tickets.statusoverride DESC, tickets.statustime ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">$numrows Tickets In Progress</h3>\n";
  echo "<table class=Box>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Queued Tickets
  //

  $query = "SELECT DISTINCT ON (tickets.tid) tickets.accepteddate, tickets.tcnumber, tickets.faculty, tickets.priority, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND ( tickets.status = 'Queued' OR tickets.status = 'Re-Queued' ) AND tickets.queue = 'Resnet' ORDER BY tickets.tid ASC,  tickets.statusoverride DESC, tickets.statustime ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\">$numrows Tickets Queued</h3>\n";
  echo "<table class=Box>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Pending Customer Tickets
  //

  $query = "SELECT DISTINCT ON (tickets.tid) tickets.accepteddate, tickets.tcnumber, tickets.faculty, tickets.priority, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND tickets.status = 'Pending Customer' AND tickets.queue = 'Resnet' ORDER BY tickets.tid ASC, tickets.statusoverride DESC, tickets.statustime ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\" style='background-image: url(/images/collapsed.png)'>$numrows Tickets Pending Customer</h3>\n";
  echo "<table class=Box style='display: none'>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Awaiting Pickup Tickets
  //

  $query = "SELECT DISTINCT ON (tickets.tid) tickets.accepteddate, tickets.tcnumber, tickets.faculty, tickets.priority, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND tickets.status = 'Awaiting Pickup' AND tickets.queue = 'Resnet' ORDER BY tickets.tid ASC, tickets.statusoverride DESC, tickets.statustime ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\" style='background-image: url(/images/collapsed.png)'>$numrows Tickets Awaiting Pickup</h3>\n";
  echo "<table class=Box style='display: none'>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Administrative Hold
  //

  $query = "SELECT DISTINCT ON (tickets.tid) tickets.accepteddate, tickets.tcnumber, tickets.faculty, tickets.priority, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND tickets.status = 'Administrative Hold' AND tickets.queue = 'Resnet' ORDER BY tickets.tid ASC, tickets.statusoverride DESC, tickets.statustime ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=\"ticketbox\">\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\" style='background-image: url(/images/collapsed.png)'>$numrows Tickets on Admin Hold</h3>\n";
  echo "<table class=Box style='display: none'>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

  //
  // Unnaccepted Tickets
  //

  $query = "SELECT DISTINCT ON (tickets.tid) tickets.accepteddate, tickets.tcnumber, tickets.faculty, tickets.priority, tickets.summary, tickets.lastupdateddate, tickets.lastupdatedby, machines.description FROM tickets, machines WHERE tickets.tcnumber = machines.tcnumber AND tickets.status = 'New' ORDER BY tickets.tid ASC, tickets.statusoverride DESC, tickets.statustime ASC;";
  $result = pg_query( $query );
  $numrows = pg_num_rows( $result );

  echo "<div class=ticketbox>\n";
  echo "<h3 class=BoxLabel onclick=\"expand( this )\" style='background-image: url(/images/collapsed.png)'>$numrows Unnaccepted Tickets</h3>\n";
  echo "<table class=Box style='display: none'>\n\n";

  showTickets( $result );

  echo "</table>\n";
  echo "</div>\n\n";

}

// close the database connection
pg_close($db_connect)
?>

</div>

</body>

</html>

