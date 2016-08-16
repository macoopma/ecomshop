<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = "TOP 10";
$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('pop_uitleg.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=260,width=330,toolbar=no,scrollbars=no,resizable=yes');\">";
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
 
if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}

 
if($header2Display=='oui') {
   print "<td colspan='3'>";
   include('includes/tabel_top2.php');
   print "</td></tr><tr>";
   print "<td colspan='3'>";
}
else {
   print "<td colspan='3'>";
}

 
if($menuVisibleTab=="oui") {
   include('includes/menu_tab.php'); 
   $styleClass1 = "TABLEMenuPathTopPage";
} 
else {
   $styleClass1 = "TABLEMenuPathTopPageMenuTabOff";
}
 
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
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | TOP 10</b>
      <?php
      if($activeRSS=="oui") {
         print "&nbsp;&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_top10.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".DIX_MEILLEURES_VENTES."' alt='RSS ".DIX_MEILLEURES_VENTES."'></a>";
      }
      ?>
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
  <tr valign="top">
    <td colspan="3">




      <table width="100%" border="0" cellpadding="3" cellspacing="5">
        <tr>
          <?php
		  // --------------------------------------
		  // linkse kolom 
		  // --------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">

            <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="3" align="center">
              <tr>
                <td valign="top">

<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
<?php 
                     print "<img src='im/accueil.gif' align='absmiddle'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | TOP 10 |";
                     if($activeRSS=="oui") {
                        print "&nbsp;&nbsp;<a href='http://".$www2.$domaineFull."/rss/rss_top10.php?lang=".$_SESSION['lang']."' target='_blank'><img src='im/rss_atom.gif' align='texttop' border='0' title='RSS ".DIX_MEILLEURES_VENTES."' alt='RSS ".DIX_MEILLEURES_VENTES."'></a>";
                     }
?>
                      </td>
                    </tr>
                  </table>
                 <br>
<?php
}
print "<p class='titre'>".DIX_MEILLEURES_VENTES."</p>";

