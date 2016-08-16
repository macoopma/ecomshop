<?php
include('../configuratie/configuratie.php');
$dir="../";
if($storeClosed == "oui") {$dirIpos = 1;}
include('../includes/plug.php');
include('functions.php');

include("../includes/lang/lang_".$_SESSION['lang'].".php");
$title = " | ".VOTRE_CADDIE;
if(isset($deee)) unset($deee);
$deee = array();

$styleDesactivate = "padding:2px; border:1px #FF0000 dotted";

if(isset($_SESSION['clientGender'])) unset($_SESSION['clientGender']);
if(isset($_SESSION['clientFirstname'])) unset($_SESSION['clientFirstname']);
if(isset($_SESSION['clientLastname'])) unset($_SESSION['clientLastname']);
if(isset($_SESSION['clientEmail'])) unset($_SESSION['clientEmail']);
if(isset($_SESSION['clientStreetAddress'])) unset($_SESSION['clientStreetAddress']);
if(isset($_SESSION['clientSurburb'])) unset($_SESSION['clientSurburb']);
if(isset($_SESSION['clientPostCode'])) unset($_SESSION['clientPostCode']);
if(isset($_SESSION['clientCountry'])) unset($_SESSION['clientCountry']);
if(isset($_SESSION['clientProvince'])) unset($_SESSION['clientProvince']);
if(isset($_SESSION['clientCity'])) unset($_SESSION['clientCity']);
if(isset($_SESSION['clientTelephone'])) unset($_SESSION['clientTelephone']);
if(isset($_SESSION['clientFax'])) unset($_SESSION['clientFax']);
if(isset($_SESSION['paymentMode'])) unset($_SESSION['paymentMode']);
if(isset($_SESSION['clientPassword'])) unset($_SESSION['clientPassword']);
if(isset($_SESSION['clientNic'])) unset($_SESSION['clientNic']);
if(isset($_SESSION['clientComment'])) unset($_SESSION['clientComment']);
if(isset($_SESSION['clientTVA'])) unset($_SESSION['clientTVA']);
if(isset($_SESSION['livraisonhors'])) unset($_SESSION['livraisonhors']);
if(isset($_SESSION['shipTax'])) unset($_SESSION['shipTax']);
if(isset($_SESSION['totalHtFinal'])) unset($_SESSION['totalHtFinal']);
if(isset($_SESSION['taxStatut'])) unset($_SESSION['taxStatut']);
if(isset($_SESSION['shipPrice'])) unset($_SESSION['shipPrice']);
if(isset($_SESSION['montantRemise'])) unset($_SESSION['montantRemise']);
if(isset($_SESSION['montantRemise2'])) unset($_SESSION['montantRemise2']);
if(isset($_SESSION['accountRemiseEffec'])) unset($_SESSION['accountRemiseEffec']);
if(isset($_SESSION['users_shipping_price'])) unset($_SESSION['users_shipping_price']);
if(isset($_SESSION['contre'])) unset($_SESSION['contre']);
if(isset($_SESSION['fact_adresse'])) unset($_SESSION['fact_adresse']);
if(isset($_SESSION['totalToPayTTC'])) unset($_SESSION['totalToPayTTC']);
if(isset($_SESSION['totalTax'])) unset($_SESSION['totalTax']);
if(isset($_SESSION['totalHtFinalPromo'])) unset($_SESSION['totalHtFinalPromo']);
if(isset($_SESSION['poids'])) unset($_SESSION['poids']);
if(isset($_SESSION['itemTax'])) unset($_SESSION['itemTax']);
if(isset($_SESSION['multipleTax'])) unset($_SESSION['multipleTax']);
if(isset($_SESSION['clientCompany'])) unset($_SESSION['clientCompany']);
if(isset($_SESSION['saveDataFromForm'])) unset($_SESSION['saveDataFromForm']);
if(isset($_SESSION['priceEmballageTTC'])) unset($_SESSION['priceEmballageTTC']);
if(isset($_SESSION['totalEmballageHt'])) unset($_SESSION['totalEmballageHt']);
if(isset($_SESSION['totalEmballageTva'])) unset($_SESSION['totalEmballageTva']);
if(isset($_SESSION['shippingName'])) unset($_SESSION['shippingName']);
if(isset($_SESSION['shippingId'])) unset($_SESSION['shippingId']);


