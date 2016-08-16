<?php
include('configuratie/configuratie.php');
include('includes/doctype.php');
 
if(!isset($_GET['lang'])) $_GET['lang'] = $langue;
include("includes/lang/lang_".$_GET['lang'].".php");
$tt="100%";
$promoIs="";

$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('http://".$www2.$domaineFull."/pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";

   $result = mysql_query("SELECT p.products_name_".$_GET['lang'].", p.products_deee, p.products_exclusive, p.fabricant_id, p.afficher_fabricant, p.products_garantie_".$_GET['lang'].", p.products_options, p.products_id, p.categories_id, p.fournisseurs_id, p.afficher_fournisseur, p.products_desc_".$_GET['lang'].", p.products_price, p.products_weight, p.products_note_".$_GET['lang'].", p.products_ref, p.products_im, p.products_image, p.products_image2, p.products_image3, p.products_image4, p.products_image5, p.products_option_note_".$_GET['lang'].", p.products_visible, p.products_taxable, p.products_tax, p.products_date_added, p.products_qt, p.products_viewed, p.products_qt, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, f.fournisseurs_company, f.fournisseurs_link, c.categories_name_".$_GET['lang'].", c.categories_id, c.parent_id, p.products_download, p.products_related
                          FROM products as p
                          LEFT JOIN specials as s
                          ON (p.products_id = s.products_id)
                          LEFT JOIN fournisseurs as f
                          ON (p.fournisseurs_id = f.fournisseurs_id)
                          LEFT JOIN categories as c
                          ON (p.categories_id = c.categories_id)
                          WHERE p.products_ref = '".$_GET['id']."'");
    $resultNum = mysql_num_rows($result);
   
    $rowp = mysql_fetch_array($result);
    $title = $rowp['products_name_'.$_GET['lang']];
 
 
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

if($_SERVER['REQUEST_URI']) {
    $url_id10 = $_SERVER['REQUEST_URI'];
}
else {
    $url_id10 = $_SERVER["SCRIPT_NAME"]."?".$_SERVER["QUERY_STRING"];
}
?>
<html>
    <head>
        <META HTTP-EQUIV="Expires" CONTENT="Fri, Jan 01 1900 00:00:00 GMT">
        <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
        <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="author" content="<?php print $auteur;?>">
        <meta name="generator" content="PsPad">
        <META NAME="description" CONTENT="<?php print $rowp['categories_name_'.$_GET['lang']];?>">
        <meta name="keywords" content="<?php print $rowp['categories_name_'.$_GET['lang']];?>">
        <meta name="revisit-after" content="15 days">
        <title>
            <?php print $title." | ". $store_name; ?>
        </title>
        <link rel="shortcut icon" href="http://<?php print $www2.$domaineFull;?>/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="http://<?php print $www2.$domaineFull;?>/css/<?php print $colorInter;?>.css" type="text/css">
<script type="text/javascript"><!--
function add_favoris(mag,art,url) {
if( navigator.appName != 'Microsoft Internet Explorer' ) {
window.sidebar.addPanel(mag+' | '+art,url,"");
}
else {
window.external.AddFavorite(url,mag+' - '+art);
}
}
//--> </script>
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php 
if($resultNum>0) {
print '<table width="450" align="center" border="0" cellpadding="'.$cellpad.'" cellspacing="5" class="TABLEBackgroundBoutiqueCentre">';
print '<tr>';
print '<td valign="top">';
					$refdet = strtoupper($rowp['products_ref']);
                  	 
					if(!empty($rowp['specials_new_price'])) {
							if($rowp['specials_visible']=="yes") {
	                           	$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
	                           	$dateMaxCheck = (!empty($rowp['specials_last_day']))? explode("-",$rowp['specials_last_day']) : explode("-","0-0-0");
	                           	$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
	                           	$dateDebutCheck = (!empty($rowp['specials_first_day']))? explode("-",$rowp['specials_first_day']) : explode("-","0-0-0");
	                           	$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
	                           	
	                        	if($dateDebut <= $today  and $dateMax >= $today) {
	                              $delayPassed = "no";
	                              $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
	                              $promoIs = "yes";
	                        	}
								else {
	                        		$delayPassed = "yes";
	                            	$EnPromo = "";
	                            	$promoIs = "no";
	                    		}
	                    	}
	                    	else {
                   				$EnPromo = "";
                   				$promoIs = "no";
							}
					}
					else {
                   		$EnPromo = "";
                   		$promoIs = "no";
					}

            if(isset($delayPassed) and $delayPassed == "no") {
                                  $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
                                  $jour=($itMiss>1)? JOURS."s": JOURS;
                                  
                                  $addDay = $dateMaxCheck[2]+1;
                                  $endPromotion = $dateMaxCheck[1]."/".$addDay."/".$dateMaxCheck[0]." 00:00 AM";
                                  $econ = $rowp['products_price'] - $rowp['specials_new_price'];
                                  $econ = sprintf("%0.2f",$econ);
                                  $econPourcent = (1-($rowp['specials_new_price']/$rowp['products_price']))*100;
                                  $econPourcent = sprintf("%0.2f",$econPourcent)."%";
                            $aa = '<script type="text/javascript" src="http://'.$www2.$domaineFull.'/includes/aftellen.js"></script>';
                            $EnPromoBasic = "<br><table border='0' width='250' cellpadding='5' cellspacing='0' class='TABLE1' align='center'>";
                            $EnPromoBasic.= "<tr>";
                            $EnPromoBasic.= "<td align='center'>";
                            $EnPromoBasic.= "<span class='fontrouge'><b>-".$econPourcent."<br>** ".SOIT." ".$econ." ".$devise."s **</b></span>";
                            $EnPromoBasic.= "</td>";
                            $EnPromoBasic.= "</tr>";
                            $EnPromoBasic.= "</table>";
                                               
                    if($itMiss > $seuilPromo) {
                            $EnPromo = $EnPromoBasic;
                    }
                    else {
                        if($activeSeuilPromo == "oui") {
							$EnPromo = "<br>".EN_PROMOTION_JUSQU_AU." ".date("d M Y",mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]))."<br>";
							$EnPromo.= "<table border='0' width='250' cellpadding='5' cellspacing='0' class='TABLE1' align='center'>";
							$EnPromo.= "<tr>";
							$EnPromo.= "<td align='center'>";
							$EnPromo.= "<span class='fontrouge'><b>-".$econPourcent."<br>** ".SOIT." ".$econ." ".$devise."s **</b></span>";
							$EnPromo.= "<br><br>";
							$EnPromo.= FIN_DE_PROMOTION_DANS."<br>".$aa;
							$EnPromo.= "</td>";
							$EnPromo.= "</tr>";
							$EnPromo.= "</table>";
                         }
                         else {
                            $EnPromo = $EnPromoBasic;
                         }
                    }
            }
                           
            if($promoIs=="yes" AND $rowp['specials_last_day'] == "2020-01-01") { $EnPromo = $EnPromoBasic;}
            

            if(!empty($rowp['specials_new_price']) AND isset($delayPassed) AND $delayPassed=="no") {
               $price = $rowp['specials_new_price'];
               $displayPrice = "<span style='FONT-SIZE: 11px'><b><s>".$rowp['products_price']." ".$symbolDevise."</s></b></span><br><span class='PromoFontColorNumber'><b>".$price." ".$symbolDevise."</b></span>";
               $sautLigne = "<br>";
            }
            else {
               $price = $rowp['products_price'];
               $displayPrice = "<span style='FONT-SIZE: 11px'><b>".$price." ".$symbolDevise."</b></span>";
               $sautLigne = "";
            }
 
print "<table width='450' border='0' cellspacing='5' cellpadding='5' class='TABLETitreProductDescription' align='center'>
        <tr>
        <td>";
print "&nbsp;<b class='titre'>".$rowp['products_name_'.$_GET['lang']]."</b></td>";
print "</tr>
        </table>";
print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='0' align='center'><tr>";
print "<td>";
print "<table width='".$tt."' border='0' cellspacing='3' cellpadding='0' align='center'>";
print "<tr>";
 
        if($rowp['products_im'] == "yes" AND !empty($rowp['products_image'])) {
        $h = @getimagesize($rowp['products_image']);
        if(!$h) $rowp['products_image']="im/zzz_gris.gif";
        $image_desc = resizeImage($rowp['products_image'],$ImageSizeDesc,200);
        print "<td width='".$image_desc[0]."' height='".$image_desc[1]."' align='left' valign='top'>";

                    if(substr($rowp['products_image'], 0, 4)=="http") {
                        $imm = $rowp['products_image'];
                    }
                    else {
                        $imm = "http://".$www2.$domaineFull."/".$rowp['products_image'];
                    }
                    
        print "<img src='".$imm."' border='0' width='".$image_desc[0]."' height='".$image_desc[1]."' alt='".$rowp['products_name_'.$_GET['lang']]."'>";
        print "</td>";
              
  
    				for($n=2; $n<=5; $n++) {
              if(!empty($rowp['products_image'.$n.''])) {
   
                  if(substr($rowp['products_image'.$n.''],-3) == "gif" OR 
                     substr($rowp['products_image'.$n.''],-3) == "jpg" OR 
                     substr($rowp['products_image'.$n.''],-3) == "png") {
                      $h1 = @getimagesize($rowp['products_image'.$n]);
                      if(!$h1) $rowp['products_image'.$n]="im/zzz_gris.gif";
                      $image_desc2 = resizeImage($rowp['products_image'.$n],$SecImageSizeDesc,$SecImageWidthDesc);
                      
                        print "<td width='".$image_desc2[0]."' height='".$image_desc2[1]."' valign='top'>
                               <table cellspacing='0' cellpadding='0' style='border:1px #000000 solid; padding:1px;'><tr><td>";
                    
                    if(substr($rowp['products_image'], 0, 4)=="http") {
                        $imm2 = $rowp['products_image'.$n];
                    }
                    else {
                        $imm2 = "http://".$www2.$domaineFull."/".$rowp['products_image'.$n];
                    }
                    
                        print "<img src='".$imm2."' width='".$image_desc2[0]."' height='".$image_desc2[1]."' border='0'>";
                        print "</td></tr></table>
                               </td>";
                  }
    
                    if(substr($rowp['products_image'.$n.''],-3) == "ram" OR 
                       substr($rowp['products_image'.$n.''],-3) == "mp3" OR 
                       substr($rowp['products_image'.$n.''],-3) == "wav" OR
                       substr($rowp['products_image'.$n.''],-2) == "au"  OR
                       substr($rowp['products_image'.$n.''],-3) == "aif" OR
                       substr($rowp['products_image'.$n.''],-3) == "wma" 
                       ) {
                        $explodeSoundFile = explode("/",$rowp['products_image'.$n.'']);
                        $namez = substr(end($explodeSoundFile), 0, -4);
                            if(substr($rowp['products_image'], 0, 4)=="http") {
                                $imm3 = $rowp['products_image'.$n];
                            }
                            else {
                                $imm3 = "http://".$www2.$domaineFull."/".$rowp['products_image'.$n];
                            }
                        print "<td width='1' valign='top'>";
                               print "<table cellspacing='0' cellpadding='0'><tr>";
                               print "<td>";
                               print "<a href='".$imm3."'><img src='http://".$www2.$domaineFull."/im/notes.gif' border='0' alt='Title: ".$namez."' title='Title: ".$namez."'></a>";
                               print "</td>";
                               print "</tr></table>";
                        print "</td>";
                  }
              }
            }
        }
        else {
                  $noImage_resizeDesc = resizeImage("im/no_image_small.gif",$ImageSizeDesc,200);
                  print "<td rowspan='3' valign='middle' align='left'><img src='http://".$www2.$domaineFull."/im/no_image_small.gif' width='".$noImage_resizeDesc[0]."' height='".$noImage_resizeDesc[1]."'></td>";
        }
        print "<td align='right' valign='top'>";
 
        print "<table width='100%' border='0' cellspacing='2' cellpadding='0'><tr>";
        print "<td>";
        print "<div align='right' style='padding:5px'>";
        if(isset($price) AND $price>0) print $displayPrice; else print "<b><span class='fontrouge'>**".OFFERT."**</span></b>";
        print "</div>";
        print "</td></tr></table>";
        print "</td>";
        print "</tr>";
        print "</table>";
 
$fabricantQuery = mysql_query("SELECT fournisseurs_company
                               FROM fournisseurs
                               WHERE fournisseurs_id = '".$rowp['fabricant_id']."'");
$fab = mysql_fetch_array($fabricantQuery);
$fabricant = $fab['fournisseurs_company'];
 
print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='2' align='center'>";
 
if($rowp['products_deee']>0) {
$openDeee = "<i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('http://".$www2.$domaineFull."/includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
    print "<tr><td colspan='2' valign='bottom' align='right'>";
    print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$rowp['products_deee']." ".$symbolDevise."</i>";
    print "</td>";
}
print "</tr>";
 
   if($rowp['afficher_fabricant']=='yes' AND !empty($fabricant)) {
       print "<tr>
              <td colspan='2' width='100%' valign='bottom' align='left'>
              ".COMPAGNIE." ".$fabricant."
              </td>
              </tr>";
   }
 
print "<tr>";
print "<td colspan='2' valign='bottom' align='left'><b>".REF."</b>: $refdet</td>";
print "</tr>";
 
   if($rowp['afficher_fournisseur']=='yes') {
      if(!empty($rowp['fournisseurs_link'])) {
       print "<tr>
              <td colspan='2' width='100%' valign='bottom' align='left'>
              <b>".FOURNISSEUR."</b>: <a href='".$rowp['fournisseurs_link']."' target='_blank'>".$rowp['fournisseurs_company']."</a>
              </td>
              </tr>";
      }
      else {
       print "<tr>
              <td colspan='2' width='100%' valign='bottom' align='left'>
              <b>".FOURNISSEUR."</b>: ".$rowp['fournisseurs_company']."
              </td>
              </tr>";
      }
   }
 
      if(!empty($rowp['products_garantie_'.$_GET['lang']])) {
         print "<tr>";
         print "<td colspan='2' width='100%' valign='bottom' align='left'><b>".GARANTIE."</b>: ".$rowp['products_garantie_'.$_GET['lang']]."</td>";
         print "</tr>";
      }
 
        if($rowp['products_qt']>0) {
            $qty = $openLeg."<img src='http://".$www2.$domaineFull."/im/lang".$_GET['lang']."/stockok.png' border='0' alt='".EN_STOCK."' title='".EN_STOCK."'></a>";
        }
        else {
            if($actRes=="oui") {
                $qty2 = ARTICLE_EN_COMMANDE;
                $qty = $openLeg."<img src='http://".$www2.$domaineFull."/im/stockin.gif' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'></a>";
            } 
            else {
                $qty = $openLeg."<img src='http://".$www2.$domaineFull."/im/stockno.gif' border='0' alt='".NOT_IN_STOCK."' title='".NOT_IN_STOCK."'></a>";
            }
        }
 
        if($rowp['products_download'] == "yes") {
            $prodDown = $openLeg."&nbsp;<img src='http://".$www2.$domaineFull."/im/download.gif' border='0' alt='".ARTICLE_TELE."' title='".ARTICLE_TELE."'></a>";
        } else {
            $prodDown = "";
        }
    print "<tr><td>";
    print "<b>".DISPO."</b>: ".$qty.$prodDown;
 
print "</tr><tr><td colspan='2'>";
       print "</td>";
if($rowp['products_options'] == 'yes') {

       print "<tr><td colspan='2'>";
       print "<hr width='".$tt."'>";
       print "</td></tr>";
 
       print "<tr><td colspan='2'><b>".strtoupper(PRODUIT_OPTIONS)."</b>:</td></tr>";
 
      $optionQuery = mysql_query("SELECT pr.products_id, pr.products_option, po.products_options_name, pr.note_option_".$_GET['lang']."
                                  FROM  products_id_to_products_options_id as pr
                                  LEFT JOIN products_options as po
                                  ON(pr.products_options_id = po.products_options_id)
                                  WHERE pr.products_id = '".$rowp['products_id']."'
                                  ORDER BY po.products_options_name
                                  ASC");
      $optionNum = mysql_num_rows($optionQuery);
      while($optionResult = mysql_fetch_array($optionQuery)) {
             $o[$optionResult['products_options_name']] = $optionResult['products_option']."|";
             $noteOpt[] = $optionResult['note_option_'.$_GET['lang']];
      }
      $optionNum = count($o);
      print "<input type='hidden' name='optionNum' value='".$optionNum."'>";
            $keys = array_keys($o);
            $values = array_values($o);
                            for($x=1; $x <= $optionNum; $x++)
                            {
                            print "<tr><td colspan='2'><b>".$keys[$x-1]."</b>:<br>";
                            // option note
                            if(!empty($noteOpt[$x-1])) { print $noteOpt[$x-1]."<br>";}
                            print "<select name='option[".$x."]' >";
                            $opt = explode(",",$values[$x-1]);
                                 $aa = count($opt);
                                       for($xx=1; $xx <= $aa-1; $xx++) {
                                         $optionFinal = explode("::",$opt[$xx-1]);
                                         if(!empty($optionFinal[1]) and $optionFinal[1]!=='+0.00') $euro = " | ".$optionFinal[1].$symbolDevise; else $euro = "";
                                         if($optionFinal[1] == '+0.00') $sel="selected"; else $sel="";
                                            print "<option value='".$optionFinal[0]."/".$optionFinal[1]."' $sel>".$optionFinal[0].$euro."</option>";
                                       }
                             print "</select>";
                             print "</td></tr>";
                           }
}

       if(!empty($rowp['products_option_note_'.$_GET['lang']]) AND $rowp['products_options'] == 'yes') {
       print "<tr><td colspan='2'>".$rowp['products_option_note_'.$_GET['lang']]."</td></tr>";
       }
 
       print "<tr><td colspan='2'>";
       print "<hr width='".$tt."'>";
        print "</td>
              </tr>";
 
       print "<tr><td colspan='2'><b>".DESCRIPTIONMAJ."</b> :</td>";
        print "</tr>";
 
        print "<tr>";
        print "<td colspan='2' valign='top'><div align='justify'>".$rowp['products_desc_'.$_GET['lang']]."</div></td></tr>";
 
        if(!empty($rowp['products_note_'.$_GET['lang']])) {
        print "<tr><td colspan='2'><div align='left'>".$rowp['products_note_'.$_GET['lang']]."</div>";
        print "</td></tr>";
        }
        print "</table>";
print "</td>";
print "</tr></table>";
print "</td></tr></table>";

print "<p align='center'><img src='im/fleche_menu2.gif' border='0' align='absmiddle'>&nbsp;<a href='http://".$www2.$domaineFull."/beschrijving.php?id=".$rowp['products_id']."&lang=".$_GET['lang']."' target='_blank' title='".$store_name."' alt='".$store_name."'><b>".LA_BOUTIQUE."</b></a></p>";
}
else {
print "<p align='center' class='fontrouge'><b>".DESOLE."</b></p>";
}
        ?>
    </body>
</html>
