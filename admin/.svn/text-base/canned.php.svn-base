<?
/**
  * canned.php
  *
  * stock response emails
  *
  * TCenter 3 - Ticket Management
  * Copyright (C) 2007-2008		Rochester Institute of Technology
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
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

$title = "Canned Responce";
include "setup.inc";
include "inc/header.inc";

if ($_REQUEST['email'] == "yes")
{
	$to = $_REQUEST['to'];
	$from = "From: ".$_REQUEST['from'];
	$subject = $_REQUEST['subject'];
	$message = $_REQUEST['message'];

	mail($to, $subject, $message, $from);

	$subject = doubleQuote($subject);
	$message = doubleQuote($message);

	// Update the ticket, then we are going to javascript reload our parent, and reload the page
    $ticket = new Ticket($_REQUEST['t_id']);

    $date = date("m/d/y", time());
    $time = date("g:i A", time());

	$action = "<font color='red'>[Emailed User]</font><br/>";
    $update = "<span class='tUpdate'><span class='tUpdateDate'>".$tcenter->user->username." [$date @ $time]</span>$action$message</span><br/>".$ticket->getAttrib("updates");

    $ticket->updateAttrib("updates", $update);
    $ticket->save();
?>
<script language="JavaScript" type="text/javascript">
	opener.location.reload(true);
	window.close();
</script>
<?	
	return;
}
else
{
    $ticket = new Ticket($_REQUEST['t_id']);
	$name = $ticket->owner->getAttrib("name");

	if ($_REQUEST['value'] == "cd") 
	{ 
		$message = "Dear $name,\n\n".
			"Resnet is currently working to complete the repair request ".
			"you have submitted for your computer. However to continue ".
			"work on you computer we will need a genuine copy of Microsoft ".
			"Windows with valid cd key, or the cds that came with your ".
			"computer. Please reply to this email or contact the resnet ".
			"office at 475-2600 or 475-4927 tty. Your computer repair ".
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

	$to = $ticket->owner->getAttrib("email");
	$username = $ticket->owner->getAttrib("username");
	$subject = "[RESNET] ".$username."-".$_REQUEST['t_id']." : ".$subject;

	//RIT Email, replaced with user email -- jdwrcc  2-5-07
	//$to = substr($_REQUEST['tcnumber'], 0, strpos($_REQUEST['tcnumber'], "-"));

	$from = 'restech@rit.edu';
}
?>

<form method='post' action='<?=$_SERVER['PHP_SELF']?>'>
	<div class="box" style="width: 90%">
		<div class="boxHeader noFold">
			Canned Responce
		</div>
		<div class="boxContent">
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
                        <input type="submit" name="submit" value='Email' onclick="window.close(); window.opener.delayRequest(400);"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
</form>
