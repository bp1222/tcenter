<?
$mysql_hostname = "localhost";
$mysql_username = "tcenter";
$mysql_password = "1209qwpo";

if ($devbuild)
$mysql_database = "tcenter_old_dev";
else
$mysql_database = "tcenter_old";
mysql_connect($mysql_hostname, $mysql_username, $mysql_password);
mysql_select_db($mysql_database);
?> 
