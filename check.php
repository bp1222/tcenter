<?

include("/var/www/inc/connection.inc");

$date = date('Y-m-d');

$newquery = "INSERT INTO stats (\"name\", \"date\", \"value\") values('open', '$date', 0);";
$newresult = pg_query($newquery);
$newquery = "INSERT INTO stats (\"name\", \"date\", \"value\") values('closed', '$date', 0);";
$newresult = pg_query($newquery);

?>
