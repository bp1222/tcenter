<?
/**
  * waittime.php
  *
  * Set the current tech-center wait time
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$title = "Adjust Wait Time";
include "inc/header.inc";

if ($_REQUEST['submit'])
{
	$waittime = mysql_real_escape_string($_REQUEST['waittime']);

	// Just make sure that s_id 1 exists...
	$sql = "SELECT * FROM system WHERE s_id = '1'";
	if (mysql_num_rows(mysql_query($sql)) != 1)
		$sql = "INSERT INTO system SET (`t_id`, `waittime`) VALUES ('1', '".$waittime."')";
	else
		$sql = "UPDATE system  SET waittime = '".$waittime."' WHERE t_id = '1'";

	mysql_query($sql);
}

// Get Current Waittime
$sql = "SELECT waittime FROM system WHERE s_id = '1'";
$time = mysql_fetch_assoc(mysql_query($sql));
?>

<form method="post" action="<?=$PHP_SELF?>">
	<div class="box" style="width: 30%">
		<?topCorner();?>
		<div class="boxHeader">
			Set Current Wait-Time
		</div>

		<div class="boxContent">
			<p> Current waittime is : <?=$time['waittime']?></p>
			<fieldset class="noborder">
					<input type="text" name="waittime" size="30" maxlength="35"
						onfocus="clearField(this)"/><br/>
				<input type="submit" align="right" name="submit" value="Set as Wait-Time" tabindex="9"/>
			</fieldset>
		</div>
		<?bottomCorner();?>
	</div>
</form>
