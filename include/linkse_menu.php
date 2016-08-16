<?php
print ("
<table width=700 border=0 cellpadding=0 cellspacing=0>
<tr>
<td width=180 valign=top>
<table width=180 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead1'>
<table width=180 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead3' height=20 align=center><b>::: Nieuwe links :::</b></td></tr>
<tr><td class='tdspace'>&nbsp;</td></tr>");
$sqln = "SELECT * FROM plaats WHERE estatus = '1' ORDER by ets DESC limit 0, 10";
$resn = mysql_query($sqln);
while($row = mysql_fetch_assoc($resn)) {
$eid = $row['eid'];
$ets = $row['ets'];
$estatus = $row['estatus'];
$eart = $row['eart'];
$weburlc = $row['weburlc'];
$etitel = $row['etitel'];
$wb_cat = $row['wb_cat'];
$wb_subcat = $row['wb_subcat'];
$wb_beschrijving = $row['wb_beschrijving'];
$escreenurl = $row['escreenurl'];
$efirma = $row['efirma'];
$wb_naam = $row['wb_naam'];
$wb_vnaam = $row['wb_vnaam'];
$wb_str = $row['wb_str'];
$kladres = $row['kladres'];
$wb_pc = $row['wb_pc'];
$wb_pl = $row['wb_pl'];
$eemail = $row['eemail'];
$link = ereg_replace("http://www.", "", $weburlc);
print ("<tr><td height=18 align=left>&nbsp;&nbsp;&nbsp;<a href='website_zien.php?siteid=$eid' target='_blank' class='a2'>$etitel</a></td></tr>");
}
print ("
<tr><td class='tdspace'>&nbsp;</td></tr>
</table>
</td></tr>
<tr><td class='tdspace'>&nbsp;</td></tr>
<tr><td>");
$sqlt = "SELECT * FROM adsense WHERE adsid = '1'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$adsid = $row['adsid'];
$adsblock1 = $row['adsblock'];
print ("$adsblock1");
}
print ("</td></tr>
<tr><td class='tdspace'>&nbsp;</td></tr>
<tr><td class='tdhead1'>
<table width=180 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdspace'>&nbsp;</td></tr>
<tr><td align=center>");
$sqlt = "SELECT * FROM adsense WHERE adsid = '2'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$adsid = $row['adsid'];
$adsblock2 = $row['adsblock'];
print ("$adsblock2");
}
print ("</td></tr>
<tr><td class='tdspace'>&nbsp;</td></tr>
</table>
</td></tr>
</table>
</td>
<td width=20>&nbsp;</td>
<td width=500 valign=top>
<table width=500 border=0 cellpadding=0 cellspacing=0>
<tr><td class='tdhead1'>
<table width=500 border=0 cellpadding=0 cellspacing=0>
<tr><td>&nbsp;</td></tr>
<tr><td valign=top height=330>
<table width=480 align=center border=0 cellpadding=0 cellspacing=0>
");

?>