// Activer Code de reduction
if(isset($_POST['codePromo']) and !empty($_POST['codePromo']) or isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == "1") {
    if(isset($_SESSION['activerCadeau'])) {
        $message = "<span class='PromoFontColorNumber'><b>".NON_CUMULABLE."</b></span>";
    }
    else {
         if(isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == "1") {
            $message = "<span class='PromoFontColorNumber'><b>".CODE_REMISE_ACTIVE."</b></span>";
               $query = mysql_query("SELECT * FROM code_promo ");
               $result = mysql_fetch_array($query);
         }
         else {
               $query = mysql_query("SELECT * FROM code_promo WHERE code_promo = '".$_POST['codePromo']."'");
               $result = mysql_fetch_array($query);

               if($result['code_promo'] == $_POST['codePromo']) {
               
               		// check date
               		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
                    $dateEndCheck = explode("-",$result['code_promo_end']);
                    $dateEnd = mktime(0,0,0,$dateEndCheck[1],$dateEndCheck[2],$dateEndCheck[0]);
               		if($dateEnd >= $today) {
               			if($result['code_promo_stat'] == "prive" and $result['code_promo_enter'] > 0) {
		                  $message = "<span class='PromoFontColorNumber'><b>".CODE_REMISE_UTILISE."</b></span>";
		                }
		                else {
						  $message = "<span class='PromoFontColorNumber'><b>".BRAVO."</b></span>";
		                  $_SESSION['activerCoupon'] = 1;
		                  $_SESSION['coupon_name'] = $result['code_promo'];
		                  $_SESSION['coupon_items'] = $result['code_promo_items'];
		                  // update +1 private
				                  if($result['code_promo_stat'] == "prive" and $result['code_promo_enter'] == 0) {
				                        mysql_query("UPDATE code_promo
		                                    		 SET code_promo_enter = code_promo_enter+1
		                                    		 WHERE code_promo ='".$_SESSION['coupon_name']."'");
								  }
								  if($result['code_promo_stat'] == "public") {
				                        mysql_query("UPDATE code_promo
		                                    		 SET code_promo_enter = code_promo_enter+1
		                                    		 WHERE code_promo ='".$_SESSION['coupon_name']."'");
								  }
						}
                    }
                    else {
                          $message = "<span class='PromoFontColorNumber'><b>".DATE_DEPASSE."</b></span>";
					}
               }
               else {
                  $message = "<span class='PromoFontColorNumber'><b>".CODE_ERRONE."</b></span>";
               }
         }
    }
}
else {
   $message = "";
}

// Desactiver point de fidélité
if(isset($_GET['action']) AND $_GET['action']=="activatefidelite") {
   if(isset($_SESSION['accountRemiseActive'])) unset($_SESSION['accountRemiseActive']);
   if(isset($_SESSION['accountRemise2'])) {
      $_SESSION['accountRemise'] = $_SESSION['accountRemise2'];
      unset($_SESSION['accountRemise2']);
   }
}

// Desactiver point de fidélité
if(isset($_GET['action']) AND $_GET['action']=="desactivatefidelite") {
   if(isset($_SESSION['accountRemise'])) {
      $_SESSION['accountRemise2'] = $_SESSION['accountRemise'];
      unset($_SESSION['accountRemise']);
   }
   $_SESSION['accountRemiseActive'] = "yes";
}

// Desactiver coupon
if(isset($_GET['action']) AND $_GET['action']=="desactivateCoupon") {
    if(isset($_SESSION['activerCoupon'])) {unset($_SESSION['activerCoupon']);}
    if(isset($_SESSION['coupon_name'])) {unset($_SESSION['coupon_name']);}
    if(isset($_SESSION['coupon_items'])) {unset($_SESSION['coupon_items']);}
    if(isset($_SESSION['montantRemise2'])) {unset($_SESSION['montantRemise2']);}
    $message = "<span class='PromoFontColorNumber'><b>".IS_DESACTIVATE_COUPON."</b></span>";
}

// Activer Cheque cadeau
if(isset($_POST['codeCadeau']) and !empty($_POST['codeCadeau']) or isset($_SESSION['activerCadeau']) and $_SESSION['activerCadeau'] == "1") {
    if(isset($_SESSION['activerCoupon'])) {
        $message20 = "<span class='PromoFontColorNumber'><b>".NON_CUMULABLE."</b></span>";
    }
    else {
         if(isset($_SESSION['activerCadeau']) and $_SESSION['activerCadeau'] == "1") {
            $message20 = "<span class='PromoFontColorNumber'><b>".CADEAU_ACTIVE."</b></span>";
               $query = mysql_query("SELECT * FROM gc ");
               $result = mysql_fetch_array($query);
         }
         else {
               $query = mysql_query("SELECT * FROM gc WHERE gc_number = '".$_POST['codeCadeau']."'");
                $queryNum = mysql_num_rows($query);
                if($queryNum > 0) {
                 $result = mysql_fetch_array($query);
                    if($result['gc_payed'] == '1') {

               		// check date
               		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
                    $dateEndCheck = explode("-",$result['gc_end']);
                    $dateEnd = mktime(0,0,0,$dateEndCheck[1],$dateEndCheck[2],$dateEndCheck[0]);
               		if($dateEnd >= $today) {
               			if($result['gc_enter'] == '1') {
		                  $message20 = "<span class='PromoFontColorNumber'><b>".CADEAU_UTILISE."</b></span>";
		                }
		                else {

		                  if(isset($_SESSION['totalDisplayedCart']) AND ($_SESSION['totalDisplayedCart']-$result['gc_amount']) >= 0 AND $_SESSION['ff'] >= $result['gc_amount']) {
        						  $message20 = "<span class='PromoFontColorNumber'><b>".CADEAU_ACTIVE."</b></span>";
        		                  $_SESSION['activerCadeau'] = 1;
        		                  $_SESSION['cadeau_number'] = $result['gc_number'];
        		                  $_SESSION['gc_reduc'] = $result['gc_amount'];
		                  }
		                  else {
                            $message20 = "<span class='PromoFontColorNumber'><b>".MONTANT_PAS_SUFFISANT."</b></span>";
                          }
						}
                    }
                    else {
                          $message20 = "<span class='PromoFontColorNumber'><b>".DATE_DEPASSE."</b></span>";
					}
                   }
                   else {
                        $message20 = "<span class='PromoFontColorNumber'><b>".CADEAU_ERRONE2."</b></span>";
                   }
               }
               else {
                    $message20 = "<span class='PromoFontColorNumber'><b>".CADEAU_ERRONE."</b></span>";
               }
         }
    }
}
else { $message20 = "";}

// Desactiver cheque cadeau
if(isset($_GET['action']) AND $_GET['action']=="desactivateCertficate") {
    if(isset($_SESSION['activerCadeau'])) {unset($_SESSION['activerCadeau']);}
    if(isset($_SESSION['cadeau_number'])) {unset($_SESSION['cadeau_number']);}
    if(isset($_SESSION['gc_reduc'])) {unset($_SESSION['gc_reduc']);}
    if(isset($_SESSION['montantRemise3'])) {unset($_SESSION['montantRemise3']);}
    if(isset($_SESSION['totalDisplayedCart'])) {unset($_SESSION['totalDisplayedCart']);}
    if(isset($_SESSION['deee'])) {unset($_SESSION['deee']);}
    $message20 = "<span class='fontrouge'><b>".IS_DESACTIVATE."</b></span>";
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="<?php print $_SESSION['css'];?>" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
        <?php include('top.php');?>
<table width="450" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
<td valign="top" class="TABLEMenuPathTopPage">

      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top" class="TABLEPageCentreProducts">



            <table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
              <tr>
                <td valign="top">



                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
                          <?php print "<b><img src='../im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='index.php?path=0&num=1&action=c'>".maj(HOME)."</a> | ".strtoupper(VOTRE_CADDIE)."</b>";?>
                      </td>
                    </tr>
                  </table>

                 <br>

<?php

            // afficher Promo livraison
if($remiseOnTax == "TTC") {$textTax = D_ACHAT_TTC;} else {$textTax = D_ACHAT_HORS_TAXE;}

            $textPromo =  POUR." <span class='fontrouge'><b>".$livraisonComprise." ".$symbolDevise."</b></span> ".$textTax;
            if($activerPromoLivraison == "oui" and $livraisonComprise == 0) {
                $textPromo =  POUR." <span class='fontrouge'><b>".maj(TOUT)."</b></span> ".ACHAT_SUR.".";
            }
            if($activerPromoLivraison == "oui" and $displayPromoShipping == "oui") {
               print "<div align='center'><b>".LIVRAISON_GRATUITE."</b> ".$textPromo." (<a href='../voorwaarden.php' target='_blank'>*</a>)</div>";
            }

            // Afficher Remise
            $textPromoRemise =  POUR." <span class='fontrouge'><b>$remiseOrderMax $symbolDevise</b></span> ".$textTax.".";
            if($activerRemise == "oui" and $remiseOrderMax == 0) {
                $textPromoRemise =  POUR." <span class='fontrouge'><b>".maj(TOUT)."</b></span> ".ACHAT_SUR.".";
            }
            if($activerRemise == "oui" and $displayPromoRemise == "oui") {
               print "<div align='center'><b>".maj(REMISE)."</b> de <b>$remise $remiseType</b> ".$textPromoRemise."</div>";
            }
            
            
            
            print "<br>";
            

if(!isset($_SESSION['list'])) {
			print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='7' class='TABLE1'>";
			print "<tr>";
			print "<td class='titre' align='center'>";
			print VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE;
			print "</td>";
			print "</tr>";
			print "</table><br><br>";
        }
        else
        {
        
// calculer nbre article et prix si module desactive
if($cartVisible == "non") {
    $art = explode(",",$_SESSION['list']);
    $nb_article = count($art);
    
    $_SESSION['tot_art'] = 0;
    $_SESSION['totalTTC'] = 0;
    
    for($n=0; $n <= $nb_article-1; $n++) {
        $article = explode("+",$art[$n]);
        $_SESSION['tot_art'] = $_SESSION['tot_art'] + $article[1];
        $_SESSION['totalTTC'] = $_SESSION['totalTTC'] + ($article[2]*$article[1]);
    }
}

if($_SESSION['list'] == "" OR (isset($_SESSION['tot_art']) AND $_SESSION['tot_art'] == "0")) {
			print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='7' class='TABLE1'>";
			print "<tr>";
			print "<td class='titre' align='center'>";
			print VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE;
			print "</td>";
			print "</tr>";
			print "</table><br><br>";
           	if(isset($_SESSION['list'])) unset($_SESSION['list']);
        }
        else
        {
print "<table border='0' width='100%' align='center'><tr>";
print "<td>";

  print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='5' class='TABLE1'>";
  print "<tr class='TABLETopTitle'>";
  print "<td align='center'><b>&nbsp;</b></td>";
  print "<td align='center'><b>".ARTICLES."</b></td>";
  print "<td align='center'><b>".PRIX."</b></td>";
  print "<td align='center'><b>".QTE."</b></td>";
  print "<td align='center'><b>".TOTAL."</b></td>";
  print "<td>&nbsp;</td></tr>";
                $split = explode(",",$_SESSION['list']);

                foreach ($split as $item) {
                    if(isset($d) and $d==1) $d=2; else $d=1;
                        // article $check[0]= products_id
                        $check = explode("+",$item);
                        $query = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_deee, p.products_qt, p.products_im, p.products_ref, p.products_image, p.products_weight, p.products_id, p.categories_id, p.products_tax, p.products_taxable, p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, p.products_download
                                              FROM products as p
                                              LEFT JOIN specials as s
                                              ON (p.products_id = s.products_id)
                                              WHERE p.products_id = '".$check[0]."'");
                        $row = mysql_fetch_array($query);
if($check[1]!=="0") {
  print "<tr class='TDTableListLine".$d."'>";
  // Afficher image
  if($addImageCart == "oui"  AND $row['products_im']=="yes" AND !empty($row['products_image'])) {
    $images_widthCad = $ImageSizeCart+5;
    if(substr($row['products_image'], 0, 4)=="http") $dirr=""; else $dirr="../";
                    $yoZ1w = @getimagesize($dirr.$row['products_image']);
                    if(!$yoZ1w) $row['products_image']="im/zzz_gris.gif";
    $image_resize_cart = resizeImage($dirr.$row['products_image'],$ImageSizeCart,$images_widthCad);
    print "<td align='center' width='".$images_widthCad."'>";
    print "<table border='0' cellspacing='0' cellpadding='2'><tr><td>";
    print "<img src='".$dirr.$row['products_image']."' border='0' width='".$image_resize_cart[0]."' height='".$image_resize_cart[1]."' alt='".$row['products_name_'.$_SESSION['lang']]."' title='".$row['products_name_'.$_SESSION['lang']]."'>";
    print "</td></tr></table>";
    print "</td>";
  }
  else {
	  print "<td>&nbsp;</td>";
  }

  // Afficher nom article

    if($row['products_ref']=="GC100") {
        print "<td align='center'>";
        print "<span class='cartItemFont'><b>".$row['products_name_'.$_SESSION['lang']]."</b></span>";
	}
	else {
        print "<td align='center'>";
        print "<a href='beschrijving.php?id=".$row['products_id']."&path=".$row['categories_id']."'><b>".$row['products_name_'.$_SESSION['lang']]."</b></a>";
        // Afficher DEEE
        if($row['products_deee']>0) {
            $deee[] = $check[1]*$row['products_deee'];
            $openDeee = "<br><i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('../includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
            print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$row['products_deee']." ".$symbolDevise."</i>";
        }
	}
	
	// afficher options
    if($row['products_download'] == "yes") { print "<br>".TELECHARGER."";}
    if($row['products_download'] == "yes") { print "<br><img src='../im/download.gif' alt='".TELECHARGER."' title='".TELECHARGER."'>";}
	if(!empty($check[6])) {
   		$_opt = explode("|",$check[6]);
		## session update option price
		$lastArray = $_opt[count($_opt)-1];
		if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_opt[count($_opt)-1]);
		$ww = implode("|",$_opt);
		$ww = str_replace("|","<br>",$ww);
		print "<br>".$ww;
	}
	print "</td>";
  	// prix
	$new_price = $row['specials_new_price'];
	$old_price = $row['products_price'];
	
	if(empty($new_price)) {
		//$price = $old_price;
		$price = $check[2];
		print "<td align='center'><b>".sprintf("%0.2f",$price)."</b></td>";
	}
	else {
		if($row['specials_visible']=="yes") {
			$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$dateMaxCheck = explode("-",$row['specials_last_day']);
			$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
			$dateDebutCheck = explode("-",$row['specials_first_day']);
			$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
			
			if($dateDebut <= $today  and $dateMax >= $today) {
				//$price = $new_price;
				$price = $check[2];
				print "<td align='center'><b><span class='fontrouge'>".sprintf("%0.2f",$price)."</span></b><br>PROMO</td>";
			}
			else {
				//$price = $old_price;
				$price = $check[2];
				print "<td align='center'><b>".sprintf("%0.2f",$price)."</b></td>";
			}
		}
		else {
			//$price = $old_price;
			$price = $check[2];
			print "<td align='center'><b>".sprintf("%0.2f",$price)."</b></td>";
		}
	}

// quantite
if($actRes=="oui" AND $row['products_qt'] <= 0) {$resa = "<input type='hidden' name='statut' value='reserve'>";} else {$resa = "";}

if(isset($_SESSION['activerCadeau']) OR $check[3]=="GC100") {
    print "<td align='center'>";
    print "<b>".$check[1]."</b>";
    print "</td>";
}
else {
  print "<form action='../add.php' method='GET'>
                                 <td align='center'>
                                <input type='hidden' value='".$check[0]."' name='id'>
                                <input type='hidden' value='".$check[6]."' name='options'>
                                <input type='hidden' value='".$row['products_ref']."' name='ref'>
                                <input type='hidden' value='".$row['products_deee']."' name='deee'>
                                <input type='hidden' value='1' name='adjust_cart'>
                                <input type='text' size='3' maxlength='3' name='amount' value='".$check[1]."'>
                                ".$resa."
                                <input type='submit' value='ok'>
                                </td>
                                </form>";
}

// Poids
                        $p_ = $row['products_weight'];
                        if($row['products_download'] == "yes") {$p_=0;}
                        $poidsOptionsArray = explode('|',$check[8]);
                        $poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
                        $poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
                        $_SESSION['poids'] = sprintf("%0.2f",array_sum($poid));

// Isoler les articles soumis à la remise des coupons
if(!in_array($row['products_id'], $_SESSION['getPromoId'])) {
  if(isset($_SESSION['coupon_items'])) {
      if($_SESSION['coupon_items']!=="") {
          $productsId = explode("|",$_SESSION['coupon_items']);
          if(in_array($row['products_id'], $productsId)) {
             $coupon[] = $check[1] * $price;
          }
          else {
             $coupon[] = 0;
          }
       }
       else {
             $coupon[] = $check[1] * $price;
       }
  }
  else {
      $coupon[] = 0;
  }
}
else {
  $coupon[] = 0;
}

// Prix HT
   $totalht = $price * $check[1];
   print "<td align='center'><b>".sprintf("%0.2f",$totalht)."</b></td>";

// bouton retirer du panier
				 if(!isset($_SESSION['activerCadeau'])) {
                     print "<td align='right'>
                                <a href='../add.php?amount=0&ref=".$row['products_ref']."&id=".$check[0]."&options=".$check[6]."&deee=".$row['products_deee']."'>
                                <img src='../im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."'>
                                </a>
                            </td></tr>";
                    }
                    else {
                     print "<td align='right'>
                                &nbsp;
                            </td></tr>";                    
                    }
				                        if(!IsSet($_SESSION['totalTTC'])) { $_SESSION['totalTTC'] = 0;}
				                        
				                        $totalHtArray[] = $totalht;
				                        $_SESSION['totalHtFinal'] = array_sum($totalHtArray);
				                        // Exoneration de la remise sur les cheques cadeaux
                                        if($row['products_ref'] == "GC100") {
                                            $priceHtCadeau[] = DisplayProductPrice($iso,$row['products_tax'],$totalht);
                                        }
                                        else {
                                            $priceHtCadeau[] = 0;
                                        }
                                        
                                            $priceHt[] = DisplayProductPrice($iso,$row['products_tax'],$totalht);
    				                        $_SESSION['totalHtFinalPromo'] = array_sum($priceHt);
				                        }
				  }
				  print "</table>";

// Calculer DEEE
if(isset($deee)) {
    $ecoRaxAmount = sprintf("%0.2f",array_sum($deee));
    if($ecoRaxAmount>0) {$_SESSION['deee'] = $ecoRaxAmount;} else {$_SESSION['deee'] = sprintf("%0.2f",0);}
}
else {
    $_SESSION['deee']=0;
}
// calculer montant promo
$totalPriceHtCadeau = array_sum($priceHtCadeau);
if($taxePosition == "Tax included") {
        $_SESSION['ff'] = $_SESSION['totalHtFinal']-$totalPriceHtCadeau-$_SESSION['deee'];
}
else {
    $_SESSION['ff'] = $_SESSION['totalHtFinalPromo']-$totalPriceHtCadeau-$_SESSION['deee'];
}

// afficher sous total
                print "<form action='../login.php' target='_blank' method='post'>";
                // start table

				print "<table width='100%' border='0' align='right' cellspacing='0' cellpadding='3'><tr>";
                print "<td align='right' colspan='3'>";
                
                if($taxePosition !== "Tax included") {
                if($remiseOnTax !== "TTC") {
    				print "<b>".SOUS_TOTAL."</b>: <b>$symbolDevise ".sprintf("%0.2f",$_SESSION['totalHtFinal'])."</b>";
				}
				}
				print "</td>";
                print "</tr><tr>";
// afficher remise

                if($activerRemise == "oui" and $_SESSION['ff']>=$remiseOrderMax) {
                       if($remiseType == "%") $_SESSION['montantRemise'] = $_SESSION['ff']*($remise/100);
                       if($remiseType == $symbolDevise) $_SESSION['montantRemise'] = $remise;
                          $_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
                          $discount[] = $_SESSION['montantRemise'];
                
	                if($taxePosition == "Tax included") {
	                   	if($remiseOnTax == "TTC") {
	                    print "<td align='right' colspan='3'>";
	                	print "<b>".REMISE." (-$remise$remiseType)</b>: ";
	                	print "Active";
	                	print "</td></tr><tr>";
	                }
	                }
	                else {
	                       if($remiseOnTax == "TTC") {
	                    print "<td align='right' colspan='3'>";
	                	print "<b>".REMISE." (-$remise$remiseType)</b>: ";
	                	print "Active";
	                	print "</td></tr><tr>";
					}
	                else {
	                    print "<td align='right' colspan='3'>";
	                	print "<b>".REMISE." (-$remise$remiseType)</b>: ";
						print "<b><span class='fontrouge'>$symbolDevise ".$_SESSION['montantRemise']."</span></b>";
						print "</td></tr><tr>";
					   }
					}
                }
                else {
                        $totttc = sprintf("%0.2f",$_SESSION['totalHtFinal']);
                        $_SESSION['montantRemise'] = sprintf("%0.2f",0);
                        $discount[] = 0;
                }
// afficher remise sur commandes precedentes
                if(isset($_SESSION['openAccount']) and isset($_SESSION['accountRemise']) AND $_SESSION['accountRemise'] > 0) {
	                    if($_SESSION['totalHtFinalPromo'] >= $_SESSION['accountRemise']) {
						  $discount[] = $_SESSION['accountRemise'];
						  $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$_SESSION['accountRemise']);
						}
						else {
						  $discount[] = $_SESSION['totalHtFinal'];
						  $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$_SESSION['totalHtFinal']);
						}

						// Désactiver Points de fidélité
                  $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('../gebruik_bonuspunten.php?viewFid=1','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=100,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
                  $stockLev = $openLeg."<b><span class='TABLE1'>&nbsp;?&nbsp;</span></b></a>";
						print "<td align='right' colspan='3'>";
						   print $stockLev."&nbsp;";
	                	print "<a href='".$_SERVER['PHP_SELF']."?action=desactivatefidelite'><img src='../im/supprimer.gif' border='0' align='absmiddle' alt='".DESACTIVE_REMISE."' title='".DESACTIVE_REMISE."'></a>&nbsp;";
	                	print "<b>".REMISE_SUR_COMMANDES."</b>: ";
						print "<b><span class='fontrouge'>$symbolDevise ".$_SESSION['accountRemiseEffec']."</span></b>";
						print "</td></tr><tr>";
				}

                  // Activer Points de fidélité
      				if(!isset($_SESSION['accountRemise']) AND isset($_SESSION['accountRemiseActive'])) {
      						print "<td align='right' colspan='3'>";
      	                	print "<a href='".$_SERVER['PHP_SELF']."?action=activatefidelite'><img src='../im/checked.gif' border='0' align='absmiddle' alt='".ACTIVE_REMISE."' title='".ACTIVE_REMISE."'></a>&nbsp;";
                           print "<b>".REMISE_SUR_COMMANDES."</b>: ";
      						print "<b><span class='fontrouge'>".$symbolDevise." 0.00</span></b>";
      						print "</td></tr><tr>";
                  }
// afficher coupon de reduction
                if(isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == 1) {
                	$query = mysql_query("SELECT *
                               FROM code_promo
                               WHERE code_promo = '".$_SESSION['coupon_name']."'");
               		$result = mysql_fetch_array($query);
               		$seuilPromoCode = $result['code_promo_seuil'];

			                if($_SESSION['ff'] >= $seuilPromoCode) {
								$remiseCoupon = $result['code_promo_reduction'];
			                    $remiseCouponType = $result['code_promo_type'];

                                if($taxePosition == "Tax included") {
                                    if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
                                    if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
                                    $discount[] = 0;
                                    
                                    print "<td align='right' colspan='3'>";
                                    print "<b>".REMISE." (-$remiseCoupon$remiseCouponType)</b>: ";
                                    print "Active";
                                    print "<br><i>Coupon: ".$result['code_promo']."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    print "</td></tr><tr>";
                                }
                                else {
                                    if($remiseOnTax == "TTC") {
                                    if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
                                    if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
                                    $discount[] = 0;
                                    
                                    print "<td align='right' colspan='3'>";
                                    print "<b>".REMISE." (-$remiseCoupon$remiseCouponType)</b>: ";
                                    print "Active";
                                    print "<br><i>Coupon: ".$result['code_promo']."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    print "</td></tr><tr>";
                                }
                                else {
                                    $amountCoupon = array_sum($coupon);
                                    if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$amountCoupon*($remiseCoupon/100));
                                    if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
                                    $discount[] = $_SESSION['montantRemise2'];
                                    
                                    print "<td align='right' colspan='3'>";
                                    print "<b>".REMISE." (-$remiseCoupon$remiseCouponType)</b>: ";
                                    print "<b><span class='fontrouge'>$symbolDevise ".$_SESSION['montantRemise2']."</span></b>";
                                    print "<br><i>Coupon: ".$result['code_promo']."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    print "</td></tr><tr>";
                                }
			                }
			                }
			                else {
			                        $totttc = sprintf("%0.2f",$_SESSION['totalHtFinal']);
			                        $_SESSION['montantRemise2'] = sprintf("%0.2f",0);
			                        $discount[] = 0;
			                }
				}
				
// Afficher cheque cadeau
                if(isset($_SESSION['activerCadeau']) and $_SESSION['activerCadeau'] == 1) {
                    $query = mysql_query("SELECT *
                                        FROM gc
                                        WHERE gc_number = '".$_SESSION['cadeau_number']."'");
                    $result = mysql_fetch_array($query);
								$remiseCadeau = $result['gc_amount'];

				                if($taxePosition == "Tax included") {
                                    $_SESSION['montantRemise3'] = $remiseCadeau;
                                    $_SESSION['montantRemise3'] = sprintf("%0.2f",$_SESSION['montantRemise3']);
                                    $discount[] = 0;
                                    
                                    print "<td align='right' colspan='3'>";
                                    print "<b>".CHEQUE_CADEAU_MIN."</b> <i>(N&deg;".$result['gc_number'].")</i>&nbsp;&nbsp;&nbsp;";
                                    print "Active";
                                    print "<br><i>-".$remiseCadeau." ".$symbolDevise."</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    print "</td></tr><tr>";
								}
						        else {
                                    $_SESSION['montantRemise3'] = $remiseCadeau;
                                    $_SESSION['montantRemise3'] = sprintf("%0.2f",$_SESSION['montantRemise3']);
                                    $discount[] = $_SESSION['montantRemise3'];
                                    
                                    print "<td align='right' colspan='3'>";
                                    print "<b>".CHEQUE_CADEAU_MIN."</b> <i>(N&deg;".$result['gc_number'].")</i>:&nbsp;&nbsp;&nbsp;";
                                    print "<b><span class='fontrouge'>-".$_SESSION['montantRemise3']." ".$symbolDevise."</span></b>";
                                    print "</td></tr><tr>";
						        }
				}
				
// afficher total
                $totalDiscount = array_sum($discount);
                $totttc = sprintf("%0.2f",($_SESSION['totalHtFinal']-$totalDiscount));
                if($taxePosition == "Tax included") {
                    $_SESSION['totalDisplayedCart'] = $_SESSION['totalHtFinal'];
                    print "<td align='right' colspan='3'>";
                    print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>$symbolDevise ".sprintf("%0.2f",$_SESSION['totalHtFinal'])."</b></span>";
                    print "</td>";
                }
                else {
                    if($remiseOnTax == "TTC") {
                            $_SESSION['totalDisplayedCart'] = $_SESSION['totalHtFinal'];
                	       print "<td align='right' colspan='3'>";
					        print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>$symbolDevise ".sprintf("%0.2f",$_SESSION['totalHtFinal'])."</b></span>";
					        print "</td>";
				}
                else {
                            $_SESSION['totalDisplayedCart'] = $totttc;
	                        print "<td align='right' colspan='3'>";
					        print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>$symbolDevise ".sprintf("%0.2f",$totttc)."</b></span>";
					        print "</td>";
				        }
				}

				        print "</tr><tr>";
                print "<td align='right' colspan='3'>";

// Afficher commentaires sous le montant total
                if($taxePosition == "Plus tax") {
	                print "<div align='right'><span class='fontrouge'>".TAXE_NON_INCLUSE."</span></div>";
				        }
                if($taxePosition == "Tax included") {
	                print "<div align='right'><span class='fontrouge'>".TAXE_INCLUSE."</span></div>";
				        }
                if($taxePosition == "No tax") {
	                print "<div align='right'><span class='fontrouge'>".PAS_DE_TAXE."</span></div>";
				        }
					        print "<div align='right'><span class='fontrouge'>".LIVRAISON_NON_INCLUSE."</span></div>";
// BOUTONS
                print "</td>";
                print "</tr><tr>";
                print "<td colspan='3'>";
				        print "<hr width='100%'>";
				        print "</td>";
                print "</tr><tr>";
                // Bouton continuer achat
                print "<td align='left' valign='top'>";
                print "<a href='javascript:history.back()'>";
                print "<img src='../im/lang".$_SESSION['lang']."/retour_achat.gif' border='0'>";
                print "</a>";
                print "</td>";
                // Bouton Vider panier
                print "<td align='center' valign='top'>";
                print "<a href='".$url_id10.$slash."var=session_destroy'>";
                print "<img src='../im/lang".$_SESSION['lang']."/vider_caddie.gif' border='0'>";
                print "</a>";
                print "</td>";
                // Bouton Commander
                print "<td align='right' valign='top'>";
                  if($_SESSION['totalHtFinal']>=$minimumOrder OR isset($_SESSION['devisNumero'])) {
                      print "<div align='right'>";
                      print "<input style='BACKGROUND: none; border: 0px #CCCCCC solid' type='image' src='../im/lang".$_SESSION['lang']."/commander.gif' border='0' alt='".COMMANDER."' title='".COMMANDER."'>";
                      print "</div>";
                  }
                  else {
                      print "<div class='PromoFont' align='right'>";
                      print "<b>".COMMANDE_MINIMUM."<br>".sprintf("%0.2f",$minimumOrder)." ".$symbolDevise."</b>";
                      print "<br>";
                      print "<i>(".AVANT_LIVRAISON.")</i>";
                      print "</div>";
                  }
                print "</td>";
                
                if($devis == "oui" AND $_SESSION['totalHtFinal']>=$minimumOrder AND !isset($_SESSION['devisNumero'])) {
                print "</tr>";
                print "<td colspan='3' align='right'>";
                print "<a href='../offertes.php' target='_blank'>";
                print "<img src='../im/lang".$_SESSION['lang']."/devis.gif' border='0'>";
                print "</a>";
                print "</td>";
                }
                                        
                
                print "</tr>";
                print "</table>";
                print "</form>";

print "</td></tr><tr>";
print "<td>";
    if(!isset($_SESSION['devisNumero'])) {
               // visualiser shipping et taxe
               $pays = mysql_query("SELECT countries_name, iso FROM countries WHERE countries_shipping != 'exclude' ORDER BY countries_name ASC");
               print "<hr width='100%'>";
               print "<table border='0' width='100%' cellspacing='0' cellpadding='2'><tr>";
               print "<form action='../berekenen.php' method='post' target='_blank'>";
               print "<input type='hidden' name='poids' value='".$_SESSION['poids']."'>";
               print "<td width='155' align='left' class='TABLE1' style='padding:3px;'>&nbsp;&nbsp;<b>".maj(VISUALISE_FACTURE)."</b></td><td>&nbsp;</td>";
               print "</tr><tr><td colspan='2'>";
               print POUR_CONNAITRE;
               print "<select name='country'>";
               print "<option value='vide'>".SELECTIONNE_PAYS."</option>";
                  while ($countries = mysql_fetch_array($pays)) {
                  if($countries['iso'] == $iso) $a = "selected"; else $a="";
                        $countryName = $countries['countries_name'];
                        $countryValue = $countries['countries_name'];
                  print "<option value='".$countryValue."' ".$a.">".$countryName."</option>";
                  }
               print "</select>";
               print "&nbsp;&nbsp;<input type='submit' name='valeur' value='".VOIR_FACTURE."'>";
               print "<br><span class='fontrouge'>".SANS_LIVRAISON."</span>";
               print "</td>";
               print "</form>";
               print "</tr>";
               print "</table>";
       
print "</td></tr><tr>";
print "<td>";

                // code remise
                   print "<hr width='100%'>";
                   print "<table width='100%' border='0' cellspacing='0' cellpadding='2'><tr>";
                   print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
                   print "<td width='155' align='left' class='TABLE1' style='padding:3px;'>&nbsp;&nbsp;<b>".maj(COUPON_CODE)."</b></td><td>&nbsp;</td>";
                   print "</tr><tr><td colspan='2' >";
                   print SAISISSEZ_ICI;
                   print "<input type='text' name='codePromo' size='12'> ";
                   print "<input type='submit' value='".ACTIVE."'> ".$message;
                   if(isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == 1) {
                        print "<br><br>";
                        print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCoupon'><span style='".$styleDesactivate."'>".DESACTIVATE_COUPON."</span></a>";
                        print "<br>";
                   }
                   print "</td>";
                   print "</form>";
                   print "</tr></table>";
    }
print "</td>";
print "</tr></table>";

        }
}
?>
             </td>
            </tr>
            </table>
          </td>
        </tr>
      </table>

    

</td>
</tr>
</table>
</body>
</html>
