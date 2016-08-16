<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");
$wwwurl = $_POST['wwwurl'];

$sql0 = "SELECT * FROM plaats WHERE weburlc = '$wwwurl'";
$res0 = mysql_query($sql0);
$zeilen = mysql_num_rows($res0);

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Wachtwoord opnieuw vragen</b></td></tr>
<tr><td>&nbsp;</td></tr>");

if ($zeilen != '0') {
$sqle = "SELECT * FROM plaats WHERE weburlc = '$wwwurl'";
$rese = mysql_query($sqle);
while($row = mysql_fetch_assoc($rese)) {
$eid = $row['eid'];
$ets = $row['ets'];
$epass = $row['epass'];
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
if ($estatus == '1') {
$status = "ok";
}
if ($estatus != '1') {
$status = "notok";
$fehler = "Uw website werd door de administrator nog niet vrij geschakeld";
}
}
}
else {
$status = "notok";
$fehler = "De ingevulde URL is niet aanwezig in het systeem";
}

if ($status == 'notok') {
print ("
<tr><td class='tdf'><b>$fehler</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='wachtwoord_opvragen.php' method='POST'>
<tr height=25 width=160><td>Uw website URL</td>
<td width=320><input type='text' name='wwwurl' style='width:320px;' class='tf' value='$wwwurl'></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Versturen' style='width:320px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>");
}
else {
$betreff = "Uw toegang gegevens";
$text = "Hallo $wb_str $wb_naam,\nUw toegang gegevens zijn:\n\nWebsite URL: $weburlc\nWachtwoord: $epass\n\n$admin_url";
mail($eemail, $betreff, $text, "FROM: <$aemail>");
print ("<tr><td align=center>Beste $wb_naam,<br><br>De gegevens werden u per e-mail vestuurd...</td></tr>");
}

include("include/voetnoot.php");
mysql_close($verbinding);
?>