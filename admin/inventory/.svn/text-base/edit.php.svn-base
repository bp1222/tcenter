<?php
include("nav.inc");

include("config.inc");
  mysql_pconnect(_DBSERVER,_USERNAME,_PASSWORD);
  mysql_select_db(_DATABASE);

if (!isset($option) ) {
  header("Location: view.php");
  exit();
} else {
}


if ($option == "removeitem" and $type == "computer") {
  include("header.inc");
  mysql_query("delete from computers where computer_id='$frmComputerid'");
  echo "Computer with id $frmComputerid has been deleted";
  include("footer.inc");
} elseif ($option == "removeitem" and $type == "template") {
  include("header.inc");
  mysql_query("delete from templates where template_id='$templateid'");
  echo "Template with id $templateid has been removed";
  include("footer.inc");
} elseif ($option == "removeitem" and $type == "software") {
  include("header.inc");
  mysql_query("delete from software where software_id='$softwareid'");
  echo "Software with id $softwareid has been deleted";
  include("footer.inc");
} elseif ($option == "modifyitem" and $type == "computer") {
  include("header.inc");
  $result = mysql_query("select * from computers where computer_id='$frmComputerid'");
  $row = mysql_fetch_assoc($result);
  include("edit.computer.inc");
  include("footer.inc");
} elseif ($option == "edit" and $type == "computer") {
  include("header.inc");
  $firsttime = 1;
  foreach($frmSoftware as $value) {
    if (!($value == "none")) {
      if ($firsttime) {
        $strSoftware = $value;
        $firsttime = 0;
      } else {
        $strSoftware .= ":$value";
      }
    }
  }
  $firsttime = 1;
  foreach($frmHardware as $value) {
    if (!($value == "none")) {
      if ($firsttime) {
        $strHardware = $value;
        $firsttime = 0;
      } else {
        $strHardware .= ":$value";
      }
    }
  }
  mysql_query("update computers set name='$frmComputername',ipaddress='$frmIPAddress',hardware='$strHardware',software='$strSoftware',os='$frmOs',other='$frmOther' where computer_id='$myCompID'");
  echo "Computer with id $myCompID has been updated";
  include("footer.inc");
} elseif ($option == "removeitem" and $type == "hardware") {
  include("header.inc");
  mysql_query("delete from hardware where hardware_id='$hardwareid'");
  echo "Hardware with id $hardwareid has been deleted";
  include("footer.inc");
} elseif ($option == "additem" and $type == "template") {
  include("header.inc");
  include("add.template.inc");
  include("footer.inc");
} elseif ($option == "additem" and $type == "hardware") {
  include("header.inc");
  include("add.hardware.inc");
  include("footer.inc");
} elseif ($option == "additem" and $type == "software") {
  include("header.inc");
  include("add.software.inc");
  include("footer.inc");
} elseif ($option == "additem" and $type == "computer") {
  include("header.inc");
  include("add.computer.inc");
  include("footer.inc");
} elseif ($option == "insert" and $type == "software") {
  include("header.inc");
  mysql_query("insert into software (name,manufacturer,version) values ('$frmName','$frmManufacturer','$frmVersion')");
  echo "<FONT size=\"+1\">Software added</FONT><P><FONT size=\"-1\">";
  echo "$frmManufacturer $frmName $frmVersion";
  include("footer.inc");
} elseif ($option == "insert" and $type == "hardware") {
  include("header.inc");
  mysql_query("insert into hardware (type,model,make,manufacturer) values ('$frmType','$frmModel','$frmMake','$frmManufacturer')");
  echo "<FONT size=\"+1\">Hardware component added</FONT><P><FONT size=\"-1\">";
  echo "$frmType: $frmMake $frmModel<BR>";
  echo "Manufactured by: $frmManufacturer";
  include("footer.inc");
} elseif ($option == "insert" and $type == "template") {
  include("header.inc");
  $firsttime = 1;
  foreach($frmSoftware as $value) {
    if (!($value == "none")) {
      if ($firsttime) {
        $strSoftware = $value;
        $firsttime = 0;
      } else {
        $strSoftware .= ":$value";
      }
    }
  }
  $firsttime = 1;
  foreach($frmHardware as $value) {
    if (!($value == "none")) {
      if ($firsttime) {
        $strHardware = $value;
        $firsttime = 0;
      } else {
        $strHardware .= ":$value";
      }
    }
  }
  mysql_query("insert into templates (name,hardware,software,os,other) values ('$frmTemplatename','$strHardware','$strSoftware','$frmOs','$frmOther')");
  echo "<FONT size=\"+1\">Template added</FONT><P><FONT size=\"-1\">";
  echo "Name: $frmTemplatename<BR>";
  include("footer.inc");
} elseif ($option == "insert" and $type == "computer") {
  include("header.inc");
  $firsttime = 1;
  foreach($frmSoftware as $value) {
    if (!($value == "none")) {
      if ($firsttime) {
        $strSoftware = $value;
        $firsttime = 0;
      } else {
        $strSoftware .= ":$value";
      }
    }
  }
  $firsttime = 1;
  foreach($frmHardware as $value) {
    if (!($value == "none")) {
      if ($firsttime) {
        $strHardware = $value;
        $firsttime = 0;
      } else {
        $strHardware .= ":$value";
      }
    }
  }
  mysql_query("insert into computers (name,ipaddress,hardware,software,os,other) values ('$frmComputername','$frmIPAddress','$strHardware','$strSoftware','$frmOs','$frmOther')");
  echo "<FONT size=\"+1\">Computer added</FONT><P><FONT size=\"-1\">";
  echo "Name: $frmComputername<BR>";
  echo "IP Address: $frmIPAddress<BR><BR>";
  include("footer.inc");
} elseif ($option == "additem" and $type == "computerfromtemplate" and isset($templateid) ) {
  include("header.inc");
  $result = mysql_query("select * from templates where template_id='$templateid'");
  $row = mysql_fetch_assoc($result);
  $rowB = $row;
  include("edit.computerft.inc");
  include("footer.inc");
} else {
  header("Location: view.php");
}
?>
