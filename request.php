<?
/**
  * request.php
  *
  * Form for users to request machines
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  */

// include main header
$title = "Service Request";
$nonav = true;
include "inc/header.inc";

// Clear error values
$errval = array();

// If they didn't accept the agreement from the previous page
// so if !submt_this_page && !accept_last (Deny only)
if (!$_REQUEST['submit'] && !$_REQUEST['accept'])
{
?>
	<div class="box" style="text-align: center; width: 40%">
		<?topCorner();?>
		<div class="boxContent">
			In order to employ Resnet for technical support you need to agree to the <a href="index.php">Terms of Agreemet</a>
		</div>
		<?bottomCorner();?>
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
	$firstName	 = mysql_real_escape_string(trim($_REQUEST['firstName'])); 
	$lastName	 = mysql_real_escape_string(trim($_REQUEST['lastName'])); 
	$username	 = mysql_real_escape_string(trim($PHP_AUTH_USER)); 
	$phone		 = mysql_real_escape_string(trim($_REQUEST['phone']));
	$email		 = mysql_real_escape_string(trim($_REQUEST['email'])); 
	$description = mysql_real_escape_string(trim($_REQUEST['description'])); 
	$loginName	 = mysql_real_escape_string(trim($_REQUEST['loginName'])); 
	$loginPass	 = mysql_real_escape_string(trim($_REQUEST['loginPass'])); 
	$inventory	 = mysql_real_escape_string(trim($_REQUEST['inventory'])); 
	$problem	 = mysql_real_escape_string(nl2br($_REQUEST['problem'])); 

	// First Name
	if (empty($firstName) || $firstName == "First")
		$error["firstName"] = true;

	// Last Name
	if (empty($lastName) || $lastName == "Last")
		$error["lastName"] = true;

	// Username
	if (empty($username) || $username == "Username")
		$error["username"] = true;

	// Phone
	// Replace all '-' with ' ', then remove spaces.  Then count, digits must = 7 or 10
	$phone_pattern = "/^[0-9]{7,10}$/";
	$phone = str_replace ('-', '', $phone);
	if ((strlen($phone) != 7 && strlen($phone) != 10)
     || preg_match($phone_pattern, $phone) == 0)
		$error["phone"] = true;

	// Email
	$email_pattern = "/^[A-Za-z0-9\._-]+@[A-Za-z0-9][A-Za-z0-9-]*(\.[A-Za-z0-9_-]+)*\.([A-Za-z]{2,6})$/";
	if ((empty($email) || $email == "Email Address") || 
	preg_match($email_pattern, $email) == 0)
		$error["email"] = true;

	// Descripion
	if (empty($description) || $description == "Description of Computer")
		$error["description"] = true;

	// Login Name
	if (empty($loginName) || $loginName == "Username on Computer")
		$error["loginName"] = true;

	// Problem Description
	if (empty($problem) || $problem == "")
		$error["problem"] = true;

	// As long as we don't have errors we should add this ticket into our DB
	if (empty($error))
	{
		// Check for existing user
		$user_sql = "SELECT * FROM user WHERE username = '$username'";
		$user_result = mysql_query($user_sql);
	
		// User does not exist, insert them.
		if (mysql_num_rows($user_result) == 0)
		{
			$user_sql = "INSERT INTO user (`u_id`, `username`, `first_name`, `last_name`,  `phone`, `email`)
				VALUES (NULL, '$username', '$firstName', '$lastName', '$phone', '$email')";
			mysql_query($user_sql);
			
			// Get this new users system u_id
			$u_id =  mysql_insert_id();
		}
		else
		{
			$userinfo = mysql_fetch_assoc($user_result);
			$u_id = $userinfo['u_id'];
		}
		// End User Checking
	
		// Insert the machine 
		$machine_sql = "INSERT INTO machine (`m_id`, `u_id`, `description`, `inventory`, `login_name`, `login_password`)
			VALUES (NULL, '$u_id', '$description', '$inventory', '$loginName', '$loginPass')";
		mysql_query($machine_sql);
	
		$m_id = mysql_insert_id();
		// End Inserting Machine
		
		// Insert into tickets
		$ticket_sql = "INSERT INTO ticket (
				`t_id`, `u_id`, `m_id`, `open_date`, `close_date`, 
				`summary`, `problem`, `updates`, `status`, `closed_by`, `repair`, `full`, `warranty`, `priority`, 
				`location`, `faculty`) VALUES ( NULL, '$u_id', '$m_id', NOW(), NULL,
				'',	'$problem', '', 0, '', '', '', '', '', '', '')";
		mysql_query($ticket_sql);
?>
		<div class="box" style="width:30%">
			<?topCorner();?>
			<div class="boxHeader" style="font-size: 1.3em;">
				Your service request has been logged
			</div>
			</br>Please make sure all items needed are left at the Resnet office.
			<?bottomCorner();?>
		</div>
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
		$errval['inventory'] = $_REQUEST['inventory']; 
		$errval['problem'] = $_REQUEST['problem']; 
?>
	<div class="box" style="width:25%">
		<?topCorner();?>
		<div class="boxHeader" style="font-size: 1.3em;">There was an Error</div>
		Please check to make sure required fields are filled out.
		<?bottomCorner();?>
	</div>
<?
	}
}

