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
<a href='gebruikers-scherm.php?ref=$ref&action=1'>Bijdrage wijzigen</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='gebruikers-scherm.php?ref=$ref&action=2'>Logo wijzigen</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href='gebruikers-scherm.php?ref=$ref&action=3'>Bijdrage verwijderen</a>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class='tdline'>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
");
if ($action == '1') {
$selectie = $_POST["selectie"]; $webtitel = $_POST["webtitel"]; $beschrijving = $_POST["beschrijving"]; $firma = $_POST["firma"]; $aanspreektitel = $_POST["aanspreektitel"]; $fnaam = $_POST["fnaam"]; $voorn = $_POST["voorn"]; $adres = $_POST["adres"]; $plz = $_POST["plz"]; $ort = $_POST["ort"]; $email = $_POST["email"];
if ($aanspreektitel == 'Heer') { $groet = "Beste heer"; }
if ($aanspreektitel == 'Mevrouw') { $groet = "Beste mevrouw"; }

if (empty($_POST['webtitel']) || empty($_POST['beschrijving']) || empty($_POST['fnaam']) || empty($_POST['voorn']) || empty($_POST['adres']) || empty($_POST['plz']) || empty($_POST['ort']) || empty($_POST['email'])) {

print ("<form action='bijdrage_update.php?ref=$ref&action=1' method='POST'>
<tr><td width=180 valign=top><b>U wenst</b></td>
<td width=300>
<table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='1'");
if ($selectie == '1') { print (" checked"); }
print ("></td><td width=270><b>Standaard link</b> (gratis)</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='2'");
if ($selectie == '2') { print (" checked"); }
print ("></td><td width=270 class='tdcont2'><b>Profi link ($profiprijs EUR/jaar)</b><br>
Der Eintrag wird optisch durch Hintergrundfarbe hervorgehoben.</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='3'");
if ($selectie == '3') { print (" checked"); }
print ("></td><td width=270 class='tdcont3'><b>Premium-Link ($premiumprijs EUR/jaar)</b><br>
Der Eintrag wird optisch durch Hintergrundfarbe hervorgehoben. Sie k&ouml;nnen dem Eintrag ein Logo oder Screenshot hinzuf&uuml;gen.</td></tr>
<tr><td width=30 valign=top>&nbsp;</td><td width=270 class='tdpt8'>(Preisangaben inkl. Mwst. in Höhe von derzeit 19%)</td></tr>
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
<tr height=22><td");
if (empty($_POST['webtitel'])) { print (" class='tdf'"); }
print ("><b>Website titel</b></td>
<td><input type='text' name='webtitel' style='width:300px;' class='tf' value='$webtitel'></td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>

<tr><td valign=top");
if (empty($_POST['beschrijving'])) { print (" class='tdf'"); }
print ("><b>Website beschrijving</b></td>
<td><textarea name='beschrijving' rows='5' cols='30' style='width:300px;' class='tf2'>$beschrijving</textarea></td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>

<tr height=22><td><b>Firma:</b></td>
<td><input type='text' name='firma' style='width:300px;' class='tf' value='$firma'></td></tr>
<tr height=22><td>&nbsp;</td>
<td><input type='radio' name='aanspreektitel' value='Heer'");
if ($aanspreektitel == 'Heer') { print (" checked"); }
print (">Heer &nbsp;&nbsp;<input type='radio' name='aanspreektitel' value='Mevrouw'");
if ($aanspreektitel == 'Mevrouw') { print (" checked"); }
print (">Mevrouw</td></tr>
<tr height=22><td");
if (empty($_POST['fnaam'])) { print (" class='tdf'"); }
print ("><b>Naam</b></td>
<td><input type='text' name='fnaam' style='width:300px;' class='tf' value='$fnaam'></td></tr>
<tr height=22><td");
if (empty($_POST['voorn'])) { print (" class='tdf'"); }
print ("><b>Voornaam</b></td>
<td><input type='text' name='voorn' style='width:300px;' class='tf' value='$voorn'></td></tr>
<tr height=22><td");
if (empty($_POST['adres'])) { print (" class='tdf'"); }
print ("><b>Straat en huisnummer</b></td>
<td><input type='text' name='adres' style='width:300px;' class='tf' value='$adres'></td></tr>
<tr height=22><td");
if (empty($_POST['plz']) || empty($_POST['ort'])) { print (" class='tdf'"); }
print ("><b>Postcode - woonplaats</b></td>
<td><table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=80><input type='text' name='plz' style='width:75px;' class='tf' value='$plz'></td>
<td width=220><input type='text' name='ort' style='width:220px;' class='tf' value='$ort'></td>
</tr></table></td></tr>
<tr height=22><td");
if (empty($_POST['email'])) { print (" class='tdf'"); }
print ("><b>E-mail</b></td>
<td><input type='text' name='email' style='width:300px;' class='tf' value='$email'></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Bevestigen' style='width:300px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>
");
}
else {
$wijzig = "UPDATE plaats SET estatus = '3' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET eart = '$selectie' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET etitel = '$webtitel' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET wb_beschrijving = '$beschrijving' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET efirma = '$firma' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET wb_str = '$aanspreektitel' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET wb_naam = '$fnaam' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET wb_vnaam = '$voorn' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET kladres = '$adres' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET wb_pc = '$plz' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET wb_pl = '$ort' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE plaats SET eemail = '$email' WHERE eid = '$ref'"; $upd = mysql_query($wijzig);

if ($selectie == '1') { $eintrag1 = "Standaard link gratis"; }
if ($selectie == '2') { $eintrag1 = "Profi link ($profiprijs EUR/jaar)"; }
if ($selectie == '3') { $eintrag1 = "Premium link ($premiumprijs EUR/jaar)"; }
$betreff = "Link bijdrage $ulink";
$text = "Er werd een link bijdrage gewijzigd:\n\nFirma: $firma\n$aanspreektitel\n$voorn $fnaam\n$adres\n$plz $ort\n$email\n\nID: $ets\nBijdrage: $eintrag1\nTitel: $webtitel\nURL: $weburlc\n\nBeschrijving: $beschrijving\n\n";

mail($aemail, $betreff, $text, "FROM: $fnaam $voorn <$email>");


print ("
<tr><td>&nbsp;</td></tr>
<tr><td>De gegevens werden succesvol gewijzigd.<br>Deze worden eerst door de administrator gecontroleerd.</td></tr>
");
}
}
if ($action == '2') {
$screenurl = $_POST["screenurl"];
$wijzig = "UPDATE plaats SET escreenurl = '$screenurl' WHERE eid = '$ref'";
$upd = mysql_query($wijzig);
print ("
<tr><td>&nbsp;</td></tr>
<tr><td>Screen-URL werd toegevoegd of gewijzigd</td></tr>
");
}
if ($action == '3') {
$loeschen = "DELETE FROM plaats WHERE eid = '$ref'";
$loesch = mysql_query($loeschen);
print ("
<tr><td>&nbsp;</td></tr>
<tr><td>Uw bijdrage werd succesvol verwijderd</td></tr>
");
}

include("include/voetnoot.php");
mysql_close($verbinding);
?>