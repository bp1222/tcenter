<?
/**
  * ticket.php
  *
  * View a given ticket
  * We use ticket_process.php to actually process the request.  This is for 2 reasons.
  * One, it's cleaner.  Two (the main one) we can remove the post-data that prevents easy
  * refreshing of the page.
  *
  * @author	David Walker	(azrail@csh.rit.edu)
  * @author Jeremy Wozniak	(jpwrcc@rit.edu)
  */

// Needed for IE to display titles on the ticket page.
// Including "inc/header.inc" later, but not using
// it's includes
// MAJOR Work-Around
// IE Needs to have the <head> tag before the <body> tag or it wont
// render with the stylesheets.  So At the top of the page we include
// everything in inc/header.  So here we tell inc/header.inc not to include
// anything, since we already did.
require "basepath.inc";
require "inc/connection.inc";
include_once "inc/lookup.inc";
include_once "inc/ticket.inc";
include_once "inc/style.inc";
$orig_ticket = getAllTicketById($_REQUEST['t_id']);
$noinc = true;
$title = $orig_ticket['username']."-".$orig_ticket['t_id'];
include "inc/header.inc";

// Probably should check around here to see if the t_id given is valid.
if (!$orig_ticket['t_id'])
{
?>
	<div class="box" style="width: 20%">
		<?topCorner();?>
		<div class="boxHeader">
			<?if($_REQUEST['t_id'] == 0){?>
				No tickets with that Username
			<?} else {?>
				Unknown Ticket
			<?}?>
		</div>
		<?bottomCorner();?>
	</div>
<?
	exit;
}

// Get information, we're displaying it all.
$ticket = getAllTicketById($_REQUEST['t_id']);
$ticket['tasks'] = intval($ticket['tasks']);
?>

