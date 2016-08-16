<?php
include("include/database_gegevens.php");
include("include/hoofding.php");
$hkatid = $_GET["hkatid"];
$subkat = $_POST["subkat"];

$inzet = "INSERT INTO subcategorietabel (uid, hid, wb_ondercat) VALUES ('', '$hkatid', '$subkat')";
$selectie = mysql_query($inzet);

$sql1 = "SELECT * FROM hoofcattabel WHERE kid = '$hkatid'";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$kid = $row['kid'];
$categorie = $row['categorie'];
}
print ("
<td width=500 height=500 valign=top class='tdhead1'>
<table width=500 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>&nbsp;&nbsp;Sub categories</b></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
<form action='subcat_nieuw.php?hkatid=$hkatid' method='POST'>
<tr height=20><td width=360><b>Nieuwe sub categorie voor &quot;$categorie&quot;:</b></td><td width=120 align=right>&nbsp;</td></tr>
<tr height=25>
<td><input type='text' name='subkat' style='width:360px;' class='tf' value=''></td>
<td align=right><input type='image' name='submit' value='submit' src='img/bewaren.png' border='0' onFocus='if (this.blur) this.blur()'></td>
</tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr height=20><td width=360><b>Sub categorie in &quot;$categorie&quot; wijzigen</b></td><td width=120 align=right>&nbsp;</td></tr>
</form>
</table>
</td></tr>
<tr><td>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>");
$sql1 = "SELECT * FROM subcategorietabel WHERE hid = '$hkatid' ORDER by wb_ondercat";
$res1 = mysql_query($sql1);
while($row = mysql_fetch_assoc($res1)) {
$uid = $row['uid'];
$hid = $row['hid'];
$wb_ondercat = $row['wb_ondercat'];
print ("
<form action='subcat_update.php?subkatid=$uid&hkatid=$hkatid' method='POST'>
<tr height=25>
<td width=240><input type='text' name='subkat' style='width:240px;' class='tf' value='$wb_ondercat'></td>
<td width=120 align=right><input type='image' name='submit' value='submit' src='img/wijzigen.png' border='0' onFocus='if (this.blur) this.blur()'></td>
<td width=120 align=right><a href='subcat_wissen.php?subkatid=$uid&hkatid=$hkatid'><img src='img/verwijderen.png' width='110' height='16' border='0'></a></td>
</tr>
</form>
");
}

print ("<tr><td>&nbsp;</td></tr>
</table>
</td>");

include("include/voetnoot.php");
mysql_close($verbinding);
?>