<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$action = $_GET["action"];
if ($action == '1') { $head = "Start pagina wijzigen"; }
if ($action == '2') { $head = "Contact pagina wijzigen"; }
if ($action == '3') { $head = "Voorwaarden wijzigen"; }

print ("
<td width=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;$head</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>");
$sqlt = "SELECT * FROM teksten WHERE tid = '$action'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$tid = $row['tid'];
$ttext = $row['ttext'];
}

print ("
<form action='teksten_update.php?action=$action' method='POST'>
<tr><td>U kunt gebruik maken van HTML code, deze wordt automatisch aangepast naar de output op het scherm.</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<textarea name='inhalt' rows='20' cols='40' style='width:480px;' class='tf2'>$ttext</textarea>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><input type='submit' value='Bevestigen' class='bt1' style='width:480;'></td></tr>
</form>
");


print ("</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td>
");
include("include/voetnoot.php");
mysql_close($verbinding);
?>