<?php
/**
  * ticketList.php
  *
  * List of active tickets
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

$title = "Ticket Listing";
include "setup.inc";
include "inc/header.inc";
?>
<div id="hiddenTicket" class="groupItem boxEntry" style="display: none;">
	<span class="tlNumber">
		<a></a>
		<span class="tlDescription"></span>
	</span><br/>
	Summary: <span class="tlSummary"></span><br/>
	<span class="tlLastRcc"></span>
	Ticket Created: <span class="tlCreated"></span><br/>
</div>

<div id="buildEnv" style="display: none"></div>

<script language="javascript">
	// Function to make the AJAX request, then parse out the JSON
	var loadPage = function (json) {
		$.each(json, function (i) {
			if (this != null)
			{
				$("#"+i+" .pageNumTickets").html("("+this.length+")");

				/* Need to build the content of the box in the buildEnv div
				   to prevent flicker, then replace */
				$.each (this, function () {
					$("#buildEnv").append($("div #hiddenTicket").clone().attr("id", this.t_id));
					$("#"+this.t_id).css("display", "block");
					$("#"+this.t_id+" a").attr("href", "ticket.php?t_id="+this.t_id).text(this.username+"-"+this.t_id);
					$("#"+this.t_id+" span.tlDescription").text("( "+this.description+" )");
					$("#"+this.t_id+" span.tlSummary").text(this.summary);
					$("#"+this.t_id+" span.tlLastUpdate").text(this.last_time);
					$("#"+this.t_id+" span.tlLastRcc").text(this.last_rcc);
					$("#"+this.t_id+" span.tlCreated").text(format_mysqldate(this.open_date));
				});

				// Now that the buildEnv is done, lets append it to the correct Content
				$("div ."+i+"Content").html($("#buildEnv").html());
				$("#buildEnv").empty();
			}
		});

		// Hack to make IE work, instead of doing it in FlyDOM
		$(".floatChange").css("float", "right");
	}

	function request () {
		$.ajax({
			type: "GET",
			url: "<?=ROOT?>ajax/ticketListAjax.php", 
			dataType: "json",
			success: loadPage
		});
	}

	loadPageInterval = setInterval(request, 20000);
	$(request);
</script>

<div id="ticketListContainer">
	<div class="listColumn1">
		<?createTicketContainer("In Progress");?>
	</div>
	<div class="listColumn2">
		<?createTicketContainer("Queued");?>
		<?createTicketContainer("Unaccepted");?>
	</div>
	<div class="listColumn3">
		<?createTicketContainer("Pending Customer", true);?>
		<?createTicketContainer("Awaiting Pickup", true);?>
		<?createTicketContainer("Admin Hold", true);?>
		<?createTicketContainer("Resnet Internal", true);?>
	</div>
</div>

<?
function createTicketContainer($status, $hidden = false) {
	$dStatus = strtolower(str_replace(" ", "", $status));
?>
<div id="<?=$dStatus?>" class="box groupWraper">
				<div class="boxHeader <?=$hidden ? "collapsed" : "expanded"?>">
					<?=$status?> <span class="pageNumTickets">(0)</span>
				</div>

				<div class="boxContent <?=$dStatus?>Content" <?=$hidden ? 'style="display: none"' : ""?>>
				</div>
		</div>
<?}?>
