<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');
print "<a href='#a90z' name='A90'></a>";


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = VOTRE_CADDIE;
// meta Tags
$description = $title." ".$store_name;
$keywords = $title.", ".$store_name.", ".$keywords;

if(isset($deee)) unset($deee);
$deee = array();
$largeBoutonCaddie = 65;
$styleDesactivate = "padding:2px; border:2px #FF0000 dotted; FONT-WEIGHT:bold";

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


   $resulti = mysql_query("SELECT products_id, products_price, products_visible FROM products WHERE products_caddie_display = 'yes' ORDER BY products_price ASC");
   $resultNumi = mysql_num_rows($resulti);

 
if((isset($_POST['codePromo']) AND !empty($_POST['codePromo'])) OR (isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon']=="1")) {
    if(isset($_SESSION['activerCadeau'])) {
        $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".NON_CUMULABLE."</b></span>";
    }
    else {
         if(isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon'] == "1") {
               $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".CODE_REMISE_ACTIVE."</b></span>";
  
   
         }
         else {
               $query = mysql_query("SELECT code_promo, code_promo_end, code_promo_stat, code_promo_enter, code_promo_items FROM code_promo WHERE code_promo = '".$_POST['codePromo']."'") or die (mysql_error());
               if(mysql_num_rows($query) > 0) {
                   $result = mysql_fetch_array($query);
    
                   if($result['code_promo'] == $_POST['codePromo']) {
    
                   		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
                         $dateEndCheck = explode("-",$result['code_promo_end']);
                         $dateEnd = mktime(0,0,0,$dateEndCheck[1],$dateEndCheck[2],$dateEndCheck[0]);
                   		if($dateEnd >= $today) {
                   		    if($result['code_promo_stat'] == "prive" AND $result['code_promo_enter'] > 0) {
    		                      $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".CODE_REMISE_UTILISE."</b></span>";
    		                   }
    		                   else {
    						      $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".BRAVO."</b></span>";
    		                      $_SESSION['activerCoupon'] = 1;
    		                      $_SESSION['coupon_name'] = $result['code_promo'];
    		                      $_SESSION['coupon_items'] = $result['code_promo_items'];
     
    				                if($result['code_promo_stat'] == "prive" AND $result['code_promo_enter'] == 0) {
    				                        mysql_query("UPDATE code_promo SET code_promo_enter = code_promo_enter+1 WHERE code_promo ='".$_SESSION['coupon_name']."'") or die (mysql_error());
    								}
    								if($result['code_promo_stat'] == "public") {
    				                        mysql_query("UPDATE code_promo SET code_promo_enter = code_promo_enter+1 WHERE code_promo ='".$_SESSION['coupon_name']."'") or die (mysql_error());
    								}
    						  }
                        }
                        else {
                              $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".DATE_DEPASSE."</b></span>";
    					     }
                   }
                   else {
                      $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".CODE_ERRONE."</b></span>";
                   }
               }
               else {
                    $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".CODE_ERRONE."</b></span>";
               }
        }
   }
}
else {
   $messageCodeReduc = "";
}

 
if(isset($_GET['action']) AND $_GET['action']=="activatefidelite") {
   if(isset($_SESSION['accountRemiseActive'])) unset($_SESSION['accountRemiseActive']);
   if(isset($_SESSION['accountRemise2'])) {
      $_SESSION['accountRemise'] = $_SESSION['accountRemise2'];
      unset($_SESSION['accountRemise2']);
   }
}

 
if(isset($_GET['action']) AND $_GET['action']=="desactivatefidelite") {
   if(isset($_SESSION['accountRemise'])) {
      $_SESSION['accountRemise2'] = $_SESSION['accountRemise'];
      unset($_SESSION['accountRemise']);
   }
   $_SESSION['accountRemiseActive'] = "yes";
}

 
if(isset($_GET['action']) AND $_GET['action']=="desactivateCoupon") {
    if(isset($_SESSION['activerCoupon'])) {unset($_SESSION['activerCoupon']);}
    if(isset($_SESSION['coupon_name'])) {unset($_SESSION['coupon_name']);}
    if(isset($_SESSION['coupon_items'])) {unset($_SESSION['coupon_items']);}
    if(isset($_SESSION['montantRemise2'])) {unset($_SESSION['montantRemise2']);}
    $messageCodeReduc = "<span class='PromoFontColorNumber'><b>".IS_DESACTIVATE_COUPON."</b></span>";
}

 
if((isset($_POST['codeCadeau']) AND !empty($_POST['codeCadeau'])) OR (isset($_SESSION['activerCadeau']) AND $_SESSION['activerCadeau']=="1")) {
    if(isset($_SESSION['activerCoupon'])) {
        $messageChequeC = "<span class='PromoFontColorNumber'><b>".NON_CUMULABLE."</b></span>";
    }
    else {
         if(isset($_SESSION['activerCadeau']) AND $_SESSION['activerCadeau'] == "1") {
            $messageChequeC = "<span class='PromoFontColorNumber'><b>".CADEAU_ACTIVE."</b></span>";
  
   
         }
         else {
                $query = mysql_query("SELECT gc_payed, gc_end, gc_enter, gc_amount, gc_number FROM gc WHERE gc_number = '".$_POST['codeCadeau']."'");
                $queryNum = mysql_num_rows($query);
                if($queryNum > 0) {
                 $result = mysql_fetch_array($query);
                    if($result['gc_payed']=='1') {
    
                   		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
                        $dateEndCheck = explode("-",$result['gc_end']);
                        $dateEnd = mktime(0,0,0,$dateEndCheck[1],$dateEndCheck[2],$dateEndCheck[0]);
                   		if($dateEnd >= $today) {
                   			if($result['gc_enter'] == '1') {
    		                  $messageChequeC = "<span class='PromoFontColorNumber'><b>".CADEAU_UTILISE."</b></span>";
    		                }
    		                else {
                                if(isset($_SESSION['totalDisplayedCart']) AND ($_SESSION['totalDisplayedCart']-$result['gc_amount']) >= 0 AND $_SESSION['ff'] >= $result['gc_amount']) {
                                    $messageChequeC = "<span class='PromoFontColorNumber'><b>".CADEAU_ACTIVE."</b></span>";
                                    $_SESSION['activerCadeau'] = 1;
                                    $_SESSION['cadeau_number'] = $result['gc_number'];
                                    $_SESSION['gc_reduc'] = $result['gc_amount'];
                                }
                                else {
                                    $messageChequeC = "<span class='PromoFontColorNumber'><b>".MONTANT_PAS_SUFFISANT."</b></span>";
                                }
    						}
                        }
                        else {
                            $messageChequeC = "<span class='PromoFontColorNumber'><b>".DATE_DEPASSE."</b></span>";
    					}
                   }
                   else {
                        $messageChequeC = "<span class='PromoFontColorNumber'><b>".CADEAU_ERRONE2."</b></span>";
                   }
               }
               else {
                    $messageChequeC = "<span class='PromoFontColorNumber'><b>".CADEAU_ERRONE."</b></span>";
               }
         }
    }
}
else {
    $messageChequeC = "";
}

 
if(isset($_GET['action']) AND $_GET['action']=="desactivateCertficate") {
    if(isset($_SESSION['activerCadeau'])) {unset($_SESSION['activerCadeau']);}
    if(isset($_SESSION['cadeau_number'])) {unset($_SESSION['cadeau_number']);}
    if(isset($_SESSION['gc_reduc'])) {unset($_SESSION['gc_reduc']);}
    if(isset($_SESSION['montantRemise3'])) {unset($_SESSION['montantRemise3']);}
    if(isset($_SESSION['totalDisplayedCart'])) {unset($_SESSION['totalDisplayedCart']);}
    if(isset($_SESSION['deee'])) {unset($_SESSION['deee']);}
    $messageChequeC = "<span class='PromoFontColorNumber'><b>".IS_DESACTIVATE."</b></span>";
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
      <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print maj(VOTRE_CADDIE);?> |</b>
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
		  // ---------------------------------------
		  // linkse kolom 
		  // ---------------------------------------
		  if($colomnLeft=='oui') include('includes/kolom_links.php');
		  ?>
          <td valign="top" class="TABLEPageCentreProducts">



            <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100%">
              <tr>
                <td valign="top">


<?php
if($addNavCenterPage=="oui") {
?>
                  <table width="100%" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathCenter">
                    <tr>
                      <td>
                          <?php print "<img src='im/accueil.gif' align='TEXTTOP'>&nbsp;<a href='cataloog.php'>".maj(HOME)."</a> | ".maj(VOTRE_CADDIE)." |";?>
                      </td>
                    </tr>
                  </table>
                  <br>
<?php
}


if(!isset($_SESSION['list'])) {
			print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='0'>";
			print "<tr>";
			print "<td class='styleAlert' align='center'><img src='im/note.gif' align='absmiddle'>&nbsp;";
			print (isset($DevisMessage))? $DevisMessage : VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE;
			print "</td>";
			print "</tr>";
			print "</table>";
        }
        else {
        

if($cartVisible == "non" AND isset($_SESSION['list']) AND !empty($_SESSION['list']) ) {
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

if($_SESSION['list']=="" OR (isset($_SESSION['tot_art']) AND $_SESSION['tot_art']=="0")) {
			print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='0'>";
			print "<tr>";
			print "<td class='styleAlert' align='center'><img src='im/note.gif' align='absmiddle'>&nbsp;";
			print VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE;
			print "</td>";
			print "</tr>";
			print "</table>";
           	if(isset($_SESSION['list'])) unset($_SESSION['list']);
        }
        else {
        
 
if($paymentsDesactive=='non') {
    display_payment_process(1,"");
}
if(isset($messageChequeC) AND $messageChequeC!=='' AND isset($_POST['codeCadeau'])) print "<p align='center'>".$messageChequeC."</p>";
if(isset($messageCodeReduc) AND $messageCodeReduc!=='' AND isset($_POST['codePromo'])) print "<p align='center'>".$messageCodeReduc."</p>";

print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='0'><tr>";
print "<td>";

  print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='5' class='TABLE1'>";
  print "<tr class='TABLETopTitle'>";
  print "<td align='left'><b>&nbsp;</b></td>";
  print "<td align='left'><b>".ARTICLES."</b></td>";
  print "<td align='left'><b>".PRIX."</b></td>";
  print "<td align='left'><b>".QTE."</b></td>";
  print "<td align='right'><b>".TOTAL."</b></td>";
  print "<td>&nbsp;</td></tr>";

$split = explode(",",$_SESSION['list']); 

foreach ($split as $item) {
                    if(isset($d) AND $d==2) $d=1; else $d=2;

                        $check = explode("+",$item);
                        $query = mysql_query("SELECT p.products_name_".$_SESSION['lang'].", p.products_deee, p.products_qt, p.products_im, p.products_ref, p.products_image, p.products_weight, p.products_id, p.categories_id, p.products_tax, p.products_taxable, p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible, p.products_download
                                              FROM products as p
                                              LEFT JOIN specials as s
                                              ON (p.products_id = s.products_id)
                                              WHERE p.products_id = '".$check[0]."'");
                        $row = mysql_fetch_array($query);
if($check[1]!=="0") {
  print "<tr class='TDTableListLine".$d."'>";
 
  if($addImageCart == "oui"  AND $row['products_im']=="yes" AND !empty($row['products_image'])) {
      $images_widthCad = $ImageSizeCart+20;
      $yoZ = @getimagesize($row['products_image']);
      if(!$yoZ) $row['products_image']="im/zzz_gris.gif";
      $image_resize_cart = resizeImage($row['products_image'],$ImageSizeCart,$images_widthCad);
      print "<td align='left' width='".$images_widthCad."'>";
      print "<table border='0' cellspacing='0' cellpadding='2'><tr><td>";
      print "<img src='".$row['products_image']."' border='0' width='".$image_resize_cart[0]."' height='".$image_resize_cart[1]."' alt='".$row['products_name_'.$_SESSION['lang']]."' title='".$row['products_name_'.$_SESSION['lang']]."'>";
      print "</td></tr></table>";
      print "</td>";
  }
  else {
	  print "<td>&nbsp;</td>";
  }

  

    if($row['products_ref']=="GC100") {
        print "<td align='left'><span class='cartItemFont'><b>".$row['products_name_'.$_SESSION['lang']]."</b></span>";
	}
	else {
	     if($row['products_price'] > 0) {
            print "<td align='left'>";
            print "<b><a href='beschrijving.php?id=".$row['products_id']."&path=".$row['categories_id']."'>".$row['products_name_'.$_SESSION['lang']]."</a></b>";
            // Prix dégressif
			if(in_array($row['products_id'], $_SESSION['discountQt'])) {
				$prodDegressif = "&nbsp;<a href='beschrijving.php?id=".$row['products_id']."&path=".$row['categories_id']."'><img src='im/degressif_logo.png' border='0' alt='".PRODUIT_A_PRIX_DEGRESSIF."' title='".PRODUIT_A_PRIX_DEGRESSIF."' height='10'></a>";
			}
			else {
				$prodDegressif = "";
			}
			print $prodDegressif;
        }
        else {
            print "<td align='left'>";
            print "<b>".$row['products_name_'.$_SESSION['lang']]."</b>";
        }
   
        if($row['products_deee']>0) {
            $deee[] = $check[1]*$row['products_deee'];
            $openDeee = "<br><i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
            print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></span></a> : ".$row['products_deee']." ".$symbolDevise."</i>";
        }
	}

   
   if($row['products_download']=="yes") { print "<br><img src='im/download.gif' title='".TELECHARGER."'>";}
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
   
	$new_price = $row['specials_new_price'];
	$old_price = $row['products_price'];
	if(empty($new_price)) {
 
		$price = $check[2];
		if($price > 0) {
			print "<td align='left'><b>".sprintf("%0.2f",$price)."</b></td>";
		}
		else {
			print "<td align='left'><b><span class='fontrouge'>**".OFFERT."**</span></b></td>";
		}
	}
	else {
		if($row['specials_visible']=="yes") {
			$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
			
			$dateMaxCheck = explode("-",$row['specials_last_day']);
			$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
			$dateDebutCheck = explode("-",$row['specials_first_day']);
			$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
			
			if($dateDebut <= $today  AND $dateMax >= $today) {
				//$price = $new_price;
				$price = $check[2];
				if($price > 0) {
					print "<td align='left'><b><span class='fontrouge'>".sprintf("%0.2f",$price)."</span></b><br>PROMO</td>";
				}
				else {
					print "<td align='left'><b><span class='fontrouge'>**".OFFERT."**</span></b></td>";
				}
			}
			else {
				//$price = $old_price;
				$price = $check[2];
				if($price > 0) {
					print "<td align='left'><b>".sprintf("%0.2f",$price)."</b></td>";
				}
				else {
					print "<td align='left'><b><span class='fontrouge'>**".OFFERT."**</span></b></td>";
				}
			}
		}
		else {
			 
			$price = $check[2];
			if($price > 0) {
				print "<td align='left'><b>".sprintf("%0.2f",$price)."</b></td>";
			}
			else {
				print "<td align='left'><b><span class='fontrouge'>**".OFFERT."**</span></b></td>";
			}
		}
	}
 
if($actRes=="oui" AND $row['products_qt'] <= 0) {$resa = "<input type='hidden' name='statut' value='reserve'>";} else {$resa = "";}

if(isset($_SESSION['activerCadeau']) OR $check[3]=="GC100") {
    print "<td align='right'>";
    print "<b>".$check[1]."</b>";
    print "</td>";
}
else {
   if(!isset($_SESSION['devisNumero'])) {
      print "<form action='add.php' method='GET'>";
                                print "<td align='right' width='75'>";
                                print "<input type='hidden' value='".$check[0]."' name='id'>";
                                print "<input type='text' size='3' maxlength='3' name='amount' value='".$check[1]."'>";
                                print "<input type='hidden' value='".$row['products_ref']."' name='ref'>";
                                print "<input type='hidden' value='".$row['products_deee']."' name='deee'>";
                                print "<input type='hidden' value='".$check[6]."' name='options'>";
                                print "<input type='hidden' value='1' name='adjust_cart'>";
                                if($row['products_price'] > 0) {
                                    print "&nbsp;<input type='submit' value='ok'>";
                                }
                                else {
                                    print "<input type='hidden' name='amount' value='1'>";
                                }
                                print $resa;
                                print "</td>";
      print "</form>";
   }
   else {
      print "<td align='left' width='75'>";
      print $check[1];
      print "</td>";
   }
}

 
$p_ = $row['products_weight'];
if($row['products_download'] == "yes") {$p_=0; $sansLiv[]=0;} else {$sansLiv[]=1;}
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

 
  $totalht = $price * $check[1];
  print "<td align='right'>";

  print "<b>".sprintf("%0.2f",$totalht)."</b>";
   
  if($devise2Visible=="oui" AND $price>0) {
      $currency = sprintf("%0.2f",$totalht*$tauxDevise2)."&nbsp;".$symbolDevise2;
      print "<div title='".A_TITRE_INDICATF."' class='FontGris tiny' align='center'><i>[".$currency."]</i></div>";
  }
  
  print "</td>";

 
				 if(!isset($_SESSION['activerCadeau']) AND !isset($_SESSION['devisNumero'])) {
				 if($actRes=="oui" AND $row['products_qt'] <= 0) {$resa2 = "&statut=reserve";} else {$resa2 = "";}
                     print "<td align='right'>";
                     print "<a href='add.php?amount=0".$resa2."&ref=".$row['products_ref']."&id=".$check[0]."&options=".$check[6]."&deee=".$row['products_deee']."'>";
                     print "<img src='im/scrap.gif' border='0' alt='".RETIRER_DU_CADDIE."'>";
                     print "</a>";
                     print "</td></tr>";
                    }
                    else {
                        print "<td align='right'>";
                        print "&nbsp;";
                        print "</td></tr>";
                    }
				                        if(!isset($_SESSION['totalTTC'])) {$_SESSION['totalTTC'] = 0;}
				                        
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
 

if(!isset($_SESSION['devisNumero'])) {
	print "<tr><td colspan='6' class='TABLE1' style='border:0px'>";
	
	print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";

	print "<td align='center' valign='top' height='8'>";
		print "<div class='dotMenu'><img src='im/zzz.gif' width='1' height='1'></div>";
	print "</td>";
	print "</tr><tr>";

	 
	print "<td align='center' valign='center' style='color:#CC0000'>";
		print "<b>&bull;</b>&nbsp;";
		print "<a HREF='#A84' NAME='a84z' class='tooltip3'><span style='FONT-SIZE:8px;'>".maj(VISUALISE_FACTURE)."</span><em style='width:55px; left:0px'><div align='center'><img src='im/bottom.png' border='0'></div></em></a>";
		if($codeReductionActive=="oui") {
			print "&nbsp;<b>&bull;</b>&nbsp;";
			print "<a HREF='#A85' NAME='a85z' class='tooltip3'><span style='FONT-SIZE:8px'>".maj(COUPON_CODE)."</span><em style='width:85px; left:0px'><div align='center'><img src='im/bottom.png' border='0'></div></em></a>";
		}
		if($gcActivate=="oui") {
			print "&nbsp;<b>&bull;</b>&nbsp;";
			print "<a HREF='#A86' NAME='a86z' class='tooltip3'><span style='FONT-SIZE:8px'>".CHEQUE_CADEAU."</span><em style='width:65px; left:0px'><div align='center'><img src='im/bottom.png' border='0'></div></em></a>";
		}
		if($resultNumi > 0 AND $_SESSION['totalHtFinal'] >= $seuilCadeau) {
			print "&nbsp;<b>&bull;</b>&nbsp;";
			print "<a HREF='#A86Q' NAME='a86zQ' class='tooltip3'><span style='FONT-SIZE:8px'>".EXTRA."</span><em style='width:45px; left:0px'><div align='center'><img src='im/bottom.png' border='0'></div></em></a>";
		}
		if($displayAffInCart=="oui") {
			print "&nbsp;<b>&bull;</b>&nbsp;";
			print "<a HREF='#A86Q1' NAME='a86zQ1' class='tooltip3'><span style='FONT-SIZE:8px'>".ART_AFFIL."</span><em style='width:70px; left:0px'><div align='center'><img src='im/bottom.png' border='0'></div></em></a>";
		}
		print "&nbsp;<b>&bull;</b>&nbsp;";
		print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
	print "</td>";
	print "</tr>";

		// <-------- Kortingen afbeelden -------->
		if($activerPromoLivraison=="oui" AND $displayPromoShipping=="oui") {
 
			$textPromoZ =  POUR." <span class='fontrouge'><b>".$livraisonComprise." ".$symbolDevise."</b></span> ".$textTax;
			if($activerPromoLivraison == "oui" AND $livraisonComprise == 0) {
 
				$textPromoZ =  POUR." ".ACHAT_SUR.".";
			}
			print "<tr c#lass='TDTableListLine1'>";
			print "<td>";
			print "<div><img src='im/zzz.gif' width='1' height='10'></div>";
			print "<div align='center'><img src='im/imp.png' border='0' align='top'>&nbsp;<b>".LIVRAISON_GRATUITE."</b> ".$textPromoZ." (<a href='voorwaarden.php' target='_blank'>*</a>)</div>";
			print "</td></tr>";
		}
		if($activerRemise=="oui" AND $displayPromoRemise=="oui") {
 
			$textPromoRemise =  POUR." <span class='fontrouge'><b>".$remiseOrderMax." ".$symbolDevise."</b></span> ".$textTax.".";
			if($activerRemise == "oui" AND $remiseOrderMax == 0) {
				$textPromoRemise =  POUR." <span class='fontrouge'><b>".maj(TOUT)."</b></span> ".ACHAT_SUR.".";
			} 
			if(isset($textPromoZ) AND $textPromoZ!=="") {
				$sep = 4;
				$endBlock = "<div><img src='im/zzz.gif' width='1' height='7'></div>";
			}
			else {
				$sep = 10;
				$endBlock = "";
			}
			print "<tr c#lass='TDTableListLine1'>";
			print "<td>";
			print "<div><img src='im/zzz.gif' width='1' height='".$sep."'></div>";
			print "<div align='center'><img src='im/imp.png' border='0' align='top'>&nbsp;<b>".maj(REMISE)." ".$remise." ".$remiseType."</b> ".$textPromoRemise."</div>";
			print $endBlock;
			print "</td></tr>";
		}
	print "</table>";
	
	print "</td></tr>";
}
print "</table>";

 
if(isset($deee)) {
    $ecoRaxAmount = sprintf("%0.2f",array_sum($deee));
    if($ecoRaxAmount>0) {$_SESSION['deee'] = $ecoRaxAmount;} else {$_SESSION['deee'] = sprintf("%0.2f",0);}
}
else {
    $_SESSION['deee']=0;
}
 
$totalPriceHtCadeau = array_sum($priceHtCadeau);
if($taxePosition == "Tax included") {
        $_SESSION['ff'] = $_SESSION['totalHtFinal']-$totalPriceHtCadeau-$_SESSION['deee'];
}
else {
    $_SESSION['ff'] = $_SESSION['totalHtFinalPromo']-$totalPriceHtCadeau-$_SESSION['deee'];
}








 

 
                if(isset($_SESSION['openAccount'])) $linkTow = 'payment.php'; else $linkTow='login.php';

				print "<table width='100%' border='0' align='right' cellspacing='0' cellpadding='3'><tr>";
				print "<form action='".$linkTow."' method='post'>";
                print "<td align='right' colspan='3'>";
                if($taxePosition !== "Tax included") {
                    if($remiseOnTax !== "TTC") {
						print "<b>".SOUS_TOTAL."</b>: <b>".sprintf("%0.2f",$_SESSION['totalHtFinal'])." ".$symbolDevise."</b>";
					}
				}
				print "</td>";
                print "</tr><tr>";
 
                if($activerRemise == "oui" and $_SESSION['ff']>=$remiseOrderMax) {
                       if($remiseType == "%") $_SESSION['montantRemise'] = $_SESSION['ff']*($remise/100);
                       if($remiseType == $symbolDevise) $_SESSION['montantRemise'] = $remise;
                          $_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
                          $discount[] = $_SESSION['montantRemise'];
                
	                if($taxePosition == "Tax included") {
	                   	if($remiseOnTax == "TTC") {
        	            	print "<td align='right' colspan='3'>";
        	                print "<b>".REMISE."</b> [-".$remise.$remiseType."]: ";
        					print "<span class='fontrouge'><b>".$_SESSION['montantRemise']." ".$symbolDevise."</b></span>";
							print "</td></tr><tr>";
	                	}
	                	else {
	                        print "<td align='right' colspan='3'>";
	                	    print "<b>".REMISE."</b> [-".$remise.$remiseType."]: ";
	                	    print "aktiefcaddie";
	                	    print "</td></tr><tr>";
						}
	                }
	                else {
	                	if($remiseOnTax == "TTC") {
	                    	print "<td align='right' colspan='3'>";
	              	        print "<b>".REMISE."</b> [-".$remise.$remiseType."]: ";
               	        print "wordt op het einde berekend";
	              	        print "</td></tr><tr>";
        	            }
        	            else {
        	            	print "<td align='right' colspan='3'>";
        	                print "<b>".REMISE."</b> [-".$remise.$remiseType."]: ";
        					print "<b><span class='fontrouge'>".$_SESSION['montantRemise']." ".$symbolDevise."</span></b>";
							print "</td></tr><tr>";
        				}
                  	}
                }
                else {
                	$totttc = sprintf("%0.2f",$_SESSION['totalHtFinal']);
                    $_SESSION['montantRemise'] = sprintf("%0.2f",0);
                    $discount[] = 0;
                }
 
				if(isset($_SESSION['accountRemise']) AND $_SESSION['accountRemise'] > 0) {
					if($_SESSION['totalHtFinalPromo'] >= $_SESSION['accountRemise']) {
						$discount[] = $_SESSION['accountRemise'];
						$_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$_SESSION['accountRemise']);
					}
					else {
						$discount[] = $_SESSION['totalHtFinal'];
						$_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$_SESSION['totalHtFinal']);
					}
					
					 
					$openLeg = "<a href='javascript:void(0);' onClick=\"window.open('gebruik_bonuspunten.php?viewFid=1','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=130,width=510,toolbar=no,scrollbars=yes,resizable=yes');\">";
					$stockLev = $openLeg."<b><span class='TABLE1'>&nbsp;?&nbsp;</span></b></a>";
					$openUseSearch = "<a href='#' class='tooltip'><b><span class='darkBackground'>&nbsp;?&nbsp;</span></b><em style='width: 375px; left:30px'><div align='left'>".SUP_FID."</div></em></a>";
					print "<td align='right' colspan='3'>";
					print $openUseSearch."&nbsp;";
					print "<a href='".$_SERVER['PHP_SELF']."?action=desactivatefidelite'><img src='im/supprimer.gif' border='0' align='absmiddle' alt='".DESACTIVE_REMISE."' title='".DESACTIVE_REMISE."'></a>&nbsp;";
					print "<b>".REMISE_SUR_COMMANDES."</b>: ";
					print "<b><span class='fontrouge'>".$_SESSION['accountRemiseEffec']." ".$symbolDevise."</span></b>";
					print "</td>";
					print "</tr><tr>";
				}
                 
      			if(!isset($_SESSION['accountRemise']) AND isset($_SESSION['accountRemiseActive'])) {
      				print "<td align='right' colspan='3'>";
      	            print "<a href='".$_SERVER['PHP_SELF']."?action=activatefidelite'><img src='im/checked.gif' border='0' align='absmiddle' alt='".ACTIVE_REMISE."' title='".ACTIVE_REMISE."'></a>&nbsp;";
                    print "<b>".REMISE_SUR_COMMANDES."</b>: ";
      				print "<b><span class='fontrouge'>0.00 ".$symbolDevise."</span></b>";
      				print "</td>";
                	print "</tr><tr>";
                }

 
                if(isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon'] == 1) {
                	$query = mysql_query("SELECT code_promo_seuil, code_promo_reduction, code_promo_type, code_promo FROM code_promo WHERE code_promo = '".$_SESSION['coupon_name']."'");
               		$result = mysql_fetch_array($query);
               		$seuilPromoCode = $result['code_promo_seuil'];
               		
					if($_SESSION['ff'] >= $seuilPromoCode) {
					$remiseCoupon = $result['code_promo_reduction'];
					$remiseCouponType = $result['code_promo_type'];
					
					if($taxePosition == "Tax included") {
						if($remiseOnTax == "TTC") {
							$amountCoupon = array_sum($coupon);
							if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$amountCoupon*($remiseCoupon/100));
							if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
							if($amountCoupon==0) $_SESSION['montantRemise2']=sprintf("%0.2f",0);
							$discount[] = $_SESSION['montantRemise2'];
							print "<td align='right' colspan='3'>";
							print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCoupon'><img src='im/supprimer.gif' border='0' align='absmiddle' alt='".DESACTIVATE_COUPON."' title='".DESACTIVATE_COUPON."'></a>&nbsp;";
							print "<b>".COUPON_CODE." </b>[".$result['code_promo']." -".$remiseCoupon.$remiseCouponType."]: ";
							print "<span class='fontrouge'><b>".$_SESSION['montantRemise2']." ".$symbolDevise."</b></span>";
							print "</td></tr><tr>";
						}
						else {
							if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
							if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
							$amountCoupon = array_sum($coupon);
							if($amountCoupon==0) $_SESSION['montantRemise2']=sprintf("%0.2f",0);
							$discount[] = 0;
							print "<td align='right' colspan='3'>";
							print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCoupon'><img src='im/supprimer.gif' border='0' align='absmiddle' alt='".DESACTIVATE_COUPON."' title='".DESACTIVATE_COUPON."'></a>&nbsp;";
							print "<b>".COUPON_CODE."</b> [".$result['code_promo']." -".$remiseCoupon.$remiseCouponType."]: ";
							print "actiefcaddie3";
							print "</td></tr><tr>";
						}
					}
					else {
						if($remiseOnTax == "TTC") {
							if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
							if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
							$amountCoupon = array_sum($coupon);
							if($amountCoupon==0) $_SESSION['montantRemise2']=sprintf("%0.2f",0);
							$discount[] = 0;
							print "<td align='right' colspan='3'>";
							print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCoupon'><img src='im/supprimer.gif' border='0' align='absmiddle' alt='".DESACTIVATE_COUPON."' title='".DESACTIVATE_COUPON."'></a>&nbsp;";
							print "<b>".COUPON_CODE." </b> [".$result['code_promo']." -".$remiseCoupon.$remiseCouponType."]: ";
							print "actiefcaddie4";
							print "</td>";
							print "</tr><tr>";
						}
						else {
							$amountCoupon = array_sum($coupon);
							if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$amountCoupon*($remiseCoupon/100));
							if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
							if($amountCoupon==0) $_SESSION['montantRemise2']=sprintf("%0.2f",0);
							$discount[] = $_SESSION['montantRemise2'];
							print "<td align='right' colspan='3'>";
							print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCoupon'><img src='im/supprimer.gif' border='0' align='absmiddle' alt='".DESACTIVATE_COUPON."' title='".DESACTIVATE_COUPON."'></a>&nbsp;";
							print "<b>".COUPON_CODE."</b> [".$result['code_promo']." -".$remiseCoupon.$remiseCouponType."]: ";
							print "<b><span class='fontrouge'>".$_SESSION['montantRemise2']." ".$symbolDevise."</span></b>";
							print "</td>";
							print "</tr><tr>";
						}
					}
					}
					else {
						$totttc = sprintf("%0.2f",$_SESSION['totalHtFinal']);
						$_SESSION['montantRemise2'] = sprintf("%0.2f",0);
						$discount[] = 0;
					}
				}

 
				if(isset($_SESSION['activerCadeau']) and $_SESSION['activerCadeau'] == 1) {
					$query = mysql_query("SELECT gc_amount, gc_number FROM gc WHERE gc_number = '".$_SESSION['cadeau_number']."'");
					$result = mysql_fetch_array($query);
					$remiseCadeau = $result['gc_amount'];
					
					if($taxePosition == "Tax included") {
						$_SESSION['montantRemise3'] = $remiseCadeau;
						$_SESSION['montantRemise3'] = sprintf("%0.2f",$_SESSION['montantRemise3']);
						$discount[] = 0;
						print "<td align='right' colspan='3'>";
						print "<b>".CHEQUE_CADEAU_MIN."</b> <i>(N&deg;".$result['gc_number'].")</i>&nbsp;&nbsp;&nbsp;";
						print "actiefcaddie6";
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

 
                $totalDiscount = array_sum($discount);
                $totttc = sprintf("%0.2f",($_SESSION['totalHtFinal']-$totalDiscount));
                if($taxePosition == "Tax included") {
                	$_SESSION['totalDisplayedCart'] = $_SESSION['totalHtFinal'];
                	print "<td align='right' colspan='3'>";
					print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>".sprintf("%0.2f",$_SESSION['totalHtFinal'])." ".$symbolDevise."</b></span>";
					if($devise2Visible=="oui" AND $_SESSION['totalHtFinal']>0) {
						$currency = sprintf("%0.2f",$_SESSION['totalHtFinal']*$tauxDevise2)."&nbsp;".$symbolDevise2;
                    	print "<div title='".A_TITRE_INDICATF."' class='FontGris tiny' align='right'><i>[".$currency."]</i></div>";
                    }
					print "</td>";
                }
                else {
                    if($remiseOnTax == "TTC") {
                        $_SESSION['totalDisplayedCart'] = $_SESSION['totalHtFinal'];
                        print "<td align='right' colspan='3'>";
                        print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>".sprintf("%0.2f",$_SESSION['totalHtFinal'])." ".$symbolDevise."</b></span>";
					    if($devise2Visible=="oui" AND $_SESSION['totalHtFinal']>0) {
					    	$currency = sprintf("%0.2f",$_SESSION['totalHtFinal']*$tauxDevise2)."&nbsp;".$symbolDevise2;
                        	print "<div title='".A_TITRE_INDICATF."' class='FontGris tiny' align='right'><i>[".$currency."]</i></div>";
						}
						print "</td>";
                    }
                    else {
                        $_SESSION['totalDisplayedCart'] = $totttc;
	                    print "<td align='right' colspan='3'>";
					    print "<b>".TOTAL."</b>: <span class='FontColorTotalPrice'><b>".sprintf("%0.2f",$totttc)." ".$symbolDevise."</b></span>";
						if($devise2Visible=="oui" AND $totttc>0) {
					    	$currency = sprintf("%0.2f",$totttc*$tauxDevise2)."&nbsp;".$symbolDevise2;
                        	print "<div title='".A_TITRE_INDICATF."' class='FontGris tiny' align='right'><i>[".$currency."]</i></div>";
                    	}
					    print "</td>";
				    }
				}
				print "</tr><tr>";
            	print "<td align='right' colspan='3'>";

 
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

 
                print "</td>";
                print "</tr><tr>";
                print "<td colspan='3'>";
                
				print "<hr width='100%'>";
				print "</td>";
                print "</tr><tr>";
                 
                if(!isset($_SESSION['devisNumero'])) {
                	print "<td width='33%' align='left' valign='top'>";
                	print "<a href='".$refererUrl."'>";
                	print "<img src='im/lang".$_SESSION['lang']."/retour_achat.gif' border='0'>";
                	print "</a>";
                	print "</td>";
                }
                 
                if(!isset($_SESSION['devisNumero'])) {
                	print "<td width='33%' align='center' valign='top'>";
                	print "<a href='".$url_id10.$slash."var=session_destroy'>";
                	print "<img src='im/lang".$_SESSION['lang']."/vider_caddie.gif' border='0'>";
                	print "</a>";
                	print "</td>";
                }
                 
                print "<td width='33%' align='right' valign='top'>";
                  	if($_SESSION['totalHtFinal']>=$minimumOrder OR isset($_SESSION['devisNumero'])) {
						if(!isset($_SESSION['recup'])) {
							$displayPaymentBut = "<div align='right'>";
							$displayPaymentBut.= "<input style='BACKGROUND: none; border:0px #CCCCCC solid' type='image' src='im/lang".$_SESSION['lang']."/commander.gif' border='0' alt='".COMMANDER."' title='".COMMANDER."'>";
							$displayPaymentBut.= "</div>";
						}
                  	}
                  	else {
                    	$displayPaymentBut = "<div class='PromoFont' align='right'>";
                    	$displayPaymentBut.= "<b>".COMMANDE_MINIMUM.": ".sprintf("%0.2f",$minimumOrder)." ".$symbolDevise."</b>";
                    	$displayPaymentBut.= "<br>";
                    	$displayPaymentBut.= "<i>(".AVANT_LIVRAISON.")</i>";
                    	$displayPaymentBut.= "</div>";
                  	}
                if($paymentsDesactive=='oui') {
							/*
							$displayPaymentBut = "<div align='right'>";
							$displayPaymentBut.= "<img style='BACKGROUND: none; border:0px #CCCCCC solid' src='im/lang".$_SESSION['lang']."/commander_no.gif' border='0' alt='".COMMANDER_NO."' title='".COMMANDER_NO."'>";
							$displayPaymentBut.= "</div>";
							$displayPaymentBut.= "<div><img src='im/zzz.gif' width='1' height='5'></div>";
							*/
							$displayPaymentBut = "";
				}
                if(isset($displayPaymentBut)) print $displayPaymentBut;

                
                if($devis == "oui" AND $_SESSION['totalHtFinal']>=$minimumOrder AND !isset($_SESSION['devisNumero'])) {
                	$displayDevisBut="";
	                if($paymentsDesactive=='non') $displayDevisBut = "<div><img src='im/zzz.gif' width='1' height='5'></div>";
	                $displayDevisBut.= "<div align='right'>";
	                $displayDevisBut.= "<a href='offerte.php'>";
	                $displayDevisBut.= "<img src='im/lang".$_SESSION['lang']."/devis.gif' border='0'>";
	                $displayDevisBut.= "</a>";
					$displayDevisBut.= "</div>";
					print $displayDevisBut;
                }
                print "</td>";
                print "</form>";
                print "</tr>";
                print "</table>";

print "</td></tr><tr>";
print "<td>";
    if(!isset($_SESSION['devisNumero'])) {
 
               $pays = mysql_query("SELECT countries_name, iso FROM countries WHERE countries_shipping != 'exclude' ORDER BY countries_name ASC");
               print "<a href='#a84z' name='A84'></a>";
               print "<br><table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
               print "<form action='berekenen.php' method='post' target='_blank'>";
               print "<input type='hidden' name='poids' value='".$_SESSION['poids']."'>";
               print "<td align='left'>";
                
				print "<table width='130' align='left' class='optionCaddieTop' border='0' cellspacing='0' cellpadding='3'><tr><td>&nbsp;<a HREF='#A90' NAME='a90z'><b>".maj(VISUALISE_FACTURE)."</b></a></td></tr></table>";
               	print "</td>";
               	print "<td>&nbsp;</td>";
               	print "</tr><tr><td colspan='2' class='optionCaddieBottom' style='padding:5px'>";
               	print POUR_CONNAITRE;
               	print "<select name='country'>";
               	print "<option value='vide'>".SELECTIONNE_PAYS."</option>";
            	while ($countries = mysql_fetch_array($pays)) {
                	if($countries['iso'] == $iso AND $countries['countries_name'] == $address_country) $a = "selected"; else $a="";
                    	$countryName = $countries['countries_name'];
                        $countryValue = $countries['countries_name'];
                        print "<option value='".$countryValue."' ".$a.">".$countryName."</option>";
                	}
            	print "</select>";
               	print "&nbsp;&nbsp;<input type='submit' name='valeur' value='".VOIR_FACTURE."'>";
               	print "</td>";
               	print "</form>";
               	print "</tr>";
               	print "</table>";
       
print "</td></tr><tr>";
print "<td>";

 
                $queryVV = mysql_fetch_row(mysql_query("SELECT COUNT(code_promo_id) FROM code_promo WHERE TO_DAYS(NOW()) <= TO_DAYS(code_promo_end)"));
                if($codeReductionActive=="oui" AND $queryVV[0] > 0) {
                    print "<a href='#a85z' name='A85'></a>";
                    print "<br>";
                    print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
                    print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
                    print "<td align='left'>";
                        print "<table width='150' align='left' class='optionCaddieTop' border='0' cellspacing='0' cellpadding='3'><tr>";
                        print "<td>&nbsp;<a HREF='#A90' NAME='a90z'><b>".maj(COUPON_CODE)."</b></a></td>";
                        print "</tr></table>";
                    print "</td>";
                    print "<td>&nbsp;</td>";
                    print "</tr><tr>";
                    print "<td colspan='2' class='optionCaddieBottom' style='padding:5px'>";
                    print SAISISSEZ_ICI;
                    print "<input type='text' name='codePromo' size='12'> ";
                    print "<input type='submit' value='".ACTIVE."'> ".$messageCodeReduc;
                    if(isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon'] == 1) {
                        print "<br><br>";
                        print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCoupon'><span style='".$styleDesactivate."'>".DESACTIVATE_COUPON."</span></a>";
                        print "<br>";
                    }
                    print "</td>";
                    print "</form>";
                    print "</tr></table>";
               }

 
                if($gcActivate=="oui") {
                   print "<a href='#a86z' name='A86'></a>";
                   print "<br>";
                   print "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
                   print "<form action='".$_SERVER['PHP_SELF']."' method='POST'>";
                   print "<td align='left'>";
                     print "<table width='150' align='left' class='optionCaddieTop' border='0' cellspacing='0' cellpadding='3'><tr><td>&nbsp;<a HREF='#A90' NAME='a90z'><b>".CHEQUE_CADEAU."</b></a></td></tr></table>";
                   print "</td>";
                   print "<td>&nbsp;</td>";
                   print "</tr><tr><td colspan='2' class='optionCaddieBottom' style='padding:5px'>";
                   print SAISISSEZ_ICI2;
                   print "<input type='text' name='codeCadeau' size='15'> ";
                   print "<input type='submit' value='".ACTIVE."'> ".$messageChequeC;
                   if(isset($_SESSION['activerCadeau']) and $_SESSION['activerCadeau'] == "1") {
                        print "<br><br>";
                        print "<a href='".$_SERVER['PHP_SELF']."?action=desactivateCertficate'><span style='".$styleDesactivate."'>".DESACTIVATE."</span></a>";
                        print "</br>";
                   }
                   print "</td>";
                   print "</form>";
                   print "</tr></table>";
                }


 
if($resultNumi > 0 AND $_SESSION['totalDisplayedCart']>=$seuilCadeau) {
	$tt="100%";
	while($resultiResult = mysql_fetch_array($resulti)) {
   		$expRel[] = $resultiResult['products_id'];
	}
	print "<a href='#a86zQ' name='A86Q'></a>";
	print "<br>";
	print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='0'>";
    print "<tr>";
	print "<td align='left'>";
	print "<table width='150' align='left' class='optionCaddieTop' border='0' cellspacing='0' cellpadding='3'><tr><td>&nbsp;<a HREF='#A90' NAME='a90z'><b>".EXTRA."</b></a></td></tr></table>";
	print "</td>";
	print "<td>&nbsp;</td>";
	print "</tr>";
	print "<tr><td colspan='2' class='optionCaddieBottom' style='padding:3px'>";
	if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
		
	foreach($expRel AS $itemId) {
			$queryRel = mysql_query("SELECT p.products_forsale, p.products_visible, p.products_tax, p.products_deee, p.products_options, p.products_qt, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_id, p.products_image, p.categories_id, p.products_desc_".$_SESSION['lang'].", p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
		                            FROM products as p
		                            LEFT JOIN specials as s
		                            ON (p.products_id = s.products_id)
		                           	WHERE p.products_caddie_display = 'yes'
		                           	AND p.products_id = '".$itemId."'
		                           	".$addToQuery."
		                           	ORDER BY p.products_name_".$_SESSION['lang']."
		                           ");
		if(mysql_num_rows($queryRel)>0) {
		$rowRel = mysql_fetch_array($queryRel);
		$popopop = "<a href='javascript:void(0);' onClick=\"window.open('artikel_fiche.php?lang=".$_SESSION['lang']."&id=".$rowRel['products_ref']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=450,width=500,toolbar=no,scrollbars=no,resizable=yes');\">";
 
		if($rowRel['products_visible']=='no') $linkZ = $popopop; else $linkZ = "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
		print "<table width='100%' border='0' cellspacing='0' cellpadding='2'><tr>";
 
		if($displayRelatedImage=="oui") {
  		    if(!empty($rowRel['products_image'])) {
 
				if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
					$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
					$isThere="yes";
				}
				else {
					$intoCart = "";
					$isThere="no";
				}
				$images_width = $ImageSizeDescRelated+20;
				$yoZ1 = @getimagesize($rowRel['products_image']);
				if(!$yoZ1) $rowRel['products_image']="im/zzz_gris.gif";
				$image_resize_related = resizeImage($rowRel['products_image'],$ImageSizeDescRelated,$images_width);
				print "<td width='".$images_width."' align='left'>";
				print $linkZ;
				// Is server GD open ?
				if($gdOpen == "non") {
					print "<img border='0' src='".$rowRel['products_image']."' width='".$image_resize_related[0]."' height='".$image_resize_related[1]."' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";
				}
				else {
					$infoImage = infoImageFunction($rowRel['products_image'],$images_width,$ImageSizeDescRelated);
					print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowRel['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";                  
				}
				print "</a>";
				print "</td>";
  			}
  			else {
            	print "<td width='1'>&nbsp;</td>";
         	}
		}
		else {
        	print "<td width='1'>&nbsp;</td>";

            if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
                $intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
                $isThere="yes";
            }
            else {
               $intoCart = "";
               $isThere="no";
            }
      	}

            		   print "<td>";
 
                       print $linkZ."<b>".$rowRel['products_name_'.$_SESSION['lang']]."</b></a>".$intoCart;
                       print "<br>";
  
                        $ProdNameProdDesc = $rowRel['products_desc_'.$_SESSION['lang']];
                        $ProdNameProdDesc = strip_tags($ProdNameProdDesc);
                        $maxCarDescAff = $maxCarDesc-60;
                           print adjust_text($ProdNameProdDesc,$maxCarDescAff,"...".$linkZ."<img src='im/next.gif' border='0'></a>");
                       print "</td>";
   
                       if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
                       print "<td width='".$largeBoutonCaddie."' align='right'>";
                            $new_price = $rowRel['specials_new_price'];
                            $old_price = $rowRel['products_price'];
                            $promoIs="";
                            
                            if(empty($new_price) OR $new_price=='') {
                                $price = "<b>".$old_price." ".$symbolDevise."</b>";
                                $clientPrice = $old_price;
                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($old_price,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
                                }
                            }
                            else {
                            	if($rowRel['specials_visible']=="yes") {
	                                $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
	                                
	                                $dateMaxCheck = explode("-",$rowRel['specials_last_day']);
	                                $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
	                                $dateDebutCheck = explode("-",$rowRel['specials_first_day']);
	                                $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
	                                
	                                if($dateDebut <= $today  and $dateMax >= $today) {
	                                    $econPourcent = (1-($rowRel['specials_new_price']/$rowRel['products_price']))*100;
	                                    $econPourcent = sprintf("%0.2f",$econPourcent)."%";
	                                    $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
	                                    $promoIs = 'yes';
	                                    $price = "<b><s>".$old_price."</s> ".$symbolDevise."</b><br><b><span class='fontrouge'>".$new_price." ".$symbolDevise."</span></b>";
	                                    $clientPrice = $new_price;
	                                }
	                                else {
	                                    $price = "<b>".$old_price." ".$symbolDevise."</b>";
	                                    $clientPrice = $old_price;
	                                }
	                            }
	                            else {
	                                $price = "<b>".$old_price." ".$symbolDevise."</b>";
	                                $clientPrice = $old_price;
								}
    
                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
                                }
                            }
                            
                        if($old_price > 0) {
                        	if($rowRel['products_forsale']=="no") $price = "--";
                        	print $price;
                        	if($devise2Visible=="oui" AND $clientPrice>0  AND $rowRel['products_forsale']=="yes") print curPrice($clientPrice,$symbolDevise2,"center");
                        }
                        else {
                        	print "<b><span class='fontrouge'>**".OFFERT."**</span></b>";
                        }
                        if(isset($price2) AND $clientPrice > 0 AND $rowRel['products_forsale']=="yes") {
                        		print "<br>".$price2;
                        }
                        if($devise2Visible=="oui" AND $clientPrice>0 AND $rowRel['products_forsale']=="yes") print curPrice($clientPrice,$symbolDevise2,"center");
                        print "</td>";




            if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowRel['products_forsale']=="yes" AND !isset($_SESSION['devisNumero'])) {
            
            if($rowRel['products_qt'] > 0) {
                if($isThere=="yes") {
                	if($rowRel['products_options'] == 'no') {
                    	print "<td width='".$largeBoutonCaddie."' align='center'>";
                	  	print "<a href='add.php?amount=0&ref=".$rowRel['products_ref']."&id=".$rowRel['products_id']."&name=".$rowRel['products_name_'.$_SESSION['lang']]."&productTax=".$rowRel['products_tax']."&deee=".$rowRel['products_deee']."'><img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."' title='".RETIRER_DU_CADDIE."'>";
                		print "</a></td>";
					}
					else {
                		print "<td width='".$largeBoutonCaddie."'>";
              	  	 	print "<div align='center'>";
              	  	 	print $linkZ;
              		 	print "<img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'>";
              		 	print "</a>";
              		 	print "</div>";
              		 	print "</td>";
                   }
            	}
                else {
                	if($rowRel['products_options'] == 'no') {
						print "<td width='".$largeBoutonCaddie."' align='center' valign='middle'>";
						print "<form action='add.php' method='get'>";
						if($old_price > 0) {
						 print "<br><input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='im/zzz.gif' width='1' height='3'><br>";
						}
						else {
						 print "<input type='hidden' name='amount' value='1'>";
						}
						print "<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
						print "<input type='hidden' value='".$rowRel['products_id']."' name='id'>";
						print "<input type='hidden' value='".$rowRel['products_ref']."' name='ref'>";
						print "<input type='hidden' value='".$rowRel['products_name_'.$_SESSION['lang']]."' name='name'>";
						print "<input type='hidden' value='".$rowRel['products_tax']."' name='productTax'>";
						print "<input type='hidden' value='".$rowRel['products_deee']."' name='deee'>";
						print "</form>";
						print "</td>";
					}
					else {
						print "<td width='".$largeBoutonCaddie."' align='center'>";
						print $linkZ;
						print "<img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'>";
						print "</a></td>";
					}
				}
            }
            else {
                if($actRes=="non") {
                    print "<td width='".$largeBoutonCaddie."' align='center'>";
                    print "<img src='im/cart_out.png' alt='".NOT_IN_STOCK."' title='".NOT_IN_STOCK."'>";
                    print "</td>";
                }
                else {
                    print "<td width='".$largeBoutonCaddie."' align='center'>";
                    print $linkZ;
                    print "<img src='im/cart_out.png' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'>";
                    print "</a>";
                    print "</td>";
                }
            }
            }
            if($rowRel['products_forsale']=="no" AND !isset($_SESSION['devisNumero'])) print "<td width='".$largeBoutonCaddie."' align='center'><img src='im/cart_no.png' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'></td>";
                       }
		print "</tr></table>";
		print "<img src='im/zzz.gif' width='1' height='8'>";
		}
    }
    print "</td></tr>";
	print "</table>";
}
else {
	## Check if extra products is in the cart
	if($resultNumi > 0 AND $_SESSION['totalDisplayedCart'] < $seuilCadeau) {
		$checkListAll2 = array();
		while($resultiResult = mysql_fetch_array($resulti)) {
		   if($resultiResult['products_visible']=="no") $itemsInExtra[] = $resultiResult['products_id'];
		}

		if(isset($itemsInExtra) AND count($itemsInExtra) > 0) {
			$splitList = explode(",",$_SESSION['list']);
			foreach($splitList AS $itemsIntoTheCart) {
				$takeItemId = explode("+",$itemsIntoTheCart);
				if(!in_array($takeItemId[0], $itemsInExtra)) {$checkListAll2[] = $itemsIntoTheCart;}
			}
			if(count($checkListAll2)>=0) {
				$_SESSION['list'] = implode(",", $checkListAll2);
				if($_SESSION['list']=="") {
					unset($_SESSION['list']);
					header("location:caddie.php");
					exit;
				}
			}
		}
	}
}
 
if($displayAffInCart=="oui") {
	$split = explode(",",$_SESSION['list']);
	$id = array();
	foreach ($split as $item) {
	      $check = explode("+",$item);
	      $queryRelProducts = mysql_query("SELECT products_related FROM products WHERE products_id='".$check[0]."'");
	      $queryRelResult = mysql_fetch_array($queryRelProducts);
	      if($queryRelResult['products_related']!=="") {
	         $explodRelated = explode("|",$queryRelResult['products_related']);
	         foreach($explodRelated AS $itemZ) {
	            $id[] = $itemZ;
	         }
	      }
	}
	if(isset($id) AND count($id)>0) {
	   	array_unique($id);
	   	$idRelated = implode("|",$id);
	   	if($displayOutOfStock=="non") {$addToQuery = " AND p.products_qt>'0'";} else {$addToQuery="";}
	   	$expRel = explode("|", $idRelated);
		$tt="100%";
		print "<a href='#a86zQ1' name='A86Q1'></a>";
		print "<br>";
		print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='0'>";  
			print "<tr>";
			print "<td align='left'>";
			print "<table width='220' align='left' class='optionCaddieTop' border='0' cellspacing='0' cellpadding='3'><tr><td>&nbsp;<a HREF='#A90' NAME='a90z'><b>".PRODUITS_AFFILIES."</b></a></td></tr></table>";
			print "</td>";
			print "<td>&nbsp;</td>";
			print "</tr>";
			print "<tr><td colspan='2' class='optionCaddieBottom' style='padding:3px'>";
	           $expRelNb = count($expRel)-1;
	           for($i=0; $i<=$expRelNb; $i++) {
	              $queryRel = mysql_query("SELECT p.products_tax, p.products_deee, p.products_options, p.products_qt, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_id, p.products_image, p.categories_id, p.products_desc_".$_SESSION['lang'].", p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
	                                        FROM products as p
	                                        LEFT JOIN specials as s
	                                        ON (p.products_id = s.products_id)
	                                        WHERE p.products_id = '".$expRel[$i]."'
	                                        ".$addToQuery."
	                                        AND p.products_visible = 'yes'
	                                        AND p.products_forsale = 'yes'
											");
	           if(mysql_num_rows($queryRel)>0) {
	           $rowRel = mysql_fetch_array($queryRel);
	
	           print "<table width='100%' border='0' cellspacing='0' cellpadding='2'><tr>";
 
			if($displayRelatedImage=="oui") {
				if(!empty($rowRel['products_image'])) {

					if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
					    $intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
						$isThere="yes";
					}
					else {
						$intoCart = "";
						$isThere="no";
					}
	               	$images_width = $ImageSizeDescRelated+20;
	               	$yoZ2 = @getimagesize($rowRel['products_image']);
	               	if(!$yoZ2) $rowRel['products_image']="im/zzz_gris.gif";
	               	$image_resize_related = resizeImage($rowRel['products_image'],$ImageSizeDescRelated,$images_width);
	      		   	print "<td width='".$images_width."' align='left'>";
	               	print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
 
	                if($gdOpen == "non") {
	                	print "<img border='0' src='".$rowRel['products_image']."' width='".$image_resize_related[0]."' height='".$image_resize_related[1]."' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";
	                }
	                else {
	                    $infoImage = infoImageFunction($rowRel['products_image'],$images_width,$ImageSizeDescRelated);
	                	print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowRel['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";                  
	                }
	               print "</a>";
	               print "</td>";
				}
	  			else {

	                if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
	                	$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
	                    $isThere="yes";
	                }
	                else {
	                	$intoCart = "";
	                	$isThere="no";
	                }
	            	print "<td>&nbsp;</td>";
				}
			}
			else {
	         	print "<td>&nbsp;</td>";
 
	            if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
	            	$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
	            	$isThere="yes";
	            }
	            else {
	            	$intoCart = "";
	            	$isThere="no";
	            }
	      	}
	
	            		   print "<td>";
 
	                       print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><b>".$rowRel['products_name_'.$_SESSION['lang']]."</b></a>".$intoCart;
	                       print "<br>";
 
	                        $ProdNameProdDesc = $rowRel['products_desc_'.$_SESSION['lang']];
	                        $ProdNameProdDesc = strip_tags($ProdNameProdDesc);
	                        $maxCarDescAff = $maxCarDesc-60;
	                       print adjust_text($ProdNameProdDesc,$maxCarDescAff,"..<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/next.gif' border='0'></a>");
	                       print "</td>";
 
	                       if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
	                       print "<td width='".$largeBoutonCaddie."' align='right'>";
	                            $new_price = $rowRel['specials_new_price'];
	                            $old_price = $rowRel['products_price'];
	                            $promoIs="";
	                            
	                            if(empty($new_price) OR $new_price=='') {
	                                $price = "<b>".$old_price." ".$symbolDevise."</b>";
	                                $clientPrice = $old_price;
	                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
	                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($old_price,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
	                                }
	                            }
	                            else {
	                            	if($rowRel['specials_visible']=="yes") {
		                                $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
		                                
		                                $dateMaxCheck = explode("-",$rowRel['specials_last_day']);
		                                $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
		                                $dateDebutCheck = explode("-",$rowRel['specials_first_day']);
		                                $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
		                                
		                                if($dateDebut <= $today  and $dateMax >= $today) {
		                                    $econPourcent = (1-($rowRel['specials_new_price']/$rowRel['products_price']))*100;
		                                    $econPourcent = sprintf("%0.2f",$econPourcent)."%";
		                                    $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
		                                    $promoIs = 'yes';
		                                    $price = "<b><s>".$old_price."</s> ".$symbolDevise."</b><br><b><span class='fontrouge'>".$new_price." ".$symbolDevise."</span></b>";
		                                    $clientPrice = $new_price;
		                                }
		                                else {
		                                    $price = "<b>".$old_price." ".$symbolDevise."</b>";
		                                    $clientPrice = $old_price;
		                                }
		                            }
		                            else {
	                                	$price = "<b>".$old_price." ".$symbolDevise."</b>";
	                                	$clientPrice = $old_price;
									}
 
	                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
	                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
	                                }
	                            }
	                        print $price;
	                        if(isset($price2)) {
	                            print "<br>".$price2;
	                        }
	                        if($devise2Visible=="oui" AND $clientPrice>0) print curPrice($clientPrice,$symbolDevise2,"right");
	                        print "</td>";
	
	
	
 
	            if(isset($_SESSION['account']) OR $activeEcom=="oui") {
	            if($rowRel['products_qt'] > 0) {
	                if($isThere=="yes") {
	                   if($rowRel['products_options'] == 'no') {
	                        print "<td width='".$largeBoutonCaddie."' align='center'>";
	                        print "<a href='add.php?amount=0&ref=".$rowRel['products_ref']."&id=".$rowRel['products_id']."&name=".$rowRel['products_name_'.$_SESSION['lang']]."&productTax=".$rowRel['products_tax']."&deee=".$rowRel['products_deee']."'>";
	                        print "<img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."' title='".RETIRER_DU_CADDIE."'>";
	                        print "</a>";
	                        print "</td>";
	                   }
	                   else {
	                        print "<td width='".$largeBoutonCaddie."'>";
	                        print "<div align='center'>";
	                        print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
	                        print "<img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'>";
	                        print "</a>";
	                        print "</div>";
	                        print "</td>";
	                   }
	                }
	                else {
	                      if($rowRel['products_options'] == 'no') {
	                         print "<td width='".$largeBoutonCaddie."' align='center' valign='middle'>";
	                         print "<form action='add.php' method='get'>";
	                         print "<br><input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='im/zzz.gif' width='1' height='3'><br>";
	                         print "<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
	                         print "<input type='hidden' value='".$rowRel['products_id']."' name='id'>";
	                         print "<input type='hidden' value='".$rowRel['products_ref']."' name='ref'>";
	                         print "<input type='hidden' value='".$rowRel['products_name_'.$_SESSION['lang']]."' name='name'>";
	                         print "<input type='hidden' value='".$rowRel['products_tax']."' name='productTax'>";
	                         print "<input type='hidden' value='".$rowRel['products_deee']."' name='deee'>";
	                         print "</form>";
	                         print "</td>";
	                      }
	                      else {
	                         print "<td width='".$largeBoutonCaddie."' align='center'>";
	                         print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'></a>";
	                         print "</td>";
	                      }
	                }
	            }
	            else {
	                if($actRes=="non") {
	                    print "<td width='".$largeBoutonCaddie."' align='center'><img src='im/cart_out.png' alt='".NOT_IN_STOCK."' title='".NOT_IN_STOCK."'></td>";
	                }
	                else {
	                    print "<td width='".$largeBoutonCaddie."' align='center'>";
	                    print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/cart_out.png' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'></a>";
	                    print "</td>";
	                }
	            }
	            }
	                       }
			   print "</tr></table>";
	            		   print "<img src='im/zzz.gif' width='1' height='8'>";
	            		}
	           }
		print "</td></tr>";
		print "</table>";
	}
}

 
if($lastViewCartVisible=="oui" AND isset($_SESSION['lastView'])) {
	$id = explode("z",$_SESSION['lastView']);
	foreach($id AS $key => $value) {if($value=="") unset($id[$key]);} 
	if(isset($id) AND count($id)>0) {
					$id = array_reverse($id);
					$id = implode(",",$id);
	              	$queryRel = mysql_query("SELECT p.products_forsale, p.products_tax, p.products_deee, p.products_options, p.products_qt, p.products_ref, p.products_name_".$_SESSION['lang'].", p.products_id, p.products_image, p.categories_id, p.products_desc_".$_SESSION['lang'].", p.products_price, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
											FROM products AS p
                     						LEFT JOIN specials as s
                     						ON (p.products_id = s.products_id)
											WHERE p.products_id IN (".$id.") ORDER BY FIELD(p.products_id,".$id.") LIMIT 0,".$lastViewCartNum."") or die (mysql_error());

		$tt="100%";
		print "<a href='#a86zQ1' name='A86Q1'></a>";
		print "<br>";
		print "<table width='".$tt."' border='0' cellspacing='0' cellpadding='0'>";  
			print "<tr>";
			print "<td align='left'>";
			print "<table width='240' align='left' class='optionCaddieTop' border='0' cellspacing='0' cellpadding='3'><tr><td>&nbsp;<a HREF='#A90' NAME='a90z'><b>".$lastViewCartNum." ".(LAST_VIEWED)."</b></a></td></tr></table>";
			print "</td>";
			print "<td>&nbsp;</td>";
			print "</tr>";
			print "<tr><td colspan='2' class='optionCaddieBottom' style='padding:3px'>";

											
	           	if(mysql_num_rows($queryRel)>0) {
	           	while($rowRel = mysql_fetch_array($queryRel)) {
	
	           		print "<table width='100%' border='0' cellspacing='0' cellpadding='2'><tr>";
 
				if($displayRelatedImage=="oui") {
					if(!empty($rowRel['products_image'])) {

						if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
					    	$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
							$isThere="yes";
						}
						else {
							$intoCart = "";
							$isThere="no";
						}
	               		$images_width = $ImageSizeDescRelated+20;
	               		$yoZ2 = @getimagesize($rowRel['products_image']);
	               		if(!$yoZ2) $rowRel['products_image']="im/zzz_gris.gif";
	               		$image_resize_related = resizeImage($rowRel['products_image'],$ImageSizeDescRelated,$images_width);
	      		   		print "<td width='".$images_width."' align='left'>";
	               		print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
 
	                	if($gdOpen == "non") {
	                		print "<img border='0' src='".$rowRel['products_image']."' width='".$image_resize_related[0]."' height='".$image_resize_related[1]."' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";
	                	}
	                	else {
	                    	$infoImage = infoImageFunction($rowRel['products_image'],$images_width,$ImageSizeDescRelated);
	                		print "<img src='mini_maker.php?backColor=".$backGdColor."&extension=".$infoImage[0]."&imageSource=".$rowRel['products_image']."&largeurOrigin=".$infoImage[1]."&hauteurOrigin=".$infoImage[2]."&largeur=".$infoImage[3]."&hauteur=".$infoImage[4]."' border='0' alt='".$rowRel['products_name_'.$_SESSION['lang']]."'>";                  
	                	}
	               		print "</a>";
	               		print "</td>";
					}
	  				else {
 
	                	if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
	                		$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
	                    	$isThere="yes";
	                	}
	                	else {
	                		$intoCart = "";
	                		$isThere="no";
	                	}
	            		print "<td>&nbsp;</td>";
					}
				}
				else {
	         		print "<td>&nbsp;</td>";
 
	            	if(isset($_SESSION['list']) and strstr($_SESSION['list'], "+".$rowRel['products_ref']."+")) {
	            		$intoCart = "&nbsp;<img src='im/cart.gif' alt='".ARTICLE_PRESENT_DANS_LE_CADDIE."' title='".ARTICLE_PRESENT_DANS_LE_CADDIE."'>";
	            		$isThere="yes";
	            	}
	            	else {
	            		$intoCart = "";
	            		$isThere="no";
	            	}
	      		}
	
	            			print "<td>";
	            		   	// Titre
	                       	print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><b>".$rowRel['products_name_'.$_SESSION['lang']]."</b></a>".$intoCart;
	                       	print "<br>";
 
	                        $ProdNameProdDesc = $rowRel['products_desc_'.$_SESSION['lang']];
	                        $ProdNameProdDesc = strip_tags($ProdNameProdDesc);
	                        $maxCarDescAff = $maxCarDesc-60;
	                       	print adjust_text($ProdNameProdDesc,$maxCarDescAff,"..<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/next.gif' border='0'></a>");
	                       	print "</td>";
 
	                       	if(isset($_SESSION['account']) OR $displayPriceInShop=="oui") {
	                       		print "<td width='".$largeBoutonCaddie."' align='right'>";
	                            $new_price = $rowRel['specials_new_price'];
	                            $old_price = $rowRel['products_price'];
	                            $promoIs="";
	                            
	                            if(empty($new_price) OR $new_price=='') {
	                                $price = "<b>".$old_price." ".$symbolDevise."</b>";
	                                $clientPrice = $old_price;
	                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
	                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($old_price,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
	                                }
	                            }
	                            else {
	                            	if($rowRel['specials_visible']=="yes") {
		                                $today = mktime(0,0,0,date("m"),date("d"),date("Y"));
		                                
		                                $dateMaxCheck = explode("-",$rowRel['specials_last_day']);
		                                $dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
		                                $dateDebutCheck = explode("-",$rowRel['specials_first_day']);
		                                $dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
		                                
		                                if($dateDebut <= $today  and $dateMax >= $today) {
		                                    $econPourcent = (1-($rowRel['specials_new_price']/$rowRel['products_price']))*100;
		                                    $econPourcent = sprintf("%0.2f",$econPourcent)."%";
		                                    $itMiss = round((mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]) - mktime(0,0,0,date("m"),date("d"),date("Y")))/86400);
		                                    $promoIs = 'yes';
		                                    $price = "<b><s>".$old_price."</s> ".$symbolDevise."</b><br><b><span class='fontrouge'>".$new_price." ".$symbolDevise."</span></b>";
		                                    $clientPrice = $new_price;
		                                }
		                                else {
		                                    $price = "<b>".$old_price." ".$symbolDevise."</b>";
		                                    $clientPrice = $old_price;
		                                }
		                            }
		                            else {
	                                	$price = "<b>".$old_price." ".$symbolDevise."</b>";
	                                	$clientPrice = $old_price;
									}
 
	                                if(isset($_SESSION['reduc']) AND $_SESSION['reduc'] > 0) {
	                                    $price2 = VOTRE_PRIX."<br><b><span class='PromoFontColorNumber'>".newPrice($clientPrice,$_SESSION['reduc'])." ".$symbolDevise."</span></b>";
	                                }
	                            }
	                            if($rowRel['products_forsale']=="no") $price = "--";
	                        	print $price;
	                        	
	                        	if(isset($price2) AND $rowRel['products_forsale']=="yes") {
	                            	print "<br>".$price2;
	                        	}
	                        	if($devise2Visible=="oui" AND $clientPrice>0 AND $rowRel['products_forsale']=="yes") print curPrice($clientPrice,$symbolDevise2,"right");
	                        	print "</td>";
	
	
	
 
					            if((isset($_SESSION['account']) OR $activeEcom=="oui") AND $rowRel['products_forsale']=="yes" AND !isset($_SESSION['devisNumero'])) {
					            		if($rowRel['products_qt'] > 0) {
						                	if($isThere=="yes") {
							                   if($rowRel['products_options'] == 'no') {
							                        print "<td width='".$largeBoutonCaddie."' align='center'>";
							                        print "<a href='add.php?amount=0&ref=".$rowRel['products_ref']."&id=".$rowRel['products_id']."&name=".$rowRel['products_name_'.$_SESSION['lang']]."&productTax=".$rowRel['products_tax']."&deee=".$rowRel['products_deee']."'>";
							                        print "<img src='im/cart_rem.png' border='0' alt='".RETIRER_DU_CADDIE."' title='".RETIRER_DU_CADDIE."'>";
							                        print "</a>";
							                        print "</td>";
							                   }
							                   else {
							                        print "<td width='".$largeBoutonCaddie."'>";
							                        print "<div align='center'>";
							                        print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'>";
							                        print "<img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'>";
							                        print "</a>";
							                        print "</div>";
							                        print "</td>";
							                   }
							                }
							                else {
							                      if($rowRel['products_options'] == 'no') {
							                         print "<td width='".$largeBoutonCaddie."' align='center' valign='middle'>";
							                         print "<form action='add.php' method='get'>";
							                         print "<br><input type='text' size='3' maxlength='3' name='amount' value='1'><br><img src='im/zzz.gif' width='1' height='3'><br>";
							                         print "<input style='BACKGROUND: none; border:0px' type='image' src='im/cart_add.png' alt='".AJOUTER_AU_CADDIE."' title='".AJOUTER_AU_CADDIE."'>";
							                         print "<input type='hidden' value='".$rowRel['products_id']."' name='id'>";
							                         print "<input type='hidden' value='".$rowRel['products_ref']."' name='ref'>";
							                         print "<input type='hidden' value='".$rowRel['products_name_'.$_SESSION['lang']]."' name='name'>";
							                         print "<input type='hidden' value='".$rowRel['products_tax']."' name='productTax'>";
							                         print "<input type='hidden' value='".$rowRel['products_deee']."' name='deee'>";
							                         print "</form>";
							                         print "</td>";
							                      }
							                      else {
							                         print "<td width='".$largeBoutonCaddie."' align='center'>";
							                         print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/cart_opt.png' border='0' alt='".VOIR_OPTIONS."' title='".VOIR_OPTIONS."'></a>";
							                         print "</td>";
							                      }
							                }
						            }
						            else {
						                if($actRes=="non") {
						                    print "<td width='".$largeBoutonCaddie."' align='center'><img src='im/cart_out.png' alt='".NOT_IN_STOCK."' title='".NOT_IN_STOCK."'></td>";
						                }
						                else {
						                    print "<td width='".$largeBoutonCaddie."' align='center'>";
						                    print "<a href='beschrijving.php?id=".$rowRel['products_id']."&path=".$rowRel['categories_id']."'><img src='im/cart_out.png' border='0' alt='".EN_COMMANDE."' title='".EN_COMMANDE."'></a>";
						                    print "</td>";
						                }
						            }
								}
					            if($rowRel['products_forsale']=="no" AND !isset($_SESSION['devisNumero'])) print "<td width='".$largeBoutonCaddie."' align='center'><img src='im/cart_no.png' title='".ITEMS_OUT_OF_STOCK."' alt='".ITEMS_OUT_OF_STOCK."'></td>";
	                       }
			   				print "</tr></table>";
	            		   	print "<img src='im/zzz.gif' width='1' height='8'>";
	            		}
	            }
		print "</td></tr>";
		print "</table>";
	}
}
    }
print "</td>";
print "</tr></table>";

        }
}
?>
             </td></tr>
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
