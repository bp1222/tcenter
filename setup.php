<?
/**
  * setup.php
  *
  * Setup file for new installs of TCenter.
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$setup = true;
$noinc = true;
include "inc/style.inc";
include "inc/header.inc";

function connectAttemptCreate ($hostname, $username, $password, $database, $socket = null, $port = null)
{
	// Try and connect to the server
	if (!empty($socket))
		$connect = @mysql_connect("$hostname:$socket", $username, $password);
	else if (!empty($port))
		$connect = @mysql_connect("$hostname:$port", $username, $password);
	else
		$connect = @mysql_connect($hostname, $username, $password);

	if (!$connect)
		return -1; // Error, Can't connect to server

	$db_connect = mysql_select_db($database);

	if (!$db_connect)
		return -2;

	// Well, seems like this is a valid connection, time to create.
	// Create User Table
	$sql = "CREATE TABLE `user` (".
			"`u_id` int(11) NOT NULL auto_increment, ".
			"`username` varchar(10) NOT NULL default '', ".
			"`first_name` varchar(63) NOT NULL default '', ".
			"`last_name` varchar(63) NOT NULL default '', ".
			"`phone` varchar(63) NOT NULL default '', ".
			"`email` varchar(127) NOT NULL default '', ".
			"PRIMARY KEY  (`u_id`)".
			") TYPE=MyISAM AUTO_INCREMENT=1000;";
	mysql_query($sql);

	// Create Machine table
	$sql = "CREATE TABLE `machine` ( ".
			"`m_id` int(11) NOT NULL auto_increment, ".
			"`u_id` int(11) NOT NULL default '0', ".
			"`description` varchar(127) NOT NULL default '', ".
			"`inventory` varchar(255) NOT NULL default '', ".
			"`login_name` varchar(127) NOT NULL default '', ".
			"`login_password` varchar(127) NOT NULL default '', ".
			"PRIMARY KEY  (`m_id`) ".
			") TYPE=MyISAM AUTO_INCREMENT=1000;";
	mysql_query($sql);

	// Create Ticket Table
	$sql = "CREATE TABLE `ticket` ( ".
			"`t_id` int(11) NOT NULL auto_increment, ".
			"`u_id` int(11) NOT NULL default '0', ".
			"`m_id` int(11) NOT NULL default '0', ".
			"`open_date` date NOT NULL default '0000-00-00', ".
			"`close_date` date default '0000-00-00', ".
			"`last_time` bigint(20) NOT NULL default '0', ".
			"`time_working` bigint(20) NOT NULL default '0', ".
			"`time_waiting` bigint(20) NOT NULL default '0', ".
			"`summary` text NOT NULL, ".
			"`problem` mediumtext NOT NULL, ".
			"`updates` longtext NOT NULL, ".
			"`status` smallint(6) NOT NULL default '0', ".
			"`reopen_count` int(11) NOT NULL default '0', ".
			"`tasks` tinyint(4) NOT NULL default '0', ".
			"`closed_by` varchar(10) NOT NULL default '', ".
			"`repair` tinyint(1) NOT NULL default '0', ".
			"`full` tinyint(1) NOT NULL default '0', ".
			"`warranty` tinyint(1) NOT NULL default '0', ".
			"`priority` tinyint(1) NOT NULL default '0', ".
			"`location` tinyint(1) NOT NULL default '0', ".
			"`faculty` tinyint(1) NOT NULL default '0', ".
			"PRIMARY KEY  (`t_id`) ".
			") TYPE=MyISAM AUTO_INCREMENT=1000;";
	mysql_query($sql);

	// Create System Table
	$sql = "CREATE TABLE `system` ( ".
			"`s_id` int(11) NOT NULL auto_increment, ".
			"`waittime` varchar(255) NOT NULL default '', ".
			"PRIMARY KEY  (`s_id`) ".
			") TYPE=MyISAM AUTO_INCREMENT=1;";
	mysql_query($sql);

	// Now that the DB is set we gotta create the connection.inc file.
	if (!$fp = @fopen("inc/connection.inc", "w+"))
	{
?>
		<div class="box" style="width: 50%">
			<?topCorner();?>
			<div class="boxHeader">
				Small Error
			</div>
			<div class="boxContent">
					<h3><font color="green">Connected to the Server</font><br/>
					<font color="green">Connected to the database</font><br/>
					<font color="red">Could not open inc/connection.inc for writing</font></h3>
					Paste the following to inc/connection.inc
					<br/><br/>
					<fieldset style="font-family: arielbd">
					&lt;?<br/>
					$mysql_hostname = "<?=$hostname?>";<br/>
					$mysql_username = "<?=$username?>";<br/>
					$mysql_password = "<?=$password?>";<br/>
					<br/>
					if ($devbuild)<br/>
						$mysql_database = "<?=$database?>_dev";<br/>
					else<br/>
						$mysql_database = "<?=$database?>";<br/>
					mysql_connect($mysql_hostname, $mysql_username, $mysql_password);<br/>
					mysql_select_db($mysql_database);<br/>
					?&gt;
					</fieldset>
			</div>
			<?bottomCorner();?>
		</div>
<?
		exit;
	} 

	fwrite($fp, "<?");
	fwrite($fp, '$mysql_hostname = "'.$hostname.'";');
	fwrite($fp, '$mysql_username = "'.$username.'";');
	fwrite($fp, '$mysql_password = "'.$password.'";');
	fwrite($fp, '');
	fwrite($fp, 'if ($devbuild)');
	fwrite($fp, '	$mysql_database = "'.$database.'";');
	fwrite($fp, 'else');
	fwrite($fp, '	$mysql_database = "'.$database.'_dev";');
	fwrite($fp, '');
	fwrite($fp, 'mysql_connect($mysql_hostname, $mysql_username, $mysql_password);');
	fwrite($fp, 'mysql_select_db($mysql_database);');
	fclose($fp);
	return 0;
}


if ($_REQUEST['submit'])
{
	// Parse out data
	$hostname = trim($_REQUEST['serverHostname']);
	$username = trim($_REQUEST['serverUsername']);
	$password = trim($_REQUEST['serverPassword']);
	$database = trim($_REQUEST['serverDatabase']);
	$socket = trim($_REQUEST['serverSocket']);
	$port = trim($_REQUEST['serverPort']);

	$retval = connectAttemptCreate($hostname, $username, $password, $database, $socket, $port);

	if ($retval == 0)
	{
?>
		<div class="box" style="width: 40%">
			<?topCorner();?>
			<div class="boxHeader">
				Success
			</div>
			<div class="boxContent">
					<h3><font color="green">Connected to the Server</font><br/>
					<font color="green">Connected to the database</font></h3><br/>
					TCenter is set up for use
			</div>
			<?bottomCorner();?>
		</div>
<?
		return;
	}
	else if ($retval == -1)
	{
?>
		<div class="box" style="width: 40%">
			<?topCorner();?>
			<div class="boxHeader">
				Fatal Error
			</div>
			<div class="boxContent">
					<font color="red"><h3>Could not connect to the Server</h3></font><br/>
				   	Check to see that this user exists, and that the server hostname is correct.
			</div>
			<?bottomCorner();?>
		</div>
<?
	} else if ($retval == -2) {
?>
		<div class="box" style="width: 40%">
			<?topCorner();?>
			<div class="boxHeader">
				Fatal Error
			</div>
			<div class="boxContent">
					<h3><font color="green">Connected to the Server</font><br/>
					<font color="red">Could not connect to the database</font></h3><br/>
					Check to see that the Database Exists and the user has privledges
			</div>
			<?bottomCorner();?>
		</div>
<?
	} 
}
?>

<form method="post" action="<?=$PHP_SELF?>">
	<div class="box" style="width: 40%">
		<?topCorner();?>
		<div class="boxHeader">
			Welcome to TCenter 2 Setup
		</div>
	
		<div class="boxContent">
			<fieldset>
				<legend>Server Information</legend>
	
				<label class="fLblRequest" for="serverHostname"><span class="required">*</span>MySQL Hostname: </label>
					<input type="text" name="serverHostname" value="localhost" size="20" maxlength="30"
						onclick="clearField(this)"/>
	
				<label class="fLblRequest"><span class="required">*</span>MySQL Username: </label>
					<input type="text" name="serverUsername" size="20" maxlength="30"
						onclick="clearField(this)"/>
						
				<label class="fLblRequest"><span class="required">*</span>MySQL Password: </label>
					<input type="password" name="serverPassword" size="20" maxlength="30"
						onclick="clearField(this)"/>
	
				<label class="fLblRequest"><span class="required">*</span>MySQL Database: </label>
					<input type="text" name="serverDatabase" size="20" maxlength="30"
						onclick="clearField(this)"/>

				<label class="fLblRequest">MySQL Socket: </label>
					<input type="text" name="serverSocket" size="20" maxlength="30"
						onclick="clearField(this)"/>
				<br/>	
				<center>
					<input type="submit" name="submit" value="Set-Up Server"/>
				</center>
			</fieldset>
		</div>
		<?bottomCorner();?>
	</div>
</form>
