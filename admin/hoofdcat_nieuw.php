<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$hkat = $_POST["hkat"];

$inzet = "INSERT INTO hoofcattabel (kid, categorie) VALUES ('', '$hkat')";
$selectie = mysql_query($inzet);

print ("
<td width=500 height=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Hoofd categories</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<form action='hoofdcat_nieuw.php' method='POST'>
<tr height=20><td width=360><b>Categorie toevoegen</b></td><td width=120 align=right>&nbsp;</td></tr>
<tr height=25>
<td><input type='text' name='hkat' style='width:360px;' class='tf' value=''></td>
<td align=right><input type='image' name='submit' value='submit' src='img/bewaren.png' border='0' onFocus='if (this.blur) this.blur()'></td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr height=20><td width=360><b>Categorie wijzigen</b></td><td width=120 align=right>&nbsp;</td></tr>
</form>
</table>
</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>");
$sql1 = "SELECT * FROM hoofcattabel ORDER by categorie";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
print ("
<form action='hoofdcat_update.php?hkatid=$kid' method='POST'>
<tr height=25>
<td width=240><input type='text' name='hkat' style='width:240px;' class='tf' value='$categorie'></td>
<td width=120 align=right><input type='image' name='submit' value='submit' src='img/wijzigen.png' border='0' onFocus='if (this.blur) this.blur()'></td>
<td width=120 align=right><a href='hoofdcat_wissen.php?hkatid=$kid'><img src='img/verwijderen.png' width='110' height='16' border='0'></a></td>
</tr>
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