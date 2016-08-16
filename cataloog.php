<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = HOME;


if(isset($_SESSION['account'])) { $tableMaxHeight = $imageSizeCatalog+115;} else {$tableMaxHeight = $imageSizeCatalog+85;}
if(!isset($_SESSION['account']) AND $displayPriceInShop=="oui") $tableMaxHeight=$tableMaxHeight+40;

if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
 
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";

$randNum = rand(0,10);
$countReq = 0;
 
if($catDisplayPromo=="on") {
$resultcPromo = "SELECT c.*, p.products_id, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_forsale, p.products_price, p.products_ref, p.products_im,  p.products_image, p.products_qt, p.products_download, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible, RAND() AS m_random
                                     FROM products as p
                                     INNER JOIN specials as s ON (p.products_id = s.products_id)
                                     LEFT JOIN categories as c ON (p.categories_id = c.categories_id)
                                     WHERE c.categories_visible = 'yes'
                                     AND p.products_ref != 'GC100'
                                     AND p.products_visible='yes'
                                     AND p.products_forsale='yes'
                                     AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                                     AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)
                                     AND s.specials_visible='yes'
                                     ".$addToQuery."
                                     ORDER BY specials_last_day";
    $resultcCountPromo = mysql_num_rows(mysql_query($resultcPromo));
    if($resultcCountPromo>0) {
      $maxRandProm = $resultcCountPromo - $NbreProduitAfficheCatalog;
      if($maxRandProm < 0) {$maxRandProm = $resultcCountPromo;}
      $randNumPromo = rand(0,$maxRandProm);
      if($randNumPromo == $resultcCountPromo) {$randNumPromo = $randNumPromo-1;}
      $resultcPromo .= " LIMIT ".$randNumPromo.",".$NbreProduitAfficheCatalog;
      $resultc['Promo'] = $resultcPromo;
      $countReq = $countReq + 1;
    }
    else {
      $resultcPromo = "SELECT products_price FROM products WHERE products_price='12532251444.22'";
      $resultc['Promo'] = $resultcPromo;
      $countReq = $countReq + 1;
    }
}
 
