
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?  include("connection.inc"); ?>

<html>

<head>
<title>User Information</title>
<? include( "ITS-head.html" ); ?>
<link rel="stylesheet" type="text/css" href="tcenter.css">
</head>

<body>

<? include( "ITS-topbar.php" ); ?>

<?

	// Extract the post data
	@extract( $_POST );

  //
  // If update was clicked, update the user info.
  //

  if( $POST_update ) {

    $POST_name = trim( $POST_name );
    $POST_username = trim( $POST_username );
    $POST_phone = trim( $POST_phone );
    $POST_address = trim( $POST_address );
    $POST_email = trim( $POST_email );

    $update_query = "UPDATE users SET name = '$POST_name', username = '$POST_username', phone = '$POST_phone', address = '$POST_address', email = '$POST_email' WHERE uid = '$POST_uid';";

    $result = pg_query( $update_query );
    $numrows = pg_affected_rows( $result );

    if( $numrows == 1 )
      echo "<div class=notice> Updated $POST_username ($numrows record) </div>\n";
    else if( $numrows > 1 )
      echo "<div class=notice> Updated $POST_username ($numrows records) </div>\n";
    else
      echo "<div class=notice> Unable to Update $POST_username ($numrows records) </div>\n";

  }

  //
  // Show search form.
  //

  ?>
  
  <table class=tickets>
    <tr class=ticket>
      <form method=GET action="user.php">
        <td class=ticket> <input type=text name=search value='<? echo $search; ?>'> </td>
        <td class=ticket> <input type=submit value='Find Users'> </td>
      </form>
    </tr>
  </table>

  <?


  //
  // Display Page
  //

  if( $uname || $search ) {

    // Try searching by username first, then full name.
    if( $uname )
      $query = "SELECT * FROM users WHERE lower(username) LIKE lower('%$uname%');";
    else
      $query = "SELECT * FROM users WHERE lower(name) LIKE lower('%$search%') OR username LIKE '%$search%';";

    $result = pg_query( $query );

    if( pg_num_rows( $result ) <= 0 )
      echo "<div class=\"error\">Error: User not found.</div>\n";
    else {

      while( $row = pg_fetch_assoc( $result ) ) {

        $uid = trim( $row[ 'uid' ] );
        $name = trim( $row[ 'name' ] );
        $username = trim( $row[ 'username' ] );
        $phone = trim( $row[ 'phone' ] );
        $address = trim( $row[ 'address' ] );
        $email = trim( $row[ 'email' ] );

        echo "<div class=ticketbox>\n\n";

        echo "<form enctype=\"multipart/form-data\" method=post action=\"" . $_SERVER[ 'REQUEST_URI' ] . "\">\n";
        echo "<input type=hidden name=\"POST_uid\" value=\"$uid\">\n\n";
        
        echo "<table class=tickets>\n\n";

        echo "  <tr class=ticket>\n";
        echo "    <td>Name:</td>\n";
        echo "    <td>" .
             "<input type=text size=40 maxlength=50 name=\"POST_name\" value=\"$name\">" .
             "</td>\n";
        echo "  </tr>\n\n";

        echo "  <tr class=ticket>\n";
        echo "    <td>Username:</td>\n";
        echo "    <td>" .
             "<input type=text size=7 maxlength=7 name=\"POST_username\" value=\"$username\">" .
             "</td>\n";
        echo "  </tr>\n\n";

        echo "  <tr class=ticket>\n";
        echo "    <td>Phone #:</td>\n";
        echo "    <td>" .
             "<input type=text size=12 maxlength=12 name=\"POST_phone\" value=\"$phone\">" .
             "</td>\n";
        echo "  </tr>\n\n";
        
        echo "  <tr class=ticket>\n";
        echo "    <td>Address:</td>\n";
        echo "    <td>" .
             "<input type=text size=40 maxlength=50 name=\"POST_address\" value=\"$address\">" .
             "</td>\n";
        echo "  </tr>\n\n";
        
        echo "  <tr class=ticket>\n";
        echo "    <td>Email:</td>\n";
        echo "    <td>" .
             "<input type=text size=40 maxlength=50 name=\"POST_email\" value=\"$email\">" .
             "</td>\n";
        echo "  </tr>\n\n";

        echo "</table>\n";

        echo "<input type=submit name=\"POST_update\" value=\"Update\">\n";
        echo "<input type=reset>\n";

        echo "<a href='/barcode/barcode.php?code=$username&scale=1&bar=ANY'>Barcode</a>\n";
        echo "<a href='machine.php?uname=$username'>Machines</a>\n";

        echo "</form>\n";

        
        echo "</div>\n";

      }

    }
    
  }

?>

</body>

</html>

<? 
// Close the database connection
pg_close($db_connect) 
?>

