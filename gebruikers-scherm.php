<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");
$ref = $_GET["ref"];
$action = $_GET["action"];
$sqle = "SELECT * FROM plaats WHERE eid = '$ref'";
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
}
print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Hallo $wb_str $wb_naam!</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align=center>
<a href='gebruikers-scherm.php?ref=$ref&action=1'>Uw bijdrage wijzigen</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='gebruikers-scherm.php?ref=$ref&action=2'>Logo wijzigen</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='gebruikers-scherm.php?ref=$ref&action=3'>Uw bijdrage verwijderen</a>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class='tdline'>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
");
if ($action == '1') {
print ("
<tr><td>&nbsp;</td></tr>
<tr><td>
<p>Gelieve alle gegevens correct in te vullen</p>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='bijdrage_update.php?ref=$ref&action=1' method='POST'>
<tr><td width=180 valign=top><b>U wenst:</b></td>
<td width=300>
<table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='1'");
if ($eart == '1') { print (" checked"); }
print ("></td><td width=270><b>Standaard link</b> (gratis)</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='2'");
if ($eart == '2') { print (" checked"); }
print ("></td><td width=270 class='tdcont2'><b>Profi link (10 EUR/jaar)</b><br>
Met een extra optische achtergrond kleur</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='3'");
if ($eart == '3') { print (" checked"); }
print ("></td><td width=270 class='tdcont3'><b>Premium link (20 EUR/jaar)</b><br>
Met een extra optische achtergrond kleur en een eigen afbeelding naar keuze</td></tr>
<tr><td width=30 valign=top>&nbsp;</td></tr>
</table>
</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr height=22><td><b>Categorie</b></td><td>");
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
print ("$categorie --&gt; $wb_ondercat</td></tr>
<tr height=22><td><b>Website URL</b></td>
<td><b>$weburlc</b></td></tr>
<tr height=22><td><b>Website titel</b></td>
<td><input type='text' name='webtitel' style='width:300px;' class='tf' value='$etitel'></td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>

<tr><td valign=top><b>Website beschrijving</b></td>
<td><textarea name='beschrijving' rows='5' cols='30' style='width:300px;' class='tf2'>$wb_beschrijving</textarea></td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>

<tr height=22><td><b>Firma:</b></td>
<td><input type='text' name='firma' style='width:300px;' class='tf' value='$efirma'></td></tr>
<tr height=22><td>&nbsp;</td>
<td><input type='radio' name='aanspreektitel' value='Heer'");
if ($wb_str == 'Heer') { print (" checked"); }
print (">Heer &nbsp;&nbsp;<input type='radio' name='aanspreektitel' value='Mevrouw'");
if ($wb_str == 'Mevrouw') { print (" checked"); }
print (">Mevrouw</td></tr>
<tr height=22><td><b>Uw naam</b></td>
<td><input type='text' name='fnaam' style='width:300px;' class='tf' value='$wb_naam'></td></tr>
<tr height=22><td><b>Voornaam</b></td>
<td><input type='text' name='voorn' style='width:300px;' class='tf' value='$wb_vnaam'></td></tr>
<tr height=22><td><b>Straat en huisnummer</b></td>
<td><input type='text' name='adres' style='width:300px;' class='tf' value='$kladres'></td></tr>
<tr height=22><td><b>Woonplaats</b></td>
<td><table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=80><input type='text' name='plz' style='width:75px;' class='tf' value='$wb_pc'></td>
<td width=220><input type='text' name='ort' style='width:220px;' class='tf' value='$wb_pl'></td>
</tr></table></td></tr>
<tr height=22><td><b>E-mail adres</b></td>
<td><input type='text' name='email' style='width:300px;' class='tf' value='$eemail'></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Bevestigen' style='width:300px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>
");
}
if ($action == '2') {
if ($eart == '3') {
print ("
<tr><td>&nbsp;</td></tr>");
if ($escreenurl == '') {
print ("<tr><td>Geen logo aanwezig</td></tr><tr><td height=40>&nbsp;</td></tr>");
}
else {
print ("<tr><td><img src='$escreenurl' width='150' height='100' border='0'></td></tr><tr><td height=40>&nbsp;</td></tr>");
}
print ("<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='bijdrage_update.php?ref=$ref&action=2' method='POST'>
<tr>
<td width=200>Afbeelding URL");
if ($escreenurl == '') { print (" toevoegen"); }
else { print (" wijzigen"); }
print (":</td>
<td width=280><input type='text' name='screenurl' style='width:280px;' class='tf' value='http://www.'></td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td>");
if ($escreenurl == '') { print ("<td><input type='submit' name='vveerr' value='Bevestigen' style='width:280px;' class='bt1'></td>"); }
else { print ("<td><input type='submit' name='vveerr' value='Bevestigen' style='width:280px;' class='bt1'></td>"); }
print ("</tr>
</form>
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><b>Tip:</b> Het logo wordt automatisch verkleind naar 150 x 100 pixels.</td></tr>
");
}
else {
print ("
<tr><td>&nbsp;</td></tr>
<tr><td align=center><b>Logo toevoegen of wijzigen is bij dit link type niet mogelijk</b></td></tr>
<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
<tr><td align=left>Om een logo toe te voegen kunt u enkel de betalende <b>Premium link</b> gebruiken.<br>Wijzig <a href='gebruikers-scherm.php?ref=$ref&action=1'>hier</a> de status.</td></tr>
");
}
}
if ($action == '3') {
print ("<tr><td>&nbsp;</td></tr>
<tr><td align=center><b>Bent u zeker om deze bijdrage te verwijderen?</b></td></tr>
<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
<tr><td align=center><form action='bijdrage_update.php?ref=$ref&action=3' method='POST'><input type='submit' name='vveerr' value='Ja, nu verwijderen' style='width:300px;' class='bt1'></form></td></tr>
<tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
<tr><td align=center><b>Opgelet</b><br><br>Deze bewerking kan niet ongedaan worden gemaakt.</td></tr>
");
}



include("include/voetnoot.php");
mysql_close($verbinding);
?>