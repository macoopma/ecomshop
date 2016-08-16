<?php
include("include/database_gegevens.php");
include("include/hoofding.php");

$firma = $_POST["firma"];
$fnaam = $_POST["fnaam"];
$voorn = $_POST["voorn"];
$adres = $_POST["adres"];
$plz = $_POST["plz"];
$ort = $_POST["ort"];
$telefon = $_POST["telefon"];
$email = $_POST["email"];
$url = $_POST["url"];
$admurl = $_POST["admurl"];

$wijzig = "UPDATE admin SET afirma = '$firma' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET aname = '$fnaam' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_vnaam = '$voorn' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_schrijf = '$adres' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_pc = '$plz' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_pl = '$ort' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_tel = '$telefon' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET aemail = '$email' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_url = '$url' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admins_url = '$admurl' WHERE aid = '1'"; $upd = mysql_query($wijzig);

$sql1 = "SELECT * FROM admin";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$admin1 = $row['admin'];
$adminpass1 = $row['adminpass'];
$afirma1 = $row['afirma'];
$aname1 = $row['aname'];
$avorname1 = $row['admin_vnaam'];
$aanschrift1 = $row['admin_schrijf'];
$aplz1 = $row['admin_pc'];
$aort1 = $row['admin_pl'];
$atelefon1 = $row['admin_tel'];
$aemail1 = $row['aemail'];
$apaypal1 = $row['apaypal'];
$astnr1 = $row['astnr'];
$austid1 = $row['austid'];
$afinanzamt1 = $row['admin_pin'];
$aktoinh1 = $row['admin_pincode'];
$akonto1 = $row['admin_rek'];
$ablz1 = $row['admin_bank'];
$abank1 = $row['abank'];
$aiban1 = $row['admin_iban'];
$aswift1 = $row['admin_swift'];
$aurl1 = $row['admin_url'];
$adminurl1 = $row['admins_url'];
}

print ("
<td width=500 height=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;De gegevens werden succesvol bewaard</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<form action='adminupdate.php' method='POST'>
<tr height=22><td width=180>Firma</td>
<td width=300><input type='text' name='firma' style='width:300px;' class='tf' value='$afirma1'></td></tr>
<tr height=22><td>Naam</td>
<td><input type='text' name='fnaam' style='width:300px;' class='tf' value='$aname1'></td></tr>
<tr height=22><td>Voornaam</td>
<td><input type='text' name='voorn' style='width:300px;' class='tf' value='$avorname1'></td></tr>
<tr height=22><td>Adres</td>
<td><input type='text' name='adres' style='width:300px;' class='tf' value='$aanschrift1'></td></tr>
<tr height=22><td>Woonplaats</td>
<td>
<table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=80><input type='text' name='plz' style='width:75px;' class='tf' value='$aplz1'></td>
<td width=220><input type='text' name='ort' style='width:220px;' class='tf' value='$aort1'></td></tr>
</table>
</td></tr>
<tr height=22><td>Telefoon</td>
<td><input type='text' name='telefon' style='width:300px;' class='tf' value='$atelefon1'></td></tr>
<tr height=22><td>E-mail adres</td>
<td><input type='text' name='email' style='width:300px;' class='tf' value='$aemail1'></td></tr>
<tr><td class='tdspace' colspan=2>&nbsp;</td></tr>
<tr height=22><td>Website URL</td>
<td><input type='text' name='url' style='width:300px;' class='tf' value='$aurl1'></td></tr>
<tr height=22><td>Administrator URL</td>
<td><input type='text' name='admurl' style='width:300px;' class='tf' value='$adminurl1'></td></tr>
<tr><td class='tdspace' colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Bevestigen' style='width:300px;' class='bt1'></td></tr>
<tr><td colspan=2 height=30>&nbsp;</td></tr>
<tr><td colspan=2><b>banner</td></tr>
</form>
</table>
</td></tr>

</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>