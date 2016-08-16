<?php 
//ob_start("ob_gzhandler");
header('P3P: CP="CAO PSA OUR"');
session_start();
//header("Cache-control: private"); 
error_reporting(E_ALL);
//error_reporting(0);
//error_reporting(E_ALL & ~E_DEPRECATED);
mysql_query("set sql_big_selects=1");
//Hide PHP-Version
Header("X-Powered-by: safe_http");
//date_default_timezone_set('Europe/Paris');

 

/*
ini_set("session.lifetime",86400);   // 86400s = 24h00
if(time() - $_SESSION['timeout'] > 30) {
	session_destroy();
	session_start();
}
$_SESSION['timeout']=time();
*/

//################################
// SESSION ID
$_SESSION['id'] = session_id();

//################################
// Anti XSS control
$chaine = strtolower($_SERVER["QUERY_STRING"]);
$searchXSS = array("<",">","'","{","","%3C","%3E",";","alert(","(",")","sql","xss","%3c","%3e","&gt","&lt","exec","select","insert","drop","create","alter","update","frame","script","function");
$replaceXSS = array("","","","","","","","","","","","","","","","","","","","","","","","","","","");
$chaine2 = str_replace($searchXSS,$replaceXSS,strip_tags($chaine));
if(preg_match("#http:#i", $chaine) OR $chaine!==$chaine2) {
	header("Location: 404.php");
	exit;
}
//--- Check all POST var
foreach($_POST AS $key=>$value) $_POST[$key] = str_replace($searchXSS,$replaceXSS,strip_tags($value));
//--- Check all GET var
foreach($_GET AS $key=>$value) $_GET[$key] = str_replace($searchXSS,$replaceXSS,strip_tags($value));

//################################
// Couleur interface boutique
if(isset($_POST['userInterface1'])) $_SESSION['userInterface'] = $_POST['userInterface1'];
if(!isset($_SESSION['userInterface']) OR empty($_SESSION['userInterface'])) $_SESSION['userInterface'] = $colorInter;

//################################
// dÈfault language if not set
if(isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'];
if(isset($_POST['lang1'])) $_SESSION['lang'] = $_POST['lang1'];
if(!isset($_SESSION['lang']) OR empty($_SESSION['lang'])) $_SESSION['lang'] = $langue;

//################################
// magasin fermÈ
if($storeClosed == "oui" AND !isset($RSSClosed)) {
	if(!isset($dirIpos)) {
		header("Location: shop_gesloten.php");
	}
	else {
		header("Location: shop_gesloten.php?lang=".$_SESSION['lang']."&css=scss");
	}
}

//################################
// largeur boutique
if(isset($_GET['widthUser'])) {$_POST['widthUser'] = $_GET['widthUser'];}
if(empty($_SESSION['storeWidthUser']) OR empty($_SESSION['storeWidthUser'])) {
	$_SESSION['storeWidthUser'] = $storeWidth;
}
if(isset($_POST['widthUser']) AND $_POST['widthUser'] !== $storeWidth) {
	$_SESSION['storeWidthUser'] = $_POST['widthUser'];
}
if(isset($_POST['widthUser']) AND $_POST['widthUser'] == $storeWidth) {
	$_SESSION['storeWidthUser'] = $storeWidth;
}

//################################
// function - Mettre en majuscule
function maj($texteZ) {
$texteZ = html_entity_decode($texteZ);
$texteZ = strtr(strtoupper($texteZ), "‰‚‡·Â„ÈËÎÍÚÛÙıˆ¯ÏÌÓÔ˘˙˚¸˝ÒÁ˛ˇÊú¯","ƒ¬¿¡≈√…»À “”‘’÷ÿÃÕŒœŸ⁄€‹›—«ﬁ›∆å–ÿ");
return $texteZ; 
}

//################################
// function - get back path from categories
function getPath2($current_parent_id,$where,$catId,$tree2,$action,$targetF,$lang) {
	global $path1, $defaultOrder, $activeRSS;
	$trans = ($where=="top")? "|" : "|";
	$current_query = mysql_query("SELECT parent_id, categories_id, categories_noeud, categories_name_".$lang." FROM categories WHERE categories_id = '".$current_parent_id."'");
	
	while($current = mysql_fetch_array($current_query)) {
		$yu = $current['categories_name_'.$lang];
		$catNum = $tree2[$yu];
		$catId = $current['categories_id'];
		$catName = $current['categories_name_'.$lang];
		$catNoeud = $current['categories_noeud'];
		if($catName == "Menu") {$catName = "";}
		if($catNoeud=="B") {
			$cat_array[] = "<a href='categories.php?path=".$catId."&num=".$catNum."&action=".$action."'>".maj($catName)."</a> ".$trans."&nbsp;";
		}
		else {
			$cat_array[] = "<a href='list.php?path=".$catId."'>".maj($catName)."</a> ".$trans."&nbsp;";
		}
		getPath2($current['parent_id'],$where,$current['categories_id'],$tree2,$action,$targetF,$lang);
	}
	if(isset($cat_array) AND sizeof($cat_array)>0) {
		$path1 = implode(",",$cat_array);
	}
	else {
		switch($targetF) {
			case "favorite":
			$path1 = "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;&nbsp;<a href='".seoUrlConvert("cataloog.php")."'>".maj(HOME)."</a> | <a href='list.php?target=favorite'>".strtoupper(COEUR)."</a> ";
			break;
			case "flash":
			$path1 = "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;&nbsp;<a href='".seoUrlConvert("cataloog.php")."'>".maj(HOME)."</a> | <a href='list.php?target=promo&tow=flash'>".strtoupper(VENTES_FLASH)."</a> ";
			break;
			case "new":
			$path1 = "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;&nbsp;<a href='".seoUrlConvert("cataloog.php")."'>".maj(HOME)."</a> | <a href='list.php?target=new'>".NOUVEAUTESMAJ."</a> ";
			break;
			case "promo":
			$path1 = "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;&nbsp;<a href='".seoUrlConvert("cataloog.php")."'>".maj(HOME)."</a> | <a href='list.php?target=promo'>".strtoupper(PROMOTIONS)."</a>";
			break;
			default:
			$path1 = "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;&nbsp;<a href='".seoUrlConvert("cataloog.php")."'>".maj(HOME)."</a>";
		}
	}
	print $path1;
}

//################################
// function - shipping price function per weight/volume
function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise,$livraisonId) {
	GLOBAL $_SESSION, $taxePosition;
	if($_poids>0) {
		$_a = mysql_query("SELECT iso, countries_shipping, countries_shipping_tax, countries_shipping_tax_active, countries_packing_tax, countries_packing_tax_active FROM countries WHERE countries_name = '".$_pays."'") or die (mysql_error());
		$_b = mysql_fetch_array($_a);
		$_zone = $_b['countries_shipping'];
		$_tax = $_b['countries_shipping_tax'];
		$_iso = $_b['iso'];
		
		if($livraisonId!=="") {
			$_c = mysql_query("SELECT weight FROM ship_price WHERE weight >= ".$_poids." AND livraison_id='".$livraisonId."' ORDER BY weight") or die (mysql_error());
			while ($_d = mysql_fetch_array($_c)) {
				$_sp[] = $_d['weight'];
			}
		}
		else {
			$_sp[0] = 0;
		}
		//if(!isset($_sp)) $_sp[0] = 0;
		//if(!isset($_sp)) $_sp[0] = 999999999;
		$_f = mysql_query("SELECT ".$_zone." FROM ship_price WHERE weight = '".$_sp[0]."' AND livraison_id='".$livraisonId."'");
		$_p = mysql_fetch_array($_f);
		
		// poids max
		if($_p[$_zone]==9999) {
			$totalWeight = sprintf("%0.2f",$_poids/1000);
			print "<p align='center'><b>";
			print TOTAL_POIDS." : ".$totalWeight." kg<br>";
			print DESTINATION." : ".$_pays."<br><br>";
			//print POIDS_MAX_DEPASSE."<br>";
			print EXPED_IMPOSSIBLE;
			print "</b></p>";
			exit;
		}
		
		$query = mysql_query("SELECT free_shipping_zone FROM admin");
		$zoneZ = mysql_fetch_array($query);
		if(preg_match ("/\b".$_zone."\b/i", $zoneZ['free_shipping_zone'])) {$gratos="yes"; $gratosPack="yes";} else {$gratos="no"; $gratosPack="no";}
		$shipPrice = $_p[$_zone]/$_poids;
		if($_b['iso']=="RM") $shipPrice = sprintf("%0.2f",0);
		if($activerPromoLivraison == "oui" AND $totalHtFinal>=$livraisonComprise AND $gratos=="yes") $shipPrice = sprintf("%0.2f",0);
		$livraisonhors = sprintf("%0.2f",$shipPrice*$_poids);
		if($_b['countries_shipping_tax_active'] == "yes") {$shipTax = sprintf("%0.2f",$livraisonhors*($_tax/100));} else {$shipTax = sprintf("%0.2f",0);}
		if($_b['countries_packing_tax_active'] == "yes") {$packTax = $_b['countries_packing_tax'];} else {$packTax = sprintf("%0.2f",0);}
		
		if(isset($_SESSION['clientTVA']) AND !empty($_SESSION['clientTVA']) AND $_iso !== $originIso) {
			$shipTax = sprintf("%0.2f",0);
			$packTax = sprintf("%0.2f",0);
		}
		if($taxePosition=="No tax") {
			$shipTax = sprintf("%0.2f",0);
			$packTax = sprintf("%0.2f",0);
		}
		return array($shipPrice, $livraisonhors, $shipTax, $gratos, $packTax, $gratosPack);
	}
	else {
		$shipPrice = sprintf("%0.2f",0);
		$livraisonhors = sprintf("%0.2f",0);
		$shipTax = sprintf("%0.2f",0);
		$gratos = "no";
		$packTax = sprintf("%0.2f",0);
		$gratosPack = "no";
		return array($shipPrice, $livraisonhors, $shipTax, $gratos, $packTax, $gratosPack);
	}
}