<form method="post" action="<?echo "ticket_process.php?t_id=$t_id"?>">
	<div class="column1" style="margin-left: 10px; width: 30%;">
		<div class="box">
			<?topCorner();?>
			<div class="boxHeader collapsed" onclick="expand(this)">
				User Information
			</div>
		
			<div class="boxContent" style="display: none;">
				<table width="100%">	
					<tr>
						<td><span class="fLblTicket">Name</span></td>
						<td><span class="fLblTicket">
							<?=$ticket['first_name']?> <?=$ticket['last_name']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Phone</td>
						<td class="fLblTicket">
							<?=$ticket['phone']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Email</td>
						<td class="fLblTicket">
							<?=$ticket['email']?>
						</td>
					</tr>
				</table>
			</div>
			<?bottomCorner();?>
		</div>

		<div class="box">
			<?topCorner();?>
			<div class="boxHeader collapsed" onclick="expand(this)">
				Machine Information
			</div>
		
			<div class="boxContent" style="display: none;">
				<table width="100%">	
					<tr>
						<td class="fLblTicket">Login Name</td>
						<td class="fLblTicket">
							<?=$ticket['login_name']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Login Password</td>
						<td class="fLblTicket">
							<?=$ticket['login_password']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Inventory</td>
						<td class="fLblTicket">
							<?=$ticket['inventory']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Add Inventory</td>
						<td class="fLblTicket">
							<input type="text" name="addInv" size="15"/>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Description</td>
						<td class="fLblTicket">
							<?=$ticket['description']?>
						</td>
					</tr>
				</table>
			</div>
			<?bottomCorner();?>
		</div>

		<div class="box">
			<?topCorner();?>
			<div class="boxHeader expanded" onclick="expand(this)">
				Ticket Information
			</div>

			<div class="boxContent" style="display: block;">
				<table width="100%">	
					<tr>
						<td class="fLblTicket">Ticket Opened</td>
						<td class="fLblTicket">
							<?=$ticket['open_date']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Summary</td>
						<td class="fLblTicket">
							<?=$ticket['summary']?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Location</td>
						<td class="fLblTicket">
							<?if($ticket['location']) {?>
								<input type="radio" name="r_location" value="0"/>Office<br/>
								<input type="radio" name="r_location" checked value="1"/>Tech Center
							<?} else {?>
								<input type="radio" name="r_location" checked value="0"/>Office<br/>
								<input type="radio" name="r_location" value="1"/>Tech Center
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Status</td>
						<td class="fLblTicket">
							<select name="s_status">
								<option <?if($ticket['status'] == "Queued") 
									echo "selected"?>>Queued</option>
								<option <?if($ticket['status'] == "In Progress") 
									echo "selected"?>>In Progress</option>
								<option <?if($ticket['status'] == "Pending Customer") 
									echo "selected"?>>Pending Customer</option>
								<option <?if($ticket['status'] == "Awaiting Pickup") 
									echo "selected"?>>Awaiting Pickup</option>
								<option	<?if($ticket['status'] == "Admin Hold") 
									echo "selected"?>>Admin Hold</option>
								<?if ($ticket['status'] == "Closed"){?>
									<option	selected>Closed</option>
								<?}?>
							</select>
					</tr>
					<tr>
						<td class="flblTicket">Senior Signoff</td>
						<td class="flblTicket">
							<input type="text" value="<?=$ticket['senior']?>" name="t_senior" maxlength="3" size="3"/>
						</td>
					<tr>
						<td class="fLblTicket">Repair Install</td>
						<td class="fLblTicket">
							<?if($ticket['repair']) {?>
								<input type="checkbox" name="c_repair" checked/>
							<?} else {?>
								<input type="checkbox" name="c_repair"/>
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Full Install</td>
						<td class="fLblTicket">
							<?if($ticket['full']) {?>
								<input type="checkbox" name="c_full" checked/>
							<?} else {?>
								<input type="checkbox" name="c_full"/>
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket">Faculty Machine</td>
						<td class="fLblTicket">
							<?if($ticket['faculty']) {?>
								<input type="checkbox" name="c_faculty" checked/>
							<?} else {?>
								<input type="checkbox" name="c_faculty"/>
							<?}?>
						</td>
					</tr>
					<tr>
						<td class="fLblTicket" valign="top">Problem Description</td>
						<td class="fLblTicket">
							<?=$ticket['problem']?>
						</td>
					</tr>
				</table>
			</div>
			<?bottomCorner();?>
		</div>

		<div class="box">
			<?topCorner();?>
			<div class="boxHeader collapsed" onclick="expand(this)">
				Canned Responses
			</div>

			<div class="boxContent" style="display: none;">
				<table width="100%">	
					<tr>
						<td>Computer Complete</td>
						<td><a style="font-size: 1em;" href="canned.php?value=done&amp;name=<?=$ticket['first_name']." ".$ticket['last_name']?>&amp;t_id=<?=$ticket['t_id']?>" target="_blank" onclick="return!open(this.href, this.target, 'width=800,height=700,screenX=150,screenY=50')">Inform User</a></td>
					</tr>
					<tr>
						<td>Need Windows / System CD</td>
						<td><a style="font-size: 1em;" href="canned.php?value=cd&amp;name=<?=$ticket['first_name']." ".$ticket['last_name']?>&amp;t_id=<?=$ticket['t_id']?>" target="_blank" onclick="return!open(this.href, this.target, 'width=800,height=700,screenX=150,screenY=50')">Request Media</a></td>
					</tr>
					<tr>
						<td>Repair Install Permission</td>
						<td><a style="font-size: 1em;" href="canned.php?value=repair&amp;name=<?=$ticket['first_name']." ".$ticket['last_name']?>&amp;t_id=<?=$ticket['t_id']?>" target="_blank" onclick="return!open(this.href, this.target, 'width=800,height=700,screenX=150,screenY=50')">Request Repair Install</a></td>
					</tr>
					<tr>
						<td>Full Install Permission</td>
						<td><a style="font-size: 1em;" href="canned.php?value=full&amp;name=<?=$ticket['first_name']." ".$ticket['last_name']?>&amp;t_id=<?=$ticket['t_id']?>" target="_blank" onclick="return!open(this.href, this.target, 'width=800,height=700,screenX=150,screenY=50')">Request Full Install</a></td>
					</tr>
					<tr>
						<td>Create Blank Email</td>
						<td><a style="font-size: 1em;" href="canned.php?value=&amp;name=<?=$ticket['first_name']." ".$ticket['last_name']?>&amp;t_id=<?=$ticket['t_id']?>" target="_blank" onclick="return!open(this.href, this.target, 'width=800,height=700,screenX=150,screenY=50')">Blank Email</a></td>
					</tr>
				</table>
			</div>
			<?bottomCorner();?>
		</div>
	</div>

	<div class="column2" style="float: none; margin-left: 33%">
		<div class="box password">
			<?topCorner();?>
			<div class="boxHeader">
				<?if($ticket['login_password']){?>
					<?=$ticket['login_password']?>
				<?} else {?>
					No Password
				<?}?>
			</div>
			<?bottomCorner();?>
		</div>
		<div class="box ticketNumber">
			<?topCorner();?>
			<div class="boxHeader">
				<a href="sticker.php?ticket=<?=longTicketNumber($ticket['t_id'])?>" target="_blank"><?=longTicketNumber($ticket['t_id'])?></a>
			</div>
			<?bottomCorner();?>
		</div>
		<?if ($ticket['status'] == "Closed") {?>
			<div class="boxContent" style="margin-left: 10%;">
				<a href="print.php?t_id=<?=$t_id?>">Printer-Friendly version</a>
			</div>
		<?}?>
		<?if ($ticket['status'] == "Unaccepted"){?>
			<div class="box" style="width: 230%">
				<?topCorner();?>
				<div class="boxContent">
					<label class="fLblTicket">Summary:</label> 
					<input type="text" name="summary" size="45" maxlength="60" value="Summary of Users Problem : Be Brief!" onfocus="clearField(this)"/>
					<input type="hidden" name="t_id" value="<?=$ticket['t_id']?>"/>
					<input type="submit" name="accept" value="Accept"/>
				</div>
				<?bottomCorner();?>
			</div>
		<?} else {?>
			<div class="box" style="width: 230%;">
				<?topCorner();?>
				<div class="boxContent">
					<fieldset style="padding: 0px;">
						<legend>Common Tasks</legend>
						<table>
							<tr>
								<td style="padding-left: 20px;">
									<span style="font-size:1.1em">Created Passwords</span>
								</td>
								<td>
									<?if ($ticket['tasks'] & 1) {?>
										<input type="checkbox" checked name="c_passwords" value="1"/>
									<?} else {?>
										<input type="checkbox" name="c_passwords" value="1"/>
									<?}?>
								</td>
								<td style="padding-left: 40px;">
									<span style="font-size:1.1em">Checked for Media</span>
								</td>
								<td>
									<?if ($ticket['tasks'] & 2) {?>
										<input type="checkbox" checked name="c_media" value="2"/>
									<?} else {?>
										<input type="checkbox" name="c_media" value="2"/>
									<?}?>
								</td>
								<td style="padding-left: 40px;">
									<span style="font-size:1.1em">Customer Contacted</span>
								</td>
								<td>
									<?if ($ticket['tasks'] & 4) {?>
										<input type="checkbox" checked name="c_contacted" value="4"/>
									<?} else {?>
										<input type="checkbox" name="c_contacted" value="4"/>
									<?}?>
								</td>
							</tr>
							<tr>
								<td style="padding-left: 20px;">
									<span style="font-size:1.1em">Software Updates</span>
								</td>
								<td>
									<?if ($ticket['tasks'] & 8) {?>
										<input type="checkbox" checked name="c_updates" value="8"/>
									<?} else {?>
										<input type="checkbox" name="c_updates" value="8"/>
									<?}?>
								</td>
								<td style="padding-left: 40px;">
									<span style="font-size:1.1em">Removed Malware</span>
								</td>
								<td>
									<?if ($ticket['tasks'] & 16) {?>
										<input type="checkbox" checked name="c_malware" value="16"/>
									<?} else {?>
										<input type="checkbox" name="c_malware" value="16"/>
									<?}?>
								</td>
								<td style="padding-left: 40px;">
									<span style="font-size:1.1em">Close Unfinished</span>
								</td>
								<td>
									<?if ($ticket['tasks'] & 32) {?>
										<input type="checkbox" checked name="c_unfinished" value="32"/>
									<?} else {?>
										<input type="checkbox" name="c_unfinished" value="32"/>
									<?}?>
								</td>
							</tr>
						</table>
					</fieldset>
					<table>
						<tr><td valign="top">Update:</td>
						<td><textarea rows="6" cols="50" name="t_update"><?="\n\n\nTo Do:\n"?></textarea></td>
						</tr>
						<tr>
							<td valign="top">Past Updates:</td>
							<td><?=$ticket['updates']?></td>
						</tr>
						<tr>
							<input type="hidden" name="t_id" value="<?=$ticket['t_id']?>"/>
							<?if ($ticket['status'] == "Closed") {?>
								<td valign="bottom"><input type="submit" name="reopen" value="Re-Open"/></td>
							<?} else if (((~0 & $ticket['tasks']) == 31) || ($ticket['tasks'] & 32)) {?>
								<td valign="bottom"><input type="submit" name="submit" value="Update"/></td>
								<?if (!$ticket['senior']) {?>
									<td valign="bottom"><input type="submit" name="close" disabled value="Close"/> (Requires Senior Sign-off)</td>
								<?} else {?>
									<td valign="bottom"><input type="submit" name="close" value="Close"/></td>
								<?}?>
							<?}else{?>
								<td valign="bottom"><input type="submit" name="submit" value="Update"/></td>
							<?}?>
						</tr>
					</table>
				</div>
				<?bottomCorner();?>
			</div>
		<?}?>
	</div>
</form>

