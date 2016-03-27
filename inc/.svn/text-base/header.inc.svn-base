<?
/**
  * header.inc
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

// See if we are in a development environment
include "dev.inc";

// See if we need to do setup! 
if (!file_exists("inc/connection.inc") && !$setup)
{
	header("Location: setup.php");
}

// Needed for IE to display titles on the ticket page.  Also needed for the
// search page, since it does re-directs
if (!$noinc) { 
	require "basepath.inc"; 
	require "connection.inc"; 
	include_once "lookup.inc";
   	include_once "ticket.inc"; 
	include_once "style.inc"; 
}

register_shutdown_function(normalExit);

function normalExit ()
{
	include "footer.inc";
	if (@mysql_ping())
		mysql_close();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>TCenter v2 Beta || <?=$title?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="stylesheets/tcenter.css">
		<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="stylesheets/tcenter-ie7.css">
		<![endif]-->
		<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="stylesheets/tcenter-ie6.css">
		<![endif]-->
	    <script type="text/javascript" src="javascript/form.js"></script>
	    <script type="text/javascript" src="javascript/tickets.js"></script>
	</head>
	<body>
		<div id="topbar">
			<div id="headerbar"></div>
			<img src="../images/its.gif" id="header"/>
		</div>

		<?if($devbuild){?>
		<center><h2><font color="red">DEVELOPMENT VERSION</font></h2></center>
		<?}?>

		<?if (!$nonav && !$setup) { // For the printer friendly page?>
		<div id="adminNav">
		    <a href="<?=ROOT?>admin/">Admin Panel</a><br/>
		    <a href="<?=ROOT?>admin/ticketList.php">Ticket Index</a><br/>
		    <a href="<?=ROOT?>admin/search.php">Search Tickets</a>
		</div>
		<?}?>

		<div id="maincontent">
			<?if ($devbuild) devInfo();?>
