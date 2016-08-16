<?php
$ukat = $_GET['ukat'];
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");
$sql = "SELECT * FROM subcategorietabel WHERE uid = '$ukat'";
$res = mysql_query($sql);
while($row = mysql_fetch_assoc($res)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
}
$sql = "SELECT * FROM hoofcattabel WHERE kid = '$hid'";
$res = mysql_query($sql);
while($row = mysql_fetch_assoc($res)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
}
$sql0 = "SELECT * FROM plaats WHERE wb_subcat = '$ukat' AND estatus = '1'";
$res0 = mysql_query($sql0);
$zeilen = mysql_num_rows($res0);

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;$categorie / $wb_ondercat</b></td></tr>
<tr><td>&nbsp;</td></tr>");
if ($zeilen != '0') {
$sqle = "SELECT * FROM plaats WHERE wb_subcat = '$ukat' AND estatus = '1' ORDER by ets";
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


$sqlh = "SELECT * FROM hits WHERE hitseid = '$eid'";
$resh = mysql_query($sqlh);
$hits = mysql_num_rows($resh);
$note = 0;
$sqlv = "SELECT * FROM stemmen WHERE veid = '$eid'";
$resv = mysql_query($sqlv);
$votezahl = mysql_num_rows($resv);
$sqlv = "SELECT * FROM stemmen WHERE veid = '$eid'";
$resv = mysql_query($sqlv);
while($row = mysql_fetch_assoc($resv)) {
$vnote = $row['vnote'];
$note = $note + $vnote;
}
if ($note != '0') {
$durchschn = $note / $votezahl;
$durchschnitt = ceil($durchschn);
}
else { $durchschnitt = "0"; }
if (($eart == '1') || ($eart == '2')) {
print ("<tr><td");
if ($eart == '1') { print (" class='tdcont1'>"); }
if ($eart == '2') { print (" class='tdcont2'>"); }
print ("<table width=480 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdspace' colspan=2>&nbsp;</td></tr>
<tr><td width=30 align=center><img src='img/home-icon.gif' width='14' height='14' border='0'></td>
<td width=450><a href='website_zien.php?siteid=$eid' target='_blank' class='a1'><b>$etitel</b></a></td></tr>
<tr><td>&nbsp;</td><td>$wb_beschrijving</td></tr>
<tr><td class='tdspace' colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>
<table width=450 border=0 cellpadding=0 cellspacing=0>
<form action='stemmen.php?siteid=$eid' method='POST'>
<tr>
<td width=80>Kliks: $hits</td>
<td width=70>Waardering:</td>
<td width=120>");
if ($durchschnitt == '0') { print ("<img src='img/star0.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '1') { print ("<img src='img/star5.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '2') { print ("<img src='img/star4.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '3') { print ("<img src='img/star3.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '4') { print ("<img src='img/star2.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '5') { print ("<img src='img/star1.gif' width='73' height='13' border='0'>"); }
print ("</td>
<td width=60>Stemmem&nbsp;</td>
<td width=100>
<select name='stemmen' class='tf1'>
<option value='1'>Uitstekend
<option value='2'>Goed
<option value='3'>Gemiddeld
<option value='4'>Kan beter
<option value='5'>Slecht
</select>
</td>
<td width=20 align=right>
<input type='image' name='submit' value='submit' src='img/vote1.gif' border='0' onFocus='if (this.blur) this.blur()' alt='Nu stemmen' title='Nu stemmen'>
</td>
</tr>
</form>
</table>
</td></tr>
</table>
</td></tr>
<tr><td class='tdspace1'>&nbsp;</td></tr><tr><td class='tdline1'>&nbsp;</td></tr><tr><td class='tdspace1'>&nbsp;</td></tr>
");
}
if ($eart == '3') {
if ($escreenurl == '') {
print ("<tr><td class='tdcont3'><table width=480 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdspace' colspan=2>&nbsp;</td></tr>
<tr><td width=30 align=center><img src='img/home-icon.gif' width='14' height='14' border='0'></td>
<td width=450><a href='website_zien.php?siteid=$eid' target='_blank' class='a1'><b>$etitel</b></a></td></tr>
<tr><td>&nbsp;</td><td>$wb_beschrijving</td></tr>
<tr><td class='tdspace' colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td>
<table width=450 border=0 cellpadding=0 cellspacing=0>
<form action='stemmen.php?siteid=$eid' method='POST'>
<tr>
<td width=80>Kliks: $hits</td>
<td width=70>Waardering:</td>
<td width=120>");
if ($durchschnitt == '0') { print ("<img src='img/star0.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '1') { print ("<img src='img/star5.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '2') { print ("<img src='img/star4.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '3') { print ("<img src='img/star3.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '4') { print ("<img src='img/star2.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '5') { print ("<img src='img/star1.gif' width='73' height='13' border='0'>"); }
print ("</td>
<td width=60>Stemmen:</td>
<td width=100>
<select name='stemmen' class='tf1'>
<option value='1'>Uitstekend
<option value='2'>Goed
<option value='3'>Gemiddeld
<option value='4'>Kan beter
<option value='5'>Slecht
</select>
</td>
<td width=20 align=right>
<input type='image' name='submit' value='submit' src='img/vote1.gif' border='0' onFocus='if (this.blur) this.blur()' alt='Bewertung abgeben' title='Bewertung abgeben'>
</td>
</tr>
</form>
</table>
</td></tr>
</table>
</td></tr>
<tr><td class='tdspace1'>&nbsp;</td></tr><tr><td class='tdline1'>&nbsp;</td></tr><tr><td class='tdspace1'>&nbsp;</td></tr>
");
}
if ($escreenurl != '') {
print ("
<tr><td class='tdcont3'>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdspace' colspan=3>&nbsp;</td></tr>
<tr>
<td width=170 valign=top align=center><a href='website_zien.php?siteid=$eid' target='_blank'><img src='$escreenurl' width='150' height='100' style='border:1px solid #000000;'></a></td>
<td width=20>&nbsp;</td>
<td width=290 valign=top>
<table width=290 border=0 cellpadding=0 cellspacing=0>
<tr><td><a href='website_zien.php?siteid=$eid' target='_blank' class='a1'><b>$etitel</b></a></td></tr>
<tr><td>$wb_beschrijving</td></tr>
</table>
</td>
</tr>
<tr><td class='tdspace' colspan=3>&nbsp;</td></tr>
<tr><td colspan=3>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='stemmen.php?siteid=$eid' method='POST'>
<tr>
<td width=12>&nbsp;</td>
<td width=80>Kliks: $hits</td>
<td width=70>Waardering:</td>
<td width=138>");
if ($durchschnitt == '0') { print ("<img src='img/star0.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '1') { print ("<img src='img/star5.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '2') { print ("<img src='img/star4.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '3') { print ("<img src='img/star3.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '4') { print ("<img src='img/star2.gif' width='73' height='13' border='0'>"); }
if ($durchschnitt == '5') { print ("<img src='img/star1.gif' width='73' height='13' border='0'>"); }
print ("</td>
<td width=60>Stemmen:</td>
<td width=100>
<select name='stemmen' class='tf1'>
<option value='1'>Uitstekend
<option value='2'>Goed
<option value='3'>Gemiddeld
<option value='4'>Kan beter
<option value='5'>Slecht
</select>
</td>
<td width=20 align=right>
<input type='image' name='submit' value='submit' src='img/vote1.gif' border='0' onFocus='if (this.blur) this.blur()' alt='Nu stemmen' title='Nu stemmen'>
</td>
</tr>
</form>
</table>
</td></tr>
<tr><td class='tdspace' colspan=3>&nbsp;</td></tr>
</table>
</td></tr>
<tr><td class='tdspace1'>&nbsp;</td></tr><tr><td class='tdline1'>&nbsp;</td></tr><tr><td class='tdspace1'>&nbsp;</td></tr>
");
}


}
}
}
else {
print ("
<tr><td align=center>Er staat geen bijdrage in deze categorie</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class='tdline1'>&nbsp;</td></tr>
");
}
print ("
<tr><td>&nbsp;</td></tr>
<tr><td align=center><a href='index.php'><b>&#171; Terug naar de start pagina</b></a>&nbsp;&nbsp;<a href='kat.php?kat=$hid'><b>&#171; Terug naar het overzicht</b></a></td></tr>
<tr><td><br><center><br><br><br>Dit is een gratis script van <a href='http://www.webhouse.be' target='_blank'>Webhouse</a></td></tr>



");

include("include/voetnoot.php");
mysql_close($verbinding);
?>