<?
/**
  * dev.inc
  *
  * Basic include file to determine if we're in a dev environment, and if we are
  * display a bit of more information
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$host = explode('.', $_SERVER['HTTP_HOST']);
if ($host[0] == "tcenter2")
	$devbuild = true;

if (!function_exists("devInfo"))
{
	function devInfo ()
	{
	?>
	<div class="box" style="margin-top: -5%">
		<?topCorner();?>
		<div class="boxHeader">
			Debug Output
		</div>
		<div class="boxContent">
	<?
		echo "Request: ";
		var_dump($_REQUEST);
	
		echo "<br/><br/>Mysql Errors: ";
		var_dump(mysql_error());
		echo "<br/>SQL: ";
		var_dump ($sql);
		echo "<br/>Mysql Affected Rows: ";
		var_dump(mysql_affected_rows());
		echo "<br/>Mysql Info: ";
		var_dump(mysql_info());
	?>
		</div>
		<?bottomCorner();?>
	</div>
<?
	}
}
?>
