<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");

 
function productNum2($productNum,$lang) {
	GLOBAL $addToQuery2;
	$sousresult2 = mysql_query("SELECT categories_id_bis, products_name_".$lang.", products_deee
								FROM products
								WHERE categories_id = '".$productNum."' 
								AND products_visible = 'yes'
								".$addToQuery2."
								OR (categories_id_bis LIKE '%|".$productNum."|%' AND products_visible = 'yes')
								");
	$rows2 = mysql_num_rows($sousresult2);
	return $rows2;
}


if(!isset($_GET['path']) OR $_GET['path']=='') pathNotSet("B");
if(!isset($_GET['action'])) $_GET['action'] = "e";
 
$catTitle = getSubCatId($_GET['path']);
$title = getSubCatName($catTitle);

 
function display_table() {
      GLOBAL $largTableCategories, 
             $tableMaxHeight, 
             $_SESSION, 
             $_GET, 
             $c_row, 
             $symbolDevise, 
             $imageSizeCat, 
             $actRes,
             $gdOpen,
             $backGdColor,
             $categoriesPop,
             $activeSeuilPromo,
             $displayPriceInShop,
             $imZoomMax,
             $seuilPromo,
             $carSizeTitleCat,
             $devise2Visible,
             $symbolDevise2,
			 $displayDescCat,
			 $displayDescCatNum,
			 $backGdColorListLine;

 
      $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";

  
      if(!empty($c_row['specials_new_price'])) {
      		if($c_row['specials_visible']=="yes") {
                     $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
                     $dateMaxCheck = (!empty($c_row['specials_last_day']))? explode("-",$c_row['specials_last_day']) : explode("-","0-0-0");
                     $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
                     $dateDebutCheck = (!empty($c_row['specials_first_day']))? explode("-",$c_row['specials_first_day']) : explode("-","0-0-0");
                     $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
                     
					 if($dateDebut <= $today  and $dateMax >= $today) {
                        $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
                        $price = $c_row['specials_new_price'];
                        $old_price = $c_row['products_price'] ;
                        $display_price = "<b><s>".$old_price."</s> ".$symbolDevise."<br><span class='PromoFontColorNumber'>".$price." ".$symbolDevise."</span></b>";
                        $saveNum = (1-($price/$old_price))*100;
                        $saveNum = sprintf("%0.2f",$saveNum);
   
                        if($activeSeuilPromo=="oui" AND $seuilPromo > 0 AND isset($itMiss) AND $itMiss <= $seuilPromo) $displayFlash = $openLeg."<img src='im/time_anim.gif' border='0' alt='".VENTES_FLASH."' title='".VENTES_FLASH."'></a>&nbsp;";
    
                        $displaySave = "";         
                        $clientPrice = $price;
                    }
                    else {
                        $display_price = "<b>".$c_row['products_price']." ".$symbolDevise."</b>";
                        $displaySave = "";
                        $clientPrice = $c_row['products_price'];
                    }
			}
			else {
				$display_price = "<b>".$c_row['products_price']." ".$symbolDevise."</b>";
				$displaySave = "";
				$clientPrice = $c_row['products_price'];
			}
      }
      else {
           $display_price = "<b>".$c_row['products_price']." ".$symbolDevise."</b>";
           $displaySave = "";
           $clientPrice = $c_row['products_price'];
      }

      if($c_row['products_deee']>0) {
            $openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
            $displayDeee = "<div>".$openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a>: ".$c_row['products_deee']." ".$symbolDevise."</i></div>";
      }
      else {
            $displayDeee = "";
      }
 
      if($c_row['products_im']=="yes" AND !empty($c_row['products_image']) AND $c_row['products_image']!=="im/no_image_small.gif") {
                               $largImageMax = $largTableCategories-10;
                               $image_resize_main = resizeImage($c_row['products_image'],$imageSizeCat,$largImageMax);
                               $display_image = "<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."'>";
 
                                       if($gdOpen == "non") {
                                            if($categoriesPop=="oui") {
                                                $popSize = @getimagesize($c_row['products_image']);
                                                if(!$popSize) $c_row['products_image']="im/zzz_gris.gif";
                                                if($popSize[1] > $imageSizeCat) {
                                                    $display_image .= "<img src='resize.php?p=".$c_row['products_image']."&h=".$image_resize_main[1]."&bg=245,245,245' onmouseover=\"trailOn('".$c_row['products_image']."','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\" onmouseout=\"hidetrail();\" border='0' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";                                           
                                                }
                                                else {
                                                    $display_image .= "<img src='resize.php?p=".$c_row['products_image']."&h=".$image_resize_main[1]."&bg=245,245,245' onmouseover=\"trailOn('im/zzz.gif','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" border='0' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";
                                                }
                                            }
                                            else {
                                                $display_image .= "<img src='".$c_row['products_image']."' border='0' width='".$image_resize_main[0]."' height='".$image_resize_main[1]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";                       
                                       }
                                       }
                                       else {
                                            $infoImage = infoImageFunction($c_row['products_image'],$largImageMax,$imageSizeCat);
                                            $popSize = @getimagesize($c_row['products_image']);
                                            if(!$popSize) $c_row['products_image']="im/zzz_gris.gif";
                                            if($categoriesPop=="oui") {
                                                if($popSize[1] > $imageSizeCat) {
                                                    $display_image .= "<img onmouseover=\"trailOn('".$c_row['products_image']."','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\" onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";
                                                }
                                                else {
                                                    $display_image .= "<img onmouseover=\"trailOn('im/zzz.gif','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[1]."&hauteur=".$infoImage[2]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";
                                                }
                                            
                                            }
                                            else {
                                                $display_image .= "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";                  
                                       }
                                       }
                               $display_image .= "</a>";
       }
       else {
 
        $largImageMax = $largTableCategories-10;
        $noImage_resizeCat = resizeImage("im/lang".$_SESSION['lang']."/no_image.png",$imageSizeCat,$largImageMax);
        $display_image = "<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."'>";
        if($categoriesPop=="oui") {
            $display_image .= "<img src='im/lang".$_SESSION['lang']."/no_image.png' onmouseover=\"trailOn('im/zzz.gif','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" border='0' width='".$noImage_resizeCat[0]."' height='".$noImage_resizeCat[1]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";        
        }
        else {
        $display_image .= "<img src='im/lang".$_SESSION['lang']."/no_image.png' width='".$noImage_resizeCat[0]."' height='".$noImage_resizeCat[1]."' border='0'>";
        }
        $display_image .= "</a>";
       }
      
  
      if(isset($_SESSION['list'])) {
         if(strstr($_SESSION['list'],"+".$c_row['products_ref']."+")) {
            $bouton = $openLeg."<img src='im/cart.gif' border='0' alt='".CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE."' title='".CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE."'></a>&nbsp;";
         }
         else {
            $bouton = "";
         }
      }
      else {$bouton = "";}

 
		if(in_array($c_row['products_id'], $_SESSION['getNewsId'])) {
			$prodNew = "<img src='im/new.gif' border='0' title='".NOUVEAUTES."' alt='".NOUVEAUTES."'>&nbsp;";
		} else {
			$prodNew = "";
		}
 
		if(in_array($c_row['products_id'], $_SESSION['discountQt']) AND $c_row['products_forsale']=='yes') {
			$prodDegressif = $openLeg."<img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."'></a>&nbsp;";
		} else {
			$prodDegressif = "";
		}
 
		if($c_row['products_download'] == "yes") {
			$prodDown = $openLeg."<img src='im/download.gif' border='0' title='".ARTICLE_TELE."' alt='".ARTICLE_TELE."'></a>&nbsp;";
		} else {
			$prodDown = "";
		}
 
		if($c_row['products_qt']>0) {
			$stockLev = $openLeg."<img src='im/lang".$_SESSION['lang']."/stockok.png' border='0' alt='".EN_STOCK."' title='".EN_STOCK."'></a>";
		} else {
			if($actRes=="oui") $stockLev = $openLeg."<img src='im/stockin.gif' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'></a>"; else $stockLev = $openLeg."<img src='im/stockno.gif' border='0' title='".NOT_IN_STOCK."'></a>";
		}
 
		if($c_row['products_exclusive'] == "yes") {
			$prodHart = $openLeg."<img src='im/coeur.gif' border='0' alt='".COEUR."' title='".COEUR."'></a>&nbsp;";
		} else {
			$prodHart = "";
		}
 
		if($c_row['products_forsale'] == "no") {
			$prodOut = $openLeg."<img src='im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'></a>&nbsp;";
		} else {
			$prodOut = "";
		}
 
		$activeRoundedCorners = "yes";
		if($activeRoundedCorners=='yes') {
			print "<div align='center'>";
			round_top('yes',$largTableCategories+2,'raised3');
			$TABLEBoxesProductsDisplayedCentrePage = "roundCornerTableDefaultBackground";
			$TABLEBoxProductsDisplayedTop = "roundCornerTableDefaultBackground";
			$TABLEBoxProductsDisplayedMiddle = "roundCornerTableDefaultBackground";
			$TABLEBoxesProductsDisplayedBottom = "roundCornerTableDefaultBackground";
		}
		else {
			print "<div align='center'>";
			$TABLEBoxesProductsDisplayedCentrePage = "TABLEBoxesProductsDisplayedCentrePage";
			$TABLEBoxProductsDisplayedTop = "TABLEBoxProductsDisplayedTop";
			$TABLEBoxProductsDisplayedMiddle = "TABLEBoxProductsDisplayedMiddle";
			$TABLEBoxesProductsDisplayedBottom = "TABLEBoxesProductsDisplayedBottom";
		}
 

                     print "<table border='0' align='center' width='".$largTableCategories."' height='".$tableMaxHeight."' class='".$TABLEBoxesProductsDisplayedCentrePage."' cellspacing='0' cellpadding='2'>";
                     print "<tr>";
                     print "<td height='35' align='center' valign='top' class='".$TABLEBoxProductsDisplayedTop."' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
                     print $prodNew;
                     print "<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."' ".display_title($c_row['products_name_'.$_SESSION['lang']],$carSizeTitleCat).">";
                     print "<b>".adjust_text($c_row['products_name_'.$_SESSION['lang']],$carSizeTitleCat,"..")."</b>";
                     print "</a>";
                     print "</td>";
                     print "</tr><tr>";
                     print "<td align='center' valign='top' class='".$TABLEBoxProductsDisplayedMiddle."'>";
                     print "<div>";
                     if((isset($_SESSION['account']) OR $displayPriceInShop=="oui") AND $c_row['products_forsale']=="yes") {
                     print "<span class='PromoFont'>".$display_price."</span>";
 
                        if(!empty($displaySave)) print "<br>".$displaySave;
                        if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                            print "<br><b>".VOTRE_PRIX." : <span class='FontColorTotalPrice'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
                        }
                        print $displayDeee;
                        if($devise2Visible=="oui" AND $clientPrice>0) print curPrice($clientPrice,$symbolDevise2,"center");
                     }
                     else {
                        if($c_row['products_forsale']=="no") {
                           print "<span class='PromoFontColorNumber'><b>".OUT_OF_STOCK."</b></span>";
                        }
                        else {
                           print "<img src='zzz.gif' width='1' height='1'>";
                        }
                     }
                     print "</div>";
                     print "<br>";
                     print $display_image;
                     
                     if($displayDescCat=="oui") {
                     print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
                     print "<div align='left' style='padding:3px'>";
                     $prodDesc = trim(strip_tags($c_row['products_desc_'.$_SESSION['lang']]));
                     print adjust_text($prodDesc,$displayDescCatNum,"&nbsp;<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."' title='".PLUS_INFOS."'><img src='im/next.gif' border='0'></a>");
                     print "</div>";
                     }
                     
                     print "</td>";
                     print "</tr><tr>";
                     print "<td height='21' align='right' valign='bottom' class='".$TABLEBoxesProductsDisplayedBottom."'>";
                     if($prodOut=="") {
                     	if(isset($displayFlash)) print $displayFlash;
                     	print $prodDegressif;
                     	print $prodHart;
                     	print $prodDown;
                     	print $bouton;
                     	if($prodDown=='') print $stockLev;
                     }
                     else {
                     	print $prodOut;
                     }
                     print "</td>";
                     print "</tr></table>";
                     
                     if($activeRoundedCorners=='yes') round_bottom('yes');
                     print "<div>";
                     print "<br>";
}
 

 
$tableMaxHeight = $imageSizeCat+125;
if(isset($_SESSION['account'])) $tableMaxHeight = $imageSizeCat+125; else $tableMaxHeight = $imageSizeCat+85;
if($displayPriceInShop=="oui") $tableMaxHeight = $tableMaxHeight+30;
 
$displayDescCat = "non";
 
$displayDescCatNum = 80;
if($displayDescCat=="oui") $tableMaxHeight = $tableMaxHeight+30;

if($displayOutOfStock == "non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}

$selectVarW = mysql_query("SELECT p.categories_id, RAND() AS m_random
                             FROM products AS p
                             LEFT JOIN categories AS c ON (c.categories_id = p.categories_id)
                             LEFT JOIN specials AS s ON (s.products_id = p.products_id)
                             WHERE c.parent_id = '".$_GET['path']."' 
                             AND p.products_ref != 'GC100'
                             AND c.categories_visible = 'yes'
                             AND p.products_visible = 'yes'
                             ".$addToQuery."
                             ORDER BY m_random");
       
$NbreW = mysql_num_rows($selectVarW);

if($NbreW>0) {
	$rowpW = mysql_fetch_array($selectVarW);
	##$randFirst = $rowpW['categories_id'];
}
else {
    if(isset($jj)) unset($jj);
    $SubCatFromCat = recurs3($_GET['path']);
    if(!isset($SubCatFromCat)) {$SubCatFromCat[] = $_GET['path'];}
    $SubCatFromCat = array_unique($SubCatFromCat);
    $rand_keys = array_rand($SubCatFromCat, 1);
  	##$randFirst = $SubCatFromCat[$rand_keys];
}
 
$selectVar = "SELECT c.parent_id, c.categories_visible, p.products_id, p.categories_id, p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_qt, p.products_download, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible,  RAND() AS m_random
				FROM products as p
				LEFT JOIN categories as c ON (p.categories_id = c.categories_id)
				LEFT JOIN specials as s ON (p.products_id = s.products_id)
				WHERE c.categories_visible = 'yes'
				AND p.products_ref != 'GC100'
				AND p.products_visible = 'yes'
				".$addToQuery."
				AND c.parent_id = '".$_GET['path']."' 
				ORDER BY m_random
				";
$selectVar .= " LIMIT 0,".$NbreProduitAffiche;

$resultc = mysql_query($selectVar);
$rowsc = mysql_num_rows($resultc);

 
$descFromTitle = str_replace('| ','- ',$title);
$description = $store_name." ".$descFromTitle;
$keysFromTitle = str_replace('| ','',$title);
$keywords = $keysFromTitle.", ".$store_name;

$findMetaQuery = mysql_query("SELECT categories_meta_title_".$_SESSION['lang'].", categories_meta_description_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$_GET['path']."'") or die (mysql_error());
$findMetaResult = mysql_fetch_array($findMetaQuery);
if($findMetaResult['categories_meta_title_'.$_SESSION['lang']]!=='') $title = $findMetaResult['categories_meta_title_'.$_SESSION['lang']];
if($findMetaResult['categories_meta_description_'.$_SESSION['lang']]!=='') $description = $findMetaResult['categories_meta_description_'.$_SESSION['lang']];
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
 
include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">

<?php 
//   header1
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

//   header2
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

// Menu tab
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
// Menu horizontaal
if($menuCssVisibleHorizon=="oui") {
   include('includes/menu_categories_layer_horizontaal.php');
   $styleClass2 = "TABLEMenuPathTopPageMenuH";
}

if(isset($styleClass1)) $styleClass=$styleClass1;
if(isset($styleClass2)) $styleClass=$styleClass2;
?>

      <?php if($tableDisplay=='oui') {?>
          <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="<?php echo $styleClass;?>">
          <tr height="32">
          <?php if($tableDisplayLeft=='oui') {?>
          <td>
          <b>
          <?php
          	$targetF = (isset($_GET['target']))? $_GET['target'] : '';
          	getPath2($_GET['path'],"top",$catId=0,$_SESSION['tree2'],"n",$targetF,$_SESSION['lang']);
          ?>
          </b>
          </td>
          <?php 
          }
          if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');?>
          </tr>
          </table>
      <?php }?>
          
          <?php include('includes/promo_afbeelden.php');?>

</td>
</tr>
</table>


<table width="100%" border="0" cellpadding="3" cellspacing="5">
<tr>
          <?php
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
<td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="3" cellpadding="0" align="center">
              <tr>
                <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
            <tr>
            <td align="left">
            <?php
            if(isset($_GET['target'])) $targetF = $_GET['target']; else $targetF = '';
            getPath2($_GET['path'],"bas",$catId=0,$_SESSION['tree2'],$_GET['action'],$targetF,$_SESSION['lang']);
            ?>
            </td>
            </tr>
            </table>
            <br>

<?php
}
 
if($addSubMenu == "oui") { 

    $findOriginQuery = mysql_query("SELECT categories_name_".$_SESSION['lang']." FROM categories WHERE categories_id = '".$_GET['path']."'");
    
    $findOrigin = mysql_fetch_array($findOriginQuery);
    $findCatName = $findOrigin['categories_name_'.$_SESSION['lang']];
    $menuNum = $_SESSION['tree2'][$findCatName];
    $h = $im_size_sm; // hauteur max image sous-menu

    $sousresultm = mysql_query("SELECT parent_id, categories_id, categories_noeud, categories_name_".$_SESSION['lang'].", categories_comment_".$_SESSION['lang'].", categories_image
                                FROM categories
                                WHERE parent_id = '".$_GET['path']."'
                                AND categories_visible = 'yes'
                                ORDER BY categories_noeud ASC, categories_order ASC, categories_name_".$_SESSION['lang']." ASC");

    if(mysql_num_rows($sousresultm)>0) {
    	$activeRoundedCornersSubCat = "yes";
    	if($activeRoundedCornersSubCat=="yes") {
			round_top('yes',"100%",'raised3');
			$TABLESousMenuPageCategory = "";
		}
		else {
			$TABLESousMenuPageCategory = "TABLESousMenuPageCategory";
		}
        print "<table border='0' width='99%' align='center' cellspacing='0' cellpadding='5' class='".$TABLESousMenuPageCategory."'>";
        print "<tr valign='top'>";
        $q=0;

        while($souscatm = mysql_fetch_array($sousresultm)) {
            if($souscatm['categories_noeud'] == "B") {
                //-----------------
                // Category display
                  $q = $q+1;
                  $menuNum = $_SESSION['tree2'][$souscatm['categories_name_'.$_SESSION['lang']]];
                  if($displaySubCategoryName=="oui") $imageSituationCat = "left"; else $imageSituationCat = "center";
                  
                  if($displaySubCategoryName=="oui") {
                     if($displaySubCategoryNameUnder=="oui") {
                        $imageSituationCat = "center";
                        $imageSituationCat2 = "center";
                     }
                     else {
                        $imageSituationCat = "left";
                        $imageSituationCat2 = "";
                     }
                  }
                  else {
                     $imageSituationCat = "center";
                     $imageSituationCat2 = "";
                  }

                  if(($q % $nbre_col_sm) == 1) {print "</tr><tr valign='middle'>";}
                  if($nbre_col_sm == 1) {print "</tr><tr valign='middle'>";}
                  print "<td width='50%'>";
                  print "<table border='0' width='100%' cellspacing='0' cellpadding='5'>";
                  print "<tr valign='top' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";

                  if(($souscatm['categories_image']) !== "im/zzz.gif") {
                      $image_resize_cat = resizeImage($souscatm['categories_image'],$h,$h);
                      print "<td align='".$imageSituationCat."' width='1'>";
                      print "<a href='".seoUrlConvert("categories.php?path=".$souscatm['categories_id']."&num=".$menuNum."&action=e")."'>";
 
                      if($gdOpen == "non") {
                         print "<img src='".$souscatm['categories_image']."' width='".$image_resize_cat[0]."' height='".$image_resize_cat[1]."' border='0' alt='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."' title='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."'>";
                      }
                      else {
                        $infoImage3 = infoImageFunction($souscatm['categories_image'],$h,$h);
                        print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage3[0]."&imageSource=".$souscatm['categories_image']."&largeurOrigin=".$infoImage3[1]."&hauteurOrigin=".$infoImage3[2]."&largeur=".$infoImage3[3]."&hauteur=".$infoImage3[4]."' alt='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."' title='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."' border='0'>";                              
                      }
                      print "</a>";
                      print "</td>";
 
                      if($displaySubCategoryName=="oui") {
 
                          if($displaySubCategoryNameUnder=="oui") print "</tr><tr>";
                          print "<td align='".$imageSituationCat2."' valign='middle'>";
                          print "<b>[ <a href='".seoUrlConvert("categories.php?path=".$souscatm['categories_id']."&num=".$menuNum."&action=e")."'>".str_replace(" ","&nbsp;",$souscatm['categories_name_'.$_SESSION['lang']])."</a> ]</b>";
                          print "</td>";
                      }
                  }
                  else {
                      print "<td align='".$imageSituationCat."' valign='middle'>";
                      if($displaySubCategoryName=="oui") {
                         print "<a href='".seoUrlConvert("categories.php?path=".$souscatm['categories_id']."&num=".$menuNum."&action=e")."'><img src='im/path.gif' border='0' align='TEXTTOP' alt='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."' title='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."'></a>&nbsp;<b>[ <a href='".seoUrlConvert("categories.php?path=".$souscatm['categories_id']."&num=".$menuNum."&action=e")."'>".str_replace(" ","&nbsp;",$souscatm['categories_name_'.$_SESSION['lang']])."</a> ]</b>";
                      }
                      else {
                         print "<a href='".seoUrlConvert("categories.php?path=".$souscatm['categories_id']."&num=".$menuNum."&action=e")."' alt='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."' title='".CATEGORIE." ".$souscatm['categories_name_'.$_SESSION['lang']]."'><img src='im/path.gif' align='TEXTTOP' border='0'></a>";
                      }
                      print "</td>";
                  }
                  
                  print "</tr></table>";
            }
            else {
 
                    $q = $q+1;
                    if(($q % $nbre_col_sm) == 1) { print "</tr><tr valign='middle'>";}
                    if($nbre_col_sm == 1) {print "</tr><tr valign='middle'>";}
                    print "<td width='50%'>";
                    print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='3' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
                    print "<tr valign='top'>";
                    
                    if(($souscatm['categories_image']) !== "im/zzz.gif") {
                        $image_resize_cat = resizeImage($souscatm['categories_image'],$h,$h);
                        
                        if($displaySubCategoryName=="oui") {
                           if($displaySubCategoryNameUnder=="oui") {
                              $imageSituation = "center";
                              $imageSituation2 = "center";
                           }
                           else {
                              $imageSituation = "left";
                              $imageSituation2 = "";
                           }
                        }
                        else {
                           $imageSituation = "center";
                           $imageSituation2 = "";
                        }
                        $hTable = $h+5;
                        print "<td align='".$imageSituation."' height='".$hTable."' c/lass='border'>";
                        print "<a href='".seoUrlConvert("list.php?path=".$souscatm['categories_id'])."'>";
 
                        if($gdOpen == "non") {
                        	print "<img src='".$souscatm['categories_image']."' width='".$image_resize_cat[0]."' height='".$image_resize_cat[1]."' border='0' alt='".$souscatm['categories_name_'.$_SESSION['lang']]."' title='".$souscatm['categories_name_'.$_SESSION['lang']]."'>";
                        }
                        else {
                        	$infoImage2 = infoImageFunction($souscatm['categories_image'],$h,$h);
                        	print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage2[0]."&imageSource=".$souscatm['categories_image']."&largeurOrigin=".$infoImage2[1]."&hauteurOrigin=".$infoImage2[2]."&largeur=".$infoImage2[3]."&hauteur=".$infoImage2[4]."' alt='".$souscatm['categories_name_'.$_SESSION['lang']]."' title='".$souscatm['categories_name_'.$_SESSION['lang']]."' border='0'>";                              
                        }
                        print "</a>";
                        print "</td>";
 
                        if($displaySubCategoryName=="oui") {
                            // Texte sous image
                            if($displaySubCategoryNameUnder=="oui") print "</tr><tr>";
                            print "<td valign='top' align='".$imageSituation2."' style='margin:0p; padding:0px'>";
                            print "<a href='".seoUrlConvert("list.php?path=".$souscatm['categories_id'])."'><span class='tiny'>".str_replace(" ","&nbsp;",$souscatm['categories_name_'.$_SESSION['lang']])."</a>&nbsp;[".productNum2($souscatm['categories_id'],$_SESSION['lang'])."]</span>";
                            print "<div><img rsc='im/zzz.gif' width='1' height='5'></div>";
                            print "</td>";
                        }
                    }
                    else {
                        print "<td align='left' valign='middle'>";
                        print "<span style='font-size:13px'>&bull;</span>&nbsp;";
                        print "<a href='".seoUrlConvert("list.php?path=".$souscatm['categories_id'])."'><span class='tiny'>".str_replace(" ","&nbsp;",$souscatm['categories_name_'.$_SESSION['lang']])."</a>&nbsp;[".productNum2($souscatm['categories_id'],$_SESSION['lang'])."]</span>";
                        print "</td>";
                    }
                    
                    print "</tr></table>";
                    print "</td>";
            }
        }
        print "</tr></table>";
        if($activeRoundedCornersSubCat=="yes") round_bottom('yes');
        print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
    }
}
                      
 
$resultm = mysql_query("SELECT categories_comment_".$_SESSION['lang'].", categories_image FROM categories WHERE categories_id = '".$_GET['path']."'");
$catm = mysql_fetch_array($resultm);
$h = 60; 
if(($catm['categories_image']) !== "im/zzz.gif") {
    $image_resize_cat_top = resizeImage($catm['categories_image'],$h,$h);
    print "<table width='100%' border='0' cellspacing='0' cellpadding='1'><tr>";
    print "<td width='100%' align='left' valign='top'>";
    print "<img src='".$catm['categories_image']."' width='".$image_resize_cat_top[0]."' height='".$image_resize_cat_top[1]."' border='0' align='left'>";
}
else {
    print "<table border='0' width='100%' cellspacing='0' cellpadding='1'><tr><td align='left'>";
}

if(!empty($catm['categories_comment_'.$_SESSION['lang']])) {
    print $catm['categories_comment_'.$_SESSION['lang']];
    print "</td>";
    print "</tr>";
    print "</table>";
    print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
}
else {
    print "</td>";
    print "</tr>";
    print "</table>";
    print "<div align='center'><img src='im/zzz.gif' width='1' height='5'></div>";
}


 
if($addCommuniqueCategories == "oui") {
   include('includes/communicatie.php');
}
 
$result2 = mysql_query("SELECT text_categories_".$_SESSION['lang']." FROM admin");
$rowpNum = mysql_num_rows($result2);
if($rowpNum>0) {
     $rowp = mysql_fetch_array($result2);
     if(!empty($rowp['text_categories_'.$_SESSION['lang']])) {
         print "<table border='0' width='100%' cellspacing='0' cellpadding='1' class='TABLE'><tr>";
         print "<td>".$rowp['text_categories_'.$_SESSION['lang']]."</td>";
         print "</tr></table>";
     }
}

//---------------------------------
 
//---------------------------------
if($rowsc==0) {

      if($NbreProduitAffiche > 0) {
              // Appeler fonction recurcive
              if(isset($jj)) unset($jj);
              $w = recurs3($_GET['path']);
              if(!isset($w)) $w[] = $_GET['path'];
                $w = array_unique($w);
            if(count($w)>0) {
                $rand_keys = array_rand($w, 1);
                $w1 = $w[$rand_keys];

				// select products
				$addToQuery2 = "";
				if(sizeof($w) > 1) {
					$addToQuery2.=" p.categories_id IN(";
					foreach($w as $elem) {
						//$addToQuery2 .= " OR p.categories_id = '".$elem."' AND p.products_visible='yes'";
						$addToQuery2.= "'".$elem."',";
					}
					$addToQuery2.= ") ";
				}
				else {
					$addToQuery2= 1;
				}
					$addToQuery2 = str_replace(",)",")",$addToQuery2);

 

               $resultd = mysql_query("SELECT c.parent_id, c.categories_id, c.categories_name_".$_SESSION['lang'].", c.categories_visible, p.products_id, p.categories_id, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_date_added, p.products_qt, p.products_download, p.products_forsale, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible, RAND() AS m_random
                                       FROM products as p
                                       LEFT JOIN categories as c
                                       ON (p.categories_id = c.categories_id)
                                       LEFT JOIN specials as s
                                       ON (p.products_id = s.products_id)
                                       WHERE ".$addToQuery2."
                                       AND p.products_visible='yes'
									   AND c.categories_visible='yes' 
                                       AND p.products_ref != 'GC100'
                                       ".$addToQuery."
                                       ORDER BY m_random
                                       DESC
                                       LIMIT 0, ".$NbreProduitAffiche."");

              $q=0;
              $resultdNum = mysql_num_rows($resultd);
              
              if($resultdNum>0) {
                  print "<table border='0' width='100%' cellspacing='0' cellpadding='3'><tr valign='top'>";
                  print "<td valign='middle' height='30' colspan='".$nbre_col."' class='titre'>";
                  print VOICI_QUELQUES_PRODUITS_DANS_CETTE_CATEGORIE2;
                  print "</td>";
                  print "</tr><tr>";
                  
                  while($c_row = mysql_fetch_array($resultd)) {
                  
 
                      $q = $q+1;
                      if(($q % $nbre_col) == 1) { print "</tr><tr valign='top'>"; }
                      if($nbre_col == 1) { print "</tr><tr valign='top'>"; }
                       print "<td>";
                          print display_table();
                       print "</td>";
                  }
                  print "</tr></table>";
              }
              else {
                    print "<p align='center' class='fontrouge'><b>".PAS_DE_PRODUITS_DANS_CETTE_CATEGORIE."</b></p>";
              }
              }
      }
}
else {
//----------------------------------
 
//----------------------------------
    $q=0;
    print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr valign='top'>";
    print "<td valign='middle' colspan='".$nbre_col."'>";
          print "<table class='TABLE' border='0' width='100%' cellspacing='0' cellpadding='2'>";
          print "<tr height='25'><td class='titre' valign='top'>";
          print VOICI_QUELQUES_PRODUITS_DANS_CETTE_CATEGORIE;
          print "</td></tr></table>";
    print "</td>";
    print "</tr><tr>";
    
    while($c_row = mysql_fetch_array($resultc)) {
          // Afficher la table
          $q = $q+1;
          if(($q % $nbre_col) == 1) { print "</tr><tr valign='top'>"; }
          if($nbre_col == 1) { print "</tr><tr valign='top'>"; }
           print "<td>";
              print display_table();
           print "</td>";
    }
    print "</tr></table>";

}
?>
</tr><tr>
<td valign="bottom" height="1%">
<?php
 
if($rowsc > 0) include('includes/nieuws_promo_afbeelden.php');
?>
</td>
</tr>
</table>

</td>
         <?php 
		  // ---------------------------------------
		  // rechtse kolom 
		  // ---------------------------------------
		 if($colomnRight=='oui') include("includes/kolom_rechts.php");
		 ?>
</tr>
</table>

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>


</body>
</html>