// Get the current wait time, we need to show that up!
$sql = "SELECT waittime FROM system WHERE s_id = '1'";
$result = mysql_fetch_assoc(mysql_query($sql));
$waittime = $result['waittime'];
?>

<form method="post" action="<?$PHP_SELF?>">
	<div class="box" style="width:65%">
		<?topCorner();?>
		<div class="boxHeader">
			Resnet Technical Support Request Form
		</div>

		<div class="boxContent">
			All of the information on this form is necessary to process your request. If you are not sure how to fill out a certain entry, ask an RCC for help. If you do not fill in all of the data for this sheet, or you fill in the sheet improperly, you will be redirected back to the form until it is properly filled out. 

			<hr/>

			<p>
				Note: Fields with a <span class="required">*</span> are required. 
			</p>
			<p>
				Current wait time for machines submitted today is : <b><?=$waittime?></b>
			</p>

			<fieldset>
				<legend>User Information</legend>

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

				<?=$error['phone']?"<font color='red'>":""?>
				<label class="fLblRequest" for="phone"><span class="required">*</span>Phone</label>
				<?=$error['phone']?"</font>":""?>

					<input type="text" name="phone"
						value="<?=$errval['phone']? $errval['phone'] : "xxx-xxx-xxxx"?>"
						size="12" maxlength="13" tabindex="4"
						<?if(!$errval['phone'] || $errval['phone'] == "xxx-xxx-xxxx"){?>onfocus="clearField(this)"<?}?>/><br/>

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

			<?=$error['loginName']?"<font color='red'>":""?>

			<label class="fLblRequest" for="loginname">
				<span class="required">*</span>Computer Username
			</label>
			<?=$error['loginName']?"</font>":""?>
				<input type="text" name="loginName"
					value="<?=$errval['loginName']? $errval['loginName'] : "Username on Computer"?>"
			    	size="35" maxlength="34" tabindex="9" 
					<?if(!$errval['loginName'] || $errval['loginName'] == "Username on Computer"){?>onfocus="clearField(this)"<?}?>/><br/>

			<label class="fLblRequest" for="loginPass">Login Password<br/>
				<span class="smalltext">Leave Blank if None</span>
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
			<textarea name="problem" rows="15" cols="50"><?=$errval['problem']? $errval['problem'] : ""?></textarea>	

		</fieldset>
		<input type="submit" name="submit" value="Submit" tabindex="9"/>
		</div>
		<?bottomCorner();?>
	</div>
</form>
