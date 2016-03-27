<?
include("config.inc");
mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
mysql_select_db(_DATABASE);
if (!isset($reportstyle)) {
  $reportstyle = _REPORTSTYLE;
}
?>
<?php
if ($selectiveReport == "true") {

//  SELECTIVE REPORT

?>
<html>

	<head>
		<title>Selective Inventory Report</title>
		<style type="text/css"><!--
.headings { color: #000; font-size: 12pt; font-family: Verdana }
.notice { color: black; font-weight: bold; font-family: Verdana }
.plaintext { color: black; font-size: 10pt; font-family: Verdana }
.remarks { color: black; font-style: italic; font-size: 8pt }
.titles { color: black; font-size: 16pt; font-family: Verdana }-->
		</style>
	</head>

	<body class="plaintext">
                <span class="titles">Selective Inventory report<br></span>
                <span class="remarks">This report was created on:
                <?php
                  $Today = date("l dS F, Y");
                  echo $Today."</span>";
                ?>
                <p><span class="headings"><U>General infomation</U></span>
                <?php
                  $result = mysql_query("select * from computers");
                  $number_of_computers = mysql_num_rows($result);
                  $result = mysql_query("select * from hardware");
                  $number_of_hardware = mysql_num_rows($result);
                  $result = mysql_query("select * from software");
                  $number_of_software = mysql_num_rows($result);
                ?>
                <TABLE>
                  <TR>
                    <TD class="plaintext">Computer in database:</TD>
                    <TD class="plaintext"><?= $number_of_computers ?></TD>
                  </TR>
                  <TR>
                    <TD class="plaintext">Hardware entries:</TD>
                    <TD class="plaintext"><?= $number_of_hardware ?></TD>
                  </TR>
                  <TR>
                    <TD class="plaintext">Software entries:</TD>
                    <TD class="plaintext"><?= $number_of_software ?></TD>
                  </TR>
                </TABLE>
<!--                <p><span class="headings"><U>Software in database</U></span><BR>
                <?php
                  $result = mysql_query("select * from software order by manufacturer, name, version");
                  while($row = mysql_fetch_assoc($result) ) {
                    echo "<span class=\"plaintext\">".$row["manufacturer"]." ".$row["name"]." ".$row["version"];
                    $resultAmount = mysql_query("select software from computers where software like '%".$row["software_id"]."%'");
                    $resultAmount = mysql_num_rows($resultAmount);
                    echo " (installed ".$resultAmount."x)</span><BR>\n";
                  }
                ?>
		</p>
                <p><span class="headings"><U>Hardware in database</U></span><BR>
                <?php
                  $result = mysql_query("select * from hardware order by type, make, model");
                  while($row = mysql_fetch_assoc($result) ) {
                    echo "<span class=\"plaintext\">".$row["type"]." - ".$row["make"]." ".$row["model"];
                    $resultAmount = mysql_query("select hardware from computers where hardware like '%".$row["hardware_id"]."%'");
                    $resultAmount = mysql_num_rows($resultAmount);
                    echo " (used ".$resultAmount."x)</span><BR>\n";
                  }
                ?>
		</p> -->
                <p><span class="headings"><B><U>Detailed infomation</U></B></span>
                <?php
                  $result = mysql_query("select * from computers order by name");
                  while($row = mysql_fetch_assoc($result) ) {
                     foreach ($selectedComputer as $value) {
                       if ($value == $row["computer_id"]) {
                ?>
		<p>
                <table border="1" cellpadding="0" cellspacing="1">
			<tr>
				<td class="plaintext" width="120">Computername:</td>
                                <td class="plaintext"><?= $row["name"] ?></td>
			</tr>
			<tr>
				<td class="plaintext" width="120">IP&nbsp;Address:</td>
                                <td class="plaintext"><?= $row["ipaddress"] ?></td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120" align="left" valign="top">Hardware:</td>
                                <TD class="plaintext">
                                        <table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php
  echo "<TR><TD class=\"plaintext\" ><FONT size=\"-1\"><U>Type:</U></TD><TD class=\"plaintext\" ><FONT size=\"-1\"><U>Model:</U></TD><TD class=\"plaintext\"><FONT size=\"-1\"><u>Make:</u></TD></TR>\n";
  $hardware_info = explode(":",$row["hardware"]);
  foreach($hardware_info as $hardware_part) {
    $hardware_queryresult = mysql_query("select * from hardware where hardware_id = '$hardware_part'");
    $hardware_result = mysql_fetch_assoc($hardware_queryresult);
    echo "<TR>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $hardware_result["type"];
    echo "</TD>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $hardware_result["model"];
    echo "</TD>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $hardware_result["make"];
    echo "</TD>\n";
    echo "</FONT></TD></TR>\n";
}
?>
					</table>
				</td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120" align="left" valign="top">Software:</td>
                                <TD class="plaintext">
                                        <table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php
  $software_info = explode(":",$row["software"]);
  foreach($software_info as $software_part) {
    $software_queryresult = mysql_query("select * from software where software_id = '$software_part'");
    $software_result = mysql_fetch_assoc($software_queryresult);
    echo "<TR>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $software_result["manufacturer"]." ".$software_result["name"]." ".$software_result["version"];
    echo "</TD>\n";
    echo "</FONT></TD></TR>\n";
  }
?>
                                        </table>
				</td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120">Operating system:</td>
                                <?php
                                  $os_info = $row["os"];
                                  $os_queryresult = mysql_query("select * from os where os_id = '$os_info'");
                                  $os_result = mysql_fetch_assoc($os_queryresult);
                                ?>
                                <TD class="plaintext"><?= $os_result["name"] ?></td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120" align="left" valign="top">Other:</td>
                                <TD class="plaintext"><?= $row["other"] ?></td>
			</tr>
		</table>
                <?php
}  
}
                }
                ?>
	</body>
</html>

<?php
} else {

// OTHER REPORTS

?>

<html>

	<head>
		<title>Inventory Report</title>
		<style type="text/css"><!--
.headings { color: #000; font-size: 12pt; font-family: Verdana }
.notice { color: black; font-weight: bold; font-family: Verdana }
.plaintext { color: black; font-size: 10pt; font-family: Verdana }
.remarks { color: black; font-style: italic; font-size: 8pt }
.titles { color: black; font-size: 16pt; font-family: Verdana }-->
		</style>
	</head>

	<body class="plaintext">
                <span class="titles">Inventory report<br></span>
                <span class="remarks">This report was created on:
                <?php
                  $Today = date("l dS F, Y");
                  echo $Today."</span>";
                ?>
                <p><span class="headings"><U>General infomation</U></span>
                <?php
                  $result = mysql_query("select * from computers");
                  $number_of_computers = mysql_num_rows($result);
                  $result = mysql_query("select * from hardware");
                  $number_of_hardware = mysql_num_rows($result);
                  $result = mysql_query("select * from software");
                  $number_of_software = mysql_num_rows($result);
                ?>
                <TABLE>
                  <TR>
                    <TD class="plaintext">Computer in database:</TD>
                    <TD class="plaintext"><?= $number_of_computers ?></TD>
                  </TR>
                  <TR>
                    <TD class="plaintext">Hardware entries:</TD>
                    <TD class="plaintext"><?= $number_of_hardware ?></TD>
                  </TR>
                  <TR>
                    <TD class="plaintext">Software entries:</TD>
                    <TD class="plaintext"><?= $number_of_software ?></TD>
                  </TR>
                </TABLE>
                <p><span class="headings"><U>Software in database</U></span><BR>
                <?php
                  $result = mysql_query("select * from software order by manufacturer, name, version");
                  while($row = mysql_fetch_assoc($result) ) {
                    echo "<span class=\"plaintext\">".$row["manufacturer"]." ".$row["name"]." ".$row["version"];
                    $resultAmount = mysql_query("select software from computers where software like '%".$row["software_id"]."%'");
                    $resultAmount = mysql_num_rows($resultAmount);
                    echo " (installed ".$resultAmount."x)</span><BR>\n";
                  }
                ?>
		</p>
                <p><span class="headings"><U>Hardware in database</U></span><BR>
                <?php
                  $result = mysql_query("select * from hardware order by type, make, model");
                  while($row = mysql_fetch_assoc($result) ) {
                    echo "<span class=\"plaintext\">".$row["type"]." - ".$row["make"]." ".$row["model"];
                    $resultAmount = mysql_query("select hardware from computers where hardware like '%".$row["hardware_id"]."%'");
                    $resultAmount = mysql_num_rows($resultAmount);
                    echo " (used ".$resultAmount."x)</span><BR>\n";
                  }
                ?>
		</p>
                <p><span class="headings"><B><U>Detailed infomation</U></B></span>
                <?php
                  $result = mysql_query("select * from computers order by name");
                  while($row = mysql_fetch_assoc($result) ) {
                ?>
		<p>
                <table border="1" cellpadding="0" cellspacing="1">
			<tr>
				<td class="plaintext" width="120">Computername:</td>
                                <td class="plaintext"><?= $row["name"] ?></td>
			</tr>
			<tr>
				<td class="plaintext" width="120">IP&nbsp;Address:</td>
                                <td class="plaintext"><?= $row["ipaddress"] ?></td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120" align="left" valign="top">Hardware:</td>
                                <TD class="plaintext">
                                        <table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php
  echo "<TR><TD class=\"plaintext\" ><FONT size=\"-1\"><U>Type:</U></TD><TD class=\"plaintext\" ><FONT size=\"-1\"><U>Model:</U></TD><TD class=\"plaintext\"><FONT size=\"-1\"><u>Make:</u></TD></TR>\n";
  $hardware_info = explode(":",$row["hardware"]);
  foreach($hardware_info as $hardware_part) {
    $hardware_queryresult = mysql_query("select * from hardware where hardware_id = '$hardware_part'");
    $hardware_result = mysql_fetch_assoc($hardware_queryresult);
    echo "<TR>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $hardware_result["type"];
    echo "</TD>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $hardware_result["model"];
    echo "</TD>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $hardware_result["make"];
    echo "</TD>\n";
    echo "</FONT></TD></TR>\n";
  }
?>
					</table>
				</td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120" align="left" valign="top">Software:</td>
                                <TD class="plaintext">
                                        <table width="100%" border="1" cellpadding="0" cellspacing="0">
<?php
  $software_info = explode(":",$row["software"]);
  foreach($software_info as $software_part) {
    $software_queryresult = mysql_query("select * from software where software_id = '$software_part'");
    $software_result = mysql_fetch_assoc($software_queryresult);
    echo "<TR>\n";
    echo "<TD class=\"plaintext\" ><FONT size=\"-1\">\n";
    echo $software_result["manufacturer"]." ".$software_result["name"]." ".$software_result["version"];
    echo "</TD>\n";
    echo "</FONT></TD></TR>\n";
  }
?>
                                        </table>
				</td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120">Operating system:</td>
                                <?php
                                  $os_info = $row["os"];
                                  $os_queryresult = mysql_query("select * from os where os_id = '$os_info'");
                                  $os_result = mysql_fetch_assoc($os_queryresult);
                                ?>
                                <TD class="plaintext"><?= $os_result["name"] ?></td>
			</tr>
			<tr>
                                <TD class="plaintext"  width="120" align="left" valign="top">Other:</td>
                                <TD class="plaintext"><?= $row["other"] ?></td>
			</tr>
		</table>
                <?php
                }
                ?>
	</body>
</html>
<?php
}
?>