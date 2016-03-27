<?  include( "connection.inc" ); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title>ITS Resnet - Tickets</title>
<? include( "ITS-head.html" ); ?>
<link rel="stylesheet" type="text/css" href="tcenter.css">

</head>

<body>

<div id="frame">

<? //include( "ITS-topbar.php" ); ?>
<?

// set variables to null to overwrite url injection
$global = "";

// get the post data
@extract($_POST);

if (!isset($start)) $start = 0;
      
// Build queries to run later
$query_inprogress = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE status = 'In Progress' AND queue = 'Resnet' ORDER BY date ASC, tid ASC;";
$query_queued = "SELECT username, tcnumber, status, tnumber, date, summary, lastupdateddate, lastupdatedby, rescon FROM tickets WHERE ( status = 'Queued' OR status = 'Re-Queued' ) AND queue = 'Resnet' ORDER BY date ASC, tid ASC;";

//
// In Progress Tickets
//

$result = pg_query($query_inprogress);
$numrows = pg_num_rows( $result );

echo "<div class=\"ticketbox\">\n\n";

echo "<div class=\"ticketcount\">$numrows Tickets In Progress</div>\n\n";

echo "<table class=\"tickets\">\n\n";

while ($row = pg_fetch_assoc($result)) {

  $username = trim($row['username']);
  $tcnumber = trim($row['tcnumber']);
  $date = trim($row['date']);
  $summary = stripslashes(trim($row['summary']));
  $tstatus = trim($row['status']);
  $rescon = trim($row['rescon']);
  $lastupdateddate = trim($row['lastupdateddate']);
  $lastupdatedby = trim($row['lastupdatedby']);

  ?>
  <tr><td class="ticket">

    <span class="ticketnumber"> <a class="ticketnumber" href="/admin/tickets.php?tcnumber=<? echo $tcnumber; ?>&referrer=tickets"><?echo $tcnumber;?></a> </span>

    <span class="lastupdate"> Last Updated on <?echo $lastupdateddate;?> by <?echo $lastupdatedby;?> </span>

    <span class="summary"> Summary: <?echo $summary;?> </span>

  </td></tr>

<?

}

echo "</table>\n</div>\n";

//
// Queued Tickets
//

$result = pg_query($query_queued);
$numrows = pg_num_rows( $result );

echo "<div class=\"ticketbox\">\n\n";

echo "<div class=\"ticketcount\">$numrows Tickets Queued</div>\n\n";

echo "<table class=\"tickets\">\n\n";

while ($row = pg_fetch_assoc($result)) {

  $username = trim($row['username']);
  $tcnumber = trim($row['tcnumber']);
  $date = trim($row['date']);
  $summary = stripslashes(trim($row['summary']));
  $tstatus = trim($row['status']);
  $rescon = trim($row['rescon']);

  ?>
  <tr class="ticket"><td class="ticket">

    <span class="ticketnumber"> <a class="ticketnumber" href="/admin/tickets.php?tcnumber=<? echo $tcnumber; ?>&referrer=tickets"><?echo $tcnumber;?></a> </span>

    <span class="lastupdate"> Last Updated on <?echo $lastupdateddate;?> by <?echo $lastupdatedby;?> </span>

    <span class="summary"> Summary: <?echo $summary;?> </span>

  </td></tr>

<?

}

echo "</table>\n\n</div>\n\n";

// close the database connection
pg_close($db_connect)
?>

</div>

</body>

</html>