if($catDisplayFew=="on") {
$resultcFew = "SELECT c.parent_id, c.categories_visible, p.products_id, p.categories_id, p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_viewed, p.products_qt, p.products_download, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible, RAND() AS m_random
                         FROM products as p
                         LEFT JOIN categories as c 
                         ON (p.categories_id = c.categories_id)
                         LEFT JOIN specials as s
                         ON (p.products_id = s.products_id)
                         WHERE c.categories_visible='yes'
                         AND p.products_ref != 'GC100'
                         AND p.products_visible='yes'
                         AND p.products_forsale='yes'
                         ".$addToQuery."
						 ORDER BY products_viewed
                         DESC
                         ";
    $resultcCountFew = mysql_num_rows(mysql_query($resultcFew));                   
    if($resultcCountFew > 0) {
        $maxRandFew = $resultcCountFew - $NbreProduitAfficheCatalog;
        if($maxRandFew < 0) {$maxRandFew = $resultcCountFew;}
        $randNumFew = rand(0,$maxRandFew);
        if($randNumFew == $resultcCountFew) {$randNumFew = $randNumFew-1;}
        $resultcFew .= " LIMIT ".$randNumFew.",".$NbreProduitAfficheCatalog."";
        $resultc['Few'] = $resultcFew;
        $countReq = $countReq + 1;
    }
    else {
        $resultcFew = "SELECT products_price FROM products WHERE products_price='12532251444.22'";
        $resultc['Few'] = $resultcFew;
        $countReq = $countReq + 1;
    }
}
if($catDisplayBest=="on") {
 
$resultcBest = "SELECT c.parent_id, c.categories_visible, p.products_id, p.categories_id, p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_viewed, p.products_qt, p.products_download, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible
                         FROM products as p
                         LEFT JOIN categories as c
                         ON (p.categories_id = c.categories_id)
                         LEFT JOIN specials as s
                         ON (p.products_id = s.products_id)
                         WHERE c.categories_visible='yes'
                         AND p.products_ref != 'GC100'
                         AND p.products_visible='yes'
                         AND p.products_forsale='yes'
                         ".$addToQuery."
                         ORDER BY products_viewed
                         DESC
                         LIMIT ".$randNum.",".$NbreProduitAfficheCatalog;
    $resultc['Best'] = $resultcBest;
    $countReq = $countReq + 1;
}
if($catDisplayNews=="on") {
 
$resultcNews = "SELECT c.parent_id, p.products_id, p.categories_id, p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_visible, p.products_qt, p.products_download, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible
                       FROM products as p
                       LEFT JOIN categories as c
                       ON (c.categories_id = p.categories_id)
                       LEFT JOIN specials as s
                       ON (p.products_id = s.products_id)
                       WHERE c.categories_visible = 'yes'
                       AND p.products_ref != 'GC100'
                       AND p.products_visible='yes'
                       AND p.products_forsale='yes'
                       AND TO_DAYS(NOW()) - TO_DAYS(p.products_date_added) <= '".$nbre_jour_nouv."'
                       ".$addToQuery."
                       ORDER BY specials_last_day
                       ASC
                       LIMIT ".$randNum.",".$NbreProduitAfficheCatalog;
    $resultc['News'] = $resultcNews;
    $countReq = $countReq + 1;
}
if($catDisplayExc=="on") {
 
$resultcExc = "SELECT c.parent_id, c.categories_visible, p.products_id, p.categories_id, p.products_forsale, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_viewed, p.products_qt, p.products_download, p.products_exclusive, p.products_deee, s.specials_new_price, s.specials_first_day, s.specials_last_day, s.specials_visible
                         FROM products as p
                         LEFT JOIN categories as c
                         ON (p.categories_id = c.categories_id)
                         LEFT JOIN specials as s
                         ON (p.products_id = s.products_id)
                         WHERE c.categories_visible='yes'
                         AND p.products_ref != 'GC100'
                         AND p.products_visible='yes'
                         AND p.products_forsale='yes'
                         ".$addToQuery."
                         AND p.products_exclusive='yes'
                         ";
    $resultcCountExc = mysql_num_rows(mysql_query($resultcExc));
    if($resultcCountExc > 0) {
        $maxRandExc = $resultcCountExc - $NbreProduitAfficheCatalog;
        if($maxRandExc < 0) {$maxRandExc = $resultcCountExc;}
        $randNumExc = rand(0,$maxRandExc);
        if($randNumExc == $resultcCountExc) {$randNumExc = $randNumExc-1;}
        $resultcExc .= " ORDER BY products_exclusive DESC LIMIT ".$randNumExc.",".$NbreProduitAfficheCatalog."";
        $resultc['Exc'] = $resultcExc;
        $countReq = $countReq + 1;
    }
    else {
        $resultcExc = "SELECT products_price FROM products WHERE products_price='12532251444.22'";
        $resultc['Exc'] = $resultcExc;
        $countReq = $countReq + 1;
    }
}
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

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">




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
// MENU HORIZONTAAL
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
          <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php"><?php echo maj(HOME);?></a></b>
          </td>
          <?php 
          }
          if($tableDisplayRight=='oui') include('includes/menu_top_rechts.php');
          ?>
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

  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr>
      <td valign="top" style="padding-top:0px;">
<!--
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr>
          <td valign="top" class="titre" style="padding-top:0px;"><?php print BIENVENU_SUR." ".$store_name;?></td>
          </tr>
          </table>
-->
<?php
 
