<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Uw bijdrage wijzigen</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td><p>U kunt hier uw link bijdrage wijzigen...</p></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='bijdrage_wijzigen_ct.php' method='POST'>
<tr height=30><td width=160>&nbsp;</td>
<td width=320>Login met de URL en uw wachtwoord</td></tr>
<tr height=25><td>Website URL</td>
<td><input type='text' name='wwwurl' style='width:320px;' class='tf' value='http://www.'></td></tr>
<tr height=25><td>Wachtwoord</td>
<td><input type='password' name='passwort' style='width:320px;' class='tf'></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Aanmelden' style='width:320px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Wachtwoord vergeten?</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='wachtwoord_opvragen.php' method='POST'>
<tr height=25 width=160><td>Uw website URL</td>
<td width=320><input type='text' name='wwwurl' style='width:320px;' class='tf' value='http://www.'></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Wachtwoord opnieuw vragen' style='width:320px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>

");

include("include/voetnoot.php");
mysql_close($verbinding);
?>