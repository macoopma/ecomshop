<?php
if(isset($_SESSION['getPromo']) AND $_SESSION['getPromo']>0) {

if($displayOutOfStock=="non") {$addToQueryccp = " AND p.products_qt>'0'";} else {$addToQueryccp="";}

$selectPromo = "SELECT c.*, p.products_id, p.products_name_".$_SESSION['lang'].", p.products_desc_".$_SESSION['lang'].", p.products_price, p.products_ref, p.products_im, p.products_image, p.products_qt, s.specials_new_price, s.specials_visible, RAND() AS m_random
                                     FROM products as p
                                     LEFT JOIN categories as c ON (c.categories_id = p.categories_id)
                                     INNER JOIN specials as s ON (s.products_id = p.products_id)
                                     WHERE c.categories_visible = 'yes'
                                     AND p.products_ref != 'GC100'
                                     AND p.products_visible='yes'
                                     AND p.products_forsale='yes'
                                     ".$addToQueryccp."
                                     AND TO_DAYS(s.specials_first_day) <= TO_DAYS(NOW())
                                     AND TO_DAYS(NOW()) <= TO_DAYS(s.specials_last_day)
                                     AND s.specials_visible='yes'
                                     ";

if(isset($_GET['path']) and $_GET['path'] !==0) $addQueryPromo = " AND c.categories_id = '".$_GET['path']."'"; else $addQueryPromo = "";

$resultPromo2 = mysql_query($selectPromo.$addQueryPromo." ORDER BY m_random LIMIT 0,".$nbre_promo);
$rowsPromo =  mysql_num_rows($resultPromo2);
?>

<div class="raised" style="width:<?php print $larg_rub;?>">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b></b>
<div class="boxcontent">

         <table border="0" cellspacing="0" cellpadding="2" class="modulePromo">
         <tr>
         <td height='<?php print $hauteurTitreModule;?>' class="modulePromoTitre contentTop" align="center" style="width:<?php print $larg_rub;?>">
             <?php print "<a href='".seoUrlConvert("list.php?target=promo")."'>";?><img src="im/lang<?php print $_SESSION['lang'];?>/menu_promo.png" border="0" title="<?php print PROMOTIONS;?>" alt="<?php print PROMOTIONS;?>"></a>
         </td>
         </tr>
         <tr>
         <td>
<?php
function IsCat($catArray) {
   GLOBAL $_SESSION;
   foreach($_SESSION['getPromoId'] AS $items) {
      $IsPromoIs = mysql_query("SELECT categories_id FROM products WHERE products_id='".$items."'");
      $promoIs = mysql_fetch_array($IsPromoIs);
      if(!in_array($promoIs['categories_id'],$catArray)) unset($catArray[array_search($promoIs['categories_id'], $catArray)]);
      $nouveau_tableau[] = array_shift($catArray);
   }
   foreach ($nouveau_tableau as $key => $value) {
      if(is_null($value) || $value=="") {
        unset($nouveau_tableau[$key]);
      }
    }
   return $nouveau_tableau;
}

if($rowsPromo == 0) {
 
if(isset($_GET['path'])) {
$wP = recurs3($_GET['path']);
}
else {
    $wP = recurs3("");
}

if(isset($wP)) {
  $rand_keysP = array_rand($wP, 1);
  $w1P = $wP[$rand_keysP];
}
else {
  if(isset($_GET['path'])) $w1P = $_GET['path']; else $w1P=1;
}

if(isset($_GET['path']) and $_GET['path'] !==0) $addQueryPromo = " AND c.categories_id = '".$w1P."'"; else $addQueryPromo = "";

$resultPromo2 = mysql_query($selectPromo.$addQueryPromo." ORDER BY m_random LIMIT 0,".$nbre_promo);
$rowsPromo =  mysql_num_rows($resultPromo2);

 
if($rowsPromo==0 AND $forcePromo=='oui') {
    $resultPromo2 = mysql_query($selectPromo." ORDER BY m_random LIMIT 0,".$nbre_promo);
    $rowsPromo =  mysql_num_rows($resultPromo2);
}
}

                    if($rowsPromo==0) {
                        print "<div align='center'>".PAS_DE_PROMOTIONS_DANS_CETTE_CATEGORIE;
                        print "<br><a href='".seoUrlConvert("list.php?target=promo")."'><img src='im/plus.gif' border='0' align='absmiddle' title='".AUTRES."' alt='".AUTRES."'></a>";
                        print "</div>";
                    }

                 while($a_rowPromo = mysql_fetch_array($resultPromo2)) {
                       print "<img src='im/fleche_right.gif'>";
                       $namePromo = strip_tags(adjust_text($a_rowPromo['products_name_'.$_SESSION['lang']],$maxCarInfo,".."));
                       print "&nbsp;<a href='".seoUrlConvert("beschrijving.php?id=".$a_rowPromo['products_id']."&target=promo")."' ".display_title($a_rowPromo['products_name_'.$_SESSION['lang']],$maxCarInfo).">".$namePromo."</a><br>";

$new_pricePromo = $a_rowPromo['specials_new_price'];
$old_pricePromo = $a_rowPromo['products_price'] ;

if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
	if(!empty($new_pricePromo)) {
		if($a_rowPromo['specials_visible']=="yes") {
			$pricePromo = $new_pricePromo;
			print "<div align='center'><b><s>".$old_pricePromo."</s> ".$symbolDevise."<br><span class='fontrouge'>".$pricePromo." ".$symbolDevise."</span></b></div>";
			$clientPrice = $new_pricePromo;
		}
		else {
			$price = $old_pricePromo;
			print "<div align='center'><b>".$pricePromo." ".$symbolDevise."</b></div>";
			$clientPrice = $old_pricePromo;
		}
	}
	else {
		$price = $old_pricePromo;
		print "<div align='center'><b>".$pricePromo." ".$symbolDevise."</b></div>";
		$clientPrice = $old_pricePromo;
	}
	
	if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
		print "<div align='center'>".VOTRE_PRIX." : <b><span class='fontrouge'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b></div>";
	}
}
                       
 
  

                       if($a_rowPromo['products_im']=="yes" AND $a_rowPromo['products_image']!=="" AND $a_rowPromo['products_image']!=="im/no_image_small.gif") {
                            $larg_rub2 = $larg_rub-30;
                              $yoZZ1 = @getimagesize($a_rowPromo['products_image']);
                              if(!$yoZZ1) $a_rowPromo['products_image']="im/zzz_gris.gif";
                            $resize_image = resizeImage($a_rowPromo['products_image'],$hautImageMaxPromo,$larg_rub2);
                            print "<div align='center' style='padding:5px;'>
                                    <a href='".seoUrlConvert("beschrijving.php?id=".$a_rowPromo['products_id']."&target=promo")."'>";
                                   // Is server GD open ?
                                   if($gdOpen == "non") {
                                            print "<img src='".$a_rowPromo['products_image']."' width='".$resize_image[0]."' height='".$resize_image[1]."' border='0' alt='".$a_rowPromo['products_name_'.$_SESSION['lang']]."' title='".$a_rowPromo['products_name_'.$_SESSION['lang']]."'>";
                                   }
                                   else {
                                            $infoImage = infoImageFunction($a_rowPromo['products_image'],$larg_rub2,$hautImageMaxPromo);
                                            print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$a_rowPromo['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$a_rowPromo['products_name_'.$_SESSION['lang']]."' title='".$a_rowPromo['products_name_'.$_SESSION['lang']]."'>";                  
                                   }
                            print "</a>";
                            print "</div>";
                           }
                           else {
                              print "<div align='center'><img src='im/zzz.gif' width='1' height='1'></div>";
                           }
                       }
if($rowsPromo > 0) print "<div align='center'><a href='".seoUrlConvert("list.php?target=promo")."'><img src='im/plus.gif' border='0' align='absmiddle' title='".AUTRES."' alt='".AUTRES."'></a></div>";
?>
            </td>
            </tr>
            </table>
</div>
<b class="bottom"><b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b></b>
</div>
            <br>
<?php
}
?>
