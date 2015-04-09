<?php
/**
  * ticketListAjax.php
  *
  * Return the HTML ticket list
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

include "ajaxHeader.inc";

$tickets = array();

$tickets['unaccepted'] = Ticket::getTicketsByStatus(0);
$tickets['queued'] = Ticket::getTicketsByStatus(1);
$tickets['inprogress'] = Ticket::getTicketsByStatus(2);
$tickets['pendingcustomer'] = Ticket::getTicketsByStatus(3);
$tickets['awaitingpickup'] = Ticket::getTicketsByStatus(4);
$tickets['adminhold'] = Ticket::getTicketsByStatus(5);
$tickets['resnetinternal'] = Ticket::getTicketsByStatus(6);

echo json_encode($tickets);
ob_flush();
?>
