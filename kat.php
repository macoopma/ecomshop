<?php
$kat = $_GET['kat'];
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");
$sql = "SELECT * FROM hoofcattabel WHERE kid = '$kat'";
$res = mysql_query($sql);
while($row = mysql_fetch_assoc($res)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
}
$sql0 = "SELECT * FROM subcategorietabel WHERE hid = '$kat'";
$res0 = mysql_query($sql0);
$zeilen = mysql_num_rows($res0);
$zeilen1 = $zeilen / 2;
$zeile = ceil($zeilen1);

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;$categorie</b></td></tr>
<tr><td>&nbsp;</td></tr>

<tr><td>
<table width=480 bgcolor='#EEEEEE' border=0 cellpadding=0 cellspacing=0 style='border: 1px solid #000000;'>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=460 align=center border=0 cellpadding=0 cellspacing=0>
<tr>
<td width=240 valign=top>
<table width=240 border=0 cellpadding=0 cellspacing=0>");
$sql1 = "SELECT * FROM subcategorietabel WHERE hid = '$kat' ORDER by wb_ondercat limit 0, $zeile";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
$sql1a = "SELECT * FROM plaats WHERE wb_subcat = '$uid' AND estatus = '1'";
$res1a = mysql_query($sql1a);
$anzahl1 = mysql_num_rows($res1a);
print ("
<tr height=20><td width=10>&nbsp;</td><td width=20><img src='img/add-item.gif' width='14' height='14' border='0'></td>
<td width=200><a href='cont.php?ukat=$uid'>$wb_ondercat ($anzahl1)</a></td></tr>
");
}
print ("</table>
</td>
<td width=240 valign=top>
<table width=240 border=0 cellpadding=0 cellspacing=0>");
$sql2 = "SELECT * FROM subcategorietabel WHERE hid = '$kat'ORDER by wb_ondercat limit $zeile, $zeilen";
$res2 = mysql_query($sql2);
while($row = mysql_fetch_assoc($res2)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
$sql1b = "SELECT * FROM plaats WHERE wb_subcat = '$uid' AND estatus = '1'";
$res1b = mysql_query($sql1b);
$anzahl2 = mysql_num_rows($res1b);
print ("
<tr height=20><td width=10>&nbsp;</td><td width=20><img src='img/add-item.gif' width='14' height='14' border='0'></td>
<td width=200><a href='cont.php?ukat=$uid'>$wb_ondercat ($anzahl2)</a></td></tr>
");
}
print ("
</table>
</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;<a href='index.php'><b>&#171; Ga terug</b></a></td></tr>
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td></tr>
");
include("include/voetnoot.php");
mysql_close($verbinding);
?>