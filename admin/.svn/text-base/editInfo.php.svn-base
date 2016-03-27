<?
/**
  * editInfo.php
  *
  * Page to edit ticket-based information
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
  * @author	John Piermarini	(jpprcc@rit.edu)
  * @author	David Walker	(azrail@csh.rit.edu)
  */

include "setup.inc";
include "inc/header.inc";

$ticket = new Ticket($_REQUEST['t_id']);
$type = $_REQUEST['info']; //what are we editing (user, machine, etc.)

if ($_REQUEST['submit'] && $type == "owner") {
   	$date = date("m/d/y", time());
	$time = date("g:i A", time());

	$name = doubleQuote($_REQUEST['name']);
	$phone = doubleQuote(checkPhoneNumber($_REQUEST['phone']));
	$email = doubleQuote($_REQUEST['email']);
	
	if(!empty($name) && $ticket->owner->getAttrib("name")!=$name) {
		$action .= "[Changed name from ".$ticket->owner->getAttrib("name")." to ".$name."]<br/>";
		$ticket->owner->updateAttrib("name",$name);	
	}
	if(!empty($phone) && $ticket->owner->getAttrib("phone")!=$phone) {
		$action .= "[Changed phone from ".$ticket->owner->getAttrib("phone")." to ".$phone."]<br/>";
		$ticket->owner->updateAttrib("phone",$phone);
	}
	if(!empty($email) && $ticket->owner->getAttrib("email")!=$email) {
		$action .= "[Changed e-mail from ".$ticket->owner->getAttrib("email")." to ".$email."]<br/>";
		$ticket->owner->updateAttrib("email",$email);
	}

	if (!is_null($action))
	{
		$action = "<font color='red'>$action</font>";
		$v = "<span class='tUpdate'><span class='tUpdateDate'>".$tcenter->user->username." [$date @ $time]</span>$action</span><br/>".$ticket->getAttrib("updates");
		$ticket->updateAttrib("updates", $v);
	}

    tdump($ticket);
	$ticket->owner->save();
	$ticket->save();
}
else if ($_REQUEST['submit'] && $type == "machine") {
    $date = date("m/d/y", time());
	$time = date("g:i A", time());

	$inventory = doubleQuote($_REQUEST['inventory']);
	$login_name = doubleQuote($_REQUEST['login_name']);
	$login_password = doubleQuote($_REQUEST['login_password']);

	if(isset($inventory) && $ticket->machine->getAttrib("inventory")!=$inventory) {
		$action .= "[Changed inventory from ".$ticket->machine->getAttrib("inventory")." to ".$inventory."]<br/>";
		$ticket->machine->updateAttrib("inventory", $inventory);
	}
	if(isset($login_name) && $ticket->machine->getAttrib("login_name")!=$login_name) {
		$action .= "[Changed login name from ".$ticket->machine->getAttrib("login_name")." to ".$login_name."]<br/>";
		$ticket->machine->updateAttrib("login_name", $login_name);
	}
	if(isset($login_password) && $ticket->machine->getAttrib("login_password")!=$login_password) {
		$action .= "[Changed login password from ".$ticket->machine->getAttrib("login_password")." to ".$login_password."]<br/>";
		$ticket->machine->updateAttrib("login_password", $login_password);
	}

	if (!is_null($action))
	{
		$action = "<font color='red'>$action</font>";
		$v = "<span class='tUpdate'><span class='tUpdateDate'>".$tcenter->user->username." [$date @ $time]</span>$action</span><br/>".$ticket->getAttrib("updates");
		$ticket->updateAttrib("updates", $v);
	}

	$ticket->machine->save();
	$ticket->save();
}

?>

<? if ( $type == "owner" ) { ?> 
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <div class="box" style="width: 300px">
        <div class="boxHeader noFold">
            Owner Information
        </div>
        <div class="boxContent">
            <table>
                <tr>
                    <td>Name: </td>
                    <td><input type="text" name="name" value="<?=$ticket->owner->getAttrib("name")?>"/></td>
                </tr>

                <tr>
                    <td>Phone:</td>
                    <td><input type="text" name="phone" value="<?=$ticket->owner->getAttrib("phone")?>"/></td>
                </tr>

                <tr>
                    <td>Email: </td>
                    <td><input type="text" name="email" value="<?=$ticket->owner->getAttrib("email")?>"/></td>
                </tr>

                <tr><td>
                    <center><input type="submit" name="submit" onclick="window.close(); window.opener.delayRequest(400);" value="Update Information"/></center>
                </td></tr>
            </table>
            <input type='hidden' name='info' value='owner'/>
            <input type='hidden' name='t_id' value='<?=$_REQUEST['t_id']?>'/>
        </div>
    </div>
</form>
<?} else if ( $type == "machine" ) {?>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
    <div class="box" style="width: 300px">
        <div class="boxHeader noFold">
            Machine Information
        </div>
        <div class="boxContent">
            <table>
                <tr>
                    <td>Login Name: </td>
                    <td><input type="text" name="login_name" value="<? echo $ticket->machine->getAttrib("login_name");?>" /></td>
                </tr>

                <tr>
                    <td>Login Password:</td>
                    <td><input type="text" name="login_password" value="<? echo $ticket->machine->getAttrib("login_password");?>" /></td>
                </tr>

                <tr>
                    <td>Inventory: </td>
                    <td><input type="text" name="inventory" value="<? echo $ticket->machine->getAttrib("inventory");?>" /></td>
                </tr>

                <tr><td>
                    <center><input type="submit" name="submit" value="Update Information" onclick="window.close(); window.opener.delayRequest(400);" /></center>
                </td></tr>
            </table>
            <input type='hidden' name='info' value='machine'/>
            <input type='hidden' name='t_id' value='<?=$_REQUEST['t_id']?>'/>
        </div>
    </div>
</form>
<? } ?>
