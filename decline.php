<? 
	include("inc/connection.inc");
	include("inc/header.php");
?>

<?

	# set variable to null so url injection is overwritten
	$accept = "";

	# extract the form data to find out if they decline or not
	@extract($_POST);

	# prompt the user that they need to accept in order to get assistance
		echo "In order to process a request, you need to accept the <a href=\"index.php\">Terms Of Agreement</a>";	

	# close the database
		pg_close($db_connect);

?>