//################################
// function - shipping price function per paid price
/*
function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise) {
    GLOBAL $_SESSION;
    if($totalHtFinal>0) {
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_a = mysql_query("SELECT iso, countries_shipping, countries_shipping_tax, countries_shipping_tax_active, countries_packing_tax, countries_packing_tax_active FROM countries WHERE countries_name = '".$_pays."'") or die (mysql_error());
         $_b = mysql_fetch_array($_a);
         $_zone = $_b['countries_shipping'];
         $_tax = $_b['countries_shipping_tax'];
         $_iso = $_b['iso'];
         $_c = mysql_query("SELECT * FROM ship_price WHERE weight >= ".$totalHtFinal." ORDER BY weight");
         while ($_d = mysql_fetch_array($_c)) {
                $_sp[] = $_d['weight'];
         }
         $_f = mysql_query("SELECT $_zone FROM ship_price WHERE weight = '".$_sp[0]."'");
         $_p = mysql_fetch_array($_f);
         $query = mysql_query("SELECT free_shipping_zone FROM admin");
         $zoneZ = mysql_fetch_array($query);
         if(preg_match ("/\b$_zone\b/i", $zoneZ['free_shipping_zone'])) {$gratos="yes"; $gratosPack="yes";} else {$gratos="no"; $gratosPack="no";}
         $shipPrice = $_p[$_zone]/$totalHtFinal;
         if($_b['iso']=="RM") $shipPrice=0;
         if($activerPromoLivraison == "oui" and $totalHtFinal>=$livraisonComprise and $gratos=="yes") $shipPrice = sprintf("%0.2f",0);
         $livraisonhors = sprintf("%0.2f",$shipPrice*$totalHtFinal);
         if($_b['countries_shipping_tax_active'] == "yes") {$shipTax = sprintf("%0.2f",$livraisonhors*($_tax/100));} else {$shipTax = sprintf("%0.2f",0);}
         if($_b['countries_packing_tax_active'] == "yes") {$packTax = $_b['countries_packing_tax'];} else {$packTax = sprintf("%0.2f",0);}
         if(isset($_SESSION['clientTVA']) AND !empty($_SESSION['clientTVA']) AND $_iso !== $originIso) {
            $shipTax = sprintf("%0.2f",0);
            $packTax = sprintf("%0.2f",0);
         }
         return array($shipPrice, $livraisonhors, $shipTax, $gratos, $packTax, $gratosPack);
    }
    else {
         $shipPrice = sprintf("%0.2f",0);
         $livraisonhors = sprintf("%0.2f",0);
         $shipTax = sprintf("%0.2f",0);
         $gratos = "no";
         $packTax = sprintf("%0.2f",0);
         $gratosPack = "no";
         return array($shipPrice, $livraisonhors, $shipTax, $gratos, $packTax, $gratosPack);
    }
}
*/

//################################
// function - shipping price function
function tax_price($_pays,$productTax) {
	GLOBAL $iso,$taxePosition;
	$_originQueryTax = mysql_query("SELECT countries_product_tax FROM countries WHERE iso = '".$iso."'") or die (mysql_error());
	$_originTotoTax = mysql_fetch_array($_originQueryTax);
	$_originTax = $_originTotoTax['countries_product_tax'];
	if($productTax !== "0.00" AND $_originTax !== $productTax) {$_originTaxFinal = $productTax;} else {$_originTaxFinal = $_originTax;}
	
	$_a = mysql_query("SELECT countries_product_tax, countries_product_tax_active FROM countries WHERE countries_name = '".$_pays."'");
	$_b = mysql_fetch_array($_a);
	$_tax = $_b['countries_product_tax'];
	if($productTax !== "0.00" AND $_tax !== $productTax) {$_taxFinal = $productTax;} else {$_taxFinal = $_tax;}
	
	if($_b['countries_product_tax_active'] == "yes") {
		$montant_taxe = sprintf("%0.2f",$_taxFinal);
	}
	else {
		if($taxePosition == "Tax included" ) {
			$montant_taxe = sprintf("%0.2f",$_originTaxFinal);
		}
		else {
			$montant_taxe = sprintf("%0.2f",0);
		}
	}
	return array($montant_taxe,$_originTax);
}

