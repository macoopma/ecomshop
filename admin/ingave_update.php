<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$id = $_GET["id"];
$action = $_GET["action"];

if ($action == '1') {
$text = "De bijdrage werd succesvol vrij geschakeld";
$wijzig = "UPDATE plaats SET estatus = '1' WHERE eid = '$id'";
$upd = mysql_query($wijzig);
$sqle = "SELECT * FROM plaats WHERE eid = '$id'";
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

$eintragen1 = "INSERT INTO `tophits` (`tophitsid`, `tophitsout`) VALUES ('$id', '0')";
$eintrag1 = mysql_query($eintragen1);
$eintragen2 = "INSERT INTO `topstemmen` (`topvoteid`, `topvoteerg`) VALUES ('$id', '0')";
$eintrag2 = mysql_query($eintragen2);

$betreff="Uw link werd vrij geschakeld";
$text1="Uw bijdrage werd succesvol vrij geschakeld. Wanneer je uw bijdrage wenst te wijzigen, kunt u zich aanmelden met de volgende gegevens:\n\nURL: $weburlc\nWachtwoord: $epass\n\nMet vriendelijke groeten\n$afirma\n$admin_url\n$aemail";
mail($eemail, $betreff, $text1, "FROM: $ulink <$aemail>");

}
if ($action == '2') {
$text = "De bijdrage werd succesvol verwijderd";
$loeschen = "DELETE FROM plaats WHERE eid = '$id'";
$loesch = mysql_query($loeschen);

$loeschen = "DELETE FROM tophits WHERE eid = '$id'";
$loesch = mysql_query($loeschen);
$loeschen = "DELETE FROM topstemmen WHERE eid = '$id'";
$loesch = mysql_query($loeschen);
}
if ($action == '3') {
$text = "De bijdrage werd succesvol geblokkeerd maar kan wel op elk ogenblik opnieuw vrij geschakeld worden";
$wijzig = "UPDATE plaats SET estatus = '2' WHERE eid = '$id'";
$upd = mysql_query($wijzig);
$loeschen = "DELETE FROM tophits WHERE eid = '$id'";
$loesch = mysql_query($loeschen);
$loeschen = "DELETE FROM topstemmen WHERE eid = '$id'";
$loesch = mysql_query($loeschen);

}

print ("
<td width=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Details</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td align=center>$text</td></tr>
</table>
</td></tr>
</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>