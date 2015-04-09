/**
  * tickets.js
  *
  * Generic Javascript
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

/* Global Vars */
var owner;
var machine;
var ticket;
var logoutInterval;
var wasPageInterval;

var toggleContent = function (e) {
	var targetContent = $('div.boxContent', this.parentNode);
	var targetHeader = $('div.boxHeader', this.parentNode);

	if ($('div.boxHeader', this.parentNode).hasClass("noFold"))
	{
		return;
	}

	if (targetContent.css('display') == 'none') {
		targetContent.slideDown(300);
		$(this).attr('class', 'boxHeader expanded');
	} else {
		targetContent.slideUp(300);
		$(this).attr('class', 'boxHeader collapsed');
	}
	return false;
};

function showHide( node ) {
	var plain = node;
	plain.style.display = 'none';
	var theId = plain.id;

	var field = document.getElementById(theId+"2");
	field.style.display = 'block';
}

function loggedOut ()
{
	$("div .removeMe").css("display", "none");
	// If there is no return text, we're probaby logged out
	$.getJSON("/ajax/alive.php", function (json) {
		if (!json.alive)
		{
			$("div .logOut").css('display', "table");
			clearInterval(logoutInterval);
			logoutInterval = false;

			if (typeof loadPageInterval != 'undefined') {
				wasPageInterval = true;
				clearInterval(loadPageInterval);
				loadPageInterval = false;
			}

			$(":input").attr("disabled", true);
		}
		else
		{
			$("div .logOut").css('display', "none");
			if (!logoutInterval)
				logoutInterval = setInterval (loggedOut, 10000);

			if (wasPageInterval && !loadPageInterval)
			{
				loadPageInterval = setInterval(request, 30000);
				$(request);
			}

			if (ticket != undefined)
				if (ticket.status != "7")
					$(":input").removeAttr("disabled");
		}
	});
}

function visibility ( node ) {
	var ph = document.getElementById("phoneHide");

	if (ph.style.visibility=="hidden"){
		ph.style.visibility="visible";
	}
	else {
		ph.style.visibility="hidden";
	}
}

function disableButton ( node ) {
	var btn = document.getElementById("btnSubmit");
	btn.disabled = true;
}

function format_date (date) {
	// date can be in msec or in a format recognized by Date.parse()
	var d = new Date(date);

	var months = Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	var month = months[d.getMonth()];
	var day = d.getDate();

	var date_formatted = month+' '+day;
	return date_formatted;
}

function format_mysqldate (mysqldate) {
	// example mysql date: 2008-01-27 20:41:25
	// we need to replace the dashes with slashes
	var date = String(mysqldate).replace(/\-/g, '/');
	return format_date(date);
}

function ajaxError (XMLHttpRequest, textStatus, errorThrown)
{
	$("#mainContent").empty();
	$("#mainContent").html(XMLHttpRequest.responseText);
}

function submitTicket(statusval)
{
	var json = "ticket=[";

	json += "{\"t_id\":\""+ticket.t_id+"\",";
	if (statusval== 1) {
		json += "\"status\":\"1\",";
		json += "\"summary\":\""+quote($("* [name='t_summary']").val())+"\",";
	} else if (statusval == 2) {
		json += "\"status\":\"7\",";
	} else {
		json += "\"status\":\""+$("* [name='t_status']").val()+"\",";
	}
	json += "\"updates\":\""+quote($("* [name='t_update']").val())+"\",";
	json += "\"senior\":\""+$("* [name='c_senior']").attr("checked")+"\",";
	json += "\"repair\":\""+$("* [name='c_repair']").attr("checked")+"\",";
	json += "\"full\":\""+$("* [name='c_full']").attr("checked")+"\"";

	if (statusval != 1)
	{
		var todos = "";

		$("span.toDo :input").each(function(a, v) { 
			todos += "\""+$(v).attr("name")+"\":\""+$(v).attr("checked")+"\","; 
		}); 

		if (todos.length != 0)
		{
			json += ",\"todo\":{"+todos;
			json = json.substr(0, json.length - 1)+"}"; 
		}
	}

	json += "}]";

	$.getJSON("/ajax/ticketUpdate.php", json);

	$("[name='t_update']").val("");
	setTimeout(request, 150);

	$("*").removeAttr("changed");
}

function userChanged (node)
{
	name = node.name;
	$("[name='"+name+"']").attr("changed", "true");
}

function openInfoPage (info)
{
	open('editInfo.php?t_id='+ticket.t_id+'&info='+info, '_blank', 'width=700,height=400,screenX=150,screenY=0');
}

function newTodo ()
{
	var todo = prompt ("Enter new Todo");
	todo = quote(todo);

	json = "ticket=[{\"t_id\":\""+ticket.t_id+"\",\"todoadd\":\""+todo+"\"}]";

	$.getJSON("/ajax/ticketUpdate.php", json);
	setTimeout(request, 300);
}

function remAllTodo ()
{
	var json = "ticket=[{\"t_id\":\""+ticket.t_id+"\",\"deletetodo\":[";

	if (!confirm("Do you really want to delete >> ALL << the todos?")) return;

	$("[name='todo_key']").each(function (i,j) {
		var todo = $(j).text();

		if (todo != "")
		{
			json += "\""+todo+"\",";
		}
	});

	json = json.substr(0, json.length - 1)+"]}]";

	$.getJSON("/ajax/ticketUpdate.php", json);
	setTimeout(request, 300);
}

function deleteToDo ( node ) {
	var todo = node.name;

	if (!confirm("Do you really want to delete "+todo+"?")) return;

	var json = "ticket=[{\"t_id\":\""+ticket.t_id+"\",\"deletetodo\":\""+todo+"\"}]";

	$.getJSON("/ajax/ticketUpdate.php", json);
	setTimeout(request, 300);
}

function closableTicket ()
{
	var closable = true;
	$(".toDo :checkbox").each(function (i,j){
		if (!$(j).attr("checked")) {
			closable = false;
		}
	});

	if (!$("* [name='c_senior']").attr("checked"))
	{
		closable = false;
	}

	if (closable && ticket.status != "7")
	{
		$("[name='closeTicket']").css("display", "block");
	}
}

function clearField(obj) {
	if (obj.defaultValue==obj.value) obj.value = '';
}

function delayRequest () {
	setTimeout(request, 400);
}

function quote( str ) {
	str = str.replace(/([^>])?\n/g, '$1<br/>\n');
	str = str.replace(/"/g,"''");
	str = str.replace(/\\/g,'');
	str = str.replace(/#/g,'');
	return str;
}

function reopenTicket () {
	var json = "ticket=[";

	json += "{\"t_id\":\""+ticket.t_id+"\",";
	json += "\"status\":\"1\",";
	json += "\"senior\":\"undefined\",";
	json += "\"reopen\":\"true\"";
	json += "}]";

	$.getJSON("/ajax/ticketUpdate.php", json);

	$("[name='t_update']").val("");
	setTimeout(request, 150);
	$("*").removeAttr("changed");
}

function removeInput (field) {
    $("*[name='"+field+"']").removeAttr("value");
}
