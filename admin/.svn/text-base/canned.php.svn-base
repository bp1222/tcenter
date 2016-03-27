<?
/**
 * canned.php
 *
 * Stock responce emails
 *
 *	@author	David Walker	(azrail@csh.rit.edu)
 */

$title = "Canned Responce";
include "inc/header.inc";

if ($_REQUEST['email'] == "yes")
{
	$to = $_REQUEST['to'];
	$from = "From: ".$_REQUEST['from'];
	$subject = $_REQUEST['subject'];
	$message = $_REQUEST['message'];

	mail($to, $subject, $message, $from);

	// Update the ticket, then we are going to javascript reload our parent, and reload the page
	$sql = "SELECT updates FROM ticket WHERE t_id = '".$_REQUEST['t_id']."'";
	$result = mysql_fetch_assoc(mysql_query($sql));

	$old_update = $result['updates'];
	$update = "<b>".$PHP_AUTH_USER." [".date("m/d/y @ g:iA")."]</b><br/>";
	$update .= "<b>[Emailed User]</b><br/>";
	$update .= nl2br($message)."<br/><br/>";
	$update .= mysql_real_escape_string($old_update);
	$ticket_update .= "updates = '$update' ";

	$sql = "UPDATE ticket SET updates = '$update' WHERE t_id = '".$_REQUEST['t_id']."'";
	mysql_query($sql);
?>
<script language="JavaScript" type="text/javascript">
  <!--
	opener.location.reload(true);
    self.close();
  // -->
</script>
<?	
	return;
}
else
{
	$name = explode(" ", $_REQUEST['name']);
	$name = $name[0];

	if ($_REQUEST['value'] == "cd") 
	{ 
		$message = "Dear $name,\n\n".
			"Resnet is currently working to complete the repair request ".
			"you have submitted for your computer. However to continue ".
			"work on you computer we will need a genuine copy of Microsoft ".
			"Windows with valid CD key, or the CDs that came with your ".
			"computer. Please reply to this email or contact the Resnet ".
			"office at 475-2600 or 475-4927 TTY. Your computer repair ".
			"will be placed on hold until we receive word from you.\n\n".
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
		$subject = 'Request for Install CD\'s';
	}
	else if ($_REQUEST['value'] == "repair")
	{
		$message = "Dear $name,\n\n".
			"Resnet is currently working to complete the repair request ".
			"you have submitted for your computer. Following extensive ".
			"testing and diagnosis, we have determined a Repair Installation ".
			"of your Operating System is in order. We can begin the process ".
			"only with your permission, due to the fact that Repair ".
			"Installations can occasionally delete data. We will, with your ".
			"permission, backup your personal data, which includes the ".
			"My Documents folder minus any copyrighted materials ".
			"(includes videos or music).  We can not back up or re-install ".
			"any applications that may be deleted in the process, unless ".
			"you can provide the licenses for these titles. Repair ".
			"installations are usually done with out any data loss or ".
			"application loss, but do require permission from the user ".
			"in the case of corruption. If you have not already brought ".
			"your Operating System CD, with valid CD key, you will also ".
			"need to bring that to us to complete the repair install. ".
			"Please reply to this email or contact the Resnet office at ".
			"475-2600. or 475-4927 TTY. Your computer repair will be ".
			"placed on hold until we receive word from you.\n\n".
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
		$subject = 'Request for Repair Install';
	}
	else if ($_REQUEST['value'] == "done")
	{
		$message = "Dear $name,\n\n".
			"Resnet has completed work on your repair request.  ".
			"Please pick up your items at your earliest possible ".
			"convenience. Our office hours and phone number are ".
			"listed below if you have any further questions.\n\n".
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

			$subject = 'Computer Work Complete';
	}
	else if ($_REQUEST['value'] == "full")
	{
		$message = "Dear $name,\n\n".
			"Resnet is currently working to complete the repair request ".
			"you have submitted for your computer. Following extensive ".
			"testing and diagnosis, we have determined a Full Re-Installation ".
			"of your Operating System is required. We can begin the process ".
			"only with your permission due to the fact that a Full ".
			"Re-Installation will erase all data and applications. ".
			"We will, with your permission, backup your personal data, ".
			"which includes the My Documents folder minus any copyrighted ".
			"materials (includes video or music).  We can not back up or ".
			"re-install any applications that may be deleted in the process, ".
			"unless you can provide the licenses for these titles. Depending ".
			"on the severity of the problem, we may or may not be able to ".
			"backup your data. If you have not already brought your Operating ".
			"System CD, with it's valid CD key, you will also need to ".
			"bring that for us to complete the full install. Please reply ".
			"to this email or contact the Resnet office at 475-2600 or ".
			"475-4927 TTY. Your computer repair will be placed on hold ".
			"until we receive word from you.\n\n".
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
			$subject = 'Request for Full Install';
	}
	else
	{
		$message = "Dear $name,\n\n  


".
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
	}

	$query = "SELECT user.username, user.email, user.first_name, user.last_name, ticket.t_id, ticket.u_id FROM user, ticket WHERE ticket.u_id = user.u_id AND ticket.t_id = '".$_REQUEST['t_id']."'";
	$result = mysql_query($query);
	$row = mysql_fetch_assoc($result);

	$username = $row['username'];
	$to = $row['email'];
	$subject = "[RESNET] ".$username."-".$_REQUEST['t_id']." : ".$subject;

	//RIT Email, replaced with user email -- jdwrcc  2-5-07
	//$to = substr($_REQUEST['tcnumber'], 0, strpos($_REQUEST['tcnumber'], "-"));

	$from = 'restech@rit.edu';
}
?>

<form method='post' action='<?=$PHP_SELF?>'>
	<div class="box">
		<?topCorner();?>
		<div class="boxHeader">
			Canned Responce
		</div>
		<div class="boxConcent">
			<table width="30%">
				<tr>
					<td>To:</td>
					<td><?=$to?></td>
				</tr>
				<tr>
					<td>From:</td>
					<td><?=$from?></td>
				</tr>
				<tr>
					<td>Subject:</td>
					<td><input type='text' name='subject' size='50' value='<?=$subject?>'/></td>
				</tr>
				<tr>
					<td valign='top'>Message:</td>
					<td>
						<textarea rows='25' cols='70' align='left' name='message' wrap='hard'><?=$message?>
						</textarea>
					</td>
				</tr>
				<tr>
					<td/>
					<td align="right">
						<input type='hidden' name='from' size='50' value='<?=$from?>'/>
						<input type='hidden' name='to' size='50' value='<?=$to?>'/>
						<input type='hidden' name='email' value='yes'/>
						<input type="hidden" name="t_id" value="<?=$_REQUEST['t_id']?>"/>
						<input type='submit' name='submit' value='Email'/>
					</td>
				</tr>
			</table>
		</div>
		<?bottomCorner();?>
	</div>
</form>
