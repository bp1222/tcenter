<?
/**
  * auth.inc
  *
  * Authentication Support for TCenter
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
  * @package TCenter
  */

include "auth/ldap.inc";

abstract class Auth
{
	protected $authenticated = false;
	protected $username = "";

	/*
	 * isAuth 
	 *
	 * Make sure that the user has authenticated with the system
	 *
	 * @param silent - Should we display the login page if fail?
	 */
	public function hasAuth ($requiredAuth = null, $silent = false)
	{
		global $tcenter;

		/*
		 * See if we're logging in
		 */
		if (!empty($_REQUEST['t_auth_name']))
		{
			$this->authenticate($_REQUEST['t_auth_name'], $_REQUEST['t_auth_password']);
			if (isset($_REQUEST['relogin'])){
			?>
				<script language='javascript'>
					window.opener.loggedOut();
					window.close();
				</script>
			<?
			}
		}

		/*
		 * How to process when the user is NOT authentication
		 */
		if (!$this->authenticated)
			if ($silent)
				return false;
			else
				$this->displayAuth();

		// Create the User Instance ... We should never get here unless we're auth
		// Don't worry about the exception from the getInstance function
		User::getInstance($this->username, true);
		return true;
	}

	/*
	 * displayAuth
	 *
	 * Show the authentication page
	 *
	 * This function does not return
	 */
	function displayAuth ()
	{
		$action = $_SERVER['PHP_SELF'];
		if ($_SERVER['QUERY_STRING'])
			$action .= "?".$_SERVER['QUERY_STRING'];

		unset($_REQUEST['PHPSESSID']);
		unset($_REQUEST['t_auth_name']);
		unset($_REQUEST['t_auth_password']);
		unset($_REQUEST['__utma']);
		unset($_REQUEST['__utmz']);
		$information = (base64_encode(serialize($_REQUEST)));

		// Create the form
?>
		<form method='post' name='auth' action='<?=$_SERVER['PHP_SELF']?>'>
			<input type='hidden' value='<?=$information?>' name='old_request'/>
			<?if (isset($_REQUEST['relogin'])){?>
				<input type='hidden' value='true' name='relogin'/>
			<?}?>
			<div class="box" style="width: 300px">
				<div class="boxHeader noFold">
					Authenticate
				</div>

				<div class="boxContent">
                    <table>
    					<tr>
	    					<td>Username:</td>
		    				<td><input type="text" name="t_auth_name"/></td>
			    		</tr>

				    	<tr>
					    	<td>Password:</td>
						    <td><input type="password" name="t_auth_password"/></td>
    					</tr>
                    </table>
    	    		<center>
	    	    		<input type="submit" name="login" value="Login"/>
		    	   	</center>
				</div>
			</div>
		</form> 
		<script language="javascript">
			clearInterval(logoutInterval);
			$("#adminNav").css("display", "none");
			document.auth.t_auth_name.focus();
		</script>
<?
		exit;
	}

	/*
	 * authenticate
	 *
	 * Function to process the "authentication" of whatever system is being used
	 */
	abstract protected function authenticate ($username, $password);
}
