<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$id = $_GET['id'];
$back = $_GET['back'];

print ("
<td width=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Details</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>");

$sqle = "SELECT * FROM plaats WHERE eid = '$id'";
$rese = mysql_query($sqle);
while($row = mysql_fetch_assoc($rese)) {
$eid = $row['eid'];
$ets = $row['ets'];
$estatus = $row['estatus'];
$eart = $row['eart'];
$weburlc = $row['weburlc'];
$etitel = $row['etitel'];
$wb_cat = $row['wb_cat'];
$wb_subcat = $row['wb_subcat'];
$wb_beschrijving = $row['wb_beschrijving'];
$escreenurl = $row['escreenurl'];
$efirma = $row['efirma'];
$wb_naam = $row['wb_naam'];
$wb_vnaam = $row['wb_vnaam'];
$wb_str = $row['wb_str'];
$kladres = $row['kladres'];
$wb_pc = $row['wb_pc'];
$wb_pl = $row['wb_pl'];
$eemail = $row['eemail'];
$datum = date('d.n.Y', $ets);
if ($eart == '1') {$art = "Standaard link"; $kosten = "gratis";} if ($eart == '2') {$art = "Profi link"; $kosten = $profiprijs;} if ($eart == '3') {$art = "Premium-Link"; $kosten = $premiumprijs;}
if ($estatus == '0') { $status = "Nog niet vrij gegeven";}
if ($estatus == '1') { $status = "Actief";}
if ($estatus == '2') { $status = "Geblokkeerd";}
if ($estatus == '3') { $status = "Werd gewijzigd";}
$sql1 = "SELECT * FROM hoofcattabel WHERE kid = '$wb_cat'";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
}
$sql2 = "SELECT * FROM subcategorietabel WHERE uid = '$wb_subcat'";
$res2 = mysql_query($sql2);
while($row = mysql_fetch_assoc($res2)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
}
print ("
<tr height=20><td width=180>ID nummer</td><td width=300>$ets</td></tr>
<tr height=20><td>Online sinds:</td><td>$datum</td></tr>
<tr height=20><td>Status:</td><td>$status</td></tr>
<tr height=20><td>Kosten:</td><td>$art - $kosten</td></tr>
<tr height=20><td>Categorie:</td><td>$categorie</td></tr>
<tr height=20><td>Sub categorie:</td><td>$wb_ondercat</td></tr>
<tr height=20><td>URL:</td><td><a href='$weburlc' target='_blank'>$weburlc</a></td></tr>");
if ($escreenurl != '') {
print ("<tr height=120><td>Scherm:</td><td><img src='$escreenurl' width='150' height='100' style='border:1px solid #000000;'></td></tr>");
}
print ("<tr height=20><td>Titel:</td><td>$etitel</td></tr>
<tr><td colspan=2 class='tdspace1'>&nbsp;</td></tr>
<tr><td valign=top>Beschrijving:</td><td>$wb_beschrijving</td></tr>
<tr><td colspan=2 class='tdspace1'>&nbsp;</td></tr>
<tr height=20><td>Firma:</td><td>$efirma</td></tr>
<tr valign=top><td>Adres:</td><td>$wb_str<br>$wb_vnaam $wb_naam<br>$kladres<br>$wb_pc $wb_pl</td></tr>
<tr height=20><td>E-mail:</td><td><a href='mailto:$eemail'>$eemail</a></td></tr>
<tr><td colspan=2 class='tdspace1'>&nbsp;</td></tr>
<tr><td colspan=2 class='tdline1'>&nbsp;</td></tr>
<tr><td colspan=2 class='tdspace1'>&nbsp;</td></tr>

<tr height=20><td>&nbsp;</td><td>
");
if ($estatus == '0') {
print ("<a href='ingave_update.php?id=$eid&action=1'><b>Bijdrage vrij schakelen</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='ingave_update.php?id=$eid&action=2'><b>Bijdrage verwijderen</b></a>");
}
if ($estatus == '1') {
print ("<a href='ingave_update.php?id=$eid&action=3'><b>Bijdrage blokkeren</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='ingave_update.php?id=$eid&action=2'><b>Bijdrage verwijderen</b></a>");

}
if ($estatus == '2') {
print ("<a href='ingave_update.php?id=$eid&action=1'><b>Bijdrage vrij schakelen</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='ingave_update.php?id=$eid&action=2'><b>Bijdrage verwijderen</b></a>");

}
if ($estatus == '3') {
print ("<a href='ingave_update.php?id=$eid&action=1'><b>Bijdrage vrij schakelen</b></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='ingave_update.php?id=$eid&action=2'><b>Bijdrage verwijderen</b></a>");

}

}

print ("</td></tr></table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>