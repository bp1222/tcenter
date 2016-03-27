
<?
  include("/var/www/inc/connection.inc");
?>


<?

  $today = date('Y-m-d');

  echo "Today is $today <br><br>\n\n";
    
  $query = "SELECT username FROM tickets ORDER BY lastupdateddate DESC LIMIT 10;";
  $result = pg_query($query);
  while( $row = pg_fetch_assoc( $result ) ) {

    $username = trim( $row['username'] );
    
    echo "$username" 
    ?>
    <br>
    <?

  }

?>

