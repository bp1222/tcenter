<?php
echo "<FONT face=\"verdana\" size=\"-1\">";
$result = mysql_query("select * from hardware");
$number_of_rows = mysql_num_rows($result);
echo "<FONT size=\"+1\">Current statistics:</FONT><BR><BR>";
echo "<B>Hardware:</B><BR>";
echo "Entries: ".$number_of_rows."<BR>\n";
$result = mysql_query("select * from software");
$number_of_rows = mysql_num_rows($result);
echo "<B>Software:</B><BR>";
echo "Entries: ".$number_of_rows."<BR>\n";
$result = mysql_query("select * from computers");
$number_of_rows = mysql_num_rows($result);
echo "<B>Computers:</B><BR>";
echo "Entries: ".$number_of_rows."<BR>\n";
$result = mysql_query("select * from os");
$number_of_rows = mysql_num_rows($result);
echo "<B>Operating Systems:</B><BR>";
echo "Entries: ".$number_of_rows."<BR>\n";
echo "</FONT>";
?>
