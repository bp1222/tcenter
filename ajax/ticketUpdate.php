<?php
/**
  * ticketUpdate.php
  *
  * Make updates to the ticket
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

include "ajaxHeader.inc";
ob_end_clean();

$error = false;
$query = json_decode($_REQUEST['ticket'], true);
$query = $query[0];

$ticket = new Ticket ($query['t_id']);

$action = "";
$update = "";

foreach ($query as $k=>$v)
{
	// Don't reset the id :P
	if (preg_match("/id$/", $k))
		continue;

	// Make javascript true/falses into PHP ones
	if ($v === "true") $v = 1;
	if ($v === "undefined") $v = 0;

	// Check major status, then add actions
	if ($k == "status")
	{
		if ($v == 7)
		{
			$ticket->updateAttrib("closed_by", $tcenter->user->username);
			$ticket->updateAttrib("close_date", "NOW");
			$action .= "[Ticket Closed]<br/>";

			$from = "From: resnet@rit.edu";
			$to = $ticket->owner->getAttrib('email');
			$subject = "[RESNET] ".$ticket->owner->getAttrib('username')."-".$ticket->getAttrib('t_id')." Survey Request";
			$message = "Dear ".substr($ticket->owner->getAttrib('name'), 0, strpos($ticket->owner->getAttrib('name'), " "))."\n\n
Recently, you had a computer serviced in the Resnet Tech Center. In the
interest of continually improving our customer's experience we are asking
that you please complete the following short survey.

This survey should take you no more than 2 minutes to complete, and will aid
us in improving this quality service.

The survey can be found at http://www.rit.edu/its/scsurvey.html

Thank You,
--
Tom Dixon
Sr. Resnet Analyst";

			mail ($to, $subject, $message, $from);
		}
		else if ($ticket->getAttrib("status") != $v)
			$action .= "[Status Changed to ".$l_status[$v]."]<br/>";
	}

	if ($k == "reopen")
	{
		$action = "[Ticket Reopened]";
		$reopen = $ticket->getAttrib("reopen_count") + 1;
		$ticket->updateAttrib("reopen_count", (string)$reopen);
		$ticket->updateAttrib("close_date", "0000-00-00 00:00:00");
		continue;
	}

	if ($k == "todo")
	{
		$old_todo = json_decode($ticket->getAttrib("todo"), true);
		$old_todo = (array)$old_todo[0];
		foreach ($v as $key=>$val)
		{
			if ($val == "undefined")
			{
				if ($old_todo[$key] != false)
					$action .= "[Unchecked ToDo : $key]<br/>";

				$old_todo[$key] = false;
			}
			else if ($val == "true")
			{
				if ($old_todo[$key] != true)
					$action .= "[Checked ToDo : $key]<br/>";

				$old_todo[$key] = true;
			}
		}
		$v = "[".json_encode($old_todo)."]";
	}

	if ($k == "todoadd")
	{
		$todo = $ticket->getAttrib("todo");
		$action .= "[Added ToDo : $v]";

		$v = substr($todo, 0, strlen($todo)-2).",\"".$v."\":false}]";

		if (substr($v, 2, 1) == ",")
			$v = "[{".substr($v, 3);

		$k = "todo";
	}

	if ($k == "deletetodo")
	{
		$todo = json_decode($ticket->getAttrib("todo"));
		$todo = (array)$todo[0];

		if (is_array($v))
		{
			foreach ($v as $td)
			{
				$todo = array_remove_key($todo, $td);
				$action .= "[Removed ToDo : $td]<br/>";
			}
		}
		else
		{
			$todo = array_remove_key($todo, $v);
			$action .= "[Removed ToDo : $v]";
		}

		$v = "[".json_encode($todo)."]";
		$k = "todo";
	}

	// Process Updates
	if ($k == "updates")
	{
		$update = $v;
		continue;
	}

	if ($k == "repair" || $k == "senior" || $k == "full")
	{
        if ($v == "false")
            $v = (string)0;
        else
            $v = (string)1;

		if ($ticket->getAttrib($k) != $v)
		{
			if ($ticket->getAttrib($k) == 1)
				$action .= "[".ucfirst($k)." was unchecked]<br/>";
			else
				$action .= "[".ucfirst($k)." was checked]<br/>";
		}
	}

    if ($k == "summary")
    {
        if ($v == "Summary of Users Problem : Be Brief!" || empty($v))
            die();
    }

	$ticket->updateAttrib($k, $v);
}

// Do the update outside of the loop since we need to wait for the action to build
$date = date("m/d/y", time());
$time = date("g:i A", time());

if (strlen($action) + strlen($update) > 0)
{
	$action = "<font color='red'>$action</font>";
	$v = "<span class='tUpdate'><span class='tUpdateDate'>".$tcenter->user->username." [$date @ $time]</span>$action$update</span><br/>".$ticket->getAttrib("updates");
	$ticket->updateAttrib("updates", $v);
}

$ticket->updateAttrib("last_rcc", $tcenter->user->username);

$ticket->save();
?>
