<?php
include("include/database_gegevens.php");
include("include/hoofding.php");

print ("
<td width=500 height=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Betaal informatie voor uw klanten</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<form action='betaal_update.php' method='POST'>
<tr height=22><td width=180>Eigenaar</td>
<td width=300><input type='text' name='ktoinh' style='width:300px;' class='tf' value='$admin_pincode'></td></tr>
<tr height=22><td>Naam van de bank</td>
<td><input type='text' name='bank' style='width:300px;' class='tf' value='$abank'></td></tr>
<tr height=22><td>IBAN code</td>
<td><input type='text' name='iban' style='width:300px;' class='tf' value='$admin_iban'></td></tr>
<tr height=22><td>BIC code</td>
<td><input type='text' name='swift' style='width:300px;' class='tf' value='$admin_swift'></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr height=22><td>Paypal account</td>
<td><input type='text' name='paypal' style='width:300px;' class='tf' value='$apaypal'></td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Bevestigen' style='width:300px;' class='bt1'></td></tr>
<tr><td colspan=2 height=30>&nbsp;</td></tr>

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