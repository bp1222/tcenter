<HTML>
  <BODY leftmargin="0" topmargin="0">
    <TABLE border="0" borderspace="0" height="100%" width="100%" cellpadding="0">
      <TR>
        <TD background="navbar.bmp" width="140" valign="top">
        <FONT face="verdana" size="-2">
<?php
if (isset($navLinks)) {
  foreach($navLinks as $title => $link) {
    echo "&nbsp;&nbsp;<A href=\"$link\">$title</A><BR>";
  }
  echo "<BR>\n";
  mysql_connect(_DBSERVER,_USERNAME,_PASSWORD);
  mysql_select_db(_DATABASE);
  $resultNav = mysql_query("select * from templates order by name");
  while($row = mysql_fetch_assoc($resultNav) ) {
    echo "&nbsp;&nbsp;<A href=\"edit.php?option=additem&type=computerfromtemplate&templateid=".$row["template_id"]."\">".$row["name"]."</A><BR>";
  }
  echo "<BR>&nbsp;&nbsp;<A href=\"report.php\" target=\"_blank\">Inventory Report</A><BR>";
}
?>
        </FONT>
        </TD>

        <TD valign="top"><FONT face="verdana">