$hids = mysql_query("SELECT p.products_desc_".$_SESSION['lang'].", p.products_forsale, p.products_image, p.products_name_".$_SESSION['lang'].", p.products_id, p.products_qt, p.products_viewed, p.categories_id, c.categories_name_".$_SESSION['lang'].", c.parent_id, p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, IF(s.products_id<>'null', 'oui','non') as toto
                     FROM products AS p
                     LEFT JOIN categories AS c
                     ON(p.categories_id = c.categories_id)
                     LEFT JOIN specials AS s
                     ON(p.products_id = s.products_id)
                     WHERE p.products_visible='yes'
                     AND p.products_ref != 'GC100'
                     ORDER BY p.products_viewed
                     DESC
                     LIMIT 0,10");

 
if(mysql_num_rows($hids)>0) {
$i=0;
print "<table border='0' cellpadding='2' cellspacing='0' align='center' class='TABLESortByCentre'>";
while ($myhid = mysql_fetch_array($hids)) {
 
	if(isset($d) AND $d==1) $d=2; else $d=1;
	$i=$i+1;
               
 
	$new_price = $myhid['specials_new_price'];
	$old_price = $myhid['products_price'];

	if(empty($new_price)) {
		$comment_start = "";
		$price = $old_price;
		$comment_end = "";
	}
	else {
		if($myhid['specials_visible']=="yes") {
			$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$dateMaxCheck = explode("-",$myhid['specials_last_day']);
			$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
			$dateDebutCheck = explode("-",$myhid['specials_first_day']);
			$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
			
			if($dateDebut <= $today  and $dateMax >= $today) {
				$comment_start = "<s>".$old_price."</s> ".$symbolDevise."<br><span class='fontrouge'>";
				$price = $new_price;
				$comment_end = "</span>";
			}
			else {
				$comment_start = "";
				$price = $old_price;
				$comment_end = "";
			}
		}
		else {
			$comment_start = "";
			$price = $old_price;
			$comment_end = "";
		}
	}

  
       $productLink = "&bull;&nbsp;<a href='beschrijving.php?id=".$myhid['products_id']."&path=".$myhid['categories_id']."'><span class='fontrouge'><b>".$myhid['products_name_'.$_SESSION['lang']]."</b></span></a>";
       $priceTop10 = $comment_start.$price." ".$symbolDevise.$comment_end;
       
        if($myhid['products_qt']>0) {
            $dispo = $openLeg."<img align='absmiddle' src='im/lang".$_SESSION['lang']."/stockok.png' border='0' title='".EN_STOCK."' alt='".EN_STOCK."'></a>";
        }
        else {
            if($actRes=="oui") {$dispo = $openLeg."<img align='absmiddle' src='im/stockin.gif' border='0' title='".EN_COMMANDE."' alt='".EN_COMMANDE."'></a>";} else {$dispo = $openLeg."<img src='im/stockno.gif' border='0' title='".NOT_IN_STOCK."'></a>";}
        }
 
		if($myhid['products_forsale']=="no") $dispo = $openLeg."<img align='absmiddle' src='im/no_stock.gif' border='0' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'></a>";
 
		if(in_array($myhid['products_id'], $_SESSION['discountQt']) AND $myhid['products_forsale']=='yes') {
			$prodDegressif = "&nbsp;".$openLeg."<img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' align='absmiddle'></a>";
		} else {
			$prodDegressif = "";
		}
	
		$images_widthTop10 = $haut_im+20;
		$image_resize_top = resizeImage($myhid['products_image'],$haut_im,$images_widthTop10);
      
 
      	print "<tr height='45' onmouseover=\"this.style.backgroundColor='#".$backGdColorListLine."';\" onmouseout=\"this.style.backgroundColor='';\" class='TDTableListLine".$d."'>";
  
      	print "<td>".$i.".</td>";
   
      	print "<td align='center' width='".$images_widthTop10."'>";
      	print "<a href='beschrijving.php?id=".$myhid['products_id']."&path=".$myhid['categories_id']."'>";
      	if($gdOpen == "non") {
	        if($listPop=="oui") {
	           $popSize = @getimagesize($myhid['products_image']);
	           if(!$popSize) $myhid['products_image']="im/zzz_gris.gif";
	           if($popSize[1] > $haut_im) {
	            	print "<img src='".$myhid['products_image']."' onmouseover=\"trailOn('".$myhid['products_image']."','".$myhid['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\" onmouseout=\"hidetrail();\" border='0' width='".$image_resize_top[0]."' height='".$image_resize_top[1]."' alt='".$myhid['products_name_'.$_SESSION['lang']]."' align='absmiddle'>";
	           }
	           else {
	            	print "<img src='".$myhid['products_image']."' border='0' width='".$image_resize_top[0]."' height='".$image_resize_top[1]."' title='".$myhid['products_name_'.$_SESSION['lang']]."' alt='".$myhid['products_name_'.$_SESSION['lang']]."'>";
	           }
	        }
	        else {
	        	print "<img src='".$myhid['products_image']."' border='0' width='".$image_resize_top[0]."' height='".$image_resize_top[1]."' title='".$myhid['products_name_'.$_SESSION['lang']]."' alt='".$myhid['products_name_'.$_SESSION['lang']]."'>";
	        }
      }
      else {
          $popSize = @getimagesize($myhid['products_image']);
          if(!$popSize) $myhid['products_image']="im/zzz_gris.gif";
          $infoImage = infoImageFunction($myhid['products_image'],$images_widthTop10,$haut_im);
                            if($listPop=="oui") {
                                if($popSize[1] > $haut_im) {
                                    print "<img onmouseover=\"trailOn('".$myhid['products_image']."','".$myhid['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','".$popSize[0]."','".$popSize[1]."','bold');\" onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$myhid['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$myhid['products_name_'.$_SESSION['lang']]."'>";
                                }
                                else {
                                    if($popSize[0]>$images_widthTop10) {
                                       $rapport2 = $popSize[0]/$popSize[1];
                                       $width2 = $images_widthTop10;
                                       $height2 = $width2/$rapport2;
                                       print "<img onmouseover=\"trailOn('im/zzz.gif','".$myhid['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_row['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$width2."&hauteur=".$height2."' border='0' alt='".$myhid['products_name_'.$_SESSION['lang']]."'>";
                                    }
                                    else {
                                       print "<img onmouseover=\"trailOn('im/zzz.gif','".$myhid['products_name_'.$_SESSION['lang']]."','','','',".$imZoomMax.",'1','200','1','');\" onmouseout=\"hidetrail();\" src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$myhid['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[1]."&hauteur=".$infoImage[2]."' border='0' alt='".$myhid['products_name_'.$_SESSION['lang']]."'>";
                                    }
                                }
                            }
                            else {
                                print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$myhid['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$myhid['products_name_'.$_SESSION['lang']]."' title='".$myhid['products_name_'.$_SESSION['lang']]."'>";                  
                            }
                            
      }
      print "</a>";
      print "</td>";
      print "<td>";
    
      print $productLink.$prodDegressif;
      print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
     
      $ProdDesc = $myhid['products_desc_'.$_SESSION['lang']];
      $ProdDesc = trim(strip_tags($ProdDesc));
      $ProdDesc = adjust_text($ProdDesc,150,"..<a href='beschrijving.php?id=".$myhid['products_id']."&path=".$myhid['categories_id']."' title='".PLUS_INFOS."'><img src='im/next.gif' border='0'></a>");
      print $ProdDesc;
      print "<br>";
      
      print "<span class='FontGris'>-&nbsp;".CATEGORIE."</span> : [<a href='list.php?path=".$myhid['categories_id']."'><i>".$myhid['categories_name_'.$_SESSION['lang']]."</i></a>]";
      print "<br>";
       
      print "<span class='FontGris'>-&nbsp;".DISPO."</span> : ".$dispo;
      print "</td>";
         if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
         	if($myhid['products_forsale']=="yes") {
               print "<td width='60'><div align='center'>".$priceTop10."</div></td>";
            }
            else {
               print "<td width='60'><div align='center' class='fontrouge'><b>".OUT_OF_STOCK."</b></div></td>";
            }
         }
      print "</tr>";
}
print "</table>";
}
else {
   print "<p class='fontrouge' align='center'><b>".NO_ITEM_FOUND."</b></p>";
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

    </td>
  </tr>
</table>      

<?php include("includes/footer.php");?>
</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
