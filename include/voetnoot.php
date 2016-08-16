<?php
print ("
</table>
</td></tr>
<tr><td>&nbsp;</td></tr>
</table>
</td></tr>
<tr><td class='tdspace'>&nbsp;</td></tr>
<tr><td class='tdhead1' align=center>
<table width=468 align=center border=0 cellpadding=0 cellspacing=0>
<tr><td height=80>");
$sqlt = "SELECT * FROM adsense WHERE adsid = '3'";
$rest = mysql_query($sqlt);
while($row = mysql_fetch_assoc($rest)) {
$adsid = $row['adsid'];
$adsblock3 = $row['adsblock'];
print ("$adsblock3");
}
print ("</td></tr>
</table>
</td></tr>
<tr><td class='tdspace'>&nbsp;</td></tr>
<tr><td class='tdhead1' align=center>&copy; $admin_url</td></tr>
</table>
</td>
</tr>
</table>
</center>
</body>
</html>
");

?>