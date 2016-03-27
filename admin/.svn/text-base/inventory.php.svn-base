<? include("../inc/header2.php"); ?>
<? include("../inc/inventory.inc"); ?>

<?
		# extract data
		$resuser = $PHP_AUTH_USER;
		@extract($_POST);
?>

<html>
<head>
<title>Resnet Inventory</title>

<script>

/* Manipulate the Menu based on selection
	 If the item is "type", show the dropdown
	 menu. If not, keep the textbot visible */

function createmenu() {

	/* Define HTML Objects */ 
	div1 = document.getElementById("gotext");
	div2 = document.getElementById("goselect");
	terms = document.getElementById("theterms");
	hiddentype = document.getElementById("hiddentype");
	hiddentext = document.getElementById("hiddentext");

	/* Acquire the value of the selected item */
	var term = terms.options;
	var theselection = term[term.selectedIndex].value;

	/* Determine what to show */
	if (theselection == "type") {
		div1.style.display = 'none';
		div2.style.display = 'inline';	
		hiddentext.value = theselection;
	}
	else {
		div2.style.display = 'none';
		div1.style.display = 'inline';	
		hiddentype.value = theselection;
	}	
}

</script>

</head>
<body>
<br>
<table width="100%">
	<tr>
		<td valign="top" align="left" width="100px">Search Inventory:&nbsp;</td>
		<td valign="top" align="left">
			<select size="1" id="theterms" name="theterms" onChange="createmenu()">
				<option value="id">Entry #</option>
				<option value="name">Name</option>
				<option value="identnumber">Identification #</option>
				<option value="number">Number of Items</option>
				<option value="type">Type</option>
				<option value="manufacturer">Manufacturer</option>
				<option value="location">Location</option>
				<option value="lastuser">Last User To Update</option>
				<option value="other">Misc. Info</option>
			</select>
			<span id="goselect" style="display:none;">
			<form enctype="mutipart/form-data" method="post" action="inventory.php">
				<select name="search" size="1">
					<option value="computer">Computer</option>
					<option value="monitor">Monitor</option>
					<option value="software">Software</option>
					<option value="software">Tester</option>
				</select>
				<input id="hiddentype" type="hidden" name="type" value="">
				<input type="submit" name="Submit" value="Submit">
			</form>
			</span>
			<span id="gotext" style="display:inline;">
			<form enctype="mutipart/form-data" method="post" action="inventory.php">
				<input type="text" size="27" name="search">
				<input id="hiddentext" type="hidden" name="term" value="">
				<input type="submit" name="Submit" value="Submit">
			</form>
			</span>
		</td>
	</tr>
</table>
<h2>Resnet Inventory</h2>
</center>
				<center>
					<table width="95%">
						<tr>
							<td colspan="6" align="right">
<?
	

		# if there isn't a starting point to the query, start at 0
		if (!isset($start)) $start = 0;

			# debug to see query statement
			echo "Type: $type\tSearch: $search";
			# if there is a type and a string to search by, use this query
			if ($type != "" && $search != "") {
				$insertquery = "SELECT * FROM stuff WHERE $type LIKE '%$search%' ORDER BY id DESC OFFSET " . $start . " LIMIT 20;";
			}
			# otherwise do a blanket query
			else {
				$insertquery = "SELECT * FROM stuff ORDER BY id DESC OFFSET " . $start . " LIMIT 20;";
			}
			# print query statement to page
			echo $insertquery;
			$result = pg_query($insertquery);

			# determine the number of items in the result over the whole table
			# we use this to determine our start/end next/previous
			if ($type != "" && $search != "") {
				$countquery = "SELECT id FROM stuff WHERE $type LIKE '%$search%';";
			}
			else {
				$countquery = "SELECT id FROM stuff;";
			}
				$counter = pg_query($countquery);
				$numrows = pg_num_rows($counter);
	
				# determine if we need to show the next and/or previous links
				if ($start >= 20) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start - 20) . "\">Next 20</a> ]";
				}	
				if ($numrows > ($start + 20)) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start + 20) . "\">Previous 20</a> ]";
				}
?>
							</td>
						</tr>
						<tr>
							<td>Name:</td><td>Identification #:</td></td><td>Number of Items:</td><td>Type:</td>
							<td>Manufacturer:</td><td>Location:</td><td>Last User To Update</td><td>Misc Info</td>
						</tr>
<?
					$i = 1;
				while ($row = pg_fetch_assoc($result)) {

					$name = trim($row['name']);
					$identnumber = trim($row['identnumber']);
					$number = trim($row['number']);
					$type = trim($row['type']);
					$manufacturer = trim($row['manufacturer']);
					$location = trim($row['location']);
					$lastuser = trim($row['lastuser']);
					$other = trim($row['other']);

					# quick way to alternate entry backgrounds
					if ( $i % 2 == 0 ) {
						echo "<tr style=\"background-color:#cccccc;\">";
					}
					else {
						echo "<tr style=\"background-color:#eeeeee;\">";
					}

					?>
				
						<td><?echo $name; ?></td><td><?echo $identnumber; ?></td><td><?echo $number; ?></td><td><?echo $type; ?></td><td><?echo $manufacturer; ?></td><td><?echo $location; ?></td><td><?echo $lastuser; ?></td><td><?echo $other; ?></td>
					</tr>
				
				<?
				$i++;
				}
	
?>
				<tr>
					<td colspan="6" align="right">
<?
				# show previous/next links at the bottom as well
				if ($start >= 20) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start - 20) . "\">Next 20</a> ]";
				}	
				if ($numrows > ($start + 20)) {
					echo "[ <a href=\"" . $PHP_SELF . "?start=" . ($start + 20) . "\">Previous 20</a> ]";
				}
?>
			</td>
		</tr>
	</table>
	</center>
<? pg_close($db_connect);?>
