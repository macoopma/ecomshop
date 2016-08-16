<?php
include("include/database_gegevens.php");
include("include/hoofding.php");

print ("
<td width=500 height=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Alle categories</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>");
$sql1 = "SELECT * FROM hoofcattabel ORDER by categorie";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
print ("<tr><td width=240 valign=top>$categorie</td><td>");
$sql2 = "SELECT * FROM subcategorietabel WHERE hid = '$kid' ORDER by wb_ondercat";
$res2 = mysql_query($sql2);
while($row = mysql_fetch_assoc($res2)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
print ("$wb_ondercat<br>");
}
print ("</td></tr><tr><td>&nbsp;</td></tr>");
}
print ("</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>