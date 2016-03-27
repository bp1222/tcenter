<?
/**
  * shiftreport.php
  *
  * Submit a shift report
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

$title = "Shift Supervisor Report";
include "inc/header.inc";

if ($_REQUEST['submit'])
{
	$to = "trdhelp@rit.edu";
	$subject = "[TCenter] $PHP_AUTH_USER - Shift Report";

	$message = "Overview: ".$_REQUEST['overview']."\n\n".
			   "Problems: ".$_REQUEST['problem']."\n\n".
			   "Other: ".$_REQUEST['other'];

	mail($to, $subject, $message, "From: $PHP_AUTH_USER@rit.edu");
}
?>

<form method="post" action="<?=$PHP_SELF?>">
	<div class="box" style="width: 65%">
		<?topCorner();?>
		<div class="boxHeader">
			Senior Shift Report
		</div>

		<div class="boxContent">
			<fieldset>
				<label class="fLblRequest" for="problem">Shift Overview</label>
				<textarea name="overview" rows="15" cols="50"></textarea>
			</fieldset>
			<fieldset>
				<label class="fLblRequest" for="problem">Problems</label>
				<textarea name="problem" rows="15" cols="50"></textarea>
			</fieldset>
			<fieldset>
				<label class="fLblRequest" for="problem">Other</label>
				<textarea name="other" rows="15" cols="50"></textarea>
			</fieldset>
			<input type="submit" name="submit" value="Submit" tabindex="9"/>
		</div>
		<?bottomCorner();?>
	</div>
</form>
