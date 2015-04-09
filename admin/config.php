<?
/**
  * config.php
  *
  * administration configuration options
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

$title = "Configuration Page";
include "setup.inc";
include "inc/header.inc";

$system = System::getInstance();

if ($_REQUEST['submit'])
{
	$system->updateAttrib(array("WaitTime"=>$_REQUEST['WaitTime'], "SessionTimeout"=>$_REQUEST['SessionTimeout']));
	$system->save();
	?>
	<div class="box" style="width: 15%">
		<div class="boxHeader noFold">
			Updated
		</div>
	</div>
	<?
}
?>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
<div class="box" style="width: 400px">
	<div class="boxHeader noFold">
		TCenter <?=$tcenterVersion?> Configuration Panel
	</div>

	<div class="boxContent">
		<fieldset class="noborder">
			<label class="fLblRequest" for="waittime">Current Wait Time :</label>
			<input type="text" name="WaitTime" size="20" maxlength="23"
				value="<?=$system->getAttrib("WaitTime")?>"/><br/>

			<label class="fLblRequest" for="timeout">Session Timeout :<br/>
			<span class="smalltext">( In Minutes ) </span> </label>
			<input type="text" name="SessionTimeout" size="3" maxlength="20"
				value="<?=$system->getAttrib("SessionTimeout")?>"/><br/>
		</fieldset>

		<input type="submit" name="submit" value="Update"/>
	</div>
</div>
</form>
