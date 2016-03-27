<?
/**
  * usermod.php
  *
  * User Modification page
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

// Include main header
$title = "User Management";
include "setup.inc";
include_once "inc/header.inc";

switch ($_REQUEST['action'])
{
	case "Update":
		$user = $_REQUEST['user'];

		if (!empty($user))
		{
			$editUser = User::getInstance($user);
			$editUser->updatePrivs($_REQUEST['priv']);
		}
?>
		<div class="box removeMe" style="width: 200px;">
			<div class="boxHeader noFold">
				Updated <?=$user?> Privs
			</div>
		</div>
<?
		include "index.php";
		exit;
	break;

	case "Add":
	case "User":
		$user = $_REQUEST['user'];

		if (!empty($user))
		{
			try {
				$editUser = User::getInstance($user);
			} catch (TCenterException $e) {
				$new = true;
			}
?>
			<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
			<div class="box" style="width:400px">
				<div class="boxHeader noFold">
					<?=$user?> - Privledge Editing
				</div>

				<div class="boxContent">
<?
					foreach ($tcenter->privs as $group=>$privs) 
					{
						print "<h3 style='margin: 0px;'>".ucfirst($group)."</h3>";

						$r = 0;
						echo "<table>";
						foreach ($privs as $priv)
						{
							if ($r%2 == 0)
								print "<tr>";

							$r++;
?>
							<td width="400px;">
								<input type='checkbox' name="priv[<?=$priv?>]" <?=$editUser->hasPriv($priv) ? "checked": ""?>/>
								<?=$priv?>
							</td>
<?
							if ($r%2 == 0)
								echo "</tr>";
						}
						echo "</table>";
						echo "<hr/>";
					}
?>
					<input type="submit" style="float: right" name="action" value="Update"/><br/>
					<input type="hidden" name="user" value="<?=$user?>"/>
				</div>
			</div>
			</form>
<?
			return;
		}
	break;
}
?>

<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
	<div class="box" style="width: 350px">
		<div class="boxHeader noFold">
			Add New User
		</div>

		<div class="boxContent">
			<center>
				Username: <input type="text" name="user"/>
			<input type="submit" name="action" value="Add"/>
			</center>
		</div>
	</div>
	<div class="box" style="width: 250px">
		<div class="boxHeader noFold">
			Active System Users
		</div>

		<div class="boxContent">
<?
			$db =& Database::getInstance();
			$sql = "select distinct admin_username from privs order by admin_username asc";
			$res = $db->query($sql);
			$i = 0;

			print "<table>";
				while ($user = $res->fetch_assoc())
				{
					if ($i%2 == 0)
						print "<tr>";

					$i++;
?>
					<td align="center" width="200px;">
						<span class='tlNumber'>
						<a href="<?=$_SERVER['PHP_SELF']?>?user=<?=$user['admin_username']?>&action=User"><?=$user['admin_username']?></a></span>
					</td>
<?
					if ($i%2 == 0)
						echo "</tr>";
				}
			print "</table>";
?>
		</div>
	</div>
</form>
