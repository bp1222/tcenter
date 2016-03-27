<FONT size="+1">Add a computer</FONT>
<TABLE border="1"><FORM action="edit.php" method="post">
<TR>
  <TD><FONT size=-1>Computername</FONT></TD>
  <TD><FONT size=-1><INPUT type="text" name="frmComputername"></FONT></TD>
</TR>
<TR>
  <TD><FONT size=-1>IP Address</FONT></TD>
  <TD><FONT size=-1><INPUT type="text" name="frmIPAddress"></FONT></TD>
</TR>
<TR>
  <TD><FONT size=-1>Hardware</FONT></TD>
  <TD><FONT size=-1>
<?php
$strHardware = explode(":",$row["hardware"]);
$strHardware = array_reverse($strHardware);
$amount_of_hardware = count($strHardware);
$myCount = $amount_of_hardware;
?>

<?php
$hardware_result = mysql_query("select * from hardware");
echo "<SELECT name=\"frmHardware[]\">";
if ($myCount) {
  $query = "select * from hardware where hardware_id='".$strHardware[$myCount-1]."'";
  $HardwareResult = mysql_query($query);
  $HardwareResult = mysql_fetch_assoc($HardwareResult);
  echo "<OPTION selected value=\"".$strHardware[$myCount-1]."\">".$HardwareResult["type"]." - ".$HardwareResult["make"]." ".$HardwareResult["model"];
  echo "<OPTION>none";
  $myCount = $myCount -1;
} else {
  echo "<OPTION selected>none";
}
while($row = mysql_fetch_assoc($hardware_result)) {
?>
 <OPTION value="<?= $row["hardware_id"] ?>"><?= $row["type"] ?> - <?= $row["make"] ?> - <?= $row["model"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<TR>
  <TD><FONT size=-1></FONT></TD>
  <TD><FONT size=-1>
<?php
$hardware_result = mysql_query("select * from hardware");
echo "<SELECT name=\"frmHardware[]\">";
if ($myCount) {
  $query = "select * from hardware where hardware_id='".$strHardware[$myCount-1]."'";
  $HardwareResult = mysql_query($query);
  $HardwareResult = mysql_fetch_assoc($HardwareResult);
  echo "<OPTION selected value=\"".$strHardware[$myCount-1]."\">".$HardwareResult["type"]." - ".$HardwareResult["make"]." ".$HardwareResult["model"];
  echo "<OPTION>none";
  $myCount = $myCount -1;
} else {
  echo "<OPTION selected>none";
}
while($row = mysql_fetch_assoc($hardware_result)) {
?>
 <OPTION value="<?= $row["hardware_id"] ?>"><?= $row["type"] ?> - <?= $row["make"] ?> - <?= $row["model"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<TR>
  <TD><FONT size=-1></FONT></TD>
  <TD><FONT size=-1>
<?php
$hardware_result = mysql_query("select * from hardware");
echo "<SELECT name=\"frmHardware[]\">";
if ($myCount) {
  $query = "select * from hardware where hardware_id='".$strHardware[$myCount-1]."'";
  $HardwareResult = mysql_query($query);
  $HardwareResult = mysql_fetch_assoc($HardwareResult);
  echo "<OPTION selected value=\"".$strHardware[$myCount-1]."\">".$HardwareResult["type"]." - ".$HardwareResult["make"]." ".$HardwareResult["model"];
  echo "<OPTION>none";
  $myCount = $myCount -1;
} else {
  echo "<OPTION selected>none";
}
while($row = mysql_fetch_assoc($hardware_result)) {
?>
 <OPTION value="<?= $row["hardware_id"] ?>"><?= $row["type"] ?> - <?= $row["make"] ?> - <?= $row["model"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<TR>
  <TD><FONT size=-1></FONT></TD>
  <TD><FONT size=-1>
<?php
$hardware_result = mysql_query("select * from hardware");
echo "<SELECT name=\"frmHardware[]\">";
if ($myCount) {
  $query = "select * from hardware where hardware_id='".$strHardware[$myCount-1]."'";
  $HardwareResult = mysql_query($query);
  $HardwareResult = mysql_fetch_assoc($HardwareResult);
  echo "<OPTION selected value=\"".$strHardware[$myCount-1]."\">".$HardwareResult["type"]." - ".$HardwareResult["make"]." ".$HardwareResult["model"];
  echo "<OPTION>none";
  $myCount = $myCount -1;
} else {
  echo "<OPTION selected>none";
}
while($row = mysql_fetch_assoc($hardware_result)) {
?>
 <OPTION value="<?= $row["hardware_id"] ?>"><?= $row["type"] ?> - <?= $row["make"] ?> - <?= $row["model"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<TR>
  <TD><FONT size=-1></FONT></TD>
  <TD><FONT size=-1>
<?php
$hardware_result = mysql_query("select * from hardware");
echo "<SELECT name=\"frmHardware[]\">";
if ($myCount) {
  $query = "select * from hardware where hardware_id='".$strHardware[$myCount-1]."'";
  $HardwareResult = mysql_query($query);
  $HardwareResult = mysql_fetch_assoc($HardwareResult);
  echo "<OPTION selected value=\"".$strHardware[$myCount-1]."\">".$HardwareResult["type"]." - ".$HardwareResult["make"]." ".$HardwareResult["model"];
  echo "<OPTION>none";
  $myCount = $myCount -1;
} else {
  echo "<OPTION selected>none";
}
while($row = mysql_fetch_assoc($hardware_result)) {
?>
 <OPTION value="<?= $row["hardware_id"] ?>"><?= $row["type"] ?> - <?= $row["make"] ?> - <?= $row["model"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<TR>

<?php
$sw = mysql_query("select * from templates where template_id='$templateid'");
$row = mysql_fetch_assoc($sw);
$strSoftware = explode(":",$row["software"]);
$strSoftware = array_reverse($strSoftware);
$amount_of_software = count($strSoftware);
$myCount = $amount_of_software;
?>
  <TD><FONT size=-1>Software</FONT></TD>
  <TD><FONT size=-1>
<?php
$software_result = mysql_query("select * from software");
echo "<SELECT name=\"frmSoftware[]\">";
if ($myCount) {
  $query = "select * from software where software_id='".$strSoftware[$myCount-1]."'";
  $SoftwareResult = mysql_query($query);
  $myRow = mysql_fetch_assoc($SoftwareResult);
  echo "<OPTION selected value=\"".$myRow["software_id"]."\">".$myRow["manufacturer"]." ".$myRow["name"]." ".$myRow["version"];
  echo "<OPTION>none";
  $myCount= $myCount -1;
} else {
  echo "<OPTION selected>none";
}
$number_of_rows = mysql_num_rows($software_result)-1;
while($row = mysql_fetch_assoc($software_result)) {
?>
 <OPTION value="<?= $row["software_id"] ?>"><?= $row["manufacturer"] ?> <?= $row["name"] ?> <?= $row["version"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<?php
for ($i=1;$i <= $number_of_rows;$i++) {
?>
<TR>
  <TD><FONT size=-1></FONT></TD>
  <TD><FONT size=-1>
<?php
$software_result = mysql_query("select * from software");
echo "<SELECT name=\"frmSoftware[]\">";
if ($myCount) {
  $query = "select * from software where software_id='".$strSoftware[$myCount-1]."'";
  $SoftwareResult = mysql_query($query);
  $myRow = mysql_fetch_assoc($SoftwareResult);
  echo "<OPTION selected value=\"".$myRow["software_id"]."\">".$myRow["manufacturer"]." ".$myRow["name"]." ".$myRow["version"];
  echo "<OPTION>none";
  $myCount= $myCount -1;
} else {
  echo "<OPTION selected>none";
}
while($row = mysql_fetch_assoc($software_result)) {
?>
 <OPTION value="<?= $row["software_id"] ?>"><?= $row["manufacturer"] ?> <?= $row["name"] ?> <?= $row["version"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>

<?php
}
?>
<TR>
  <TD><FONT size=-1>Operating system</FONT></TD>
  <TD><FONT size=-1>
<?php
$os_result = mysql_query("select * from os");
$myOS = mysql_query("select * from os where os_id='".$rowB["os"]."'");
$myOS = mysql_fetch_assoc($myOS);
echo "<SELECT name=\"frmOs\">";
echo "<OPTION selected value=\"".$rowB["os"]."\">".$myOS["name"];
echo "<OPTION >none";
$number_of_rows = mysql_num_rows($os_result)-1;
while($row = mysql_fetch_assoc($os_result)) {
?>
 <OPTION value="<?= $row["os_id"] ?>"><?= $row["name"] ?>
<?php
}
echo "</SELECT>";
?>
  </FONT></TD>
</TR>
<TR>
  <TD valign="top"><FONT size=-1>Other</FONT></TD>
  <TD><FONT size=-1><TEXTAREA name="frmOther" rows=5 cols=40><?= $rowB["other"] ?></TEXTAREA></FONT></TD>
</TR>

</TABLE><BR>
<INPUT type="hidden" value="insert" name="option">
<INPUT type="hidden" value="computer" name="type">
<INPUT type="submit" value="Insert" name="frmSubmit">
<INPUT type="reset" value="Reset">
</FORM>

    
