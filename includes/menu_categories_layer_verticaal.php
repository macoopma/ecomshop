<?php

$menuWidthCSSV = $larg_rub-2;
$leftDecalageCSSV = $menuWidthCSSV-10;
$leftDecalageCSSVSub2 = $menuWidthCSSVSub-10;
        
print "<table border='0' width='".$larg_rub."' cellspacing='0' cellpadding='0' class='moduleMenuVertical'>";
print "<tr>";
print "<td>";
 
class build_tree {
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
                        $url = $this->genSeo("categories.php?path=".$tree[$k][4]);
                        $isSubCat = $tree[$k][2];
                        $resQuery = mysql_query("SELECT categories_id FROM categories WHERE parent_id = '".$menId."'");
                        (mysql_num_rows($resQuery) > 0)? $isSubCat='yes' : $isSubCat = 'no';
                    }
                    else {
                         
                        $yo1 = adjust_text($catName,$nbreCarSubCat,"..");
                        $url = $this->genSeo("list.php?path=".$tree[$k][4]);
                        $isSubCat = 'no';
                    }
            }
        }
        if(isset($levelTree) AND isset($url) AND isset($isSubCat)) return array($levelTree, $url, $isSubCat);
    }
    
     
   function drawSubNode($parentID,$lang) {
      GLOBAL $nbreCarSubCat,$menuWidthCSSV,$leftDecalageCSSV,$menuWidthCSSVSub,$leftDecalageCSSVSub2;
      if(isset($this->elementArray[$parentID])) {
         $niveau = $this->checkLevel($_SESSION['tree'],$parentID,$lang);
         $niveauX = $niveau[0]+1;
         if($niveauX>1) {
            $leftDecalageCSSV2 = "left:".$leftDecalageCSSV."px";
            if($niveauX>2) {
                $leftDecalageCSSV2 = "left:".$leftDecalageCSSVSub2."px";
            }
         }
         else {
            $leftDecalageCSSV2="";
         }

	         print "<ul class='niveau".$niveauX."' style='width:".$menuWidthCSSVSub."px; ".$leftDecalageCSSV2."'>";
	         for($no=0; $no<count($this->elementArray[$parentID]); $no++) {
	            if($this->elementArray[$parentID][$no][4]=='no') {$classMenu = "";} else {$classMenu = " class='sousmenu'";}
	            if(strlen($this->elementArray[$parentID][$no][1]) > $nbreCarSubCat) $displayName2 = $this->elementArray[$parentID][$no][1]; else $displayName2="";
	                 
	                print "<li ".$classMenu." style='width:".$menuWidthCSSVSub."px;'><a title='".$displayName2."' href='".$this->elementArray[$parentID][$no][3]."'>".adjust_text($this->elementArray[$parentID][$no][1],$nbreCarSubCat,"..")."</a>";
	                $this->drawSubNode($this->elementArray[$parentID][$no][0],$lang);
	                print "</li>";
	         }
	         print "</ul>";
         ##}
      }
   }
   
	 
	function genSeo($raw_url) {
		$seo_switch = true;
		if ($seo_switch) {
			$param_sort = array("id", "path", "target", "sort", "view", "page", "num", "action", "info", "lang");
			$rawparam_arr = array();
			$seo_url_param = array();
			$seo_url_lastparam = "";
			$seo_url = "";
			$tmp_rawurl = explode("?", $raw_url);
			if(isset($tmp_rawurl[0])) {
				$tmp_urlfname = explode(".", $tmp_rawurl[0]);
				 
				if(isset($tmp_rawurl[1])) {
					$tmp_paramarr = explode("&", $tmp_rawurl[1]);
					foreach($tmp_paramarr as $value) {
						$tmp_kv = explode("=", $value);
						if(isset($tmp_kv[1]))
							$rawparam_arr[$tmp_kv[0]] = $tmp_kv[1];
					}
				}
				if(isset($tmp_urlfname[0])) {
					if(isset($rawparam_arr['id']))
						$seo_url_param[] = $tmp_urlfname[0] . $rawparam_arr['id'];
					else
						$seo_url_param[] = $tmp_urlfname[0];
					foreach($param_sort as $value) {
						if(isset($rawparam_arr[$value]) && $value != "id")
							$seo_url_param[] = $rawparam_arr[$value];
					}
					switch($tmp_urlfname[0]) {
						case "cataloog":
							break;
						case "categories":
						case "list":
							if(isset($rawparam_arr['path'])) {
								$queryCat = mysql_query("SELECT categories_name_".$_SESSION['lang']." as cat_name FROM categories WHERE categories_id = '".$rawparam_arr['path']."' AND categories_visible = 'yes'");
								$queryCatNum = mysql_num_rows($queryCat);
								if($queryCatNum > 0) {
									while ($cat = mysql_fetch_array($queryCat)) {
										$cat_name = $cat['cat_name'];
									}
									$seo_cat_name = str_replace(" ", "_", $cat_name);
									$seo_url_lastparam .= $seo_cat_name;
								}
							}
							break;
						default:
							break;
					}
					$seo_url .= implode("-", $seo_url_param);
					if(count($seo_url_param) > 1) {
						$seo_url .= "_".$seo_url_lastparam;
					}
					$seo_url .= ".html";
					$seo_url = str_replace("_>_", "_", $seo_url);
					return $seo_url;
				}
				else
					return $raw_url;
			}
		}
		else 
			return $raw_url;
	}
   
    
   function drawTree($lang) {
      GLOBAL $moteurVisibleMenuV,$nbreCarCat,$menuV,$larg_rub,$menuNewsVisible,$menuPromoVisible,$menuWidthCSSV,$hauteurTitreModule,$defaultOrder,$menuAccueilVisible,$moteurVisible,$url_id10,$slash;
      print "<div ID='menu44'>";
      if($menuV == "fixe") {
            if(isset($_SESSION['userInterface']) AND ($_SESSION['userInterface']=="perso" OR $_SESSION['userInterface']=="Jaune-Yellow")) $larg_rub2 = $larg_rub+4; else $larg_rub2 = $larg_rub;
            print '<div class="raised" style="width:'.$larg_rub2.'">';
            print '<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>';
            print '<div class="boxcontent">';
            print '<table border="0" height="'.$hauteurTitreModule.'" cellspacing="0" cellpadding="0" class="mo/duleMenu">';
            print '<tr>';
            print '<td class="moduleMenuTitre contentTop" align="center" style="width:'.$larg_rub.'">';
            print "<img src='im/zzz.gif' width='1' height='2'><br>";
            print "<a href='".$this->genSeo("cataloog.php")."'><img src='im/lang".$lang."/menu_produits.png' border='0'></a>";
            print "<br><img src='im/zzz.gif' width='1' height='2'>";
            print '</td>';
            print '</tr>';
            print '</table>';
            print '</div>';
            print '</div>';
            print "<ul class='niveau1' style='border-top:0px;'>";
      }
      if($menuV == "deroulant") {
            print "<ul class='niveau0' style='width:".$menuWidthCSSV."px;'>";
            print "<li class='sousmenu plop2' style='width:".$menuWidthCSSV."px'>";
            print "<a href='".$this->genSeo("cataloog.php")."'><b>".LA_BOUTIQUE."</b></a>";
            print "<ul class='niveau1'>";
      }
       
      if($menuAccueilVisible=="oui") {
          print "<li class='s/ousmenu' style='width:".$menuWidthCSSV."px;'>";
          print "<a href='".$this->genSeo("cataloog.php")."'><img src='im/accueil.gif' border='0'>&nbsp;".maj(HOME)."</a>";
          print "</li>";
      }
      $noNb = count($this->elementArray[0]);
      for($no=0;$no<$noNb;$no++) {
            if($this->elementArray[0][$no][4]=='no') {$classMenu = "";} else {$classMenu = " class='sousmenu'";}
            if(strlen($this->elementArray[0][$no][1]) > $nbreCarCat) $displayName1 = $this->elementArray[0][$no][1]; else $displayName1="";
                 
                print "<li ".$classMenu." style='width:".$menuWidthCSSV."px;'>";
				print "<a title='".$displayName1."' href='".$this->elementArray[0][$no][3]."'>".adjust_text($this->elementArray[0][$no][1],$nbreCarCat,"..")."</a>";
                $this->drawSubNode($this->elementArray[0][$no][0],$lang);
                print "</li>";
      }
       
      if($menuNewsVisible == "oui") {
          if(isset($_SESSION['getNews']) AND $_SESSION['getNews']>0) {
              print "<li class='s/ousmenu' style='width:".$menuWidthCSSV."px;'>";
              print "<a href='list.php?target=new'><img src='im/fleche_right.gif' border='0'>&nbsp;".NOUVEAUTESMAJ."</a>";
              print "</li>";
          }
      }
       
      if($menuPromoVisible == "oui") {
          if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {
              print "<li class='s/ousmenu' style='width:".$menuWidthCSSV."px;'>";
              print "<a href='list.php?target=promo'><img src='im/fleche_right.gif' border='0'>&nbsp;".maj(PROMOTIONS)."</a>";
              print "</li>";
          }
      }
      
		 
		if($moteurVisibleMenuV == 'oui') {
			$larg_rub2 = $larg_rub-20;
			print "<li class='s/ousmenu' style='width:".$menuWidthCSSV."px;'>";
					print "<table width='".$larg_rub2."' align='center' border='0' cellspacing='0' cellpadding='0'>";
					print "<tr>";
					print "<form action='includes/redirectzoeken.php' method='post'>";
					print "<td align='center' valign='bottom' width='1'>";
					print "<div><img src='im/zzz.gif' width='1' height='5' border='0'></div>";
					print "<input style='border:1px #CCCCCC solid; background:url(im/loupe.gif) top left no-repeat #FAFAFA; width:".$larg_rub2."px; height:18px; padding-left:17px;' name='search_query' maxlength='100' value='' type='text'>";
					print "</td>";
					print "</form>";
					print "</tr></table>";

					print "<table width='' align='center' border='0' cellspacing='0' cellpadding='0'>";
					print "<tr>";
					print "<form action='zoeken.php?action=search&AdSearch=on' method='post'>";
					print "<td align='right' valign='top'>";
					print "<div><img src='im/zzz.gif' width='1' height='3' border='0'></div>";
					print "<input type='submit' value='".RECHERCHE_AVANCEE."' style='padding:0px; margin:0px; BACKGROUND:#none; border:0px; FONT-SIZE:9px;' class='FontGris'>";
					print "<div><img src='im/zzz.gif' width='1' height='3' border='0'></div>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
			print "</li>";
		}
		

      print "</ul>";
      print "</li>";
      if($menuV == "deroulant") {print "</ul>";}
      print "</div>";
   }
}
 

$res = mysql_query("SELECT categories_id, parent_id, categories_name_".$_SESSION['lang']." 
                    FROM categories 
                    WHERE categories_id != '0' 
                    AND categories_visible='yes'
                    ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");

$treeObj = new build_tree();
if(mysql_num_rows($res) > 0) {
    while($inf = mysql_fetch_array($res)) {
        $treeObj->addToArray($inf['categories_id'],$inf['categories_name_'.$_SESSION['lang']],$inf['parent_id'],$_SESSION['lang']);
    }

 
$treeObj->drawTree($_SESSION['lang']); 
}

print "</td></tr>";
print "</table>";
?>
<br>
