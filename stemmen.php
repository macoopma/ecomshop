<?php
include("include/database_gegevens.php");
$ts = time();
$ip = $_SERVER['REMOTE_ADDR'];
$siteid = $_GET['siteid'];
$stemmen = $_POST['stemmen'];

$inzet = "INSERT INTO `stemmen` (`vid`, `vts`, `veid`, `vnote`, `vip`) VALUES ('', '$ts', '$siteid', '$stemmen', '$ip')";
$selectie = mysql_query($inzet);

$note = 0;
$sqlv = "SELECT * FROM stemmen WHERE veid = '$siteid'";
$resv = mysql_query($sqlv);
$votezahl = mysql_num_rows($resv);
$sqlv = "SELECT * FROM stemmen WHERE veid = '$siteid'";
$resv = mysql_query($sqlv);
while($row = mysql_fetch_assoc($resv)) {
$vnote = $row['vnote'];
$note = $note + $vnote;
}
if ($note != '0') {
$durchschn = $note / $votezahl;
$durchschnitt = ceil($durchschn);
}
else { $durchschnitt = "0"; }

$wijzig = "UPDATE topstemmen SET topvoteerg = '$durchschnitt' WHERE topvoteid = '$siteid'";
$upd = mysql_query($wijzig);

include("include/hoofding.php");
include("include/linkse_menu.php");

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Beste dank...</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Uw stem werd opgeslagen.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 bgcolor='#EEEEEE' border=0 cellpadding=0 cellspacing=0 style='border: 1px solid #000000;'>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=460 align=center border=0 cellpadding=0 cellspacing=0>
<tr>
<td width=240>
<table width=240 border=0 cellpadding=0 cellspacing=0>");
$sql1 = "SELECT * FROM hoofcattabel ORDER by kid limit 0,11";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
$sql1a = "SELECT * FROM plaats WHERE wb_cat = '$kid' AND estatus = '1'";
$res1a = mysql_query($sql1a);
$anzahl1 = mysql_num_rows($res1a);
print ("
<tr height=20><td width=10>&nbsp;</td><td width=20><img src='img/add-item.gif' width='14' height='14' border='0'></td>
<td width=200><a href='kat.php?kat=$kid'>$categorie ($anzahl1)</a></td></tr>
");
}
print ("</table>
</td>
<td width=240>
<table width=240 border=0 cellpadding=0 cellspacing=0>");
$sql1 = "SELECT * FROM hoofcattabel ORDER by kid limit 11,22";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
$sql1b = "SELECT * FROM plaats WHERE wb_cat = '$kid' AND estatus = '1'";
$res1b = mysql_query($sql1b);
$anzahl2 = mysql_num_rows($res1b);
print ("
<tr height=20><td width=10>&nbsp;</td><td width=20><img src='img/add-item.gif' width='14' height='14' border='0'></td>
<td width=200><a href='kat.php?kat=$kid'>$categorie ($anzahl2)</a></td></tr>
");
}
print ("
</table>
</td>
</tr>
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td></tr>
");



include("include/voetnoot.php");

mysql_close($verbinding);
?>