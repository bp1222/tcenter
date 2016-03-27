<FONT size="+1">Add a template</FONT>
<TABLE border="1"><FORM action="edit.php" method="post">
<TR>
  <TD><FONT size=-1>Template name</TD>
  <TD><INPUT type="text" name="frmTemplatename"></TD>
</TR>
<TR>
  <TD><FONT size=-1>Hardware</FONT></TD>
  <TD><FONT size=-1>
<?php
$hardware_result = mysql_query("select * from hardware");
echo "<SELECT name=\"frmHardware[]\">";
echo "<OPTION selected>none";
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
echo "<OPTION selected>none";
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
echo "<OPTION selected>none";
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
echo "<OPTION selected>none";
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
echo "<OPTION selected>none";
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
  <TD><FONT size=-1>Software</FONT></TD>
  <TD><FONT size=-1>
<?php
$software_result = mysql_query("select * from software");
echo "<SELECT name=\"frmSoftware[]\">";
echo "<OPTION selected>none";
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
echo "<OPTION selected>none";
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
echo "<SELECT name=\"frmOs\">";
echo "<OPTION selected>none";
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
  <TD><FONT size=-1><TEXTAREA name="frmOther" rows=5 cols=40></TEXTAREA></FONT></TD>
</TR>

</TABLE><BR>
<INPUT type="hidden" value="insert" name="option">
<INPUT type="hidden" value="template" name="type">
<INPUT type="submit" value="Insert" name="frmSubmit">
<INPUT type="reset" value="Reset">
</FORM>

    
