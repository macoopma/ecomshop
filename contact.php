<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Contacteer ons</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='contact_versturen.php' method='POST'>
<tr><td width=180>&nbsp;</td><td width=300>&nbsp;</td></tr>
<tr height=22 width=180><td><b>Firma:</b></td>
<td width=300><input type='text' name='firma' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td>&nbsp;</td>
<td><input type='radio' name='aanspreektitel' value='Heer' checked>Heer &nbsp;&nbsp;<input type='radio' name='aanspreektitel' value='Mevrouw'>Mevrouw</td></tr>
<tr height=22><td><b>Naam *</b></td>
<td><input type='text' name='fnaam' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td><b>Voornaam *</b></td>
<td><input type='text' name='voorn' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td><b>Straat en huisnummer</b></td>
<td><input type='text' name='adres' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td><b>Postcode - woonplaats</b></td>
<td><table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=80><input type='text' name='plz' style='width:75px;' class='tf' value=''></td>
<td width=220><input type='text' name='ort' style='width:220px;' class='tf' value=''></td>
</tr></table></td></tr>
<tr height=22><td><b>E-mail *</b></td>
<td><input type='text' name='email' style='width:300px;' class='tf' value=''></td></tr>
<tr><td valign=top><b>Uw bericht *</b></td>
<td><textarea name='nachricht' rows='5' cols='30' style='width:300px;' class='tf2'></textarea></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Versturen' style='width:300px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>