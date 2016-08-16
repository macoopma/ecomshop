<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");

$selectie = $_POST["selectie"];
$kategorie1 = $_POST["categorie"];
$wwwurl = $_POST["wwwurl"];
$webtitel = $_POST["webtitel"];
$beschrijving = $_POST["beschrijving"];
$firma = $_POST["firma"];
$aanspreektitel = $_POST["aanspreektitel"];
$fnaam = $_POST["fnaam"];
$voorn = $_POST["voorn"];
$adres = $_POST["adres"];
$plz = $_POST["plz"];
$ort = $_POST["ort"];
$email = $_POST["email"];
if ($aanspreektitel == 'Heer') { $groet = "Beste heer"; }
if ($aanspreektitel == 'Mevrouw') { $groet = "Beste mevrouw"; }

if (($kategorie1 == '0') || ($wwwurl == '') || ($wwwurl == 'http://www.') || empty($_POST['webtitel']) || empty($_POST['beschrijving']) || empty($_POST['fnaam']) || empty($_POST['voorn']) || empty($_POST['adres']) || empty($_POST['plz']) || empty($_POST['ort']) || empty($_POST['email'])) {
print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Link toevoegen</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<p>Gelieve <font class='tdf'><b>alle gegevens</b></font> in te vullen.</p>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='bijdrage_versturen.php' method='POST'>
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
Met een extra optische achtergrond kleur</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='3'");
if ($selectie == '3') { print (" checked"); }
print ("></td><td width=270 class='tdcont3'><b>Premium link ($premiumprijs EUR/jaar)</b><br>
Met een extra optische achtergrond kleur en een eigen afbeelding naar keuze</td></tr>
<tr><td width=30 valign=top>&nbsp;</td></td></tr>
</table>
</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>");
if ($kategorie1 == '0') {
print ("
<tr height=22><td>&nbsp;</td>
<td class='tdf'><b>Selecteer een categorie --&gt;</b></td></tr>
");
}
print ("<tr height=22><td");
if ($kategorie1 == '0') { print (" class='tdf'"); }
print ("><b>Categorie</b></td><td><select name='categorie' class='tf1' style='width:300px;'>");
$sql1 = "SELECT * FROM hoofcattabel ORDER by kid";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
print ("<option value='0'>$categorie");
$sql2 = "SELECT * FROM subcategorietabel WHERE hid = '$kid' ORDER by uid ";
$res2 = mysql_query($sql2);
while($row = mysql_fetch_assoc($res2)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
print ("<option value='$uid'");
if ($kategorie1 == $uid) { print (" selected"); }
print (">&nbsp;&nbsp;--&gt; $wb_ondercat");
}
}
print ("</select></td></tr>

<tr height=22><td");
if (($wwwurl == '') || ($wwwurl == 'http://www.')) { print (" class='tdf'"); }
print ("><b>Website URL</b></td>
<td><input type='text' name='wwwurl' style='width:300px;' class='tf' value='$wwwurl'></td></tr>
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
$ts = time();
$userlink = "$admin_url"."?id=$ts";
$ulink = ereg_replace("http://www.", "", $admin_url);
$code = "&lt;a href=\"$userlink\" target=\"_blank\"&gt;$ulink&lt;/a&gt;";
$code1 = "<a href=\"$userlink\" target=\"_blank\">$ulink</a>";
if ($selectie == '1') { $eintrag1 = "Standaard link gratis"; }
if ($selectie == '2') { $eintrag1 = "Profi link ($profiprijs EUR/jaar)"; $centen = $profiprijs;}
if ($selectie == '3') { $eintrag1 = "Premium link ($premiumprijs EUR/jaar)";  $centen = $premiumprijs;}
$betreff = "Link bijdrage $ulink";
$pplink = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&business=$apaypal&item_name=Linkbijdrage&item_number=$ts&amount=$centen&no_shipping=2&no_note=1&currency_code=EUR&lc=NL&bn=PP%2dBuyNowBF&charset=UTF%2d8";

$text1 = "$groet $fnaam,\n\nUw aanmelding werd succesvol uitgevoerd. Plaats de bijhorende link goed zichtbaar op de start pagina van uw website. De link bijdrage wordt door ons gecontroleerd.\n\nLink-code:\n\n$code1\n\n";
if ($selectie == '1') {
$text1 .= "Met vriendelijke groeten\n$afirma\n$admin_url\n$aemail";
}
else {
$text1 .= "Gelieve de betaling van $centen Euro op de volgende rekening te vereffenen:\n\n
Ontvanger: $admin_pincode\n\n\n$abank\nIBAN: $admin_iban\nBIC: $admin_swift\n\nOfwel via Paypal op de volgende link: $pplink\n\n\nMet vriendelijke groeten\n$afirma\n$admin_url\n$aemail";
}

$text2 = "Er werd een nieuwe link toegevoegd op $ulink:\n\nFirma: $firma\n$aanspreektitel\n$voorn $fnaam\n$adres\n$plz $ort\n$email\n\nID: $ts\nBijdrage: $eintrag1\nTitel: $webtitel\nURL: $wwwurl\n\nBeschrijving: $beschrijving\n\n\n\nAdministrator login: $admins_url";

mail($email, $betreff, $text1, "FROM: $ulink <$aemail>");
mail($aemail, $betreff, $text2, "FROM: $fnaam $voorn <$email>");

$sql3 = "SELECT * FROM subcategorietabel WHERE uid = '$kategorie1'";
$res3 = mysql_query($sql3);
while($row = mysql_fetch_assoc($res3)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
}
$passw = "t";
$passw .= substr($ts, 5, 4);
$passw .= "e";

$inzet = "INSERT INTO `plaats` (`eid`, `ets`, `epass`, `estatus`, `eart`, `weburlc`, `etitel`, `wb_cat`, `wb_subcat`, `wb_beschrijving`, `escreenurl`, `efirma`, `wb_naam`, `wb_vnaam`, `wb_str`, `kladres`, `wb_pc`, `wb_pl`, `eemail`) VALUES ('', '$ts', '$passw', '0', '$selectie', '$wwwurl', '$webtitel', '$hid', '$kategorie1', '$beschrijving', '', '$firma', '$fnaam', '$voorn', '$aanspreektitel', '$adres', '$plz', '$ort', '$email')";
$selectie = mysql_query($inzet);

$sqle = "SELECT * FROM plaats WHERE ets = '$ts'";
$rese = mysql_query($sqle);
while($row = mysql_fetch_assoc($rese)) {
$eid = $row['eid']; $ets = $row['ets']; $epass = $row['epass']; $estatus = $row['estatus']; $eart = $row['eart']; $weburlc = $row['weburlc']; $etitel = $row['etitel']; $wb_cat = $row['wb_cat']; $wb_subcat = $row['wb_subcat']; $wb_beschrijving = $row['wb_beschrijving']; $escreenurl = $row['escreenurl']; $efirma = $row['efirma']; $wb_naam = $row['wb_naam']; $wb_vnaam = $row['wb_vnaam']; $wb_str = $row['wb_str']; $kladres = $row['kladres']; $wb_pc = $row['wb_pc']; $wb_pl = $row['wb_pl']; $eemail = $row['eemail'];
}



print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;De URL werd geregistreerd</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><p><b>$groet $fnaam,</b><br><br>
Uw aanmelding werd succesvol uitgevoerd. Plaats de volgende link goed zichtbaar op de start pagina van uw website. De link bijdrage wordt door ons gecontroleerd.<br><br>
Link code:<br>
<form>
<input type='text' name='code' style='width:480px;' class='tf' value='$code'>
</form>
<br>
----
</p></td></tr>
<tr><td height=100>&nbsp;</td></tr>
");

}

include("include/voetnoot.php");
mysql_close($verbinding);
?>