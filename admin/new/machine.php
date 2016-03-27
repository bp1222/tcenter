<?  include( "connection.inc" ); ?>

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

  // Extract post data
  @extract( $_POST );

  // This page can be used to view, update, and delete a machine.
  if( $Update ) {


    // Check validity of mac address
    if( $POST_mac && preg_match( "/^([A-Fa-f0-9]{1,2}[:-]){5}[A-fa-f0-9]{1,2}$/", $POST_mac ) ) {

      $update_query = "UPDATE machines SET  description = '$POST_description', username = '$POST_username', password = '$POST_password', snumber = '$POST_snumber', mac = '$POST_mac' WHERE mid = '$POST_mid';";

    }
    else if( !$POST_mac ) {

      $update_query = "UPDATE machines SET  description = '$POST_description', username = '$POST_username', password = '$POST_password', snumber = '$POST_snumber', mac = NULL WHERE mid = '$POST_mid';";

    }
    else {
    
      $update_query = "UPDATE machines SET  description = '$POST_description', username = '$POST_username', password = '$POST_password', snumber = '$POST_snumber' WHERE mid = '$POST_mid';";

      echo "<div class=error> Invalid Mac Address. It must look like this: 00-00-00-00-00-00 or 00:00:00:00:00:00 </div>\n";

    }


    $result = pg_query( $update_query );
    $numrows = pg_affected_rows( $result );

    if( $numrows == 1 )
      echo "<div class=notice> Updated Machine ($numrows record) </div>\n";
    else if( $numrows > 1 )
      echo "<div class=notice> Updated Machine ($numrows records) </div>\n";
    else
      echo "<div class=error> Failed to Update Machine </div>\n";

  }
  else if( $Delete ) {

    echo "<div class=notice> Deleting Machine </div>\n";

  }

  // Display machine(s) 

  // Search by most identifyable info first
  if( $mid )
    $query = "SELECT * FROM machines WHERE mid = '$mid' ORDER BY mid;";
  else if( $tcnumber )
    $query = "SELECT * FROM machines WHERE tcnumber = '$tcnumber' ORDER BY mid;";
  else if( $uname )
    $query = "SELECT * FROM machines WHERE owner = '$uname' ORDER BY mid;";
  else
    $query = "SELECT * FROM machines WHERE owner = '$PHP_AUTH_USER' ORDER BY mid;";

  $result = pg_query( $query );
  if( pg_num_rows( $result ) > 0 ) {

    while( $row = pg_fetch_assoc( $result ) ) {

      echo "<div class=ticketbox>\n\n";

      // Make sure this page gets the same URL, so that the same info is displayed.
      echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"" . $_SERVER[ 'REQUEST_URI' ] . "\">\n";
      echo "<input type=hidden name=\"POST_mid\" value=\"" . $row[ 'mid' ] . "\">\n\n";

      echo "<table class=tickets>\n\n";
      
      echo "  <tr class=ticket>\n";
      echo "    <td>Description:</td>\n";
      echo "    <td> <input type=text name=\"POST_description\" maxlength=\"200\" value=\"" . $row[ 'description' ] . "\"> </td>\n";
      echo "  </tr>\n\n";

      echo "  <tr class=ticket>\n";
      echo "    <td>Login Account:</td>\n";
      echo "    <td> <input type=text name=\"POST_username\" maxlength=\"50\" value=\"" . $row[ 'username' ] . "\"> </td>\n";
      echo "  </tr>\n\n";

      echo "  <tr class=ticket>\n";
      echo "    <td>Login Password:</td>\n";
      echo "    <td> <input type=text name=\"POST_password\" maxlength=\"50\" value=\"" . $row[ 'password' ] . "\"> </td>\n";
      echo "  </tr>\n\n";

      echo "  <tr class=ticket>\n";
      echo "    <td>Serial/Service Tag #:</td>\n";
      echo "    <td> <input type=text name=\"POST_snumber\" maxlength=\"50\" value=\"" . $row[ 'snumber' ] . "\"> </td>\n";
      echo "  </tr>\n\n";

      echo "  <tr class=ticket>\n";
      echo "    <td>MAC Address:</td>\n";
      echo "    <td> <input type=text name=\"POST_mac\" maxlength=\"50\" value=\"" . $row[ 'mac' ] . "\"> </td>\n";
      echo "  </tr>\n\n";

      echo "</table>\n\n";

      echo "<input type=submit name=\"Update\" value=\"Update\">\n";
      echo "<input type=reset>\n";
      echo "<input type=submit name=\"Delete\" value=\"Delete\">\n\n";

      echo "</form>\n\n";
      
      echo "</div>\n\n";

    }

  }
  else
    echo "<div class=error>Unable to find any machines.</div>\n";

?>

<?  pg_close($db_connect); ?>

</body>

</html>

