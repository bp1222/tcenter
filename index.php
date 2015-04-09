<?php
/**
  * index.php
  *
  * Main Public page.  Display the waiver.
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

// Include main header
$title = "Welcome to TCenter";
include "inc/header.inc";

// Form needs to start here otherwise safari does not show the buttons.
?>
<form method="post" action="request.php">
	<div class="box" style="width: 820px">
		<div class="boxHeader noFold">
			ITS RESNET TECHNICAL SUPPORT REQUEST AND WAIVER
		</div>
	
		<div class="boxContent">
			<b> 
				I hereby request ITS technical support on the submitted equipment. I also state the submitted equipment legally belongs to me. By accepting technical support from Resnet staff, I understand that (You must agree to all terms):
			</b>
	
			<ol>
				<li>
					I expressly waive all claims against Resnet and its agents for any damages to my computer system or data that are incurred as a result of the technical support rendered by Resnet. ("Resnet" includes Information & Technology Services & RIT throughout this document). I acknowledge that this is necessary due to the fact that the Resnet team has no way of verifying the condition of the machine until it is accepted into our care.
				</li>
				<li>
					The technical support I receive from ITS may void manufacturer warranties.
				</li>
				<li>
					Resnet offers no verbal or written warranty, either expressed or implied, regarding the success of this technical support.
				</li>
				<li>
					I waive any privacy rights in data or files on said equipment that are accessed in regard to the service provided by Resnet (to the extent that they are viewed pursuant to said service).
				</li>
				<li>
					A Resnet technician that sees anything illegal on said equipment at any point during our service may be required to report same to his or her supervisor.
				</li>
				<li>
					I have the right not to accept support from Resnet staff and to seek technical assistance elsewhere. However, if my machine is quarantined due to a virus, I will need to provide adequate repair details to Resnet before my machine will be removed from quarantine.
				</li>
				<li>
					I understand that Resnet is unable to support Unlicensed or cracked versions of software. If I am running an illegal copy of my operating system Resnet will not be able to provide support, and I may be removed from the network until a license is obtained.
				</li>
			</ol>

			<center><span style="width:20%; font-size: 1.4em;"><b>Computers admitted to the techcenter <u>must</u> be picked up by the user that dropped it off. With RIT ID.<br/><br/>Resnet reserves the right to dispose of devices after 12 weeks of requesting user information</b></span></center>
		
			<hr/>
		
			<b>
				Notice: All computers received by Resnet and serviced in the Tech Center will have the following actions taken:
			</b>
	<br/>	
		    * Upon admission to the Resnet Tech Center, all of the local account passwords on your machine will be reset to protect your privacy. When your computer is returned to you, we encourage you to reset all of your local account passwords for your safety and privacy.
	<br/>	
			* The Operating System will be updated with the latest Security Patches and Updates.
	<br/>	
			* If you currently have a virus scanner installed and we are unable to update it to the latest virus definitions, we may need to disable it. If so, R.I.T.'s free Virus Scanning software (McAfee's Virus Scan) will be installed and configured.
	<br/>	
			* Any and all virii that are located will be cleaned andg or deleted.
	<br/>	
			* A general network security audit will be performed.
	<br/>	
			* Spyware and any other applications that appear to be malicious or affect system functionality may be removed (this could include file-sharing programs such as Kazaa).
	<br/>	
		
			<p><b>***If your system requires back up we will back up legitimate data files, however, Resnet will not back up any media files (mp3, mpeg, etc.) on our server. These files include, but are not limited to, anything of an adult graphic nature or that may be in violation of copyright laws.***</b></p>
		
			<input type="submit" name="accept" value="Accept"/>
			<input type="submit" name="deny" value="Deny"/>
		</div>
	</div>
</form>
