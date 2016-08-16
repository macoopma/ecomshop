<script type="text/javascript">
   function sendIt(fileName) {
      if(fileName != "") {
         location.href=fileName
      }
   }
</script>
<?php
 

########################################
// CSS
if(!isset($_GET['css'])) {
	$_css = explode("-",$colorInter);
	if(isset($_css[1])) $_GET['css'] = strtolower($_css[1]); else $_GET['css'] = strtolower($_css[0]);
}
if(preg_match("#www.#i", $_GET['css'])) $_SESSION['css'] = "http://".$_GET['css']; else $_SESSION['css'] = "css/".$_GET['css'].".css";

########################################
// langue

if(isset($_GET['lang'])) {$_SESSION['lang'] = $_GET['lang'];}

########################################
// count items in the cart
function cart_Item2() {
    GLOBAL $_SESSION;
    if(isset($_SESSION['list']) AND !empty($_SESSION['list'])) {
        $split = explode(",", $_SESSION['list']);
        foreach ($split as $item) {
            $check = explode("+", $item);
            $itemCount[]=$check[1];
            $totalTTCIpos[] = $check[2]*$check[1];
        }
        $itemNum = array_sum($itemCount);
        $_SESSION['totalTTC'] = sprintf("%0.2f", array_sum($totalTTCIpos));
        $_SESSION['tot_art'] = $itemNum;
    }
    else {
      $itemNum = 0;
    }
    return $itemNum; 
}
$itemNum = cart_Item2();

########################################
// resize image
function resizeImageMini($imageToResize,$haut,$largeurMax) {
                 $size = @getimagesize($imageToResize);

                 if($size[1] >= $haut) {
                      $hauteur = $haut;
                      $reduction_hauteur = $hauteur/$size[1];
                      $largeur = $size[0]*$reduction_hauteur;
                     }
                     else {
                      $hauteur = $size[1];
                      $largeur = $size[0];
                     }

                     if($largeurMax > 0) {
                            if($largeur > $largeurMax) {
		                      $largeur = $largeurMax;
		                      $reduction_largeur = $largeur/$size[0];
		                      $hauteur = $size[1]*$reduction_largeur;
		                     }
		             }
    return array($largeur,$hauteur);
}

################################
// Get back path from categories
function getPath20($current_parent_id,$where,$catId,$tree2,$action,$targetF,$lang) {
global $path1, $defaultOrder;
      ($where=="top")? $trans="|" : $trans="|";
      $current_query = mysql_query("select categories_name_".$lang.", parent_id, categories_id, categories_noeud 
                                    from categories 
                                    where categories_id = '".$current_parent_id."'");
      while($current = mysql_fetch_array($current_query)) {
            $yu = $current['categories_name_'.$lang];

            $catNum = $tree2[$yu];
            $catId = $current['categories_id'];
            $catName = $current['categories_name_'.$lang];
            $catNoeud = $current['categories_noeud'];
           if($catName == "Menu") { $catName = "";}
           if($catNoeud=="B") $goTo = "index"; else $goTo = "list";
           $cat_array[] = "<img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href=\"index.php\">".maj(HOME)."</a> | <a href=\"".$goTo.".php?path=".$catId."&num=".$catNum."&action=".$action."&sort=".$defaultOrder."\">".strtoupper($yu)."</a> ".$trans."&nbsp;";
           
        }
                       if(isset($cat_array) and sizeof($cat_array)>0) {
                       $path1 = implode(",",$cat_array);
                       }
                       else {
                        switch($targetF) {
                           case "favorite":
                                 $path1 = "<img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href=\"index.php\">".maj(HOME)."</a> | <a href=\"list.php?target=favorite\">".strtoupper(COEUR)."</a>";
                           break;
                           case "flash":
                                 $path1 = "<img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href=\"index.php\">".maj(HOME)."</a> | <a href=\"list.php?target=promo&tow=flash\">".strtoupper(VENTES_FLASH)."</a>";
                           break;
                           case "new":
                                 $path1 = "<img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href=\"index.php\">".maj(HOME)."</a> | <a href=\"list.php?target=new\">".NOUVEAUTESMAJ."</a>";
                           break;
                           case "promo":
                                 $path1 = "<img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href=\"index.php\">".maj(HOME)."</a> | <a href=\"list.php?target=promo\">".strtoupper(PROMOTIONS)."</a>";
                       	   break;
                           default:
                                 $path1 = "<img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href=\"index.php\">".maj(HOME)."</a>";
                       }
                       }
             print "<b>".$path1."</b>";
  }
?>
