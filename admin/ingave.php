<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$action = $_GET["action"];
$next = $_GET['next'];
$page = $_GET['page'];
$i = 0;
$prosite = 15;
$i = $next;
$y = $i + $prosite;

if ($action == '1') { $head = "Bijdragen"; }
if ($action == '2') { $status = "1"; $head = "Actieve bijdragen"; }
if ($action == '3') { $status = "0"; $head = "Nieuwe bijdragen"; }
if ($action == '4') { $status = "3"; $head = "Gewijzigede bijdragen"; }
if ($action == '5') { $status = "2"; $head = "Geblokkeerde bijdragen"; }
if ($action != '1') {
$sql0 = "SELECT * FROM plaats WHERE estatus = '$status'";
$res0 = mysql_query($sql0);
$zeilen = mysql_num_rows($res0);
$contseiten = $zeilen / $prosite;
$contseiten1 = (ceil ($contseiten));
$zeile = 1;
}
print ("
<td width=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;$head</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td height=320 valign=top>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>");

if ($action == '1') {
print ("<tr height=20><td>Totaal aantal bijdragen: ");
$sqlg1 = "SELECT * FROM plaats";
$resg1 = mysql_query($sqlg1);
$zeileng1 = mysql_num_rows($resg1);
print ("$zeileng1</td></tr>");
print ("<tr height=20><td>Actieve bijdragen: ");
$sqlg2 = "SELECT * FROM plaats WHERE estatus = '1'";
$resg2 = mysql_query($sqlg2);
$zeileng2 = mysql_num_rows($resg2);
print ("$zeileng2</td></tr>");
print ("<tr height=20><td>Nieuwe bijdragen: ");
$sqlg3 = "SELECT * FROM plaats WHERE estatus = '0'";
$resg3 = mysql_query($sqlg3);
$zeileng3 = mysql_num_rows($resg3);
print ("$zeileng3</td></tr>");
print ("<tr height=20><td>Gewijzigde bijdragen: ");
$sqlg4 = "SELECT * FROM plaats WHERE estatus = '3'";
$resg4 = mysql_query($sqlg4);
$zeileng4 = mysql_num_rows($resg4);
print ("$zeileng4</td></tr>");
print ("<tr height=20><td>Geblokkeerde bijdragen: ");
$sqlg5 = "SELECT * FROM plaats WHERE estatus = '2'";
$resg5 = mysql_query($sqlg5);
$zeileng5 = mysql_num_rows($resg5);
print ("$zeileng5</td></tr>");
}

if ($action != '1') {
$sqle = "SELECT * FROM plaats WHERE estatus = '$status' ORDER by ets DESC limit $next, $prosite";
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
if ($eart == '1') {$art = "gratis";} if ($eart == '2') {$art = "Profi";} if ($eart == '3') {$art = "Prem";}
$url = ereg_replace("http://www.","",$weburlc);
$urllen = strlen($url);
$url1 = substr($url, 0, 30);
if ($urllen > 30) { $url1 .= "..."; }
print ("
<tr height=20><td width=80>$ets</td>
<td width=40>$art</td>
<td width=240>$url1</td>
<td width=120 align=right><a href='ingave_bekijken.php?id=$eid&back=$action'><img src='img/wijzigen.png' width='110' height='16' border='0'></a></td></tr>
");
}
}


print ("
</table>
</td></tr>
");
 
if ($action != '1') {
print ("
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<tr><td align=center>");
$p = 1;
$newstart = 0;
for ($a = 1; $a <= $contseiten1; $a++) {
if ($contseiten1 >= '$p') {
print ("<a href='ingave.php?action=$action&next=$newstart&page=$p'");
if ($p == $page) {
print (" class='a1'>[$p]");
}
else {
print (" class='a2'>[$p]");
}
if ($p < $contseiten1) {
print ("&nbsp;");
}
}
$p++;
$newstart = $newstart + $prosite;
}
print ("</td></tr></table>
</td></tr>");
}
 
print ("
</table>
</td>
");
include("include/voetnoot.php");
mysql_close($verbinding);
?>