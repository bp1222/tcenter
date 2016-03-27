<?php
/**
  * alive.php
  *
  * Simple, quick, AJAX to return if the user is still authenticated
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

register_shutdown_function(alive);
include "ajaxHeader.inc";

function alive() {
	global $tcenter;

	ob_clean();
	$json = '{alive:';
	$json .= $tcenter->auth->hasAuth(null, true) ? "true" : "false";
	$json .= '}';
	print $json;
	ob_flush();
}
