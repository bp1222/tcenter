<?
/**
  * ticket.inc
  *
  * Functions for getting data from the database about tickets
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */


/**
  * getTicketsById
  *
  * @return	array() - Result is an array containing ticket information 
  * @return null - Returns null if 0 are returned
  */
function getAllTicketById ($t_id) {
	global $l_status;

	$sql = "SELECT ticket.*, user.*, machine.* FROM ticket, user, machine WHERE ticket.t_id = '". $t_id ."' AND user.u_id = ticket.u_id AND machine.m_id = ticket.m_id";
	$result = mysql_query($sql);

	if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_assoc($result);
		$row['status'] = array_search($row['status'], $l_status);
		return $row;
	} else {
		return null;
	}
}

/**
  * getTicketsByStatus
  *
  * @return	$result - Result is a MYSQL responce
  * @return null - Returns null if 0 are returned
  */
function getTicketsByStatus ($status) {
	global $l_status;

	$sql = "SELECT ticket.*, machine.description, machine.m_id, user.username, user.u_id FROM ticket, machine, user WHERE ticket.status = '".$l_status[$status]."' AND ticket.u_id = user.u_id AND ticket.m_id = machine.m_id";

	$result = mysql_query($sql);

	if (mysql_num_rows($result) == 0)
		return null;

	return buildReturnFromMysql($result);
}

function buildReturnFromMysql ($result)
{
	$returns = array();

	// Build the array to ship back
	while ($row = mysql_fetch_assoc($result))
	{
		$returns[] = $row;
	}


	return $returns;
}

function getTicketsBySql ($sql) {
	$result = mysql_query($sql);

	if (mysql_num_rows($result) == 0)
		return null;

	return buildReturnFromMysql($result);
}

function longTicketNumber ($t_id)
{
	$sql = "SELECT 
		u.username, u.u_id, t.t_id FROM ticket t, user u 
		WHERE u.u_id = t.u_id AND t.t_id = '$t_id'";
	$result = mysql_query($sql);
	
	$row = mysql_fetch_assoc($result);
	
	// Set variables from the record
	$u_id = $row["u_id"];
	$username = $row["username"];
		
	// Generate the ticket number
	return $username."-".$t_id; 
}

function makeDate ($timestamp)
{
	if (empty($timestamp))
		return "No Last Date";
	return date("Y-m-d", $timestamp);
}
?>
