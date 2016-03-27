<html>
<head>
<? include ("connection.inc"); ?>
<? include( "ITS-head.html" ); ?>
</head>
<body>
<center>

<!--comensa capsalera -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" background="../../images/logo-right.gif">

<tr>

<!-- TOP HEADER -->

  <td>

    <div style="position:absolute;top:2px;right:3px;border-width:0px;font-weight:bold;color:white;">
    </div>

    <table width="100%" border="0" cellspacing="0" cellpadding="0" background="../../images/logo-right.gif" align="left">
    <tr><td><img src="../../images/logo-left.gif" alt="RIT Residential Computing" border="0"></td><td></td></tr>
    </table>

  </td>

  </tr>

<tr>

  <td colspan="2" valign="middle" bgcolor="#ff6600"><img src="../../images/espai.gif" width="10" height="10" alt="." border="0">

    <span class="slogan">Welcome to ITS Resnet &#032;&#064;&#032; RIT, <? echo $PHP_AUTH_USER; ?> </span>
  </td>

  <td bgcolor="#ff6600" valign="middle">
    <span class="slogan">
    <? echo date("M j, Y - g:i A"); ?>&nbsp;&nbsp;</span>
  </td>

</tr>

<tr>
  <td colspan="3" valign="middle" height="10"><img src="../../images/ombra.gif" width="100%" height="10" alt="." border="0"></td>
</tr>

<tr bgcolor="#FFFFFF">
  <td colspan="3" valign="middle" height="15"><img src="../../images/espai.gif" width="15" height="15" alt="." border="0"></td>
</tr>

</table>

<?
  if ($_REQUEST['POST_email'] == "yes")
  {
    $to = $_REQUEST['POST_to'];
    $from = "From: ".$_REQUEST['POST_from'];
    $subject = $_REQUEST['POST_subject'];
    $message = $_REQUEST['POST_message'];

    mail($to, $subject, $message, $from);
?>
    <table>
      <tr>
        <td>
          <h2>The email has been sent</h2>
        </td>
      </tr>
    </table>
    <input type='submit' name='close' value='Close Window' onclick='window.close()'/>
    <!-- since we close early hwere -->
    </center>
    </body>
    </html>
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

		$subject = "[RESNET] ".$_REQUEST['tcnumber']." : ".$subject;

		$username = substr($_REQUEST['tcnumber'], 0, strpos($_REQUEST['tcnumber'], "-"));
		$query = "SELECT * FROM users WHERE lower(username) LIKE lower('%$username%');";
		$result = pg_query($query);
		$row = pg_fetch_assoc($result);
    $to = trim($row['email']);

    //RIT Email, replaced with user email -- jdwrcc  2-5-07
		//$to = substr($_REQUEST['tcnumber'], 0, strpos($_REQUEST['tcnumber'], "-"));

    $from = 'restech@rit.edu';
  }
?>
  
  <form method='post' action='canned.php'>
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
      <td><input type='text' name='POST_subject' size='50' value='<?=$subject?>'/></td>
    </tr>
    <tr>
      <td valign='top'>
        Message:
      </td>
      <td>
        <textarea rows='25' cols='70' align='left' name='POST_message' wrap='hard'><?=$message?></textarea>
      </td>
    </tr>
    <tr>
      <td/>
      <td align="right">
      	<input type='hidden' name='POST_from' size='50' value='<?=$from?>'/>
      	<input type='hidden' name='POST_to' size='50' value='<?=$to?>'/>
        <input type='hidden' name='POST_email' value='yes'/>
        <input type='submit' name='POST_submit' value='Email'/>
      </td>
    </td>
  <table>
  </form>

</center>
</body>
</html>
