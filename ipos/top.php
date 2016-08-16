<?php
$resultSSS = mysql_query("SELECT * FROM categories
                            WHERE categories_visible = 'yes'
                            AND categories_id != '0'
                            ORDER BY categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$id = 0;
$i = 0;
while($message = mysql_fetch_array($resultSSS))
{

$result2SSSS = mysql_query("SELECT * FROM products
                       WHERE categories_id = '".$message['categories_id']."' OR (categories_id_bis LIKE '%|".$message['categories_id']."|%' AND products_visible = 'yes')
                       ORDER BY products_name_".$_SESSION['lang']."");
$productsNum = mysql_num_rows($result2SSSS);

$papa = $message['categories_id'];
$fils = $message['parent_id'];
$visible = $message['categories_visible'];
$noeud = $message['categories_noeud'];
if($message['categories_visible']=='yes') $visible="yes"; else $visible="<span color=red><b>No</b></span>";
if($message['categories_noeud']=="B") $titre = $message['categories_name_'.$_SESSION['lang']]; else $titre=$message['categories_name_'.$_SESSION['lang']];

$dataIpos[] = array($papa,$fils,$titre,$visible,$noeud);
}

function espace3a($rang3) {
  $ch="";
  for($x=0;$x<$rang3;$x++) {
      $ch=$ch."&nbsp;&nbsp;&nbsp;&nbsp;";
  }
return $ch;
}

function recurw($tab,$pere,$rang3,$lang) {
global $defaultOrder,$_GET;
  $tabNb = count($tab);
  for($x=0;$x<$tabNb;$x++) {

    if($tab[$x][1]==$pere) {
                                if(isset($_GET['path'])) {
                                    $smenu_var2 = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_noeud
                                                        FROM categories
                                                        WHERE categories_id = '".$_GET['path']."'");
                                                        $smenu2 = mysql_fetch_array($smenu_var2);
                                                        $catSelected = $smenu2['categories_name_'.$_SESSION['lang']];
                                }
                                else {
                                    $catSelected = "";
                                }
          $catOrigin = $tab[$x][2];
          if($catSelected == $catOrigin) {$dd="selected";} else {$dd="";}
          if($tab[$x][4] == "L") {
                $valueLink = "list.php?path=".$tab[$x][0]."&action=e";
                $classStyle = "style=\"BACKGROUND-COLOR: #f1f1f1; color:#000000\"";
                $catNom = $tab[$x][2];
          }
          else {
                $valueLink = "";
                $classStyle = "style=\"BACKGROUND-COLOR: #dddddd; color:#808080\"";
                $catNom = "&bull; ".$tab[$x][2];
          }

       print "<option value=\"".$valueLink."\" $dd $classStyle>".espace3a($rang3).$catNom."</option>";
       recurw($tab,$tab[$x][0],$rang3+1,$lang);
    }
  }
}


























$smenu_var = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_id, parent_id
                         FROM categories
                         WHERE categories_visible = 'yes'
                         AND categories_noeud = 'L'
                         ORDER BY categories_name_".$_SESSION['lang']."");
while($smenu = mysql_fetch_array($smenu_var)) {
	$scat[] = array($smenu['categories_name_'.$_SESSION['lang']],$smenu['categories_id'], $smenu['parent_id']);
}
         
if(isset($_GET['path'])) {
	$smenu_var2 = mysql_query("SELECT categories_name_".$_SESSION['lang']."
                        FROM categories
                        WHERE categories_id = '".$_GET['path']."'");
	$smenu2 = mysql_fetch_array($smenu_var2);
	$catSelected = $smenu2['categories_name_'.$_SESSION['lang']];
}
else {
	$catSelected = "";
}
                    print "<div align=\"center\"><img src=\"im/zzz.gif\" width=\"1\" height=\"5\"></div>";

                    print "<table width=\"450\" height=\"70\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" class=\"toto\">";
                    print "<tr>";
                    // form
                    print "<form action= \"\">";
                    print "<td colspan=\"2\" valign=\"middle\" align=\"left\">";
                    print "<img src=\"im/zzz.gif\" width=\"1\" height=\"1\"><br><img src=\"im/fleche_right.gif\">&nbsp;";


print "<select name=\"path\" onChange=\"sendIt(this.options[selectedIndex].value)\" >";
print "<option value=\"index.php\">".LA_BOUTIQUE."</option>";
print "<option value=\"\">&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;</option>";
recurw($dataIpos,"0","0",$_SESSION['lang']);
print "</select>";

/*
                    print "<select name=\"path\" onChange=\"sendIt(this.options[selectedIndex].value)\" >";
                    
                    // Menu
                    print "<option value=\"\">".LA_BOUTIQUE."</option>";
                    print "<option value=\"\">---</option>";
                    for($ccc=0; $ccc<= count($scat)-1; $ccc++) {
                      $catOrigin = $scat[$ccc][0];
                      //$scat[$ccc][0] = adjust_text($scat[$ccc][0],30,"..");

                      if($catSelected == $catOrigin) {$dd="selected";} else {$dd="";}
                      print "<option value=\"list.php?path=".$scat[$ccc][1]."&action=e\" $dd>".$scat[$ccc][0]."</option>";
                     }
                    print "</select>";
*/
                    print "</td></tr><tr><td>";
                    print "<img src=\"im/zzz.gif\" width=\"1\" height=\"8\">";
                    
                    print "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" ><tr class=\"toto\">";
                    print "<td valign=\"middle\" align=\"left\">";
                    print "<img src=\"im/fleche_right.gif\">&nbsp;";
                    print "</td><td>";
                    print "<div style=\"COLOR:#000000; BACKGROUND-COLOR:#f5f5f5; border-width:1px; border-style:solid; BORDER-TOP-COLOR:#000000; border-right-color:#b0b0b0; border-left-color:#000000; border-bottom-color:#b0b0b0; padding:1px;\">";
                    print "&nbsp;<a href=\"zoeken.php?action=search\">".RECHERCHER."</a>&nbsp;";
                    print "</div>";
                    print "</td></tr></table>";
                    
                    
                    print "</td>";
                    
                    
                    print "<td align=\"right\" width=\"350\">";
                    print "<br><img src=\"im/zzz.gif\" width=\"1\" height=\"8\">";
                    // panier
                    if(isset($_SESSION['account']) OR $activeEcom=="oui") {
                        print "<table border=\"0\" align=\"right\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\"><tr>";
                        print "<td valign=\"middle\" align=\"right\">";
                        print "</td>";
                        print "<td align=\"center\" valign=\"middle\">";
                        if($itemNum>0) {
                            ($itemNum>1)? $art=ARTICLES : $art=ARTICLE;
                            print "<span style=\"COLOR: #000000; BACKGROUND-COLOR:#f5f5f5; padding:1px; border: 1px #CCCCCC solid;\"><b>&nbsp;".$itemNum."</b> ".$art."&nbsp;</span>";
                            print "&nbsp;|&nbsp;<a href=\"caddie.php\">".VOTRE_CADDIE."</a>&nbsp;|&nbsp;";
                            $sep = (substr($url_id10,-4)==".php")? "?" : "&";
							print "<a href=\"".$url_id10.$sep."var=session_destroy\">".VIDER_CADDIE."</a>&nbsp;|";
                        }
                        else {
                            print "<span style=\"COLOR: #000000; BACKGROUND-COLOR:#f5f5f5; padding:1px; border: 1px #CCCCCC solid;\">&nbsp;<b>".$itemNum."</b>&nbsp;".ARTICLE."&nbsp;</span>";
                        }
                        print "</td>";
                        print "</tr></table>";
                    }

                    print "</td>";
                    print "</form>";
                    print "</tr>";
                    print "<tr>";
                    print "<td colspan=\"2\"><img src=\"im/zzz.gif\" width=\"1\" height=\"8\"></td></tr><tr>";
                    print "<td colspan=\"2\" valign=\"bottom\">";
                    print "<div id=\"tab\" align=\"center\">";
                    if(!isset($_GET['target'])) {$class1 = "class=\"here\"";} else {$class1 = "";}
                    if(isset($_GET['target']) and $_GET['target'] == "new") {$class2 = "class=\"here\"";} else {$class2 = "";}
                    if(isset($_GET['target']) and $_GET['target'] == "promo" and !isset($_GET['tow'])) {$class3 = "class=\"here\"";} else {$class3 = "";}
                    if(isset($_GET['target']) and $_GET['target'] == "favorite") {$class4 = "class=\"here\"";} else {$class4 = "";}
                    if(isset($_GET['tow']) and $_GET['tow'] == "flash") {$class5 = "class=\"here\"";} else {$class5 = "";}
                    
                    print "<a ".$class1." href=\"index.php\"><span><b>".HOME."</b></span></a>";
                    print "<a ".$class2." href=\"list.php?target=new\"><span><b>".NOUVEAUTES."</b></span></a>";
                    print "<a ".$class3." href=\"list.php?target=promo\"><span><b>".PROMOTIONS."</b></span></a>";
                    print "<a ".$class4." href=\"list.php?target=favorite\"><span><b>".NOS_SELECTIONS."</b></span></a>";
                    print "<a ".$class5." href=\"list.php?target=promo&tow=flash\"><span><b>".VENTES_FLASH."</b></span></a>";
                    print "</div>";
                    print "</td>";
                    print "</tr>";
                    print "</table>";
?>
