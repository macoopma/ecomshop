<?php

if($displayOutOfStock=="non") {$addToQuery2 = " AND products_qt>'0'";} else {$addToQuery2="";}

function productNum($productNum,$lang) {
	GLOBAL $addToQuery2;
	$sousresult2 = mysql_query("SELECT categories_id_bis, products_name_".$lang."
								FROM products
								WHERE categories_id = '".$productNum."'
								AND products_visible = 'yes'
								".$addToQuery2."
								OR (categories_id_bis LIKE '%|".$productNum."|%' AND products_visible = 'yes')");
	$rows2 = mysql_num_rows($sousresult2);
	return $rows2;
}


 
function displayTree(&$tree,$currentRoot,$action,$lang) {
global $_SERVER, $_GET ,$defaultOrder, $menuNewsVisible, $menuPromoVisible, $expandMenu, $menuAccueilVisible, $qtMenu, $backGdColorCatLine, $moteurVisibleMenuPhp;
global $tmpAction,$larg_rub,$nbreCarCat,$nbreCarSubCat;

if($lang == "1") $oo = 5;
if($lang == "2") $oo = 9;
if($lang == "3") $oo = 10;

    $j = $currentRoot;
    
    if($action == "c") {
        $currentLevel = $tree[$j][7];
        $tree[$j][1] = "N";
        $j++;
        while ($j < sizeof($tree)) {
            if($tree[$j][7] > $currentLevel) {
                $tree[$j][8] = "0";
                $tree[$j][1] = "N";
                $j++;
            }
            else {
                break;
            }
        }
    }
    else if($action == "e") {
        $currentLevel = $tree[$j][7];
        $parent = $tree[$currentRoot][4];
        $tree[$j][1] = "Y";
        $j++;
        while ($j < sizeof($tree)) {
            if(($tree[$j][7] == $currentLevel + 1) && $parent == $tree[$j][2]) {
                $tree[$j][8] = "1";
            }
            $j++;
        }
    }
    print "<table border='0' width='100%' cellspacing='0' cellpadding='0'>";
 
            if($menuAccueilVisible=="oui") {
                $classSelected30 = (preg_match("/\bcataloog.php\b/i", $_SERVER['PHP_SELF']))? "backgroundCategorySelected" : "fontMenuSubCategory";
                print "<tr class='".$classSelected30."'>";
                print "<td height='0' width='".$larg_rub."'>";
                print "<img src='im/zzz.gif' width='1' height='15'>";
                print "<img src='im/accueil.gif' border='0'>&nbsp;<a href='cataloog.php'><b>".maj(HOME)."</b></a>";
                print "<div class='dotMenu'><img src='im/zzz.gif' width='1' height='1'></div>";
                print "</td>";
                print "</tr>";
            }
    for($k=0 ;$k < sizeof($tree) ; $k++) {
            $_SESSION['tree2'][$tree[$k][5]] = $k;
            $_SESSION['tree2'][$tree[$k][9]] = $k;
            $_SESSION['tree2'][$tree[$k][10]] = $k;
            
 
        if($expandMenu == 'oui') {
            $_aa = "0";
            $_bb = "B";
        }
        else {
            $_aa = "1";
            $_bb = "";
        }

        if($tree[$k][8] == $_aa OR $tree[$k][3] == $_bb) {
            $currentColor = ($k==$currentRoot ? "class='backgroundCategorySelected'" : "");
            if(isset($_GET['path']) AND $tree[$k][4] == $_GET['path']) {$currentColor = "class='backgroundCategorySelected'";} else {$currentColor = "";}
            $img = $tree[$k][3] == "L" ? "src='im/zzz_noir.gif' width='3' height='3'" : ($tree[$k][1]=="Y" ? "includes/menu/minus.gif" : "includes/menu/plus.gif");
            $tmpAction = $tree[$k][3] == "L" ? "" : ($tree[$k][1]=="Y" ? "c" : "e");
            
			if($tree[$k][3]=="L") { print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorCatLine."';\" onmouseout=\"this.style.backgroundColor='';\" class='backgroundMenuSousCategory'>";} else {print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorCatLine."';\" onmouseout=\"this.style.backgroundColor='';\">";}
            if($tree[$k][3] == "R") {
                print "<td height='0' width='".$larg_rub."'>";
            }
            else {
                print "<td height='0' width='".$larg_rub."' ".$currentColor.">";
            }
            for($l=1;$l < $tree[$k][7];$l++) {print "<img src='im/zzz.gif' width='7' height='1'>";}
            $displayText = $tree[$k][$oo];

	            if($tree[$k][3]=="L") {

	                $yo1 = adjust_text($tree[$k][$oo],$nbreCarSubCat,"..");
	                if(isset($_GET['path']) AND $tree[$k][4] == $_GET['path']) {
	                      $img = "src='im/fleche_right_red.gif'";
	                      $classSelected = "fontMenuSubCategorySelected";
	                  }
	                  else {
	                      $classSelected = "fontMenuSubCategory";
	                  }
	                    print "<img src='im/zzz.gif' width='1' height='12'><img border=0 ".$img.">&nbsp;";
	                    print "<a href='list.php?path=".$tree[$k][4]."'>";
	                    print "<span class='".$classSelected."' ".display_title($displayText,$nbreCarSubCat).">".$yo1."</span>";
	                    print "</a>";
	                    if($qtMenu=='oui') {
	                    	print "<img src='im/zzz.gif' width='3' height='1'>";
	                    	print "<span class='tiny'>[".productNum($tree[$k][4],$lang)."]</span>";
	                    }
	                if(isset($tree[$k+1][3]) AND $tree[$k+1][3] == "B") {
	                    print "<div class='dotMenu'><img src='im/zzz.gif' width='1' height='1'></div>";
	                }
	               print "</td>";
	            }
	            else {
	               if($tree[$k][3] =="R") {
	                  $a=1;
	               }
	               else {
	                    if($tmpAction=="c") $tmpAction2="e";
	                    if($tmpAction=="e") $tmpAction2="c";
	                    
	
 
	                    $yo2 = adjust_text($tree[$k][$oo],$nbreCarCat,"..");
	                    if($tree[$k][2] == 0) {
	                        print "<img src='im/zzz.gif' width='1' height='15'>";
	                    }
 
	                    print "<a href='categories.php?path=".$tree[$k][4]."&num=".$k."&action=".$tmpAction."'><img border=0 src='".$img."'></a>&nbsp;<a href='categories.php?path=".$tree[$k][4]."&num=".$k."&action=$tmpAction2' ".display_title($displayText,$nbreCarCat)."><span class='fontMenuCategory'><b>".$yo2."</b></span></a>";
	                    
  
 
	                     


	                    print "<div class='dotMenu'><img src='im/zzz.gif' width='1' height='1'></div>";
	                    print "</td>";
	               }
	            }
             
            print "</tr>";
      }
   }
  
        if($menuNewsVisible == "oui") {
            if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
                    if(isset($_GET['target']) AND $_GET['target'] == 'new') $classSelected2 = "backgroundCategorySelected"; else $classSelected2 = "fontMenuSubCategory";
                print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorCatLine."';\" onmouseout=\"this.style.backgroundColor='';\" class='".$classSelected2."'>";
                print "<td height='0' width='".$larg_rub."'>";
                print "<img src='im/zzz.gif' width='1' height='15'>";
                print "<img src='im/fleche_right.gif'>&nbsp;<a href='list.php?target=new'><b>".NOUVEAUTESMAJ."</b></a>";
                print "<div class='dotMenu'><img src='im/zzz.gif' width='1' height='1'></div>";
                print "</td>";
                print "</tr>";
            }
        }
 
        if($menuPromoVisible == "oui") {
            if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {
                    if(isset($_GET['target']) AND $_GET['target'] == 'promo') $classSelected3 = "backgroundCategorySelected"; else $classSelected3 = "fontMenuSubCategory";
                print "<tr onmouseover=\"this.style.backgroundColor='#".$backGdColorCatLine."';\" onmouseout=\"this.style.backgroundColor='';\" class='".$classSelected3."'>";
                print "<td height='0' width='".$larg_rub."'>";
                print "<img src='im/zzz.gif' width='1' height='15'>";
                print "<img src='im/fleche_right.gif'>&nbsp;<a href='list.php?target=promo'><b>".maj(PROMOTIONS)."</b></a>";
                print "<div class='dotMenu'><img src='im/zzz.gif' width='1' height='1'></div>";
                print "</td>";
                print "</tr>";
            }
        }
         
        if($moteurVisibleMenuPhp == "oui") {
        		$larg_rub2 = $larg_rub-20;
        		print "<tr>";
        		print "<td height='0' width='".$larg_rub."'>";
					print "<table width='".$larg_rub2."' align='center' border='0' cellspacing='0' cellpadding='0'>";
					print "<tr>";
					print "<form action='includes/redirectzoeken.php' method='post'>";
					print "<td align='center' valign='bottom' width='1'>";
					print "<div><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
					print "<input style='border:1px #CCCCCC solid; background:url(im/loupe.gif) top left no-repeat #FAFAFA; width:".$larg_rub2."px; height:18px; padding-left:17px;' name='search_query' maxlength='100' value='' type='text'>";
					print "</td>";
					print "</form>";
					print "</tr></table>";

					print "<table width='".$larg_rub2."' align='center' border='0' cellspacing='0' cellpadding='0'>";
					print "<tr>";
					print "<form action='zoeken.php?action=search&AdSearch=on' method='post'>";
					print "<td align='center' valign='top'>";
					print "<input type='submit' value='".RECHERCHE_AVANCEE."' style='width:".$larg_rub2."px; padding:0px; margin:0px; BACKGROUND:#none; B/ACKGROUND-COLOR:#none; border:0px; FONT-SIZE:9px;' class='FontGris'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
                print "</td>";
                print "</tr>";
        }
        print "</table>";
}

     
if(!preg_match("/cataloog.php/", strtolower($url_id10))) {
      if(!isset($treename)) $treeName = "tree";
      (!isset($_GET['action']))? $action = "e" : $action = $_GET['action'];
      (!isset($_GET['num']))? $num = 0 : $num = $_GET['num'];
      
      if(!isset($_SESSION['tree'])) {
          $ltmTree = new myTree;
          $ltmTree->dbConnect($bddHost,$bddName,$bddUser,$bddPass);
          $ltmTree->initTree(urldecode($treeName),$_SESSION['lang']);
          $_SESSION['tree'] = $ltmTree->tree;
          $_SESSION['maxlevel'] = $ltmTree->maxLevel;
      }        
}
else {
      $treeName = "tree";
      $action = "e";
      $num = 0;
      
      $ltmTree = new myTree;
      $ltmTree->dbConnect($bddHost,$bddName,$bddUser,$bddPass);
      $ltmTree->initTree(urldecode($treeName),$_SESSION['lang']);
      $_SESSION['tree'] = $ltmTree->tree;
      $_SESSION['maxlevel'] = $ltmTree->maxLevel;
}
 
?>
<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">
<?php
print "<table border='0' cellspacing='0' cellpadding='2' class='moduleMenu'><tr>";
print "<td height='".$hauteurTitreModule."' class='moduleMenuTitre contentTop' align='center' style='width:".$larg_rub."'>";
print "<a href='cataloog.php'><img src='im/lang".$_SESSION['lang']."/menu_produits.png' border='0'></a>";
print "</td>";
print "</tr><tr>";
print "<td>";
displayTree($_SESSION['tree'],$num,$action,$_SESSION['lang']);
print "</td>";
print "</tr>";
print "<tr height='7'><td></td></tr>";
print "</table>";
?>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
<br>
