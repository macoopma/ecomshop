<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

<table border="0" cellspacing="0" cellpadding="2" class="moduleSearch">
<tr>
<td height='<?php print $hauteurTitreModule;?>' class="moduleSearchTitre contentTop" colspan="2" align="center" style="width:<?php print $larg_rub;?>">
   <a href="zoeken.php?action=search"><img src="im/lang<?php print $_SESSION['lang'];?>/menu_search.png" border="0" title="<?php print RECHERCHER;?>" alt="<?php print RECHERCHER;?>"></a>
</td>
</tr>
<tr>
<td><div><img src='im/zzz.gif' height="1" width="1"></div></td>
</tr><tr>
<td colspan="2">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<?php


$openUseSearch = "<a href='#' class='tooltip'><b><span class='darkBackground'>&nbsp;?&nbsp;</span></b><em style='width:375px; left:30px'>".AIDE."</em></a>";

print "<form action='includes/redirectzoeken.php' method='post'>";
    if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch']=="on") {
        print "<td colspan='3'>";
        include('zoeken_uitgebreid.php');
        print "</td><td width='1'>&nbsp;</td></tr><tr>";
    }
print "<td align='left' valign='bottom'>";
print "<img src='im/fleche_right.gif'><img src='im/zzz.gif' width='4' height='1'>";
print "<input type='text' name='search_query' maxlength='100' style='width:90%;'>";
print "</td>";
print "<td width='1' align='right' valign='bottom'>";
print "<input type='image' src='im/search.gif' alt='".RECHERCHER."' style='background:none; border:0px'>";
print "</td>";
print "<td width='1'>&nbsp;</td>";
print "</form>";
?>
</tr>
</table>


</td>
</tr>
<tr>
<td valign='bottom' align='left'>&nbsp;<?php print $openUseSearch;?></td>
<?php
if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch'] == "off") {
	print "<td valign='bottom' align='right'><a href='".$url_id10.$slash."AdSearch=on'><span class='tiny fontgris' style='font-size:10px'>".RECHERCHE_AVANCEE."</span></a>&nbsp;</td>";
}
if(isset($_SESSION['AdSearch']) AND $_SESSION['AdSearch'] == "on") {
	print "<td valign='bottom' align='right'><a href='".$url_id10.$slash."AdSearch=off'><img src='includes/menu/minus.gif' align='absmiddle' border='0'></a>&nbsp;</td>";
}
if(!isset($_SESSION['AdSearch'])) {
	print "<td valign='bottom' align='right'><a href='".$url_id10.$slash."AdSearch=on'><span class='tiny' style='font-size:10px'>".RECHERCHE_AVANCEE."</span></a>&nbsp;</td>";
}
?>
</tr>
</table>

</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
