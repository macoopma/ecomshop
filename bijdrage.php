<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
include("include/linkse_menu.php");

print ("
<tr><td class='tdhead4' height=20 align=center><b>&nbsp;&nbsp;Link toevoegen</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<p>Gelieve alles volledig in te vullen. Uw gegevens worden door ons eerst gecontroleerd.</p>
</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 border=0 cellpadding=0 cellspacing=0>
<form action='bijdrage_versturen.php' method='POST'>
<tr><td width=180 valign=top><b>U wenst</b></td>
<td width=300>
<table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='1' checked></td><td width=270><b>Standaard link</b> (gratis)</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='2'></td><td width=270 class='tdcont2'><b>Profi link ($profiprijs EUR/jaar)</b><br>
Met een extra optische achtergrond kleur</td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>
<tr><td width=30 valign=top><input type='radio' name='selectie' value='3'></td><td width=270 class='tdcont3'><b>Premium link ($premiumprijs EUR/jaar)</b><br>
Met een extra optische achtergrond kleur en een eigen afbeelding naar keuze</td></tr>
<tr><td width=30 valign=top>&nbsp;</td></tr>
</table>
</td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr height=22><td><b>Categorie</b></td><td><select name='categorie' class='tf1' style='width:300px;'>");
$sql1 = "SELECT * FROM hoofcattabel ORDER by kid";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
print ("<option value='0'>$categorie");
$sql2 = "SELECT * FROM subcategorietabel WHERE hid = '$kid' ORDER by uid ";
$res2 = mysql_query($sql2);
while($row = mysql_fetch_assoc($res2)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
print ("<option value='$uid'>&nbsp;&nbsp;--&gt; $wb_ondercat");
}
}
print ("</select></td></tr>

<tr height=22><td><b>Website URL</b></td>
<td><input type='text' name='wwwurl' style='width:300px;' class='tf' value='http://www.'></td></tr>
<tr height=22><td><b>Website titel</b></td>
<td><input type='text' name='webtitel' style='width:300px;' class='tf' value=''></td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>

<tr><td valign=top><b>Website beschrijving</b></td>
<td><textarea name='beschrijving' rows='5' cols='30' style='width:300px;' class='tf2'></textarea></td></tr>
<tr><td colspan=2 class='tdspace'>&nbsp;</td></tr>

<tr height=22><td><b>Firma</b></td>
<td><input type='text' name='firma' style='width:300px;' class='tf'></td></tr>
<tr height=22><td>&nbsp;</td>
<td><input type='radio' name='aanspreektitel' value='Heer' checked>Heer &nbsp;&nbsp;<input type='radio' name='aanspreektitel' value='Mevrouw'>Mevrouw</td></tr>
<tr height=22><td><b>Naam</b></td>
<td><input type='text' name='fnaam' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td><b>Voornaam</b></td>
<td><input type='text' name='voorn' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td><b>Straat en huisnummer</b></td>
<td><input type='text' name='adres' style='width:300px;' class='tf' value=''></td></tr>
<tr height=22><td><b>Postcode - woonplaats</b></td>
<td><table width=300 border=0 cellpadding=0 cellspacing=0>
<tr><td width=80><input type='text' name='plz' style='width:75px;' class='tf' value=''></td>
<td width=220><input type='text' name='ort' style='width:220px;' class='tf' value=''></td>
</tr></table></td></tr>
<tr height=22><td><b>E-mail</b></td>
<td><input type='text' name='email' style='width:300px;' class='tf' value=''></td></tr>
<tr height=30><td>&nbsp;</td>
<td><input type='submit' name='vveerr' value='Versturen' style='width:300px;' class='bt1'></td></tr>
</form>
</table>
</td></tr>
");

include("include/voetnoot.php");
mysql_close($verbinding);
?>