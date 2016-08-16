<?php

// Bekijk de handleiding om deze gegevens in te vulle

$server = "localhost";
$user = "webhouse_maik";
$pass = "090909";
$database = "webhouse_maik";


// vanf hier niets meer wijzigen
// vanf hier niets meer wijzigen
// vanf hier niets meer wijzigen

$verbinding = mysql_connect($server,$user,$pass) or die ("Er is geen connectie met de database.");
mysql_select_db($database) or die ("De database werd niet gevonden.");

$sqla = "SELECT * FROM admin";
$resa = mysql_query($sqla);
while($row = mysql_fetch_assoc($resa)) {
$admin = $row['admin'];
$adminpass = $row['adminpass'];
$afirma = $row['afirma'];
$aname = $row['aname'];
$admin_vnaam = $row['admin_vnaam'];
$admin_schrijf = $row['admin_schrijf'];
$admin_pc = $row['admin_pc'];
$admin_pl = $row['admin_pl'];
$admin_tel = $row['admin_tel'];
$aemail = $row['aemail'];
$apaypal = $row['apaypal'];
$astnr = $row['astnr'];
$austid = $row['austid'];
$admin_pin = $row['admin_pin'];
$admin_pincode = $row['admin_pincode'];
$admin_rek = $row['admin_rek'];
$admin_bank = $row['admin_bank'];
$abank = $row['abank'];
$admin_iban = $row['admin_iban'];
$admin_swift = $row['admin_swift'];
$admin_url = $row['admin_url'];
$admins_url = $row['admins_url'];
}
$sqlp1 = "SELECT * FROM prijzen WHERE wb_prijsid = '1'";
$resp1 = mysql_query($sqlp1);
while($row = mysql_fetch_assoc($resp1)) {
$wb_prijsid = $row['wb_prijsid'];
$wb_prijsaan = $row['wb_prijsaan'];
$profiprijs = $wb_prijsaan;
}
$sqlp2 = "SELECT * FROM prijzen WHERE wb_prijsid = '2'";
$resp2 = mysql_query($sqlp2);
while($row = mysql_fetch_assoc($resp2)) {
$wb_prijsid = $row['wb_prijsid'];
$wb_prijsaan = $row['wb_prijsaan'];
$premiumprijs = $wb_prijsaan;
}
?>