if($countReq == 0) {
   if($NbreProduitAfficheCatalog == 0) {
       
      if($addCommuniqueHome=="oui") {
         include('includes/communicatie.php');
      }
       
      $result2 = mysql_query("SELECT text_home_".$_SESSION['lang']." FROM admin");
      $rowpNum = mysql_num_rows($result2);
      if($rowpNum>0) {
      $rowp = mysql_fetch_array($result2);
      if(!empty ($rowp['text_home_'.$_SESSION['lang']])) {
            print "<div align='center'>".$rowp['text_home_'.$_SESSION['lang']]."</div>";
      }
      }
   }
   else {
 
      if($addCommuniqueHome=="oui") {
      include('includes/communicatie.php');
      }
      

      $result2 = mysql_query("SELECT text_home_".$_SESSION['lang']." FROM admin");
      $rowpNum = mysql_num_rows($result2);
      if($rowpNum>0) {
        $rowp = mysql_fetch_array($result2);
        if(!empty($rowp['text_home_'.$_SESSION['lang']])) {
              print "<div align='center'>".$rowp['text_home_'.$_SESSION['lang']]."</div>";
        }
      }
   }
}
else {
       
      if($addCommuniqueHome=="oui") {
        print "<br>";
        include('includes/communicatie.php');
      }
       
      $result2 = mysql_query("SELECT text_home_".$_SESSION['lang']." FROM admin");
      $rowpNum = mysql_num_rows($result2);
      if($rowpNum>0) {
        $rowp = mysql_fetch_array($result2);
        if(!empty($rowp['text_home_'.$_SESSION['lang']])) {
          print "<div align='center'>".$rowp['text_home_'.$_SESSION['lang']]."</div>";
        }
      }

       
            
            $countResultc = count($resultc);
            $keys[] = array_keys($resultc);
            
            if($catDisplayRandOne=="on") { shuffle($keys[0]); $countResultc = 1; }
            if($catDisplayRandAll=="on") { shuffle($keys[0]);}

 

$countResultcMoins1 = $countResultc-1;
for($i=0; $i<=$countResultcMoins1; $i++) {
		$q=0;
        
        $resultVar = ${'resultc'.$keys[0][$i]};
        $resultVarQuery = mysql_query($resultVar) or die (mysql_error());
        $resultVarNum = mysql_num_rows($resultVarQuery);
        
      	if($resultVarNum > 0) {
	      	
	      	round_top('yes','99%','raised2');
	      	if($activeRoundTop=='no') {$a1="class='TABLE1'"; $a2="height='25'";} else {$a1=""; $a2="height='35'";}
	      	
	      	print "<table ".$a1." border='0' width='99%' cellspacing='0' cellpadding='2'>";
	      	print "<tr ".$a2." >";
				
				if($keys[0][$i] == "Best") {
					print "<td width='1' width='1' style='padding-left:5px;'><img src='im/i05.gif'></td>";
					print "<td class='titre'>&nbsp;".MEILLEURES_VENTES."</td>";
					if($activeRSS=="oui") {
						print "<td width='1'><a href='http://".$www2.$domaineFull."/rss/rss_top10.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".MEILLEURES_VENTES."' alt='RSS ".MEILLEURES_VENTES."'></a></td>";
					}
					$linkTo = "<td align='right' width='1'><a href='top10.php'><img src='im/info_plus.gif' border='0' alt='".PLUS_INFOS."' title='".PLUS_INFOS."'></a></td>";
				}
				
				if($keys[0][$i] == "Few") {
					print "<td width='1' width='1' style='padding-left:5px;'><img src='im/i05.gif'></td>";
					print "<td class='titre'>&nbsp;".FEW_PRODUCTS."</td>";
					$linkTo = "<td align='right' width='1'>&nbsp;</td>";
				}

				if($keys[0][$i] == "News") {
					print "<td width='1' width='1' style='padding-left:5px;'><img src='im/i05.gif'></td>";
					print "<td class='titre'>&nbsp;".LAST_NEWS."</td>";
					if($activeRSS=="oui") {
						print "<td width='1'><a href='http://".$www2.$domaineFull."/rss/rss_nieuws.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".NOUVEAUTES."' alt='RSS ".NOUVEAUTES."'></a></td>";
					}
					$linkTo = "<td align='right' width='1'><a href='list.php?target=new'><img src='im/info_plus.gif' border='0' alt='".PLUS_INFOS."' title='".PLUS_INFOS."'></a></td>";
				}
				
				if($keys[0][$i] == "Promo") {
					print "<td width='1' width='1' style='padding-left:5px;'><img src='im/i05.gif'></td>";
					print "<td class='titre'>&nbsp;".EN_PROMO."</td>";
					if($activeRSS=="oui") {
						print "<td width='1'><a href='http://".$www2.$domaineFull."/rss/rss_promoties.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".PROMOTIONS."' alt='RSS ".PROMOTIONS."'></a></td>";
					}
					$linkTo = "<td align='right' width='1'><a href='list.php?target=promo'><img src='im/info_plus.gif' border='0' alt='".PLUS_INFOS."' title='".PLUS_INFOS."'></a></td>";
				}
				
				if($keys[0][$i] == "Exc") {
					print "<td width='1' width='1' style='padding-left:5px;'><img src='im/i05.gif'></td>";
					print "<td class='titre'>&nbsp;".EN_EXCLUSIVITE."&nbsp;<img src='im/coeur.gif'></td>"; 
					$linkTo = "<td align='right' width='1'><a href='list.php?target=favorite'><img src='im/info_plus.gif' border='0' alt='".PLUS_INFOS."' title='".PLUS_INFOS."'></a></td>";
				}
	      	if(isset($linkTo)) print $linkTo;
	      	print "</tr></table>";
	      
	      
	      	round_bottom('no');
	      	print "<br>";
		}
      
		print "<table border='0' width='100%' cellspacing='0' cellpadding='3'>";
		print "<tr valign='top'>";
// ----------
// START LOOP
// ----------
	while($c_row = mysql_fetch_array($resultVarQuery)) {
 
		if(isset($displayFlash)) unset($displayFlash);
                
		if(!empty($c_row['specials_new_price'])) {
			if($c_row['specials_visible']=="yes") {
				$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
				$dateMaxCheck = explode("-",$c_row['specials_last_day']);
				$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
				$dateDebutCheck = explode("-",$c_row['specials_first_day']);
				$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
				
				if($dateDebut <= $today  and $dateMax >= $today) {
					$itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
					$price = $c_row['specials_new_price'];
					$old_price = $c_row['products_price'];
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
    $displayDeee = "<br>".$openDeee."<span style='color:#00CC00'><b>".ECOTS."</b></span></a>: ".$c_row['products_deee']." ".$symbolDevise."</i>";
}
else {
    $displayDeee = "";
}

if($c_row['products_im']=="yes" AND !empty($c_row['products_image']) AND $c_row['products_image']!=="im/no_image_small.gif") {
	$largImageMax = $largTableCatalog-10;
    if($displayDescCatalog=="oui" AND $displayUnderDescCatalog=="non") {$largImageMax22 = $largImageMax*43/100;} else {$largImageMax22 = $largImageMax;}
    $ImageSizze = resizeImage($c_row['products_image'],$imageSizeCatalog,$largImageMax22);

    $display_image = "<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."'>";
    
	
	if($gdOpen == "non") {
		if($catalogPop=="oui") {
			$popSize = @getimagesize($c_row['products_image']);
			if(!$popSize) $c_row['products_image']="im/zzz_gris.gif";
			if($popSize[1] > $imageSizeCatalog+10) {
				$display_image .= "<img src='resize.php?p=".$c_row['products_image']."&h=".$ImageSizze[1]."&bg=245,245,245' onmouseover=\"trailOn('".$c_row['products_image']."','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\"   onmouseout=\"hidetrail();\" border='0' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";                                           
			}
			else {
				$display_image .= "<img src='resize.php?p=".$c_row['products_image']."&h=".$ImageSizze[1]."&bg=245,245,245' onmouseover=\"trailOn('im/zzz.gif','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\"  onmouseout=\"hidetrail();\" border='0' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";
			}
		}
		else {
			$display_image .= "<img src='".$c_row['products_image']."' border='0' width='".$ImageSizze[0]."' height='".$ImageSizze[1]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";
		}
	}
	else {
		$popSize = @getimagesize($c_row['products_image']);
		if(!$popSize) $c_row['products_image']="im/zzz_gris.gif";
		$infoImage = infoImageFunction($c_row['products_image'],$largImageMax,$imageSizeCatalog);
		if($catalogPop=="oui") {
			if($popSize[1] > $imageSizeCatalog+10) {
				$display_image .= "<img onmouseover=\"trailOn('".$c_row['products_image']."','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\"   onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";                  
			}
			else {
				$display_image .= "<img onmouseover=\"trailOn('im/zzz.gif','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\"   onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[1]."&hauteur=".$infoImage[2]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";
			}
		}
		else {
			$display_image .= "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$c_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."' title='".$c_row['products_name_'.$_SESSION['lang']]."' border='0'>";
		}
	}
	$display_image .= "</a>";
}
else {
	$largImageMax = $largTableCatalog-10;
	$noImage_resizeCat = resizeImage("im/lang".$_SESSION['lang']."/no_image.png",$imageSizeCatalog,$largImageMax);
	$display_image = "<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."'>";
	if($catalogPop=="oui") {
		$display_image .= "<img src='im/lang".$_SESSION['lang']."/no_image.png' onmouseover=\"trailOn('im/zzz.gif','".$c_row['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" border='0' width='".$noImage_resizeCat[0]."' height='".$noImage_resizeCat[1]."' alt='".$c_row['products_name_'.$_SESSION['lang']]."'>";
	}
	else {
		$display_image .= "<img src='im/lang".$_SESSION['lang']."/no_image.png' width='".$noImage_resizeCat[0]."' height='".$noImage_resizeCat[1]."' border='0'>";
	}
	$display_image .= "</a>";
}

 
if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$c_row['products_ref']."+")) {
	$bouton = $openLeg."<img src='im/cart.gif' border='0' alt='".CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE."' title='".CET_ARTICLE_EST_PRESENT_DANS_VOTRE_CADDIE."' align='absmiddle'></a>&nbsp;";
}
else {
	$bouton = "";
}

 if($c_row['products_download'] == "yes") {
	$prodDown = $openLeg."<img src='im/download.gif' border='0' alt='".ARTICLE_TELE."' title='".ARTICLE_TELE."' align='absmiddle'></a>&nbsp;";
} else {
	$prodDown = "";
}

 
if($c_row['products_qt']>0) {
	$stockLev = $openLeg."<img src='im/lang".$_SESSION['lang']."/stockok.png' border='0' alt='".EN_STOCK."' title='".EN_STOCK."' align='absmiddle'></a>";
} else {
	if($actRes=="oui") $stockLev = $openLeg."<img src='im/stockin.gif' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."' align='absmiddle'></a>"; else $stockLev = $openLeg."<img src='im/stockno.gif' border='0' title='".NOT_IN_STOCK."' align='absmiddle'></a>";
}

 
if($c_row['products_exclusive'] == "yes") {
	$prodHart = $openLeg."<img src='im/coeur.gif' border='0' alt='".COEUR."' title='".COEUR."' align='absmiddle'></a>&nbsp;";
} else {
	$prodHart = "";
}

 if(in_array($c_row['products_id'], $_SESSION['discountQt']) AND $c_row['products_forsale']=='yes') {
	$prodDegressif = $openLeg."<img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>&nbsp;";
} else {
	$prodDegressif = "";
}

 
if(in_array($c_row['products_id'], $_SESSION['getNewsId'])) {
	$prodNew = "<img src='im/new.gif' border='0' title='".NOUVEAUTES."' alt='".NOUVEAUTES."' align='absmiddle'>&nbsp;";
} else {
	$prodNew = "";
}

 

			$q = $q+1;
			if(($q % $nbre_col_catalog) == 1) {print "</tr><tr valign='top'>";}
			if($nbre_col_catalog == 1) { print "</tr><tr valign='top'>"; }
			print "<td align='center'>";
			if(isset($_SESSION['account'])) {$tableMaxHeight2 = $tableMaxHeight+23;} else {$tableMaxHeight2 = $tableMaxHeight;}
			if($displayPriceInShop=="oui") $tableMaxHeight2=$tableMaxHeight2+20;
			if($displayUnderDescCatalog=="oui") $tableMaxHeight2=$tableMaxHeight2+40;
			if($devise2Visible=="oui") $tableMaxHeight2=$tableMaxHeight2+15;
			## Start Table round corners
			$activeRoundedCorners = "yes";
			if($activeRoundedCorners=='yes') {
				round_top('yes',$largTableCatalog+2,'raised3');
				$TABLEBoxesProductsDisplayedCentrePage = "roundCornerTableDefaultBackground";
				$TABLEBoxProductsDisplayedTop = "roundCornerTableDefaultBackground";
				$TABLEBoxProductsDisplayedMiddle = "roundCornerTableDefaultBackground";
				$TABLEBoxProductsDisplayedMiddlePrice = "roundCornerTableDefaultBackground";
				$TABLEBoxesProductsDisplayedBottom = "roundCornerTableDefaultBackground";
			}
			else {
				$TABLEBoxesProductsDisplayedCentrePage = "TABLEBoxesProductsDisplayedCentrePage";
				$TABLEBoxProductsDisplayedTop = "TABLEBoxProductsDisplayedTop";
				$TABLEBoxProductsDisplayedMiddle = "TABLEBoxProductsDisplayedMiddle";
				$TABLEBoxProductsDisplayedMiddlePrice = "TABLEBoxProductsDisplayedMiddlePrice";
				$TABLEBoxesProductsDisplayedBottom = "TABLEBoxesProductsDisplayedBottom";
			}
 
			
 
			print "<table border='0' align='center' width='".$largTableCatalog."' height='".$tableMaxHeight2."' class='".$TABLEBoxesProductsDisplayedCentrePage."' cellspacing='0' cellpadding='2'>";
			print "<tr>";
			 
			print "<td colspan='2' height='35' align='center' valign='middle' class='".$TABLEBoxProductsDisplayedTop."' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\">";
			print $prodNew."<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."' ".display_title($c_row['products_name_'.$_SESSION['lang']],$maxCarTitleAff).">";
			print "<b>".adjust_text($c_row['products_name_'.$_SESSION['lang']],$maxCarTitleAff,"..")."</b>";
			print "</a>";
			print "</td>";
			print "</tr>";
			
			print "<tr>";
			 
			print "<td height='".$imageSizeCatalog."' align='center' valign='top' style='padding:5px 0px 0px 5px;' class='".$TABLEBoxProductsDisplayedMiddle."'>";
			print $display_image;
			print "</td>";
			
			 
			if($displayDescCatalog=="oui") {
				if(($c_row['products_desc_'.$_SESSION['lang']])!=="") {
					if($displayUnderDescCatalog=="oui") print "</tr><tr><td><img src='im/zzz.gif' width='1' height='1'></td></tr><tr>";
					print "<td valign='top' width='60%' style='padding:3px 3px 0px 5px;' class='".$TABLEBoxProductsDisplayedMiddle."'>";
					$ProdNameProdCat = $c_row['products_desc_'.$_SESSION['lang']];
					$ProdNameProdCat = trim(strip_tags($ProdNameProdCat));
					print adjust_text($ProdNameProdCat,$maxCarCatAff,"..&nbsp;<a href='".seoUrlConvert("beschrijving.php?id=".$c_row['products_id']."&path=".$c_row['categories_id'])."' title='".PLUS_INFOS."'><img src='im/next.gif' border='0'></a>");
					print "</td>";
				}
			}
			print "</tr>";
			print "<tr>";
			
			 			if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
				print "<td colspan='2' class='".$TABLEBoxProductsDisplayedMiddlePrice."'>";
				print "<div style='BACKGROUND:url(im/spacer_grey.gif); background-repeat:repeat-x; margin:5px 35px 5px 35px;'><img src='im/zzz.gif' width='1' height='1'></div>";
				print "<div align='center'>";
				print "<span class='PromoFont'>".$display_price."</span>";
				if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
					print "<br>".VOTRE_PRIX." : <b><span class='FontColorTotalPrice'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
				}
				print $displayDeee;
				 
				if($devise2Visible=="oui" AND $clientPrice>0) print curPrice($clientPrice,$symbolDevise2,"center");
				if(!empty($displaySave)) print "<br>".$displaySave;
				print "</div>";
				print "<div style='BACKGROUND:url(im/spacer_grey.gif); background-repeat:repeat-x; margin:5px 35px 5px 35px;'><img src='im/zzz.gif' width='1' height='1'></div>";
				print "</td>";
				print "</tr><tr>";
			}
			
			
			print "<td colspan='2' height='30' align='right' valign='bottom' class='".$TABLEBoxesProductsDisplayedBottom."'>";
				 
				print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
				if($euroPayment == "oui" AND $id_partenaire!=="" AND $displayGraphics == "oui") {
					if($clientPrice > 60 AND $clientPrice < 5000  ) {
						if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
							print "<td align='left' valign='bottom'>&nbsp;<a alt='1euro.com - ".$c_row['products_name_'.$_SESSION['lang']]."' title='1euro.com - ".$c_row['products_name_'.$_SESSION['lang']]."' href='javascript:calculette(\"https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".newPrice($clientPrice,$_SESSION['reduc'])."\")'><img src='im/betaal-logos/calc_bt_noir.gif' border='0'></a></td>";
						}
						else {
							print "<td align='left' valign='bottom'>&nbsp;<a alt='1euro.com - ".$c_row['products_name_'.$_SESSION['lang']]."' title='1euro.com - ".$c_row['products_name_'.$_SESSION['lang']]."' href='javascript:calculette(\"https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".$clientPrice."\")'><img src='im/betaal-logos/calc_bt_noir.gif' border='0'></a></td>";
						}
					}
					else {
						print "<td align='left'><img src='im/zzz.gif' width='1' height='30'></td>";
					}
				}
				print "<td align='right' valign='bottom'>";
				if(isset($displayFlash)) print $displayFlash;
				print $prodDegressif;
				print $prodHart;
				print $prodDown;
				print $bouton;
				if($prodDown=='') print $stockLev;
				print "</td>";
				print "</tr></table>";
			print "</td>";
			print "</tr></table>";
			if($activeRoundedCorners=='yes') round_bottom('yes');
			print "<br>";
			print "</td>";
}
// --------
// END LOOP
// --------
print "</tr></table>";
      //round_bottom('yes');
      //print "<br>";
}
}
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