//################################
// function - shipping price function
function DisplayProductPrice($_pays,$productTax,$prix) {
	GLOBAL $iso, $taxePosition;
	if($taxePosition == "Tax included") {
		$_a = mysql_query("SELECT countries_product_tax, countries_product_tax_active FROM countries WHERE iso = '".$_pays."'");
		$_b = mysql_fetch_array($_a);
		$_tax = $_b['countries_product_tax'];
		if($productTax !== "0.00" and $_tax !== $productTax) {$_taxFinal = $productTax;} else {$_taxFinal = $_tax;}
		if($_b['countries_product_tax_active'] == "yes") $montant_taxe = sprintf("%0.2f",$_taxFinal); else $montant_taxe = sprintf("%0.2f",0);
		$displayedPrice = sprintf("%0.2f",$prix*100/($montant_taxe+100));
	}
	if($taxePosition == "Plus tax" OR $taxePosition == "No tax") {$displayedPrice = $prix;}
	return $displayedPrice;
}

//################################
// function - ajust text width function
function adjust_text($text, $long, $comet) {
	if(strlen(strip_tags($text)) > $long) {
		$resultText = trim(substr($text,0,$long)).$comet;
	}
	else {
		$resultText = $text;
	}
	return $resultText;
}

//################################
// function - resize image function
function resizeImage($imageToResize,$haut,$largeurMax) {
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

//################################
// function - pop up size set to image size
function pop_up_to_imageSize($imagez,$namez) {
	$h1 = @getimagesize($imagez);
	if(!$h1) {$h1[1]=""; $h1[0]="";}
	print "<a href='javascript:void(0);' onClick=\"window.open('pop_up.php?im=".$imagez."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=".$h1[1].",width=".$h1[0].",toolbar=no,scrollbars=no,resizable=yes');\">
	<img src='im/_im.gif' border='0' alt='".$namez."'></a>";
	//no image file (video, music...)
	//print "<a href='".$imagez."'><img src='im/_im.gif' border='0' alt='".$namez."'></a>";
}

//################################
// function - nombre de promo en cours
function getPromo() {
	$queryx = mysql_query("SELECT s.specials_id, p.products_id
                     	  	FROM specials as s
                          	LEFT JOIN products as p ON (p.products_id = s.products_id)
					 		WHERE  s.specials_visible = 'yes'
					 	    AND p.products_visible = 'yes'
						    AND TO_DAYS(s.specials_last_day) - TO_DAYS(NOW()) >= '0' 
                          	AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())");
	$numCurrentPromo = mysql_num_rows($queryx);

   if($numCurrentPromo > 0) {
      while($promoIdx = mysql_fetch_array($queryx)) {
         $getPromoIdx[] = $promoIdx['products_id'];
      }
   }
   else {
      $getPromoIdx[] = '98755466654456466';
   }
	return array($numCurrentPromo,$getPromoIdx);
}
//################################
// Get news
function getNews() {
GLOBAL $nbre_jour_nouv;
$countNews = mysql_query("SELECT products_ref, products_id
                       FROM products
                       WHERE TO_DAYS(NOW()) - TO_DAYS(products_date_added) <= '".$nbre_jour_nouv."'
                       AND products_visible='yes'
                       AND products_ref !='GC100'");
    $numCurrentNews = mysql_num_rows($countNews);
   if($numCurrentNews > 0) {
      while($newsIdx = mysql_fetch_array($countNews)) {
         $getNewsIdx[] = $newsIdx['products_id'];
      }
   }
   else {
      $getNewsIdx[] = '98755466654456466';
   }
   return array($numCurrentNews,$getNewsIdx);
}

//################################
// function detect image or flash swf
function detectIm($image,$wi,$he) {
  $endFile = substr($image,-3);
  if($endFile == "gif" OR $endFile == "jpg" OR $endFile == "png") {
    if($wi==0 AND $he==0) {
       $returnImage = "<img src='".$image."' border='0'>";
    }
    else {
       $returnImage = "<img src='".$image."' border='0' width='".$wi."' height='".$he."' border='0'>";
    }
  }
  if($endFile == "swf") {
        if($wi==0 AND $he==0) {
            $sizeSwf = getimagesize($image);
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" '.$sizeSwf[3].'></embed>';    
        }
        else {
            $returnImage = '<embed src="'.$image.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width='.$wi.' height='.$he.'></embed>';
        }
  }
  return $returnImage;
}

//################################
// Referer url
if(isset($_SESSION['ActiveUrl'])) {
    $refererUrl = $_SESSION['ActiveUrl'];
}

//################################
// current url
if($_SERVER['REQUEST_URI']) {
    $url_id10 = $_SERVER['REQUEST_URI'];
    $_SESSION['ActiveUrl'] = $_SERVER['REQUEST_URI'];
}
else {
    $url_id10 = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
    $_SESSION['ActiveUrl'] = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
}

//################################
// Actualiser panier sauvegardÈ
if(isset($_SESSION['caddie_password']) AND isset($_GET['var']) AND $_GET['var'] == "upd") {
	$verif_list = mysql_query("SELECT users_caddie_session
	                           FROM users_caddie
	                           WHERE users_caddie_password = '".$_SESSION['caddie_password']."'
	                           AND users_caddie_number = '".$_SESSION['caddie_id']."'
	                           ");
	$row = mysql_fetch_array($verif_list);
	$session_old = $row["users_caddie_session"];
	$session_new = $_SESSION['list'];
	$update_list = mysql_query("UPDATE users_caddie
	                            SET users_caddie_session = '".$_SESSION['list']."', users_caddie_date = NOW()
	                            WHERE users_caddie_password = '".$_SESSION['caddie_password']."'
	                            AND users_caddie_number = '".$_SESSION['caddie_id']."'
	                            ");
	if($_SESSION['lang']==1) define('CADDIE_MIS_A_JOUR2','Panier mis &agrave; jour');
	if($_SESSION['lang']==2) define('CADDIE_MIS_A_JOUR2','Cart updated');
	if($_SESSION['lang']==3) define('CADDIE_MIS_A_JOUR2','Carrito actualizado');
	$message2 = "<div align='center'><b><span class='fontrouge'>".CADDIE_MIS_A_JOUR2."</span></b></div>";
}
else {
   $message2 = "";
}

//################################
// Supprimer panier sauvegardÈ
if(isset($_SESSION['caddie_password']) AND isset($_GET['var']) AND $_GET['var'] == "deleteSavedCart") {
	$delete_caddie = mysql_query("DELETE FROM users_caddie
									WHERE users_caddie_password = '".$_SESSION['caddie_password']."'
									AND users_caddie_number = '".$_SESSION['caddie_id']."'
									");
	if(isset($_SESSION['caddie_password'])) {unset($_SESSION['caddie_password']);}
	if(isset($_SESSION['caddie_id'])) {unset($_SESSION['caddie_id']);}
}

//################################
// Fermer panier sauvegardÈ
if(isset($_SESSION['caddie_password']) AND isset($_GET['var']) AND $_GET['var'] == "closeSavedCart") {
	if(isset($_SESSION['caddie_password'])) unset($_SESSION['caddie_password']);
	if(isset($_SESSION['caddie_id'])) unset($_SESSION['caddie_id']);
}

//################################
// redirection
function redirectionZ($val, $res) {
   GLOBAL $url_id10;
   $_ar1 = array("&".$val."=".$res, "?".$val."=".$res);
   $_ar2 = array("","");
   $urlZ = str_replace($_ar1,$_ar2,$url_id10);
   $urlZ = str_replace("?","&",$urlZ);
   $urlZ = str_replace(".php&",".php?",$urlZ);
   return $urlZ;
}

if(isset($_GET['var']) AND ($_GET['var']=="upd" OR $_GET['var']=="deleteSavedCart" OR $_GET['var']=="closeSavedCart" OR $_GET['var']=="session_destroy")) $url_id10 = redirectionZ("var",$_GET['var']);
if(isset($_GET['act']) AND $_GET['act'] == "sent") $url_id10 = redirectionZ("act",$_GET['act']);
if(isset($_GET['AdSearch']) AND ($_GET['AdSearch'] == "on" OR $_GET['AdSearch'] == "off")) $url_id10 = redirectionZ("AdSearch",$_GET['AdSearch']);
if(isset($_GET['widthUser'])) $url_id10 = redirectionZ("widthUser",$_GET['widthUser']);
if(isset($_GET['lang']) AND ($_GET['lang']=="1" OR $_GET['lang']=="2" OR $_GET['lang']=="3")) $url_id10 = redirectionZ("lang",$_GET['lang']);

//################################
// ? || &
if(strstr($url_id10,"?")) $slash = "&"; else $slash = "?";

//################################
// Verifier affiliation
if(isset($_GET['eko']) AND !empty($_GET['eko']) AND !isset($_SESSION['affiliateNumber'])) {
	$queryAff = mysql_query("SELECT aff_com FROM affiliation  WHERE aff_number = '".$_GET['eko']."'");
	$queryAffNum = mysql_num_rows($queryAff);
	if($queryAffNum>0) {
		$findAffiliate = mysql_fetch_array($queryAff);
		$_SESSION['affiliateNumber'] = $_GET['eko'];
		$_SESSION['affiliateCom'] = $findAffiliate['aff_com'];
	}
}

//################################
// fontion recurcive de recherche des sous-categories dans les categories
function recurs3($origin) {
	global $jj;
	$findOriginQuery = mysql_query("SELECT categories_id, parent_id, categories_noeud, categories_visible FROM categories WHERE parent_id = '".$origin."'");
	if(mysql_num_rows($findOriginQuery)>0) {
		while($findOrigin = mysql_fetch_array($findOriginQuery)) {
			if($findOrigin['categories_noeud']=="L" AND $findOrigin['categories_visible']=='yes') $jj[] = $findOrigin['categories_id'];
			recurs3($findOrigin['categories_id']);
		}
		return $jj;
	}
}

//################################
// Function text width
function display_title($name,$num) {
    $artLong = strlen(strip_tags($name));
    if($artLong > $num) {$dispTitle = "title='".$name."'";} else {$dispTitle = '';}
    return $dispTitle;
}
		
//################################
// Initialiser le menu deroulant
if(isset($dir) AND $dir=="../") {
    require("../includes/menu/treeclass.inc");
} 
else {
    require("includes/menu/treeclass.inc");
}

//################################
//Initialiser le menu tab
if(!isset($_SESSION['tree'])) {
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
	if(!isset($_SESSION['tree2'])) {
		for($k=0 ;$k < sizeof($_SESSION['tree']) ; $k++) {
			$_SESSION['tree2'][$_SESSION['tree'][$k][5]] = $k;
			$_SESSION['tree2'][$_SESSION['tree'][$k][9]] = $k;
			$_SESSION['tree2'][$_SESSION['tree'][$k][10]] = $k;
		}
	}
}

//################################
// Advanced search
if(isset($_GET['AdSearch']) AND $_GET['AdSearch']=="on") { $_SESSION['AdSearch'] = "on";}
if(isset($_GET['AdSearch']) AND $_GET['AdSearch']=="off") { $_SESSION['AdSearch'] = "off";}
if(!isset($_SESSION['AdSearch'])) { $_SESSION['AdSearch'] = "off";}

//################################
// function resize image + optimisation with server GD on ON 
function infoImageFunction($image, $largMax, $hautMax) {
	$sizeImOrigin = @getimagesize($image);
	if(!$sizeImOrigin) $image="im/zzz_gris.gif";
	//$ext=strstr($image, '.');
	$ext2 = explode(".", $image);
	$ext2 = end($ext2);
	$ext = ".$ext2";
	$extension=strtoupper($ext);
	$largImOrigin=$sizeImOrigin[0];
	$hautImOrigin=$sizeImOrigin[1];
	if($largImOrigin>$largMax) {
		$hautImDisplayed=$hautImOrigin*$largMax/$largImOrigin;
		$hautImDisplayed=round($hautImDisplayed);
		$largImDisplayed=$largMax;
	}
	else {
		$largImDisplayed=$largImOrigin;
		$hautImDisplayed=$hautImOrigin;
	}
	if($hautImDisplayed>$hautMax) {
		$largImDisplayed=$largImDisplayed*$hautMax/$hautImDisplayed;
		$largImDisplayed=round($largImDisplayed);
		$hautImDisplayed=$hautMax;
	}
	return array($extension, $largImOrigin, $hautImOrigin, $largImDisplayed, $hautImDisplayed);
}

//################################
// Vider panier

if(isset($_GET['var']) and $_GET['var'] == "session_destroy") {
	if(isset($_SESSION['list'])) unset($_SESSION['list']);
	if(isset($_SESSION['list2'])) unset($_SESSION['list2']);
	if(isset($_SESSION['taxStatut'])) unset($_SESSION['taxStatut']);
	if(isset($_SESSION['totalHtFinal'])) unset($_SESSION['totalHtFinal']);
	if(isset($_SESSION['totalHtFinalPromo'])) unset($_SESSION['totalHtFinalPromo']);
	if(isset($_SESSION['livraisonhors'])) unset($_SESSION['livraisonhors']);
	if(isset($_SESSION['totalToPayTTC'])) unset($_SESSION['totalToPayTTC']);
	if(isset($_SESSION['livraisonTTC'])) unset($_SESSION['livraisonTTC']);
	if(isset($_SESSION['totalTax'])) unset($_SESSION['totalTax']);
	if(isset($_SESSION['shipTax'])) unset($_SESSION['shipTax']);
	if(isset($_SESSION['itemTax'])) unset($_SESSION['itemTax']);
	if(isset($_SESSION['totalTTC'])) unset($_SESSION['totalTTC']);
	if(isset($_SESSION['tot_art'])) unset($_SESSION['tot_art']);
	if(isset($_SESSION['caddie_password'])) unset($_SESSION['caddie_password']);
	if(isset($_SESSION['caddie_id'])) unset($_SESSION['caddie_id']);
	if(isset($_SESSION['poids'])) unset($_SESSION['poids']);
	if(isset($_SESSION['clientComment'])) unset($_SESSION['clientComment']);
	if(isset($_SESSION['clientTVA'])) unset($_SESSION['clientTVA']);
	if(isset($_SESSION['clientNic'])) unset($_SESSION['clientNic']);
	if(isset($_SESSION['clientPassword'])) unset($_SESSION['clientPassword']);
	if(isset($_SESSION['paymentMode'])) unset($_SESSION['paymentMode']);
	if(isset($_SESSION['clientFax'])) unset($_SESSION['clientFax']);
	if(isset($_SESSION['clientTelephone'])) unset($_SESSION['clientTelephone']);
	if(isset($_SESSION['clientCity'])) unset($_SESSION['clientCity']);
	if(isset($_SESSION['clientProvince'])) unset($_SESSION['clientProvince']);
	if(isset($_SESSION['clientCountry'])) unset($_SESSION['clientCountry']);
	if(isset($_SESSION['clientPostCode'])) unset($_SESSION['clientPostCode']);
	if(isset($_SESSION['clientSurburb'])) unset($_SESSION['clientSurburb']);
	if(isset($_SESSION['clientStreetAddress'])) unset($_SESSION['clientStreetAddress']);
	if(isset($_SESSION['clientEmail'])) unset($_SESSION['clientEmail']);
	if(isset($_SESSION['clientLastname'])) unset($_SESSION['clientLastname']);
	if(isset($_SESSION['clientCompany'])) unset($_SESSION['clientCompany']);
	if(isset($_SESSION['clientFirstname'])) unset($_SESSION['clientFirstname']);
	if(isset($_SESSION['clientGender'])) unset($_SESSION['clientGender']);
	if(isset($_SESSION['montantRemise'])) unset($_SESSION['montantRemise']);
	if(isset($_SESSION['shipPrice'])) unset($_SESSION['shipPrice']);
	if(isset($_SESSION['activerCoupon'])) unset($_SESSION['activerCoupon']);
	if(isset($_SESSION['coupon_name'])) unset($_SESSION['coupon_name']);
	if(isset($_SESSION['montantRemise2'])) unset($_SESSION['montantRemise2']);
	if(isset($_SESSION['users_shipping_price'])) unset($_SESSION['users_shipping_price']);
	if(isset($_SESSION['saveDataFromForm'])) unset($_SESSION['saveDataFromForm']);
	if(isset($_SESSION['users_coupon_note'])) unset($_SESSION['users_coupon_note']);
	if(isset($_SESSION['users_remise_note'])) unset($_SESSION['users_remise_note']);
	if(isset($_SESSION['users_account_remise_note'])) unset($_SESSION['users_account_remise_note']);
	if(isset($_SESSION['users_shipping_note'])) unset($_SESSION['users_shipping_note']);
	if(isset($_SESSION['fact_adresse'])) unset($_SESSION['fact_adresse']);
	if(isset($_SESSION['contre'])) unset($_SESSION['contre']);
	if(isset($_SESSION['accountRemise'])) unset($_SESSION['accountRemise']);
	if(isset($_SESSION['devisNumero'])) unset($_SESSION['devisNumero']);
	if(isset($_SESSION['activerCadeau'])) unset($_SESSION['activerCadeau']);
	if(isset($_SESSION['cadeau_number'])) unset($_SESSION['cadeau_number']);
	if(isset($_SESSION['gc_reduc'])) unset($_SESSION['gc_reduc']);
	if(isset($_SESSION['montantRemise3'])) unset($_SESSION['montantRemise3']);
	if(isset($_SESSION['totalDisplayedCart'])) unset($_SESSION['totalDisplayedCart']);
	if(isset($_SESSION['ff'])) unset($_SESSION['ff']);
	//if(isset($_SESSION['ActiveUrl'])) {unset($_SESSION['ActiveUrl']);}
	if(isset($_SESSION['multipleTax'])) unset($_SESSION['multipleTax']);
	if(isset($_SESSION['deee'])) unset($_SESSION['deee']);
	if(isset($_SESSION['getNews'])) unset($_SESSION['getNews']);
	if(isset($_SESSION['getNewsId'])) unset($_SESSION['getNewsId']);
	if(isset($_SESSION['getPromo'])) unset($_SESSION['getPromo']);
	if(isset($_SESSION['getPromoId'])) unset($_SESSION['getPromoId']);
	if(isset($_SESSION['accountRemiseEffec'])) unset($_SESSION['accountRemiseEffec']);
	if(isset($_SESSION['priceEmballageTTC'])) unset($_SESSION['priceEmballageTTC']);
	if(isset($_SESSION['totalEmballageHt'])) unset($_SESSION['totalEmballageHt']);
	if(isset($_SESSION['totalEmballageTva'])) unset($_SESSION['totalEmballageTva']);
	if(isset($_SESSION['coupon_items'])) unset($_SESSION['coupon_items']);
	if(isset($_SESSION['accountRemiseActive'])) unset($_SESSION['accountRemiseActive']);
	if(isset($_SESSION['accountRemise2'])) unset($_SESSION['accountRemise2']);
	if(isset($_SESSION['langDispoZ'])) unset($_SESSION['langDispoZ']);
	if(isset($_SESSION['shippingName'])) unset($_SESSION['shippingName']);
	if(isset($_SESSION['shippingId'])) unset($_SESSION['shippingId']);
	if(isset($_SESSION['discountQt'])) unset($_SESSION['discountQt']);
}

//################################
// Ouvrir session devisNumero
if((isset($_POST['devisNumero']) OR isset($_GET['dvn'])) AND !isset($_GET['var'])) {
	if(isset($_POST['devisNumero'])) $devisNumeroV = $_POST['devisNumero'];
	if(isset($_GET['dvn'])) $devisNumeroV = $_GET['dvn'];
	$devisQuery = mysql_query("SELECT devis_traite, TO_DAYS(NOW())-TO_DAYS(devis_date_end) as diff FROM devis WHERE devis_number = '".$devisNumeroV."'") or die (mysql_error());
	$devisQueryNum = mysql_num_rows($devisQuery);
	if($devisQueryNum > 0) {
		$myhid = mysql_fetch_array($devisQuery);
			if($myhid['diff'] <= 0 AND $myhid['devis_traite']=="no") {
			$_SESSION['devisNumero'] = $devisNumeroV;
		}
		else {
			if(isset($_SESSION['lang']) AND $_SESSION['lang']==1) {DEFINE("DEVIS11","Devis non valide!");}  
			if(isset($_SESSION['lang']) AND $_SESSION['lang']==2) {DEFINE("DEVIS11","Estimate not valid!");}
			if(isset($_SESSION['lang']) AND $_SESSION['lang']==3) {DEFINE("DEVIS11","Offerte nummer is ongeldig");}
			$DevisMessage = DEVIS11;
		}
	}
	else {
		if(isset($_SESSION['lang']) AND $_SESSION['lang']==1) {DEFINE("DEVIS11","Aucun devis trouvÈ!");}
		if(isset($_SESSION['lang']) AND $_SESSION['lang']==2) {DEFINE("DEVIS11","No estimate found!");}
		if(isset($_SESSION['lang']) AND $_SESSION['lang']==3) {DEFINE("DEVIS11","Offerte nummer is ongeldig");} 
		$DevisMessage = DEVIS11;
	}
	
	if(isset($_SESSION['devisNumero'])) {
		$devisQuery = mysql_query("SELECT devis_products_new, devis_products, devis_client, devis_products_new, devis_remise_commande, devis_remise_coupon, devis_tva 
									FROM devis 
									WHERE devis_number = '".$_SESSION['devisNumero']."'") or die (mysql_error());
		$thisDevis = mysql_fetch_array($devisQuery);
		if(empty($thisDevis['devis_products_new'])) {
			$_SESSION['list'] = $thisDevis['devis_products'];
			if(!empty($thisDevis['devis_client'])) {
				$_SESSION['openAccount'] = "yes";
				$_SESSION['account'] = $thisDevis['devis_client'];
			}
			else {
				if(isset($_SESSION['openAccount'])) unset($_SESSION['openAccount']);
				if(isset($_SESSION['account'])) unset($_SESSION['account']);
				if(isset($_SESSION['caddie_password'])) unset($_SESSION['caddie_password']);
				if(isset($_SESSION['caddie_id'])) unset($_SESSION['caddie_id']);
			}
		}
		else {
			$_SESSION['list'] = $thisDevis['devis_products_new'];
			if(!empty($thisDevis['devis_client'])) {
				$_SESSION['openAccount'] = "yes";
				$_SESSION['account'] = $thisDevis['devis_client'];
			}
			else {
				if(isset($_SESSION['openAccount'])) unset($_SESSION['openAccount']);
				if(isset($_SESSION['account'])) unset($_SESSION['account']);
				if(isset($_SESSION['caddie_password'])) unset($_SESSION['caddie_password']);
				if(isset($_SESSION['caddie_id'])) unset($_SESSION['caddie_id']);
			}
			if($thisDevis['devis_remise_commande'] !== 0) {
				if(!isset($_SESSION['accountRemiseActive'])) $_SESSION['accountRemise'] = $thisDevis['devis_remise_commande'];
			}
			if(!empty($thisDevis['devis_remise_coupon'])) {
				$_SESSION['activerCoupon'] = 1;
				$_SESSION['coupon_name'] = $thisDevis['devis_remise_coupon'];
			}
			if(!empty($thisDevis['devis_tva'])) {
				$_SESSION['clientTVA'] = $thisDevis['devis_tva'];
			}
		}
	}
}

//################################
// function quick menu (complete menu list from quick menu module)
function recur($tab,$pere,$rang3,$lang) {
	global $defaultOrder,$_GET,$maxCarQuick,$_SESSION;
	$tabNb = count($tab);
	for($x=0;$x<$tabNb;$x++) {
		if($tab[$x][1]==$pere) {
			if(isset($_GET['path'])) {
				$smenu_var2 = mysql_query("SELECT categories_name_".$_SESSION['lang'].", categories_noeud
											FROM categories
											WHERE categories_id = '".$_GET['path']."'
											AND categories_visible = 'yes'");
				$smenu2 = mysql_fetch_array($smenu_var2);
				$catSelected = $smenu2['categories_name_'.$lang];
			}
			else {
				$catSelected = "";
			}
			$catOrigin = $tab[$x][2];
			if($catSelected == $catOrigin) {$dd="selected";} else {$dd="";}
			if($tab[$x][4] == "L") {
				$valueLink = "list.php?path=".$tab[$x][0]."&action=e";
				$wt = "scat";
				$classStyle = "style='BACKGROUND-COLOR: #f1f1f1; color:#000000'";
				$catNom = $tab[$x][2];
			}
			else {
				$menuNumee = $_SESSION['tree2'][$tab[$x][2]];
				$valueLink = "categories.php?path=".$tab[$x][0]."&num=".$menuNumee."&action=e";
				$wt = "cat";
				$classStyle = "style='BACKGROUND-COLOR: #dddddd; color:#808080'";
				$catNom = "&bull; ".$tab[$x][2];
			}
			//if(substr($catNom,0,7)=="&bull; ") $maxCarQuick2 = $maxCarQuick+1; else $maxCarQuick2 = $maxCarQuick;
			$catNom = adjust_text($catNom,$maxCarQuick,"..");
			print "<option value='".$valueLink."' $dd $classStyle>".espace3($rang3).$catNom."</option>";
			recur($tab,$tab[$x][0],$rang3+1,$lang);
		}
	}
}

//################################
// function quick menu (calcul des espaces module quick menu)
function espace3($rang3) {
	$ch="";
	for($x=0;$x<$rang3;$x++) {
		$ch=$ch."&nbsp;";
  	}
	return $ch;
}

//################################
// Appliquer reduc client
function newPrice($price, $reduc) {
    $newPrice = sprintf("%0.2f",$price-($price*$reduc/100));
    return $newPrice;
}

//################################
// function remove-replace slash
function rep_slash($rem) {
	$rem = stripslashes($rem);
	$rem = str_replace("&#146;","'",$rem);
	return $rem;
}

/*################################
functions de cryptage
---------------------------------------------------------
NE PAS SUPPRIMER - NE PAS MODIFIER LA FONCTION CI-DESSOUS
---------------------------------------------------------
Cette fonction est obligatoire. Il permet d'identifier le titulaire de la licence et la version de cette boutique.
Toute suppression ou modification du code ci-dessous ou du fichier version.txt devra Ítre justifiÈ et pourrait Ítre considÈrÈ comme une offense par l'auteur et/ou le service juridique de BoutikOne.
Merci de votre comprÈhension.
L'Èquipe BoutikOne.
*/
function GenerationCle($Texte,$CleDEncryptage) {
	$CleDEncryptage = md5($CleDEncryptage);
	$Compteur=0;
	$VariableTemp = "";
	for($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
		if($Compteur==strlen($CleDEncryptage))
		$Compteur=0;
		$VariableTemp.= substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1);
		$Compteur++;
	}
	return $VariableTemp;
}
function Crypte($Texte,$Cle) {
	srand((double)microtime()*1000000);
	$CleDEncryptage = md5(rand(0,32000));
	$Compteur=0;
	$VariableTemp = "";
	for($Ctr=0;$Ctr<strlen($Texte);$Ctr++) {
		if($Compteur==strlen($CleDEncryptage))
		$Compteur=0;
		$VariableTemp.= substr($CleDEncryptage,$Compteur,1).(substr($Texte,$Ctr,1) ^ substr($CleDEncryptage,$Compteur,1) );
		$Compteur++;
	}
	return base64_encode(GenerationCle($VariableTemp,$Cle) );
}

//################################
// DISPLAY PAYMENT PROCESS
function display_payment_process($pos,$chem) {
    if($pos==1) {
		$proc1="<img src='".$chem."im/fleche_bottom_process.gif'>"; $proc2=""; $proc3=""; $proc4=""; 
        $process_color1="TABLEPaymentProcessSelected"; $process_color2=""; $process_color3=""; $process_color4="";
    }
    if($pos==2) {
		$proc1=""; $proc2="<img src='".$chem."im/fleche_bottom_process.gif'>"; $proc3=""; $proc4="";
        $process_color1=""; $process_color2="TABLEPaymentProcessSelected"; $process_color3=""; $process_color4="";
    }
    if($pos==3) {
		$proc1=""; $proc2=""; $proc3="<img src='".$chem."im/fleche_bottom_process.gif'>"; $proc4="";
        $process_color1=""; $process_color2=""; $process_color3="TABLEPaymentProcessSelected"; $process_color4="";
    }
    if($pos==4) {
		$proc1=""; $proc2=""; $proc3=""; $proc4="<img src='".$chem."im/fleche_bottom_process.gif'>";
        $process_color1=""; $process_color2=""; $process_color3=""; $process_color4="TABLEPaymentProcessSelected";
    }
	// top 
	print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>";
	print "<tr>";
	print "<td width='25%' align='center'>".$proc1."</td>";
	print "<td align='center' width='1'><img src='".$chem."im/zzz.gif' height='1' width='1'></td>";
	print "<td width='25%' align='center'>".$proc2."</td>";
	print "<td align='center' width='1'><img src='".$chem."im/zzz.gif' height='1' width='1'></td>";
	print "<td width='25%' align='center'>".$proc3."</td>";
	print "<td align='center' width='1'><img src='".$chem."im/zzz.gif' height='1' width='1'></td>";
	print "<td width='25%' align='center'>".$proc4."</td>";
	print "</tr>";
	print "</table>";
	// bottom
	print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='0' class='TABLEPaymentProcess'>";  
	print "<tr height='20'>";
	print "<td width='25%' align='center' class='".$process_color1."'>";
	print "<b>1</b>. ".maj(VOTRE_CADDIE);
	print "</td>";
	print "<td align='center' width='1'><img src='".$chem."im/zzz_gris.gif' height='16' width='1'></td>";
	
	print "<td width='25%' align='center' class='".$process_color2."'>";
	print "<b>2</b>. ".maj(LIV);
	print "</td>";
	print "<td align='center' width='1'><img src='".$chem."im/zzz_gris.gif' height='16' width='1'></td>";
	
	print "<td width='25%' align='center' class='".$process_color3."'>";
	print "<b>3</b>. ".maj(PAIEMENT);
	print "</td>";
	print "<td align='center' width='1'><img src='".$chem."im/zzz_gris.gif' height='16' width='1'></td>";
	
	print "<td width='25%' align='center' class='".$process_color4."'>";
	print "<b>4</b>. ".maj(CONF);
	print "</td>";
	print "</tr>";
	print "</table>";
	print "<br>";
}

//################################
// Recursive function - Get title id from categories
function getSubCatId($origin) {
	GLOBAL $jj;
	$findOriginQuery = mysql_query("SELECT categories_id, parent_id, categories_name_1 FROM categories WHERE categories_id = '".$origin."'");
	$findOrigin = mysql_fetch_array($findOriginQuery);
	if($findOrigin['parent_id'] == "0") {
		$jj[] = $findOrigin['categories_id'];
		return $jj;
	}
	else {
		$jj[] = $findOrigin['categories_id'];
		$jjw = getSubCatId($findOrigin['parent_id']);
		return $jjw;
	}
}

//################################
// Recursive function - Get title Name from categories
function getSubCatName($catTitle) {
    if(sizeof($catTitle) > 0) {
        foreach($catTitle as $elem) {
        	$recurCatQuery = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$elem."'");
            $recurCatResult = mysql_fetch_array($recurCatQuery);
            $recurCat[] = $recurCatResult['categories_name_'.$_SESSION['lang']];
        }
    }
    if(sizeof($recurCat) > 0) {
    	$recurCat = array_reverse($recurCat);
        $recurCat = array_unique($recurCat);
        $titleCat = implode(" > ",$recurCat);
        return $titleCat;
    }
}

//################################
// comptabiliser news & promos
if(!isset($_SESSION['getNews'])) {
	$newsCountAll = getNews();
	$_SESSION['getNews'] = $newsCountAll[0];
	$_SESSION['getNewsId'] = $newsCountAll[1];   
}
if(!isset($_SESSION['getPromo'])) {
	$promoCountAll = getPromo();
	$_SESSION['getPromo'] = $promoCountAll[0];
	$_SESSION['getPromoId'] = $promoCountAll[1];
}

//################################
// replace single cote
function replace_ap($val) {
	$_val = str_replace("'","&#146;",$val);
	return $_val;
}

//################################
// function round corner top - bottom
function round_top($active,$widthZ, $css) {
	GLOBAL $activeRoundTop;
	if($active=='yes') {
		print '<div class="'.$css.'" style="width:'.$widthZ.'">';
		print '<b class="top2"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>';
		print '<div class="boxcontent2">';
	}
	else {
		print '<div class="'.$css.'" style="width:'.$widthZ.'">';
		print '<div class="boxcontent2No">';
	}
	$activeRoundTop = $active;
}
function round_bottom($active) {
	if($active=='yes') {
		print '</div>';
		print '<b class="bottom2"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>';
		print '</div>';
	}
	if($active=='no') {
		print '</div>';
		print '<b class="bottom2"><b class="b1b2"></b></b>';
		print '</div>';
	}
}

/*
function round_top($active, $widthZ, $borderColor, $backColor) {
      GLOBAL $activeRoundTop;
      if($active=='yes') {
      print '<div class="raised2" style="width:'.$widthZ.'">';
      print '<b class="top2"><b class="b1" style="BACKGROUND:#'.$borderColor.';"></b><b class="b2" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'"></b><b class="b3" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'"></b><b class="b4" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'"></b></b>';
      print '<div class="boxcontent2" style="BACKGROUND:#'.$backColor.'; border-right-color:#'.$borderColor.'; border-left-color:#'.$borderColor.'; ">';
      }
      else {
      print '<div class="raised2" style="width:'.$widthZ.'">';
      print '<div class="boxcontent2No" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'; border-top-color:#'.$borderColor.';">';
      }
      $activeRoundTop = $active;
}
function round_bottom($active, $borderColor, $backColor) {
      if($active=='yes') {
      print '</div>';
      print '<b class="bottom2"><b class="b4b" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'"></b><b class="b3b" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'"></b><b class="b2b" style="BACKGROUND:#'.$backColor.'; border-left-color:#'.$borderColor.'; border-right-color:#'.$borderColor.'"></b><b class="b1b" style="BACKGROUND:#'.$borderColor.';"></b></b>';
      print '</div>';
      }
      if($active=='no') {
      print '</div>';
      print '<b class="bottom2"><b class="b1b2" style="BACKGROUND:#'.$borderColor.';"></b></b>';
      print '</div>';
      }
}
*/


//################################
// function add_flags
function add_flags() {
	GLOBAL $url_id10,$slash,$_SESSION;
	$langDispo = mysql_query("SELECT code, visible FROM languages WHERE visible='yes'");
	while($arow = mysql_fetch_array($langDispo)) {
		if($arow['visible']=='yes') {
	    	if($arow['code'] == 1 ) {$tit = 'title = "FranÁais"'; $sort=2;}
	        if($arow['code'] == 2 ) {$tit = 'title = "English"'; $sort=3;}
	        if($arow['code'] == 3 ) {$tit = 'title = "Nederlands"'; $sort=1;}
	        if(!isset($tit)) $tit = "";
	        $url_id101 = str_replace("lang=".$_SESSION['lang']."&","",$url_id10);
	        $flags[$sort] = "<a href=".$url_id101.$slash."lang=".$arow['code']."><img ".$tit." src='im/flag_".$arow['code'].".gif' border='0' align='absmiddle'></a>&nbsp;";
	    }
	}
	ksort($flags);
	foreach ($flags as $item) {print "&nbsp;".$item;}
}

//################################
// count items in the cart
function cart_Item() {
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

//################################
// function Format date
function dateFr($fromDate,$langId) {
	$_qq = explode(" ",$fromDate);
	$_qq1 = explode("-",$_qq[0]);
	if($langId==1 OR $langId==3) $_qq3 = $_qq1[2]."/".$_qq1[1]."/".$_qq1[0];
	if($langId==2) $_qq3 = $_qq[0];
	return $_qq3;
}

//################################
// Define randomly path if categories_id is not set
function pathNotSet($noeud) {
	$pathQuery = mysql_query("SELECT categories_id 
	                          FROM categories 
	                          WHERE categories_id != '0' 
	                          AND categories_noeud='".$noeud."' 
	                          AND categories_visible='yes' 
	                          ORDER BY rand() limit 1") or die (mysql_error());
	if(mysql_num_rows($pathQuery)>0) {
    $pathResult = mysql_fetch_array($pathQuery);
    	$_GET['path'] = $pathResult['categories_id'];
	}
	else {
    	header("Location: ".seoUrlConvert("cataloog.php")."");
	}
}

//################################
// Select lang dispo
if(!isset($_SESSION['langDispoZ'])) {
	$langDispoZ = mysql_query("SELECT languages_id FROM languages WHERE visible='yes'");
	$_SESSION['langDispoZ'] = mysql_num_rows($langDispoZ);
}

//################################
// Display price in currency2
function curPrice($pri, $cur, $position) {
	GLOBAL $_SESSION, $tauxDevise2;
	if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
		$currencyPrice =  newPrice($pri, $_SESSION['reduc'])*$tauxDevise2;
	}
	else {
		$currencyPrice = $pri*$tauxDevise2;
	}
	$currencyPrice = "<div title='".A_TITRE_INDICATF."' class='FontGris tiny' align='".$position."'><i>[".sprintf("%0.2f",$currencyPrice)."&nbsp;".$cur."]</i></div>";
	return $currencyPrice;
}

//#################################
// Annulation rÈcupÈration commande
if(isset($_GET['cancel']) AND $_GET['cancel']==1 AND isset($_SESSION['recup'])) unset($_SESSION['recup']);

//#################################
// Function create NIC
function generate_nic() {
	GLOBAL $str1, $str2;
	$clientNic1 = '';
	for( $i=0; $i<5; $i++) {
	    $clientNic1 .= substr($str1, rand(0, strlen($str1) - 1), 1);
	}
	$clientNic2 = '';
	for( $i=0; $i<2; $i++) {
	    $clientNic2 .= substr($str2, rand(0, strlen($str2) - 1), 1);
	}
	$_nic = $clientNic1.'-'.$clientNic2;
	$queryQuery = mysql_query("SELECT users_id FROM users_orders WHERE users_nic = '".$_nic."'");
    if(mysql_num_rows($queryQuery)>0) {
		$_nic = generate_nic();
	}
	return $_nic;
}

//#################################
// Function customer account
function generate_account() {
	GLOBAL $str1;
	$_account = '';
    	for( $i = 0; $i < 7 ; $i++) {
        	$_account.= substr($str1, rand(0, strlen($str1) - 1), 1);
        }
	$queryQuery = mysql_query("SELECT users_pro_id FROM users_pro WHERE users_pro_password = '".$_account."'");
    if(mysql_num_rows($queryQuery)>0) {
		$_account = generate_account();
	}
	return $_account;
}

//################################
// function - get discount on quantity
function getDiscount() {
	$queryDiscount = mysql_query("SELECT discount_qt_prod_id FROM discount_on_quantity");
	$queryDiscountNum = mysql_num_rows($queryDiscount);
	if($queryDiscountNum > 0) {
		while($discountId = mysql_fetch_array($queryDiscount)) {
			$getDiscountId[] = $discountId['discount_qt_prod_id'];
		}
		$getDiscountId = array_unique($getDiscountId);
		return $getDiscountId;
	}
	else {
		$getDiscountId[] = '98755466654456466';
		// FIX FOR ERROR OUTPUT START
		return $getDiscountId;
		// FIX FOR ERROR OUTPUT END
	}
}
if(!isset($_SESSION['discountQt'])) {
	$_SESSION['discountQt'] = getDiscount();
}

//################################
// function - seoUrlConvert
function seoUrlConvert($raw_url) {
	global $_SESSION;
	$seo_switch = true;
	$seourl_names = array(
					  //french
					  "fr" => array(
					  		   "cataloog"		=> "catalogue",
							   "categories"	=> "catÈgories",
							   "list"			=> "liste",
							   "beschrijving"	=> "description",
							   "infos"		=> "infos"
					  	      ),
					  //english
					  "en" => array(
					  		   "cataloog"		=> "catalog",
							   "categories"	=> "categories",
							   "list"			=> "list",
							   "beschrijving"	=> "description",
							   "infos"		=> "info"
					  	      ),
					  //dutch
					  "br" => array(
					  		   "cataloog"		=> "cataloog",
							   "categories"	=> "categories",
							   "list"			=> "list",
							   "beschrijving"	=> "beschrijving",
							   "infos"		=> "infos"
					  	      )
					);
	$param_sort = array("id", "path", "target", "sort", "view", "page", "num", "action", "info", "lang");
	$rawparam_arr = array();
	$seo_url_param = array();
	$seo_url_lastparam = "";
	$seo_url = "";
	if($seo_switch) {
		//get url file name and parameters
		$tmp_rawurl = explode("?", $raw_url);
		if(isset($tmp_rawurl[0])) {
			$tmp_urlfname = explode(".", $tmp_rawurl[0]);
			//get param from path
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
							//$catTitleMain = getSubCatId($_GET['path']);
    						//$cat_name = getSubCatName($catTitleMain);
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
					case "beschrijving":
						if(isset($rawparam_arr['id'])) {
							$queryProduct = mysql_query("SELECT products_name_".$_SESSION['lang']." as prod_name FROM products WHERE products_id = '".$rawparam_arr['id']."' AND products_visible = 'yes'");
							$queryProductNum = mysql_num_rows($queryProduct);
							if($queryProductNum > 0) {
								while($product = mysql_fetch_array($queryProduct)) {
									$prod_name = $product['prod_name'];
								}
								$seo_prod_name = str_replace(" ", "_", $prod_name);
								$seo_url_lastparam .= $seo_prod_name;
							}
						}
						break;
					case "infos":
						if(isset($rawparam_arr['info'])) {
							$info_name_arr = array(
												1	=> EXPEDITIONS_ET_RETOURS_MAJ,
												3	=> PAIEMENTS,
												4	=> CONDITIONS_D_USAGE,
												5	=> NOUS_CONTACTER,
												6	=> UTILISATION_CADDIE,
												8	=> NOUS_CONTACTER,
												9	=> PARTENAIRES,
												10	=> QUI_SOMMES_NOUS,
												11	=> INTERFACE_DE_SUIVIT_CLIENT,
												12	=> CONDITIONS_AFF
											 );
							if(isset($info_name_arr[$rawparam_arr['info']])) {
								$seo_info_name = str_replace(" ", "_", $info_name_arr[$rawparam_arr['info']]);
								$seo_url_lastparam .= $seo_info_name;
							}
						}
						break;
					default:
						break;
				}
				$seo_url .= implode("-", $seo_url_param);
				if(isset($seo_url_lastparam) && !empty($seo_url_lastparam)) {
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

//################################
// function basicSeo
function basicSeo($seo_url) {
	$param_sort = array("id", "path", "target", "sort", "view", "page", "num", "action", "info", "lang");
}

?>