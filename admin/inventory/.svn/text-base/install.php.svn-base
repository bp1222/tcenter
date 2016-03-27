<?php
if ($install) {
$file = fopen("config.inc",w);
fwrite($file,"<?php\n
define(\"_DBSERVER\",\"$server\");\n
define(\"_USERNAME\",\"$username\");\n
define(\"_PASSWORD\",\"$password\");\n
define(\"_DATABASE\",\"$database\");\n
define(\"_DEFAULTVIEW\",\"simple\");\n
define(\"_ORDERBY\",\"name\");\n
define(\"_REPORTSTYLE\",\"1\");\n
?>\n");
fclose($file);
$file = fopen("index.php",w);
fwrite($file,"<?php\n
header(\"Location: start.php\");\n
?>\n
");
fclose($file);
echo "<HTML><HEAD><TITLE>PHP Inventory Manager</TITLE></HEAD><BODY><FONT face=verdana>";
echo "<H1>PHP Invetory Manager</H1><BR><H3>installation</H3><HR><P>";

echo "<TABLE><TR><TD><B>Connecting to server: </B></TD>";
mysql_connect($server,$username,$password) or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";

if (!$existsdatabase) {
echo "<TR><TD><B>Creating database $database: </B></TD>";
$result = mysql_query("create database $database") or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";
}

echo "<TR><TD><B>Opening database $database: </B></TD>";
mysql_select_db($database) or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";

echo "<TR><TD><B>Creating table computers: </B></TD>";
$result = mysql_query("create table computers(computer_id integer auto_increment primary key,name varchar(60),ipaddress varchar(15),hardware text,software text,os char(2),other text)") or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";

echo "<TR><TD><B>Creating table templates:</B></TD>";
$result = mysql_query("create table templates(template_id integer auto_increment primary key,name varchar(60),hardware text,software text,os char(2),other text)") or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";

echo "<TR><TD><B>Creating table software: </B></TD>";
$result = mysql_query("create table software(software_id integer auto_increment primary key,name varchar(60),manufacturer varchar(60),version varchar(5),other text)") or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";

echo "<TR><TD><B>Creating table hardware: </B></TD>";
$result = mysql_query("create table hardware(hardware_id integer auto_increment primary key,type varchar(60),manufacturer varchar(60),model varchar(60),make varchar(60),other text)") or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR>";

echo "<TR><TD><B>Creating table os: </B></TD>";
$result = mysql_query("create table os(os_id integer auto_increment primary key,name varchar(60))") or die(mysql_error());
echo "<TD><FONT color=red>successful</FONT></TD></TR></TABLE>";

echo "</FONT></BODY></HTML>";
} else {
?>
<html>

<head>
<title>PHP Inventory Manager Installtion</title>
</head>

<body>
<FONT face=verdana><H1>PHP Invetory Manager</H1><BR><H3>installation</H3><HR><P><p></FONT><font face="verdana">Welcome to the installation program of PHP&nbsp;Inventory Manager.<br>
<form name="install" action="install.php" method="post" target="_top"><INPUT type=hidden name=install value=true>
Please fill in all the information requested.</font></p>
<p><font face="verdana">
<table border="4" cellpadding="0" cellspacing="2">
<tr>
<td>Database server:</td>
<td><input type="text" name="server" size="24"></td>
</tr>
<tr>
<td>Database Username:</td>
<td><input type="text" name="username" size="24"></td>
</tr>
<tr>
<td>Database Password:</td>
<td><input type="password" name="password" size="24"></td>
</tr>
<tr>
<td>Database:</td>
<td><input type="text" name="database" size="24"><BR><INPUT type=checkbox name=existsdatabase>Database exists</td>
</tr>
</table>
</font></p>
<p><font face="verdana" size="2"><i><INPUT type=submit>
</form>
</i></font></p>
<p><font face="verdana" size="2"><i>Database server:<br>
</i>The IP address or hostname of the server which is running the mysql-server</font></p>
<p><font face="verdana" size="2"><i>Database username:</i><br>
The username required to access the server/database</font></p>
<p><font face="verdana" size="2"><i>Database password:<br>
</i>The password required to log on to the database server.</font></p>
<p><font face="verdana" size="2"><i>Database:<br>
</i>The name of the database to be created</font>
<p><font face="verdana" size="2"><i>Database exists:<br>
</i>Only create the tables in the database</font>
</body>

</html>



<?php
}
?>
