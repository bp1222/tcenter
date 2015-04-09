<?
/**
  * header.inc
  *
  * Header script.  Sets up all information and style
  *
  * TCenter 3 - Ticket Management
  * Copyright (C) 2007-2008		Rochester Institute of Technology
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 2 of the License, or
  * (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$tcenterVersion = "v3.2b";
require "basepath.inc"; 

// Prevent XSS attacks
$_SERVER['PHP_SELF'] = $_SERVER['SCRIPT_NAME'];

// Controller for some DB settings
define ('RIT_WEBSPACE', false);

/*
 * Main Includes
 */
include_once "helper_functions.inc";
include_once "content_functions.inc";
include_once "ticket_functions.inc";
include_once "database.inc"; 
include_once "tcenter.inc";
include_once "system.inc";
include_once "lookup.inc";

$system = System::getInstance();
session_start();

/*
 * Turn on output buffering
 */
ob_start();

/*
 * Handle logouts
 */
if ($_REQUEST['logout']) {
	session_destroy();
	header("Location: index.php");
}

/*
 * Register our shutdown Function And exception handler
 */
register_shutdown_function(normalExit);
set_exception_handler(uncaughtException);

/*
 * Get our connection to the API
 */
if (!($tcenter = $_SESSION['tcenter']))
	$tcenter = new TCenter();

/*
 * Display the beginning of the page
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>TCenter <?=$tcenterVersion?> || <?=$title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="/stylesheets/tcenter.css">
		<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="/stylesheets/tcenter-ie7.css">
		<![endif]-->
		<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="/stylesheets/tcenter-ie6.css">
		<![endif]-->
		<script type="text/javascript" src="/javascript/jquery.js"></script>
		<script type="text/javascript" src="/javascript/tickets.js"></script>
	</head>
	<body>
		<div id="contentWrapper">
			<div id="topbar">
				<div id="headerbar"></div>
				<img src="/images/its.gif" id="header"/>
				<div class="logOut" style="position: absolute; z-index: 900; margin: 0 auto; text-align: center; width: 100%; display: none;">
					<font color="red">
						<h2>You have been LoggedOut</h2>
						<a onclick="return!open('<?=ROOT?>/index.php?relogin=true','_blank', 'width=600,height=400,screenX=150,screenY=0')">( Click Here to LogIn )</a>
					</font>
				</div>
			</div>

			<div id="authUser">Welcome to TCenter <?=$tcenter->user->username ? ":".$tcenter->user->username : ""?></div>

			<div id="adminNav">
				<a href="<?=ROOT?>admin/">Admin Panel</a><br/>
				<a href="<?=ROOT?>admin/ticketList.php">Ticket Index</a><br/>
				<a href="<?=ROOT?>admin/search.php">Search Tickets</a><br/>
				<a href="<?=ROOT?>logout.php">Logout</a>
			</div>
		
			<script language="javascript">
				var bindVars = function () {
					$("div.boxHeader").bind("click", toggleContent);
				}
		
				$(bindVars);
	
				logoutInterval = setInterval (loggedOut, 10000);
			</script>

			<div id="mainContent">
<?

/*
 * Make sure if the page requrires authentication we check.
 */
$tcenter->hasAuth();

/*
 * Make sure the user is an employee before giving them access to ajax/admin
 */
if (preg_match('/\/admin/', $_SERVER['SCRIPT_NAME']) || preg_match('/\/ajax/', $_SERVER['SCRIPT_NAME']))
{
	if (!$tcenter->user->hasPriv('USER_EMPLOYEE'))
	{?>
		<div class="box" style="width: 20%">
			<div class="boxHeader noFold">Requires Admin Privileges</div>
		</div>
	<?
		exit;
	}
}

/*
 * Manage old requests, that need to be populated back to $_REQUEST
 */
if ($_REQUEST['old_request']) 
{
	$old = unserialize(base64_decode($_REQUEST['old_request']));
	unset ($_REQUEST['old_request']);
	$_REQUEST = array_merge($_REQUEST, $old);
}
