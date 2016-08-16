<?php
include("include/database_gegevens.php");
include("include/hoofding.php");

print ("
<td width=500 height=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Prijzen instellen</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<form action='prijzen_update.php' method='POST'>
<tr height=25><td width=180><b>Standaard link</b></td>
<td width=200>Gratis</td><td width=100>&nbsp;</td></tr>
<tr height=25><td><b>Pro link</b></td>
<td><input type='text' name='profi' style='width:200px;' class='tf' value='$profiprijs'></td><td>&nbsp;&nbsp;per jaar</td></tr>
<tr height=25><td><b>Premium link</b></td>
<td><input type='text' name='premium' style='width:200px;' class='tf' value='$premiumprijs'></td><td>&nbsp;&nbsp;per jaar</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Bevestigen' style='width:200px;' class='bt1'></td><td>&nbsp;</td></tr>
</form>
</table>
</td></tr>
</table>
</td>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>