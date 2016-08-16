<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");
$firma = $_POST["firma"];
$aanspreektitel = $_POST["aanspreektitel"];
$fnaam = $_POST["fnaam"];
$voorn = $_POST["voorn"];
$adres = $_POST["adres"];
$plz = $_POST["plz"];
$ort = $_POST["ort"];
$email = $_POST["email"];
$nachricht = $_POST["nachricht"];
if ($aanspreektitel == 'Heer') { $groet = "Beste heer"; }
if ($aanspreektitel == 'Mevrouw') { $groet = "Beste mevrouw"; }

if (empty($_POST['fnaam']) || empty($_POST['voorn']) || empty($_POST['email']) || empty($_POST['nachricht'])) {
print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Contacteer ons</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='contact_versturen.php' method='POST'>
<tr><td width=180>&nbsp;</td><td width=300 class='tdf'><b>Alle gegevens met een * zijn verplicht</b></td></tr>
<tr height=22 width=180><td><b>Firma:</b></td>
<td width=300><input type='text' name='firma' style='width:300px;' class='tf' value='$firma'></td></tr>
<tr height=22><td>&nbsp;</td>
<td><input type='radio' name='aanspreektitel' value='Heer'");
if ($aanspreektitel == 'Heer') { print (" checked"); }
print (">Heer &nbsp;&nbsp;<input type='radio' name='aanspreektitel' value='Mevrouw'");
if ($aanspreektitel == 'Mevrouw') { print (" checked"); }
print (">Mevrouw</td></tr>
<tr height=22><td");
if (empty($_POST['fnaam'])) { print (" class='tdf'"); }
print ("><b>Naam: *</b></td>
<td><input type='text' name='fnaam' style='width:300px;' class='tf' value='$fnaam'></td></tr>
<tr height=22><td");
if (empty($_POST['voorn'])) { print (" class='tdf'"); }
print ("><b>Voornaam: *</b></td>
<td><input type='text' name='voorn' style='width:300px;' class='tf' value='$voorn'></td></tr>
<tr height=22><td><b>Straat en huisnummer:</b></td>
<td><input type='text' name='adres' style='width:300px;' class='tf' value='$adres'></td></tr>
<tr height=22><td><b>Postcode - woonplaats:</b></td>
<td><table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=80><input type='text' name='plz' style='width:75px;' class='tf' value='$plz'></td>
<td width=220><input type='text' name='ort' style='width:220px;' class='tf' value='$ort'></td>
</tr></table></td></tr>
<tr height=22><td");
if (empty($_POST['email'])) { print (" class='tdf'"); }
print ("><b>E-mail: *</b></td>
<td><input type='text' name='email' style='width:300px;' class='tf' value='$email'></td></tr>
<tr><td valign=top");
if (empty($_POST['nachricht'])) { print (" class='tdf'"); }
print ("><b>Uw bericht: *</b></td>
<td><textarea name='nachricht' rows='5' cols='30' style='width:300px;' class='tf2'>$nachricht</textarea></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Versturen' style='width:300px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>
");
}
else {
$ip = $_SERVER['REMOTE_ADDR'];
$betreff = "Mail contact formulier";
$text = "U heeft een nieuwe e-mail ontvangen van:\n\nFirma: $firma\n$aanspreektitel\n$voorn $fnaam\n\nStraat en huisnummer: $adres\n$plz $ort\n\nE-mail: $email\n\n$nachricht\n\nIP-adres: $ip";

mail($aemail, $betreff, $text, "FROM: $fnaam $voorn <$email>");

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Contacteer ons</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align=center><b>$groet $fnaam,</b><br><br>Uw bericht werd succesvol verstuurd.<br></td></tr>
");

}



include("include/voetnoot.php");
mysql_close($verbinding);
?>