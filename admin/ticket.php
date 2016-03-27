<?
/**
  * ticket.php
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

include "setup.inc";
include "inc/header.inc";
?>
<script language="javascript">
var loadPage = function (json) {
	owner = json.owner[0];
	machine = json.machine[0];
	ticket = json.ticket[0];

	$.each(owner, function (j,l) {
		$("span.owner[name='"+j+"']").text(l);
	});

	$.each(machine, function (j,l) {
		$("span.machine[name='"+j+"']").text(l);
	});

	$("span.ticket[name='open_date']").text(ticket.open_date);
	$("span.ticket[name='summary']").text(ticket.summary);
	$("span.ticket[name='t_id']").text(owner.username+"-"+ticket.t_id);
	$("span.ticket[name='problem']").html(ticket.problem);
	$("span.ticket[name='updates']").html(ticket.updates);

	var check = [];
	check["full"] = ticket.full;
	check["repair"] = ticket.repair;
	check["senior"] = ticket.senior;
	for (i in check) {
		if (!$("input[name='c_"+i+"']").attr("changed"))
		{
			if (check[i] == 1) {
				$("input[name='c_"+i+"']").attr("checked", "true");
			} else {
				$("input[name='c_"+i+"']").removeAttr("checked");
			}
		}
	}

	// Get current todos
	// Check to make sure we respect users input, and don't override.
	var current = $(".toDo :checkbox");
	var changed = [];

	$.each (current, function (name, tdval) {
		tdname = tdval.name;
		if ($(tdval).attr("changed")) {
			val = $(tdval).attr("checked");
			if (val == undefined)
				changed[tdname] = 'none';
			else
				changed[tdname] = $(tdval).attr("checked");
		}
	});

	count = 1;
	$(".todoCol1").empty();
	$(".todoCol2").empty();
	$.each(ticket.todo[0], function (key, val) {
		var newElem = $("#todo_checkbox").clone()

			$("[name='todo_key']", newElem).html(key);
		$("[name='todo_val']", newElem).attr("name", key);

		if (changed[key])
		{
			if (changed[key] == 'none')
				$("[name='"+key+"']", newElem).removeAttr('checked');
			else
				$("[name='"+key+"']", newElem).attr('checked', changed[key]);
			$("[name='"+key+"']", newElem).attr('changed', 'true');
		} else {
			$("[name='"+key+"']", newElem).attr('checked', val);
		}

		$("a", newElem).attr("name", key);
		$("*", newElem).appendTo(".todoCol"+count);

		count++;
		if (count == 3)
			count = 1;
	});

	if (!$("#t_status").attr("changed"))
	{
		if (ticket.status == "0")
		{
			$("div .unaccepted").css("display", "block");
			$("div .accepted").css("display", "none");

			if ($("#t_status option:first").text() != "Unaccepted") 
			{
				$("#t_status option:first").clone().text("Unaccepted").attr("value", "0").insertBefore("#t_status option:first");
				$("#t_status option[value='0']").attr("selected", "true");
			}
		} else if (ticket.status == "7")
		{
			$("div .accepted").css("display", "block");

			if ($("#t_status option:last").text() != "Closed")
			{
				// Need to create an option for closed
				$("#t_status option:first").clone().text("Closed").attr("value", "7").insertAfter("#t_status option:last");
			}

			$("#t_status option[value='7']").attr("selected", "true");
			$("div .printerFriendly").css("display", "block");
			$(".todoLink").css("visibility", "hidden");
			$("[name='updateTicket']").css("display", "none");
			$("[name='closeTicket']").css("display", "none");
			$(":input").attr("disabled", true);
			$("[name='reopenTicket']").css("display", "block").removeAttr("disabled");
		} else 
			{
				if ($("#t_status option:first").text() == "Unaccepted")
					$("#t_status option:first").remove();

				$("div .unaccepted").css("display", "none");
				$("div .accepted").css("display", "block");
				$("#t_status option[value='"+ticket.status+"']").attr("selected", "true");

				if ($("#t_status option:last").text() == "Closed")
				{
					$("#t_status option:last").remove();
					$(".todoLink").css("visibility", "visible");
					$("div .printerFriendly").css("display", "none");
					$("[name='reopenTicket']").css("display", "none");
					$("[name='updateTicket']").css("display", "block");
					$(":input").removeAttr("disabled");
				}
			}
	}

	closableTicket();

	$("a.sticker").attr("href", "sticker.php?ticket="+owner.username+"-"+ticket.t_id);
	$("a.print").attr("href", "print.php?t_id="+ticket.t_id);

	// Hate for safari
	if (document.title.substr(document.title.length - 2, 1) == "|") {
		document.title = $("title").text()+" "+owner.username+"-"+ticket.t_id;
	}
};

var request = function () {
	$.ajax({
		type: "GET",
			url: "/ajax/ticketAjax.php?t_id=<?=$_REQUEST['t_id']?>", 
			dataType: "json",
			success: loadPage,
			error: ajaxError
	});
};

loadPageInterval = setInterval(request, 30000);
$(request);
</script>

<div id="ticketContainer">
	<div class="ticketColumn1">
		<div class="box">
			<div class="boxHeader collapsed">
				Owner Information
			</div>

			<div class="boxContent" style="display: none;">
				<table>
					<tr>
						<td>Name:</td>
						<td><span class="owner" name="name"></span></td>
					</tr>

					<tr>
						<td>Phone:</td>
						<td>
							<span class="owner" name="phone"></span>
							<a onclick="return!open('phone.php?first_name='+owner.name+'&number='+owner.phone, '_blank', 'width=800,height=700,screenX=150,screenY=0')" style="font-size: 1em; font-weight: normal;">(large)</a>
						</td>
					</tr>

					<tr>
						<td>Email: </td>
						<td><span class="owner" name="email"></span></td>
					</tr>

					<tr>
						<td><?t_editInfo("owner")?></td>
						<td/>
					</tr>
				</table>
			</div>
		</div>

		<div class="box">
			<div class="boxHeader collapsed">
				Machine Information
			</div>

			<div class="boxContent" style="display: none;">
				<table>
					<tr>
						<td>Login Name: </td>
						<td><span class="machine" name="login_name"></span></td>
					</tr>

					<tr>
						<td>Password: </td>
						<td><span class="machine" name="login_password"></span></td>
					</tr>

					<tr>
						<td>Inventory: </td>
						<td><span class="machine" name="inventory"></span></td>
					</tr>

					<tr>
						<td>OS / Task: </td>
						<td><span class="machine" name="os"></span></td>
					</tr>

					<tr>
						<td>Description: </td>
						<td><span class="machine" name="description"></span></td>
					</tr>

					<tr>
						<td><?t_editInfo("machine")?></td>
						<td/>
					</tr>
				</table>
			</div>
		</div>

		<div class="box">
			<div class="boxHeader expanded">
				Ticket Information
			</div>

			<div class="boxContent">
				<table>
					<tr>
						<td>Ticket Opened: </td>
						<td><span class="ticket" name="open_date"></span></td>
					</tr>

					<tr>
						<td>Summary: </td>
						<td><span class="ticket" name="summary"></span></td>
					</tr>

					<tr>
						<td>Status: </td>
						<td>
							<select id="t_status" name="t_status" onchange="userChanged(this)">
								<option value='1'>Queued</option>
								<option value='2'>In Progress</option>
								<option value='3'>Pending Customer</option>
								<option value='4'>Awaiting Pickup</option>
								<option value='5'>Admin Hold</option>
								<option value='6'>Resnet Internal</option>
							</select>
						</td>
					</tr>

					<tr>
						<?t_checkbox("Senior Signoff", "c_senior", "TICKET_SENIOR");?>
					</tr>
					<tr>
						<?t_checkbox("Repair Install", "c_repair", "TICKET_REPAIR");?>
					</tr>
					<tr>
						<?t_checkbox("Full Install", "c_full", "TICKET_FULL");?>
					</tr>

					<tr>
						<td>Problem Desc: </td>
						<td><span class="ticket" name="problem"></span></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="box">
			<div class="boxHeader collapsed">
				Canned Responses
			</div>

			<div class="boxContent" style="display: none;">
				<table width="100%">	
					<tr>
						<td>Computer Complete</td>
						<td><a style="font-size: 1em;" href="canned.php?value=done" target="_blank" onclick="return!open(this.href+'&t_id='+ticket.t_id, this.target, 'width=800,height=700,screenX=150,screenY=50')">Inform User</a></td>
					</tr>
					<tr>
						<td>Need Windows / System CD</td>
						<td><a style="font-size: 1em;" href="canned.php?value=cd" target="_blank" onclick="return!open(this.href+'&t_id='+ticket.t_id, this.target, 'width=800,height=700,screenX=150,screenY=50')">Request Media</a></td>
					</tr>
					<tr>
						<td>Repair Install Permission</td>
						<td><a style="font-size: 1em;" href="canned.php?value=repair" target="_blank" onclick="return!open(this.href+'&t_id='+ticket.t_id, this.target, 'width=800,height=700,screenX=150,screenY=50')">Request Repair Install</a></td>
					</tr>
					<tr>
						<td>Full Install Permission</td>
						<td><a style="font-size: 1em;" href="canned.php?value=full" target="_blank" onclick="return!open(this.href+'&t_id='+ticket.t_id, this.target, 'width=800,height=700,screenX=150,screenY=50')">Request Full Install</a></td>
					</tr>
					<tr>
						<td>Create Blank Email</td>
						<td><a style="font-size: 1em;" href="canned.php?value=" target="_blank" onclick="return!open(this.href+'&t_id='+ticket.t_id, this.target, 'width=800,height=700,screenX=150,screenY=50')">Blank Email</a></td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div class="ticketColumn2">
		<div class="box ticketNumber">
			<div class="boxHeader noFold">
				<a class="sticker" target="_blank" style="curser:pointer;"><span class="ticket" name="t_id"></span></a>
			</div>
		</div>
		<div class="box password">
			<div class="boxHeader noFold">
				<span class="machine" name="login_password"></span>
			</div>
		</div>
		<div class="boxContent printerFriendly" style="display: none; clear: both; margin-left: 35px;">
			<a class="print">Printer-Friendly version</a>
		</div>

		<!-- Show when unaccepted -->
		<div class="box ticketContent">
			<div class="unaccepted" style="display: none">
				<div class="boxContent">
					Summary: <input type="text" name="t_summary" size="65" maxlength="60" value="Summary of Users Problem : Be Brief!" onfocus="clearField(this)"/>
					<input type="submit" name="accept" value="Accept" onclick="submitTicket(1)"/>
				</div>
			</div>

<div id="todo_checkbox" style="display: none;">
	<input type="checkbox" name="todo_val" onclick="userChanged(this);"></input>
	<span name="todo_key"></span><a onclick="deleteToDo(this);" class="todoLink" style="font-size: .6em"> ( remove )</a><br/>
</div>

			<div class="accepted" style="display: none;">
				<div class="boxContent">
					<table>
						<tr>
							<td>To Do's:<br/>
								<a onclick="newTodo()" class="todoLink" style="font-size: .75em; clear: both; display: table;">[ New ToDo ]</a>
								<a onclick="remAllTodo()" class="todoLink" style="font-size: .75em; clear: both; display: table;">[ Remove All ToDo ]</a>
							</td>
							<td><span class="toDo" name="colh">
								<span class="todoCol1" name="col1"></span>
								<span class="todoCol2" name="col2"></span>
							</td>
						</tr>

						<tr>
							<td>Update:</td>
							<td><textarea rows="8" cols="60" name="t_update"></textarea></td>
						</tr>

						<tr>
							<td>Past Updates:</td>
							<td><span class="ticket" name="updates"></span></td>
						</tr>

						<tr>
							<td>
								<input type="submit" name="reopenTicket" value="ReOpen Ticket" style="display: none; margin-top: 5px;" onclick="reopenTicket()"/>
								<input type="submit" name="closeTicket" value="Close Ticket" style="display: none; margin-top: 5px;" onclick="submitTicket(2)"/>
								<input type="submit" name="updateTicket" value="Update Ticket" onclick="submitTicket()"/>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
