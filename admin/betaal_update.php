<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$ktoinh = $_POST["ktoinh"]; $konto = $_POST["konto"]; $blz = $_POST["blz"];
$bank = $_POST["bank"]; $iban = $_POST["iban"]; $swift = $_POST["swift"];
$paypal = $_POST["paypal"]; $stnr = $_POST["stnr"]; $ustid = $_POST["ustid"];
$finanzamt = $_POST["finanzamt"];

$wijzig = "UPDATE admin SET admin_pincode = '$ktoinh' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_rek = '$konto' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_bank = '$blz' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET abank = '$bank' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_iban = '$iban' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_swift = '$swift' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET apaypal = '$paypal' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET astnr = '$stnr' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET austid = '$ustid' WHERE aid = '1'"; $upd = mysql_query($wijzig);
$wijzig = "UPDATE admin SET admin_pin = '$finanzamt' WHERE aid = '1'"; $upd = mysql_query($wijzig);

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
<tr><td class='tdhead3' height=20 align=center><b>Betaal informatie voor uw klanten</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<form action='betaal_update.php' method='POST'>
<tr height=22><td width=180>Eigenaar</td>
<td width=300><input type='text' name='ktoinh' style='width:300px;' class='tf' value='$aktoinh1'></td></tr>
<tr height=22><td>Naam van de bank</td>
<td><input type='text' name='bank' style='width:300px;' class='tf' value='$abank1'></td></tr>
<tr height=22><td>IBAN code</td>
<td><input type='text' name='iban' style='width:300px;' class='tf' value='$aiban1'></td></tr>
<tr height=22><td>BIC code</td>
<td><input type='text' name='swift' style='width:300px;' class='tf' value='$aswift1'></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr height=22><td>Paypal account</td>
<td><input type='text' name='paypal' style='width:300px;' class='tf' value='$apaypal1'></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Bevestigen' style='width:300px;' class='bt1'></td></tr>
<tr><td colspan=2 height=30>&nbsp;</td></tr>
<tr><td></td></tr>
</form>
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>