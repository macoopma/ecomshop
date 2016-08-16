<?php
$ts = time();
$ip = $_SERVER['REMOTE_ADDR'];
$siteid = $_GET['siteid'];
include("include/database_gegevens.php");
$sqle = "SELECT * FROM plaats WHERE eid = '$siteid'";
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
$link = $weburlc;
}
$inzet = "INSERT INTO `hits` (`hitsid`, `hitsts`, `hitseid`, `hitsip`) VALUES ('', '$ts', '$siteid', '$ip')";
$selectie = mysql_query($inzet);

$sqlh = "SELECT * FROM hits WHERE hitseid = '$siteid'";
$resh = mysql_query($sqlh);
$x = mysql_num_rows($resh);

$wijzig = "UPDATE tophits SET tophitsout = '$x' WHERE tophitsid = '$siteid'";
$upd = mysql_query($wijzig);

mysql_close($verbinding);
header ("Location: $link");
?>