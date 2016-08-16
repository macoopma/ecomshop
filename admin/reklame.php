<?php
include("include/database_gegevens.php");
include("include/hoofding.php");

print ("
<td width=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Adsense-Werbebl&ouml;cke</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td>Vergeet niet uw Publisher-Nummer bij google_ad_client=' ' in te vullen</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>Reklameblok 1 op de linkse zijde onder de nieuwe links (maximaal 180 pixel)</td></tr>
");
$sqlt = "SELECT * FROM adsense WHERE adsid = '1'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$adsid = $row['adsid'];
$adsblock = $row['adsblock'];
print ("
<form action='reklame_update.php?action=1' method='POST'>
<tr><td><textarea name='block' rows='10' cols='40' style='width:480px;' class='tf2'>$adsblock</textarea></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><input type='submit' value='Bevestigen' class='bt1' style='width:480;'></td></tr>
</form>
");
}


print ("<tr><td>&nbsp;</td></tr><tr><td>Reklameblok 2 op de linkse zijde onder de nieuwe links (maximaal 180 pixel)</td></tr>");
$sqlt = "SELECT * FROM adsense WHERE adsid = '2'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$adsid = $row['adsid'];
$adsblock = $row['adsblock'];
print ("
<form action='reklame_update.php?action=2' method='POST'>
<tr><td><textarea name='block' rows='10' cols='40' style='width:480px;' class='tf2'>$adsblock</textarea></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><input type='submit' value='Bevestigen' class='bt1' style='width:480;'></td></tr>
</form>
");
}
print ("<tr><td>&nbsp;</td></tr><tr><td>Reklameblok index onderaan (Maximale breedte 468 pixel)</td></tr>");
$sqlt = "SELECT * FROM adsense WHERE adsid = '3'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$adsid = $row['adsid'];
$adsblock = $row['adsblock'];
print ("
<form action='reklame_update.php?action=3' method='POST'>
<tr><td><textarea name='block' rows='10' cols='40' style='width:480px;' class='tf2'>$adsblock</textarea></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><input type='submit' value='Bevestigen' class='bt1' style='width:480;'></td></tr>
</form>
");
}

print ("</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>