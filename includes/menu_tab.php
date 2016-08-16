<!-- START TAB -->
<?php
if(isset($_GET['module']) and $_GET['module'] == "menu_cart") {$class = "class='here'";} else {$class = "";}
if(isset($_GET['module']) and $_GET['module'] == "your_account") {$class1 = "class='here'";} else {$class1 = "";}
if(isset($_GET['module']) and $_GET['module'] == "search") {$class2 = "class='here'";} else {$class2 = "";}
if(isset($_GET['module']) and $_GET['module'] == "affiliation") {$class3 = "class='here'";} else {$class3 = "";}
if(isset($_GET['module']) and $_GET['module'] == "menu_subscribe") {$class4 = "class='here'";} else {$class4 = "";}
if(isset($_GET['module']) and $_GET['module'] == "promo") {$class5 = "class='here'";} else {$class5 = "";}
if(isset($_GET['module']) and $_GET['module'] == "new") {$class6 = "class='here'";} else {$class6 = "";}
if(isset($_GET['module']) and $_GET['module'] == "top10") {$class7 = "class='here'";} else {$class7 = "";}
if(isset($_GET['module']) and $_GET['module'] == "menu_interface") {$class8 = "class='here'";} else {$class8 = "";}
if(isset($_GET['module']) and $_GET['module'] == "menu_languages") {$class9 = "class='here'";} else {$class9 = "";}
if(isset($_GET['module']) and $_GET['module'] == "menu_quick") {$class10 = "class='here'";} else {$class10 = "";}
if(isset($_GET['module']) and $_GET['module'] == "menu_converter") {$class11 = "class='here'";} else {$class11 = "";}
if(isset($_GET['module']) and $_GET['module'] == "info4") {$class12 = "class='here'";} else {$class12 = "";}
if(isset($_GET['module']) and $_GET['module'] == "info5") {$class13 = "class='here'";} else {$class13 = "";}
if(isset($_GET['module']) and $_GET['module'] == "flash") {$class14 = "class='here'";} else {$class14 = "";}
if(isset($_GET['module']) and $_GET['module'] == "favorite") {$class15 = "class='here'";} else {$class15 = "";}
if(preg_match("/\bcataloog.php\b/i", $_SERVER['PHP_SELF'])) {$class250 = "class='here'";} else {$class250 = "";} 

$spacer = '<img src="im/zzz.gif" border="0" width="1" height="5">';


function recurs($origin) {
                $findOriginQuery = mysql_query("SELECT categories_id, parent_id, categories_name_1 FROM categories WHERE categories_id = '".$origin."'");
                $findOrigin = mysql_fetch_array($findOriginQuery);
                if($findOrigin['parent_id'] == "0") {
                    $jj = $findOrigin['categories_id'];
                    return $jj;
                }
                else {
                   $jjw = recurs($findOrigin['parent_id']);
                    return $jjw;
                }
}
 
            $menuTabQuery = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, cat_or_subcat, parent_id
                                         FROM categories
                                         WHERE categories_visible = 'yes'
                                         AND parent_id = '0'
                                         ORDER BY categories_order");
            $menuTabNum = mysql_num_rows($menuTabQuery);
?>
      <table width="99%" border="0" align="center" border="0" cellspacing="0" cellpadding="0" class="colorBackgroundTableMenuTab">
      <tr>
      <td align="center" valign="middle">

<?php
 
if($menuTabCenter=="oui") {
   $_center1Tab="center";
   $_center2Tab="center";
}
else {
   $_center1Tab="left";
   $_center2Tab="left";
}
?>
<div align='<?php print $_center1Tab;?>'>
<table border='0' cellspacing='0' cellpadding='0' align='<?php print $_center2Tab;?>'><tr><td>

<div align='center' id="tab">
<?php
if(isset($_GET['path']) and $_GET['path'] == "0") {$class = "class='here'";} else {$class = "";}
 
