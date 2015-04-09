<?
/**
  * request.php
  *
  * Requesting new tickets from TCenter
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

// include main header
$title = "Service Request";
include "inc/header.inc";

// Clear error values
$errval = array();

// If they didn't accept the agreement from the previous page
// so if !submt_this_page && !accept_last (Deny only)
if (!$_REQUEST['submit'] && !$_REQUEST['accept'])
{
?>
	<div class="box" style="text-align: center; width: 40%">
		<div class="boxContent">
			In order to employ Resnet for technical support you need to agree to the <a href="index.php"></br><center>Terms of Agreemet</center></a>
		</div>
	</div>
<?
	return;
}
// If they are submitting this form
if ($_REQUEST['submit'])
{
	// Errors
	$error = array();

	// Check the fields coming in, and build the SQL
	$otherUser   = doubleQuote(trim($_REQUEST['otherUser'])); 
	$firstName	 = doubleQuote(trim($_REQUEST['firstName'])); 
	$lastName	 = doubleQuote(trim($_REQUEST['lastName'])); 
	$username	 = $tcenter->user->username; 
	$phone		 = doubleQuote(trim($_REQUEST['phone']));
	$email		 = doubleQuote(trim($_REQUEST['email'])); 
	$description = doubleQuote(trim($_REQUEST['description'])); 
	$loginName	 = doubleQuote(trim($_REQUEST['loginName'])); 
	$loginPass	 = doubleQuote(trim($_REQUEST['loginPass'])); 
	$os			 = doubleQuote(trim($_REQUEST['os']));
	$inventory	 = doubleQuote(trim($_REQUEST['inventory'])); 
	$problem	 = doubleQuote(trim($_REQUEST['problem'])); 
	$contact = false;

	// First Name
	if (empty($firstName) || $firstName == "First")
		$error["firstName"] = true;

	// Last Name
	if (empty($lastName) || $lastName == "Last")
		$error["lastName"] = true;

	// Username
	{
		if (empty($username) || $username == "Username")
			$error["username"] = true;

		if ($tcenter->user->hasPriv("TICKET_MANUAL") && !empty($otherUser))
			$username = $otherUser;
	}

	// Operating System 
	if (empty($os) || $os == "select")
		$error["os"] = true;

	// Phone
	// Replace all '-' with ' ', then remove spaces.  Then count, digits must = 7 or 10
	if ($phone = checkPhoneNumber($phone))
		$contact = true;
    else
        $error["phone"] = true;

	// Email
	$email_pattern = "/^[A-Za-z0-9\._-]+@[A-Za-z0-9][A-Za-z0-9-]*(\.[A-Za-z0-9_-]+)*\.([A-Za-z]{2,6})$/";
	if (!((empty($email) || $email == "Email Address") || preg_match($email_pattern, $email) == 0))
		$contact = true;

	if (!$contact)
	{
		$error["phone"] = true;
		$error["email"] = true;
	}

	// Descripion
	if (empty($description) || $description == "Description of Computer")
		$error["description"] = true;

	// Problem Description
	if (empty($problem) || $problem == "")
		$error["problem"] = true;

	// As long as we don't have errors we should add this ticket into our DB
	if (empty($error))
	{
		$owner = new Owner();
		$owner->updateAttrib(array("username"=>$username, "name"=>$firstName." ".$lastName, "phone"=>$phone, "email"=>$email));
		$o_id = $owner->create();

		$machine = new Machine();
		$machine->updateAttrib(array("o_id"=>$o_id, "description"=>$description, "inventory"=>$inventory, "os"=>$os, "login_name"=>$loginName, "login_password"=>$loginPass));
		$m_id = $machine->create();

		$ticket = new Ticket();
		$ticket->updateAttrib(array("o_id"=>$o_id, "m_id"=>$m_id, "open_date"=>"", "summary"=>"", "problem"=>$problem, "updates"=>"", "todo"=>defaultToDo($os), "status"=>0, "closed_by"=>"", "repair"=>0, "full"=>0));
		$t_id = $ticket->create();

		// Make Email headers
		$to = $email;
		$subject = "[RESNET] ".$username."-".$t_id." : Repair Request Created";
		$from = "From: restech@rit.edu";
		$waittime = $system->getAttrib("WaitTime");

		// Kindly Email user information.
		$message = "Dear $firstName,\n\n".
			"Thank you for coming to ITS Resnet.  The current estimated wait time ".
			"for your computer is $waittime.  If you have any further ".
			"questions feel free to reply to this email or call us referencing ".
			"ticket number: ".$username."-".$t_id."\n\n".
			"ITS Resnet Staff
Information & Technology Services
Rochester Institute of Technology
Nathaniel Rochester Hall, Room 1034
585.475.2600 (voice) 585.475.4927 (tty)
585.475.6064 (fax)
Restech@rit.edu
Hours:
M-F 9am-9pm
Sat-Sun 12pm-5pm"; 

		mail ($to, $subject, $message, $from);

?>
		<div class="box" style="width:30%">
			<div class="boxHeader" style="font-size: 1.3em;">
				Your service request has been logged
			</div>
			<br/><center>Please make sure all items needed are left at the Resnet office.</center>
			<br/>
			<?
			if ($otherUser)
				header("Location: admin/index.php");
			else 
				session_destroy();?>
		</div>
		<script type="text/javascript">
			function relocate () {
				location.href = "index.php";
			}

			setTimeout('relocate()',15000);	
		</script>
<?
		return;
	}
	else // There was an error
	{
		// Set values to our error value array, this is for data retention
		global $errval;
		$errval['firstName'] = $_REQUEST['firstName']; 
		$errval['lastName']	 = $_REQUEST['lastName']; 
		$errval['phone'] = $_REQUEST['phone'];
		$errval['email'] = $_REQUEST['email']; 
		$errval['description'] = $_REQUEST['description']; 
		$errval['loginName'] = $_REQUEST['loginName']; 
		$errval['loginPass'] = $_REQUEST['loginPass']; 
		$errval['os'] = $_REQUEST['os']; 
		$errval['inventory'] = $_REQUEST['inventory']; 
		$errval['problem'] = $_REQUEST['problem']; 
?>
	<div class="box" style="width:25%">
		<div class="boxHeader" style="font-size: 1.3em;">There was an Error</div>
		Please check to make sure required fields are filled out.
	</div>
<?
	}
}
?>

<form method="post" action="<?$_SERVER['PHP_SELF']?>">
	<div class="box" style="width:600px">
		<div class="boxHeader noFold">
			Resnet Technical Support Request Form
		</div>

		<div class="boxContent">
			All of the information on this form is necessary to process your request. If you are not sure how to fill out a certain entry, ask an RCC for help. If you do not fill in all of the data for this sheet, or you fill in the sheet improperly, you will be redirected back to the form until it is properly filled out. 

			<hr/>

			<p>
				Note: Fields with a <span class="required">*</span> are required. 
			</p>
			<p>
				Current wait time for machines submitted today is : <b><?=$system->getAttrib("WaitTime")?></b>
			</p>

			<fieldset>
				<legend>User Information</legend>

				<?if ($tcenter->user->hasPriv("ADMIN_MANUAL")){?>
					<label class="fLblRequest" for="otherUser">Other Username</label>
						<input type="text" name="otherUser"
							value="<?=$errval['firstName'] ? $errval['firstName'] : ""?>"
							size="12" maxlength="7" tabindex="1"/><br/><br/><br/>
				<?}?>

				<?=$error['firstName']||$error['lastName']?"<font color='red'>":""?>
				<label class="fLblRequest" for="firstName"><span class="required">*</span>Name</label>
				<?=$error['firstName']||$error['lastName']?"</font>":""?>

					<input type="text" name="firstName" 
						value="<?=$errval['firstName']? $errval['firstName'] : "First"?>"
				    	size="12" maxlength="16" tabindex="1" 
						<?if(!$errval['firstName'] || $errval['firstName'] == "First"){?>onfocus="clearField(this)"<?}?>/>

					<input type="text" name="lastName" 
						value="<?=$errval['lastName']? $errval['lastName'] : "Last"?>"
				    	size="12" maxlength="16" tabindex="2" 
						<?if(!$errval['lastName'] || $errval['lastName'] == "Last"){?>onfocus="clearField(this)"<?}?>/><br/>

				<span id="phoneHide">
					<?=$error['phone']?"<font color='red'>":""?>
					<label class="fLblRequest" for="phone"><span class="required">*</span>Phone</label>
					<?=$error['phone']?"</font>":""?>

						<input type="text" name="phone"
							value="<?=$errval['phone']? $errval['phone'] : "xxx-xxx-xxxx"?>"
							size="13" maxlength="14" tabindex="4"
							<?if(!$errval['phone'] || $errval['phone'] == "xxx-xxx-xxxx"){?>onfocus="clearField(this)"<?}?>/>
				</span>
				<input type="checkbox" name="phonex" onclick="visibility(this); removeInput('phone');"/> Don't have phone<br/>

				<?=$error['email']?"<font color='red'>":""?>
				<label class="fLblRequest" for="email"><span class="required">*</span>Email or Sidekick</label>
				<?=$error['email']?"</font>":""?>

					<input type="text" name="email"
						value="<?=$errval['email']? $errval['email'] : "Email Address"?>"
						size="35" maxlength="34" tabindex="7"
						<?if(!$errval['email'] || $errval['email'] == "Email Address"){?>onfocus="clearField(this)"<?}?>/><br/>
		</fieldset>

		<br/>

		<fieldset>
			<legend>Machine Information</legend>

			<?=$error['description']?"<font color='red'>":""?>
			<label class="fLblRequest" for="description">
				<span class="required">*</span>Description
			</label>
			<?=$error['description']?"</font>":""?>

				<input type="text" name="description"
					value="<?=$errval['description']? $errval['description'] : "Description of Computer"?>"
			    	size="35" maxlength="34" tabindex="8" 
					<?if(!$errval['description'] || $errval['description'] == "Description of Computer"){?>onfocus="clearField(this)"<?}?>/><br/>

			<?=$error['os']?"<font color='red'>":""?>
			<label class="fLblRequest" for="os">
				<span class="required">*</span>OS / Task
			</label>
			<?=$error['os']?"</font>":""?>
			<select name="os">
				<option value="select" >Select</option>
				<option value="Windows"<?=$errval['os'] == "Windows" ? "selected" : ""?>>Windows</option>
				<option value="MacOS" <?=$errval['os'] == "MacOS" ? "selected" : ""?>>Mac OS</option>
				<option value="Linux" <?=$errval['os'] == "Linux" ? "selected" : ""?>>Linux</option>
				<option value="Other" <?=$errval['os'] == "OtherOS" ? "selected" : ""?>>Other</option>
				<option value="DataBackup" <?=$errval['os'] == "DataBackup" ? "selected" : ""?>>Data Backup</option>
			</select><br/>

			<label class="fLblRequest" for="loginname">
				Computer Username
			</label>
				<input type="text" name="loginName"
					value="<?=$errval['loginName']? $errval['loginName'] : ""?>"
			    	size="35" maxlength="34" tabindex="9" 
					<?if(!$errval['loginName']){?>onfocus="clearField(this)"<?}?>/><br/>

			<label class="fLblRequest" for="loginPass">
				Login Password
			</label>
				<input type="text" name="loginPass"
					value="<?=$errval['loginPass']? $errval['loginPass'] : ""?>"
			    	size="35" maxlength="45" tabindex="10" 
					<?if(!$errval['loginPass']){?>onfocus="clearField(this)"<?}?>/><br/>
		
			<label class="fLblRequest" for="inventory">Inventory<br/>
				<span class="smalltext">Other items left at Resnet</span>
			</label>
				<input type="text" name="inventory"
					value="<?=$errval['inventory']? $errval['inventory'] : ""?>"
			    	size="35" maxlength="45" tabindex="11" 
					<?if(!$errval['inventory']){?>onfocus="clearField(this)"<?}?>/>
		</fieldset>

		<br/>

		<fieldset>
			<legend>Problem Information</legend>
			<?=$error['problem']?"<font color='red'>":""?>
			<label class="fLblRequest" for="problem"><span class="required">*</span>Problem Description</label>
			<?=$error['problem']?"</font>":""?>
			<textarea name="problem" rows="15" cols="50" tabindex="12"><?=$errval['problem']? $errval['problem'] : ""?></textarea>	

		</fieldset>
		<input type="submit" name="submit" value="Submit" tabindex="13"/>
		</div>
	</div>
</form>
