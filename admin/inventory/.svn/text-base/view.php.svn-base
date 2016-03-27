<?php
include("config.inc");
include("nav.inc");
include("header.inc");
if (!isset($view)) {
  $view = _DEFAULTVIEW;
}
if (!isset($orderby)) {
  if (_ORDERBY == "id") {
    $orderby = "computer_id";
  } elseif (_ORDERBY == "ip") {
    $orderby = "ipaddress";
  } else {
    $orderby = "name";
  }
}

if ($view == "advanced") {
mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
mysql_select_db(_DATABASE);
$result = mysql_query("select * from computers order by $orderby");
echo "<FONT size=\"+1\"><U>Entries in the computers database</U></FONT><BR>\n";
echo "<FONT size=\"-2\">Order by: <A href=\"view.php?view=".$view."&orderby=computer_id\">ID</A> <A href=\"view.php?view=".$view."&orderby=ipaddress\">IP Address</A> <A href=\"view.php?view=".$view."&orderby=name\">Name</A><P>";
$counter = 0;
while ($rowdata = mysql_fetch_assoc($result)) {
  echo "<P><TABLE border=\"1\">\n";
  echo "<TR><TD><FONT size=\"-1\"><B>Computer id: </B></FONT></TD><TD><FONT size=\"-1\">".$rowdata["computer_id"]."</FONT></TD></TR>\n";      
  echo "<TR><TD><FONT size=\"-1\"><B>Computer name: </B></FONT></TD><TD><FONT size=\"-1\">".$rowdata["name"]."</FONT></TD></TR>\n";      
  echo "<TR><TD><FONT size=\"-1\"><B>IP Address: </B></FONT></TD><TD><FONT size=\"-1\">".$rowdata["ipaddress"]."</FONT></TD></TR>\n";      
  echo "<TR><TD valign=\"top\"><FONT size=\"-1\"><B>Hardware: </B></FONT></TD>\n";
  echo "<TD><TABLE border=\"1\">\n";
  echo "<TR><TD><FONT size=\"-1\"><U>Type:</U></TD><TD><FONT size=\"-1\"><U>Model:</U></TD><TD><FONT size=\"-1\"><u>Make:</u></TD></TR>\n";

  $hardware_info = explode(":",$rowdata["hardware"]);
  foreach($hardware_info as $hardware_part) {
    $hardware_queryresult = mysql_query("select * from hardware where hardware_id = '$hardware_part'");
    $hardware_result = mysql_fetch_assoc($hardware_queryresult);
    echo "<TR>\n";
    echo "<TD><FONT size=\"-1\">\n";
    echo $hardware_result["type"];
    echo "</TD>\n";
    echo "<TD><FONT size=\"-1\">\n";
    echo $hardware_result["model"];
    echo "</TD>\n";
    echo "<TD><FONT size=\"-1\">\n";
    echo $hardware_result["make"];
    echo "</TD>\n";
    echo "</FONT></TD></TR>\n";
  }
  echo "</TABLE></TD></TR>\n";
  echo "<TR><TD valign=\"top\"><FONT size=\"-1\"><B>Software:</B></FONT></TD>\n";

  echo "<TD><TABLE>\n";
  $software_info = explode(":",$rowdata["software"]);
  foreach($software_info as $software_part) {
    $software_queryresult = mysql_query("select * from software where software_id = '$software_part'");
    $software_result = mysql_fetch_assoc($software_queryresult);
    echo "<TR>\n";
    echo "<TD><FONT size=\"-1\">\n";
    echo $software_result["manufacturer"]." ".$software_result["name"]." ".$software_result["version"];
    echo "</TD>\n";
    echo "</FONT></TD></TR>\n";
  }
  echo "</TABLE></TD></TR>\n";

  echo "<TR><TD><FONT size=\"-1\"><B>Operating System: </B></FONT></TD><TD><FONT size=\"-1\">\n";
  $os_info = $rowdata["os"];
  $os_queryresult = mysql_query("select * from os where os_id = '$os_info'");
  $os_result = mysql_fetch_assoc($os_queryresult);
  echo $os_result["name"];
  echo "</FONT></TD></TR>\n";
  echo "<TR><TD valign=\"top\"><FONT size=\"-1\"><b>Other: </B></FONT></TD><TD><FONT size=\"-1\">".$rowdata["other"]."</FONT></TD></TR>\n\n";
  echo "<TD><FONT size=\"-1\"><A href=\"edit.php?option=modifyitem&type=computer&frmComputerid=".$rowdata["computer_id"]."\">Edit</A>";
  echo "&nbsp;&nbsp;<A href=\"edit.php?option=removeitem&type=computer&frmComputerid=".$rowdata["computer_id"]."\">Remove</A>";
  echo "</FONT></TD>\n";
  echo "</TABLE><BR>\n";

}

} elseif ($view == "software") {
  mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
  mysql_select_db(_DATABASE);
  echo "<FONT size=\"+1\"><U>Entries in the software database</U></FONT><FONT size=\"-1\"><P>\n";
  $result = mysql_query("select * from software");
  while ($row = mysql_fetch_assoc($result)) {
    echo $row["manufacturer"]." ".$row["name"]." ".$row["version"]."&nbsp;&nbsp;&nbsp;<A href=\"edit.php?option=removeitem&type=software&softwareid=".$row["software_id"]."\">remove</A><BR>\n";
  }
  echo "</FONT>";
} elseif ($view == "hardware") {
  mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
  mysql_select_db(_DATABASE);
  echo "<FONT size=\"+1\"><U>Entries in the hardware database</U></FONT><FONT size=\"-1\"><P>\n";
  $result = mysql_query("select * from hardware");
  while ($row = mysql_fetch_assoc($result)) {
    echo $row["type"]
         ." - ".
         $row["make"]
         ." ".
         $row["model"]
         ." - ".
         $row["manufacturer"]
         ."&nbsp;&nbsp;&nbsp;<A href=\"edit.php?option=removeitem&type=hardware&hardwareid="
         .$row["hardware_id"].
         "\">remove</A><BR>\n";
  }
  echo "</FONT>";
} elseif ($view == "templates") {
  mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
  mysql_select_db(_DATABASE);
  echo "<FONT size=\"+1\"><U>Entries in the templates database</U></FONT><FONT size=\"-1\"><P>\n";
  $result = mysql_query("select * from templates");
  while ($row = mysql_fetch_assoc($result) ) {
    echo $row["name"]
         ."&nbsp;&nbsp;&nbsp;<A href=\"edit.php?option=removeitem&type=template&templateid="
         .$row["template_id"].
         "\">remove</A><BR>\n";
  }
  echo "</FONT>";
} else {

mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
mysql_select_db(_DATABASE);
$result = mysql_query("select * from computers order by $orderby");
echo "<FONT size=\"+1\"><U>Entries in the computers database</U></FONT><BR>\n";
echo "<FONT size=\"-2\">Order by: <A href=\"view.php?view=".$view."&orderby=computer_id\">ID</A> <A href=\"view.php?view=".$view."&orderby=ipaddress\">IP Address</A> <A href=\"view.php?view=".$view."&orderby=name\">Name</A><P>";

$counter = 0;
echo "<TABLE border=\"1\"><FORM action=\"report.php\" method=post>\n";
echo "<TR><TD><FONT size=\"-1\"><B>ID</B></TD><TD><FONT size=\"-1\"><B>Name</B></TD><TD><FONT size=\"-1\"><B>IP Address</B></TD></TR>\n";

while ($rowdata = mysql_fetch_assoc($result)) {
  echo "<TR>\n";
  echo "<TD><FONT size=\"-1\">".$rowdata["computer_id"]."</FONT></TD>\n";      
  echo "<TD><FONT size=\"-1\">".$rowdata["name"]."</FONT></TD>\n";      
  echo "<TD><FONT size=\"-1\">".$rowdata["ipaddress"]."</FONT></TD>\n";
  echo "<TD><FONT size=\"-1\"><A href=\"edit.php?option=modifyitem&type=computer&frmComputerid=".$rowdata["computer_id"]."\">Edit</A>";
  echo "&nbsp;&nbsp;<A href=\"edit.php?option=removeitem&type=computer&frmComputerid=".$rowdata["computer_id"]."\">Remove</A>";
  echo "</FONT></TD>\n";
  echo "<TD><INPUT type=checkbox name=\"selectedComputer[]\" value=".$rowdata["computer_id"]."></TD>";
  echo "</TR>\n";
}
echo "<INPUT type=\"hidden\" name=\"selectiveReport\" value=\"true\"></TABLE><BR><INPUT type=\"submit\" value=\"Maak rapport aan\"></FORM>";
}


include("footer.inc");
?>