print "<a ".$class250." href='cataloog.php'><span><b>".maj(HOME)."</b></span></a>";
$i=0;
if($menuTabNum>0) {
	while($menuTab = mysql_fetch_array($menuTabQuery)) {
	               $i=$i+1;
                 if(($i % $nbreMenuTab) == 0) {
                    print "<br style='clear:left'>".$spacer."<br>";
                }
                 
                 if(isset($_GET['path']) and $_GET['path'] == $menuTab['categories_id']) {
                    $class = "class='here'";
                 }
                 else {
                    $class = "";
                 }
                 
                 if((isset($_GET['path']) and $_GET['path']!== "0")) {
                    $bb = recurs($_GET['path']);

                    if($menuTab['categories_id'] == $bb) {
                        $class = "class='here'";
                    }
                    else {
                        $class = "";
                    }
                }
                                         
                                        $findOriginQuery2 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                                                                        FROM categories
                                                                        WHERE categories_id = '".$menuTab['categories_id']."'
                                                                        ");

                                            $findOrigin2 = mysql_fetch_array($findOriginQuery2);
                                            $findCatName2 = $findOrigin2['categories_name_'.$_SESSION['lang']];
                                            $menuNum2 = $_SESSION['tree2'][$findCatName2];
 
      print "<a ".$class." href='categories.php?path=".$menuTab['categories_id']."&num=".$menuNum2."&action=e'><span><b>".$menuTab['categories_name_'.$_SESSION['lang'].'']."</b></span></a>";
  }
}
 
if($menuNewsVisible == "oui" AND isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
        print "<a ".$class6." href='list.php?target=new&module=new'><span><b>".NOUVEAUTESMAJ."</b></span></a>";
}
 
if($menuPromoVisible == "oui" AND isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {
        print "<a ".$class5." href='list.php?target=promo&module=promo'><span><b>".maj(PROMOTIONS)."</b></span></a>";
}
?>
</div>

</td></tr></table>
</div>
      </td>
      </tr>
      <tr>
<?php
if($menuVisibleTab=="oui") {
  print "<td colspan='3' class='borderHeightBottomMenuTabYes'><img src='im/zzz.gif' width='1' height='0'></td>";
}
else {
  print "<td colspan='3'><img src='im/zzz.gif' width='1' height='0'></td>";
}
?>
      </tr>
      </table>
<!-- END TAB1 -->

<!--
      <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0">
      <tr>
      <td width="4"><img src="im/zzz.gif" width="4" height="1"></td>
      <td align="center" valign="middle">
        <div id="tab">
          <a <?php print $class;?> href="pagina_box.php?module=menu_cart"><span><?php print VOTRE_CADDIE;?></span></a> 
          <a <?php print $class1;?> href="mijn_account.php?module=your_account"><span><?php print VOTRE_COMPTE;?></span></a>
          <a <?php print $class2;?> href="zoeken.php?action=search&module=search"><span><?php print RECHERCHER;?></span></a>
          <a <?php print $class3;?> href="affiliation.php?module=affiliation"><span><?php print AFFILIATION;?></span></a>
          <a <?php print $class4;?> href="pagina_box.php?module=menu_subscribe"><span><?php print NEWSLETTER;?></span></a>
          <a <?php print $class5;?> href="list.php?target=promo&module=promo"><span><?php print PROMOTIONS;?></span></a>
          <a <?php print $class6;?> href="list.php?target=new&module=new"><span><?php print NOUVEAUTES;?></span></a>
        </div>
      </td></tr><tr><td><img src="im/zzz.gif" width="4" height="1"></td>
      <td align="center" valign="middle">
        <div id="tab">
          <a <?php print $class7;?> href="top10.php?module=top10"><span>Top 10</span></a>
          <a <?php print $class8;?> href="pagina_box.php?module=menu_interface"><span><?php print INTERFACEW;?></span></a>
          <a <?php print $class9;?> href="pagina_box.php?module=menu_languages"><span><?php print LANGUES;?></span></a>
          <a <?php print $class10;?> href="pagina_box.php?module=menu_quick"><span><?php print MENU_RAPIDE;?></span></a> 
          <a <?php print $class11;?> href="pagina_box.php?module=menu_converter"><span><?php print CONVERTISSEUR;?></span></a>
          <a <?php print $class12;?> href="infos.php?info=4&module=info4"><span><?php print CONDITIONS_DE_VENTE;?></span></a>
          <a <?php print $class13;?> href="infos.php?info=5&module=info5"><span><?php print NOUS_CONTACTER;?></span></a>
          <a <?php print $class14;?> href="list.php?target=promo&tow=flash&module=flash"><span><?php print VENTES_FLASH;?></span></a>
          <a <?php print $class15;?> href="list.php?target=favorite&module=favorite"><span><?php print COEUR;?></span></a>
          </div>
      </td>
      </tr>
      </table>
-->
