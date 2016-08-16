<?php


 
$activeRoundedCorners = "yes";
if($activeRoundedCorners=="yes") {
	$tableDynMenuH = "tableDynMenuHRounded";
}
else {
	$tableDynMenuH = "tableDynMenuH";
}

 
if($menuCssHorizonCenter=="oui") {
   $_center1="center";
   $_center2="center";
}
else {
   $_center1="";
   $_center2="";
}

print "<div align='center'>";
if($activeRoundedCorners=='yes') round_top('yes','99%','raised4');
print "<table align='center' border='0' width='99%' cellspacing='0' cellpadding='0' class='".$tableDynMenuH."'><tr>";
print "<td>";

print "<div align='".$_center1."'>";
print "<table border='0' cellspacing='0' cellpadding='0' align='".$_center2."'><tr><td>";
        

class build_treeH {
   function addToArray($id,$name,$parentID, $lang) {
      if(empty($parentID)) $parentID = 0;
      $level = $this->checkLevel($_SESSION['tree'], $id, $lang);
      $this->elementArray[$parentID][] = array($id,$name,$level[0],$level[1],$level[2]);
   }
 
    function checkLevel($tree, $menId, $lang) {
        GLOBAL $nbreCarCat,$nbreCarSubCat,$defaultOrder;
        for($k=0; $k < sizeof($tree); $k++) {
            if($lang == "1") $oo = 5;
            if($lang == "2") $oo = 9;
            if($lang == "3") $oo = 10;
            $catName = $tree[$k][$oo]; 
            
            if($tree[$k][4]==$menId) {
                $levelTree = $tree[$k][7];
                    if($tree[$k][3]=="B") {
  
                        $yo1 = adjust_text($catName,$nbreCarCat,"..");
                        $url = "categories.php?path=".$tree[$k][4];
                        $isSubCat = $tree[$k][2];
                        $resQuery = mysql_query("SELECT categories_id FROM categories WHERE parent_id = '".$menId."'");
                        (mysql_num_rows($resQuery) > 0)? $isSubCat='yes' : $isSubCat = 'no';
                    }
                    else {
   
                        $yo1 = adjust_text($catName,$nbreCarSubCat,"..");
                        if(!isset($_GET['action'])) $dep2="n";
                        if(isset($_GET['action']) AND $_GET['action']=='c') $dep2="e";
                        if(isset($_GET['action']) AND $_GET['action']=='e') $dep2="c";
                        $url = "list.php?path=".$tree[$k][4];
                        $isSubCat = 'no';
                    }
            }
        }
        if(isset($levelTree) AND isset($url) AND isset($isSubCat))  return array($levelTree, $url, $isSubCat);
    }
    
    
   function drawSubNode($parentID,$lang) {
      GLOBAL $nbreCarSubCat,$menuWidthCSSH,$menuWidthCSSH2;
      if(isset($this->elementArray[$parentID])) {
         $niveau = $this->checkLevel($_SESSION['tree'],$parentID,$lang);
         $niveauX = $niveau[0]+1;
         if($niveauX>2) {$crt = "style='left: ".$menuWidthCSSH2."px;'";} else {$crt="";}
     
			 echo "<ul class='niveau".$niveauX."' ".$crt.">";
	         $parentIDNb = count($this->elementArray[$parentID]);
	         for($no=0; $no<$parentIDNb; $no++) {
	            if($this->elementArray[$parentID][$no][4]=='no') {$classMenu = "";} else {$classMenu = " class='sousmenu plop'";}
	            if(strlen($this->elementArray[$parentID][$no][1]) > $nbreCarSubCat) $displayName2 = $this->elementArray[$parentID][$no][1]; else $displayName2="";
	                echo "<li ".$classMenu." style='width:".$menuWidthCSSH2."px;'>";
	                echo "<div id='borderTopSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>";
	 
	                echo "<a title='".$displayName2."' href='".$this->elementArray[$parentID][$no][3]."'>".adjust_text($this->elementArray[$parentID][$no][1],$nbreCarSubCat,"..")."</a>";
	                $this->drawSubNode($this->elementArray[$parentID][$no][0],$lang);
	                echo "<div id='borderBottomSousmenu'><img src='im/zzz.gif' height='1' width='0'></div>";
	                echo "</li>";
	         }
	         echo "</ul>";
        
      }
   }
   
 
   function drawTree($lang) {
      GLOBAL $nbreCarCat,$menuNewsVisible,$menuPromoVisible,$menuWidthCSSH,$defaultOrder,$tableDisplay,$tableDisplayLeft;
      echo "<div ID='menu45'>";
      echo "<ul class='niveau1'>";
      			if($tableDisplay=='non' OR $tableDisplayLeft=='non') {
	                echo "<li class='sousmenuA' style='width:70px;'>";
	                echo "<div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
	                echo "<div align='left'><a href='cataloog.php'><img src='im/accueil.gif' border='0'><img src='im/zzz.gif' height='1' width='5' border='0'>".maj(HOME)."</a></div>";
	                echo "<div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
	                echo "<div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>";
	                echo "</li>";
                }
      $noNb = count($this->elementArray[0]);
      for($no=0;$no<$noNb;$no++) {
            if($this->elementArray[0][$no][4]=='no') {$classMenu = "";} else {$classMenu = " class='sousmenu plop2'";}
            if(strlen($this->elementArray[0][$no][1]) > $nbreCarCat) $displayName1 = $this->elementArray[0][$no][1]; else $displayName1="";
                echo "<li ".$classMenu." style='width:".$menuWidthCSSH."px;'>";
                echo "<div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
 
                echo "<a title='".$displayName1."' href='".$this->elementArray[0][$no][3]."'>".adjust_text($this->elementArray[0][$no][1],$nbreCarCat,"..")."</a>";
                echo "<div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
                echo "<div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>";
                $this->drawSubNode($this->elementArray[0][$no][0],$lang);
                echo "</li>";
      }
 
                if($menuNewsVisible == "oui") {
                    if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
                        echo "<li class='sousmenuA' style='width:".$menuWidthCSSH."px;'>";
                        echo "<div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
                        echo "<a href='list.php?target=new'><img src='im/cam.gif' border='0'> ".NOUVEAUTESMAJ."</a>";
                        echo "<div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
                        echo "<div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>";
                        echo "</li>";
                    }
                }
 
