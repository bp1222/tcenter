<?php
/**
 * import_db.php
 *
 * Imports data from old version of TCenter (postgresql) to TCenter v2 (mysql)
 *
 * @author	Jeremy Wozniak	(jpwrcc@rit.edu)
 */

include "lookup.inc";

pg_connect("dbname=techcenter user=techcenter password=resnetbackwards");
$mysql_link = mysql_connect('localhost','tcenter','du88elyuH');
mysql_select_db('tcenter_dev');
$DISPLAY_TABLES = "SELECT * FROM tickets";
$QUERY = pg_query($DISPLAY_TABLES);
while($row = pg_fetch_array($QUERY,NULL,PGSQL_ASSOC))
{
	$TNUMBER = $row['tcnumber'];

	// These two tickets are broken in the old version, and should not be imported
	// jdw - 7-02
	if ($TNUMBER == "crm0964-1831" || $TNUMBER == "crm0964-1832")
		continue; 

	$USER = $row['username'];
	$SUBQUERY_USER = "SELECT * FROM users WHERE username = '$USER' LIMIT 1";
	$SUBQUERY_USER = pg_query($SUBQUERY_USER);
	while($user_row = pg_fetch_array($SUBQUERY_USER,NULL,PGSQL_ASSOC))
	{
		$USER_NAME_RIT = mysql_real_escape_string($user_row['username']);
		$USER_FULL_NAME = mysql_real_escape_string($user_row['name']);
		$name = explode(" ",$USER_FULL_NAME);
		$USER_FIRST_NAME = $name[0];
		$name_array = array($name[1], $name[2]);
		$USER_LAST_NAME = implode($name_array, " ");
		$USER_PHONE = mysql_real_escape_string($user_row['phone']);
		$USER_PHONE = str_replace(")","-",$USER_PHONE);
		$USER_PHONE = str_replace("(","",$USER_PHONE);
		$USER_EMAIL = mysql_real_escape_string($user_row['email']);
		$USER_INSERT = "INSERT INTO user (`u_id`,`username`,`first_name`,`last_name`,`phone`,`email`) VALUES(NULL,'$USER_NAME_RIT','$USER_FIRST_NAME','$USER_LAST_NAME','$USER_PHONE','$USER_EMAIL')";
		mysql_query($USER_INSERT);
		$u_id = mysql_insert_id();

	}
	$SUBQUERY_MACHINE = "SELECT * FROM machines WHERE tcnumber = '$TNUMBER' LIMIT 1";
	$SUBQUERY_MACHINE = pg_query($SUBQUERY_MACHINE);
	while($machine_row = pg_fetch_array($SUBQUERY_MACHINE,NULL,PGSQL_ASSOC))
	{
		$MACHINE_DESCRIPTION = mysql_real_escape_string($machine_row['description']);
		$MACHINE_INVENTORY =  mysql_real_escape_string($machine_row['inventory']);
		$MACHINE_LOGIN = mysql_real_escape_string($machine_row['username']);
		$MACHINE_PASSWORD = mysql_real_escape_string($machine_row['password']);
		$TICKET_WARRANTY = mysql_real_escape_string($machine_row['warranty']);
		$TICKET_LOCATION = mysql_real_escape_string($machine_row['location']);
		$MACHINE_INSERT = "INSERT INTO machine (`m_id`,`u_id`,`description`,`inventory`,`login_name`,`login_password`) VALUES(NULL,'$u_id','$MACHINE_DESCRIPTION','$MACHINE_INVENTORY','$MACHINE_LOGIN','$MACHINE_PASSWORD')";
		mysql_query($MACHINE_INSERT);
		$m_id = mysql_insert_id();
	}
	$TICKET_NUMBER = substr($row['tcnumber'], strpos($row['tcnumber'], "-")+1);
	$TICKET_ACCEPTED_DATE = $row['accepteddate'];
	$TICKET_CLOSED_DATE = $row['closeddate'];
	$TICKET_SUMMARY = mysql_real_escape_string($row['summary']);
	$TICKET_PROBLEM = mysql_real_escape_string($row['description']);
	$TICKET_UPDATES = mysql_real_escape_string($row['progress']);
	$TICKET_STATUS = $l_status[$row['status']];
	$TICKET_REPAIR = $row['repairinstall'];
	$TICKET_REPAIR = $TICKET_REPAIR? 0:1;
	$TICKET_FULL = $row['fullinstall'];
	$TICKET_FULL = $TICKET_FULL? 0:1;
	$TICKET_PRIORITY = $row['priority'];
	$TICKET_FACULTY = $row['faculty'];
	$TICKET_INSERT = "INSERT INTO ticket (`t_id`,`u_id`,`m_id`,`open_date`,`close_date`,`last_time`,`summary`,`problem`,`updates`,`status`,`repair`,`full`,`warranty`,`priority`,`location`,`faculty`) VALUES('$TICKET_NUMBER','$u_id','$m_id','$TICKET_ACCEPTED_DATE','$TICKET_CLOSED_DATE','$TICKET_LAST_TIME','$TICKET_SUMMARY','$TICKET_PROBLEM','$TICKET_UPDATES','$TICKET_STATUS','$TICKET_REPAIR','$TICKET_FULL','$TICKET_WARRANTY','$TICKET_PRIORITY','$TICKET_LOCATION','$TICKET_FACULTY')";
	mysql_query($TICKET_INSERT);
}


?>

