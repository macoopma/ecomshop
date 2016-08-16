<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

         <table border="0" cellspacing="0" cellpadding="2" class="moduleTop10">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' class="moduleTop10Titre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <?php print "<a href='".seoUrlConvert("top10.php")."'>";?><img src="im/lang<?php print $_SESSION['lang'];?>/menu_top10.png" border="0" title="<?php print DIX_MEILLEURES_VENTES;?>" alt="<?php print DIX_MEILLEURES_VENTES;?>"></a>
         </td>
         </tr>
         <tr>
         <td>
<?php


$hids = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_id, p.products_viewed, p.categories_id, p.products_qt, c.categories_name_".$_SESSION['lang'].",  IF(s.products_id<>'null', 'oui','non') as toto
                     FROM products AS p
                     LEFT JOIN categories AS c ON(p.categories_id = c.categories_id)
                     LEFT JOIN specials AS s ON(p.products_id = s.products_id)
                     WHERE p.products_ref != 'GC100'
                     AND p.products_visible='yes'
                     ORDER BY p.products_viewed
                     DESC
                     LIMIT 0,5
                     ");

print "<table width='100%' border='0' cellpadding='1' cellspacing='0' align='left'>";

while ($myhid = mysql_fetch_array($hids)) {
     if($myhid['products_qt']>0) {
        print "<tr><td>";
        print "<img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("beschrijving.php?id=".$myhid['products_id']."&path=".$myhid['categories_id'])."' ".display_title($myhid['products_name_'.$_SESSION['lang']],$maxCarInfo).">".adjust_text($myhid['products_name_'.$_SESSION['lang']],$maxCarInfo,"..")."</a>";
        print "</td>";
        print "</tr>";
     }
     else {
        if($actRes=="oui") {
            print "<tr><td>";
            print "<img src='im/fleche_right.gif'>&nbsp;<a href='".seoUrlConvert("beschrijving.php?id=".$myhid['products_id']."&path=".$myhid['categories_id'])."' ".display_title($myhid['products_name_'.$_SESSION['lang']],$maxCarInfo).">".adjust_text($myhid['products_name_'.$_SESSION['lang']],$maxCarInfo,"..")."</a>";
            print "</td>";
            print "</tr>"; 
        }
        else {
            print "<tr><td><img src='im/fleche_right.gif'>&nbsp;".adjust_text($myhid['products_name_'.$_SESSION['lang']],$maxCarInfo,"..")."</td></tr>";
        }
      }
}
print "</table>";

?>
        </td>
        </tr>
        <tr>
		<td>
		<?php print "<div align='center'><a href='".seoUrlConvert("top10.php")."'><img src='im/plus.gif' border='0' align='absmiddle' title='".AUTRES."' alt='".AUTRES."'></a></div>";?>
        </td>
        </tr>
        </table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