                if($menuPromoVisible == "oui") {
                    if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {
                        echo "<li class='sousmenuA' style='width:".$menuWidthCSSH."px;'>";
                        echo "<div id='borderTopMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
                        echo "<a href='list.php?target=promo'><img src='im/cam.gif' border='0'> ".maj(PROMOTIONS)."</a>";
                        echo "<div id='borderBottomMainMenu'><img src='im/zzz.gif' height='1' width='0'></div>";
                        echo "<div id='test3'><img src='im/zzz.gif' height='1' width='0'></div>";
                        echo "</li>";
                    }
                }
      echo "</ul>";
      echo "</div>";
   }
}
 

$res = mysql_query("SELECT categories_id, parent_id, categories_name_".$_SESSION['lang']." 
                    FROM categories 
                    WHERE categories_id != '0'
                    AND categories_visible='yes'
                    ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");
$treeObj = new build_treeH();
if(mysql_num_rows($res) > 0) {
    while($inf = mysql_fetch_array($res)) {
        $treeObj->addToArray($inf['categories_id'],$inf['categories_name_'.$_SESSION['lang']],$inf['parent_id'],$_SESSION['lang']);
    }
 
$treeObj->drawTree($_SESSION['lang']); 
 
}

print "</td></tr></table>";
print "</div>";
print "</td></tr></table>";
if($activeRoundedCorners=='yes') round_bottom('no');
print "<div align='center'>";
?>
