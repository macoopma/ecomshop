<?php
// ----------------------//
// function display invoice
// ----------------------//
function invoice_display($_country,$_style,$_field,$_livraisonId) {
GLOBAL $_SESSION,$_POST,$iso3;

if(isset($_SESSION['list']) AND !empty($_SESSION['list'])) {
$pays = mysql_query("SELECT iso, countries_product_tax, countries_product_tax_active, countries_shipping_tax FROM countries WHERE countries_name = '".$_country."'") or die (mysql_error());
$p = mysql_fetch_array($pays);
$iso3 = $p['iso'];

// details taxes
function tax_details($taxeItem,$totalTaxe1) {
    GLOBAL $taxeName,$taxePosition;
    $exist =array();
    $a = "";
        $taxeItemNb = count($taxeItem)-1;
        for($uu=0; $uu<=$taxeItemNb; $uu++) {
            if(in_array($taxeItem[$uu],$exist)) {
                $montant[$taxeItem[$uu]] = $montant[$taxeItem[$uu]] + $totalTaxe1[$uu];
            }
            else {
                $exist[] = $taxeItem[$uu];
                $montant[$taxeItem[$uu]] = $totalTaxe1[$uu];
            }
        }
        while (list($key, $val) = each($montant)) {
            if($taxePosition == "No tax") {
                $multipleTax[] = "0.00>0.00";
                if($key>0) {
                $a .=  "<td align='left'>".strtoupper($taxeName)."</td>";
                $a .=  "<td align='right'>";
                $a .= sprintf("%0.2f",$val)."<br>";
                $a .= "</td>";
                $a .= "</tr>";
                $a .= "<tr>";
                }
            }
            else {
                $multipleTax[] = $key.">".sprintf("%0.2f",$val);
                if($key>0) {
                $a .=  "<td align='left'>".strtoupper($taxeName)." ".$key."%</td>";
                $a .=  "<td align='right'>";
                $a .= sprintf("%0.2f",$val);
                $a .= "</td>";
                $a .= "</tr>";
                $a .= "<tr>";
                }
            }
        }
            $_SESSION['multipleTax'] = implode("|",$multipleTax);
    return $a;
}

// End function

include($_field.'configuratie/configuratie.php');

//------------
// Udate Order
//------------
if(isset($_POST['action']) AND $_POST['action']=='saveOrder' AND isset($_SESSION['clientNicZ'])) {
	$couponC1 = (!isset($_SESSION['montantRemise']))? "0.00" : $_SESSION['montantRemise'];
	$couponC = (!isset($_SESSION['montantRemise2']))? "0.00" : $_SESSION['montantRemise2'];
	$couponN = (!isset($_SESSION['coupon_name']))? "" : $_SESSION['coupon_name'];
	$users_coupon_note = (!isset($_SESSION['users_coupon_note']))? "" : $_SESSION['users_coupon_note'];
	$users_remise_note = (!isset($_SESSION['users_remise_note']))? "" : $_SESSION['users_remise_note'];
	$users_shipping_note = (!isset($_SESSION['users_shipping_note']))? "" : $_SESSION['users_shipping_note'];
	$accountRemiseEffecBd = (!isset($_SESSION['accountRemiseEffec']))? "0.00" : $_SESSION['accountRemiseEffec'];
	$users_account_remise_note = (!isset($_SESSION['users_account_remise_note']) OR $accountRemiseEffecBd=="0.00")? "" : $_SESSION['users_account_remise_note'];
	$_gc = (!isset($_SESSION['cadeau_number']))? "" : $_SESSION['cadeau_number'];
	$gcAmount = (!isset($_SESSION['montantRemise3']))? "0.00" : $_SESSION['montantRemise3'];
	$mTax = (isset($_SESSION['multipleTax']))? $_SESSION['multipleTax'] : "";
	if(isset($_SESSION['recup'])) unset($_SESSION['recup']);
	
		// Affiliation
        if(!isset($_SESSION['affiliateNumber'])) {
          $usersAffNumber = "";
          $usersAffAmount = 0;
        }
        else {
          $usersAffNumber = $_SESSION['affiliateNumber'];
          $usersAffAmount = $_SESSION['totalHtFinal']*($_SESSION['affiliateCom']/100);
        }
        // DEEE
        if(isset($_SESSION['deee'])) {
            $splitDeee = explode(",",$_SESSION['list']);
            
            foreach ($splitDeee as $item) {
                $checkDeee = explode("+",$item);
                   if($taxePosition=="Tax included") {
                      $priceTTCDeee = $checkDeee[7] * $checkDeee[1];
                      $deeeHt = $priceTTCDeee*100/($checkDeee[5]+100);
                      $deeeTax = $deeeHt * ($checkDeee[5]/100);
                   }
                   if($taxePosition=="Plus tax") {
                      $deeeHt = $checkDeee[7] * $checkDeee[1];
                      $deeeTax = $deeeHt * ($checkDeee[5]/100);
                   }
                   if($taxePosition=="No tax") {
                      $deeeHt = $checkDeee[7] * $checkDeee[1];
                      $deeeTax = 0;
                   }
                   $deeeHtArray[] = $deeeHt;
                   $deeeTaxArray[] = $deeeTax;
            }
            $deeeHtFinal = sprintf("%0.2f",array_sum($deeeHtArray));
            $deeeTaxFinal = sprintf("%0.2f",array_sum($deeeTaxArray));
        }
        else {
            $deeeHtFinal = sprintf("%0.2f",0);
            $deeeTaxFinal = sprintf("%0.2f",0);
        }
		// Devis
		$devisSearchQuery = mysql_query("SELECT users_devis FROM users_orders WHERE users_nic = '".$_SESSION['clientNicZ']."'") or die (mysql_error());
		$devisSearch = mysql_fetch_array($devisSearchQuery);
		if($devisSearch['users_devis']!=="") {
			$devisSearchQuery2 = mysql_query("SELECT devis_number FROM devis WHERE devis_number = '".$devisSearch['users_devis']."'") or die (mysql_error());
			if(mysql_num_rows($devisSearchQuery2)>0) {
				mysql_query("UPDATE devis SET devis_products='".$_SESSION['list']."' WHERE devis_number = '".$devisSearch['users_devis']."'") or die (mysql_error());		
			}
		}
	// Add note interne
	$addNoteQuery = mysql_query("SELECT users_note FROM users_orders WHERE users_nic = '".$_SESSION['clientNicZ']."'") or die (mysql_error());
	$addNote = mysql_fetch_array($addNoteQuery);
	$hoy = dateFr(date("Y-m-d H:i:s"), $_SESSION['lang']);
	$addNoteZ = "\r\n".COMMANDE_MODIFIEE." ".LE." ".$hoy."\r\n---\r\n".$addNote['users_note'];
	mysql_query("UPDATE users_orders SET users_note='".$addNoteZ."' WHERE users_nic = '".$_SESSION['clientNicZ']."'") or die (mysql_error());

	// Save order
	mysql_query("UPDATE users_orders SET 
					users_products='".$_SESSION['list']."',
					users_products_weight='".$_SESSION['poids']."',
					users_products_weight_price='".$_SESSION['shipPrice']."',
					users_total_to_pay='".$_SESSION['totalToPayTTC']."',
					users_shipping_price='".$_SESSION['users_shipping_price']."',
					users_ship_ht='".$_SESSION['livraisonhors']."',
					users_ship_tax='".$_SESSION['shipTax']."',
					users_products_ht='".$_SESSION['totalHtFinal']."',
					users_products_tax='".$_SESSION['itemTax']."',
					users_remise='".$couponC1."',
					users_remise_coupon='".$couponC."',
					users_remise_coupon_name='".$couponN."',
					users_coupon_note='".$users_coupon_note."',
					users_remise_note='".$users_remise_note."',
					users_shipping_note='".$users_shipping_note."',
					users_account_remise_note='".$users_account_remise_note."',
					users_account_remise_app='".$accountRemiseEffecBd."',
					users_affiliate='".$usersAffNumber."',
					users_affiliate_amount='".$usersAffAmount."',
					users_gc='".$_gc."',
					users_remise_gc='".$gcAmount."',
					users_multiple_tax='".$mTax."',
					users_deee_ht='".$deeeHtFinal."',
					users_deee_tax='".$deeeTaxFinal."',
					users_sup_ttc='".$_SESSION['priceEmballageTTC']."',
					users_sup_ht='".$_SESSION['totalEmballageHt']."',
					users_sup_tax='".$_SESSION['totalEmballageTva']."',
					users_shipping='".$_POST['shipping']."'
				WHERE users_nic = '".$_SESSION['clientNicZ']."'") or die (mysql_error());
	
	$updateOrderMessage = COMMANDE_MODIFIEE;
	if(isset($_SESSION['clientNicZ'])) unset($_SESSION['clientNicZ']);
}

// --------------------------------------
// Récupérer/Modifier cette commande FORM
if(isset($_SESSION['recup']) AND $_SESSION['recup']=="yes") {
	print "<form method='POST' action='berekenen.php'>";
	print "<input type='hidden' name='action' value='saveOrder'>";
	print "<input type='hidden' name='country' value='".$_country."'>";
	print "<input type='hidden' name='shipping' value='".$_POST['shipping']."'>";
}

print "<table width='500' cellpadding='3' cellspacing='0' class='".$_style."' border='0' align='center'><tr><td>";
print "<table border='0' width='100%' align='center' cellspacing='3' cellpadding='0'>";
print "<tr>";
print "<td align='left'><b><u>Ref/".ARTICLES."</u></b><br><img src='im/zzz.gif' width='1' height='3'><br></td>";
print "<td width='50' align='center'><b><u>".QTE."</u></b></td>";
print "<td width='50' align='center'><b><u>".PRIX_UNITAIRE."</u></b></td>";
if($taxePosition!=="No tax") {print "<td width='50' align='center'><b><u>".TAXE."</u></b></td>";}
print "<td width='80' align='right'><b><u>".PRIX_HT."</u></b></td>";

//----------------------------
// start loop between products
//----------------------------
$split = explode(",",$_SESSION['list']);

foreach ($split as $item) {
                        // article $check[0]= products_id
                        $check = explode("+",$item);
                        $query = mysql_query("SELECT p.products_delay_1, p.products_delay_2, p.products_delay_1a, p.products_delay_2a, p.products_delay_1b, p.products_delay_2b, p.products_sup_shipping,p.products_deee,p.products_name_".$_SESSION['lang'].",p.products_id,p.categories_id,p.products_download,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_price,p.products_weight,p.products_note_".$_SESSION['lang'].",p.products_ref,p.products_im,p.products_image,p.products_image2,p.products_image3,p.products_image4,p.products_option_note_".$_SESSION['lang'].",p.products_visible,p.products_taxable,p.products_tax,p.products_date_added,p.products_qt, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
                                              FROM products as p
                                              LEFT JOIN specials as s
                                              ON (p.products_id = s.products_id)
                                              WHERE p.products_id = '".$check[0]."'");
                        $row = mysql_fetch_array($query);
if($check[1]!=="0") {
                        print "</tr><tr>";
                        // Ref/article
                        if(!empty($check[6])) {
                        	$_optZ = explode("|",$check[6]);
							## session update option price
							$lastArray = $_optZ[count($_optZ)-1];
							if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
							$_optZ = implode("|",$_optZ);
							$q = "<br><span class='fontrouge'><i>".$_optZ."</i></span>";
						}
						else {
							$q="";
						}
                        print "<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br>".$row['products_name_'.$_SESSION['lang']]." ".$q;
                            // Afficher DEEE
                            if($row['products_deee']>0) {
                                $deee[] = $check[1]*$row['products_deee'];
                                $openDeee = "<br><i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
                                print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></a> </span>: ".$row['products_deee']." ".$symbolDevise."</i>";
                            }
						      //if($row['products_download'] == "yes") {print "<br>".TELECHARGER."";}
                        print "<br><img src='im/zzz.gif' width='1' height='2'><br>";
						print "</td>";
                        // prix
                        $new_price = $row['specials_new_price'];
                        $old_price = $row['products_price'];
						if(empty($new_price)) {
							$price = $check[2];
						}
						else {
							if($row['specials_visible']=="yes") {
								$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
								$dateMaxCheck = explode("-",$row['specials_last_day']);
								$dateMax = mktime(0,0,0,$dateMaxCheck[1],$dateMaxCheck[2],$dateMaxCheck[0]);
								$dateDebutCheck = explode("-",$row['specials_first_day']);
								$dateDebut = mktime(0,0,0,$dateDebutCheck[1],$dateDebutCheck[2],$dateDebutCheck[0]);
								
								if($dateDebut <= $today  AND $dateMax >= $today) {
									$price = $check[2];
								}
								else {
									$price = $check[2];
								}
							}
							else {
								$price = $check[2];
							}
						}
                                 
                      // Affichage Prix
                      $tutu = tax_price($_country,$row['products_tax']);
                      if($row['products_taxable']=="yes") {

                           if($taxePosition=="Tax included") {
                              $priceTTC = $price * $check[1];
                              $totalht = $priceTTC*100/($tutu[0]+100);
                              $total_taxe = $totalht * ($tutu[0]/100);
                              $price_display = $priceTTC;
                              $priceTTCDeee = $check[7] * $check[1];
                              $totalhtDeee = $priceTTCDeee*100/($tutu[0]+100);
                           }
                           if($taxePosition=="Plus tax") {
                              $totalht = $price* $check[1];
                              $priceTTC = $totalht + ($totalht* ($tutu[0]/100));
                              $total_taxe = $totalht * ($tutu[0]/100);
                              $price_display = $totalht;
                              $totalhtDeee = $check[7] * $check[1];
                              $priceTTCDeee = $totalhtDeee + ($totalhtDeee* ($tutu[0]/100));
                           }
                           if($taxePosition=="No tax") {
                              $priceTTC = $price * $check[1];
                              $totalht = $priceTTC;
                              $total_taxe = 0;
                              $price_display = $priceTTC;
                              $priceTTCDeee = $check[7] * $check[1];
                              $totalhtDeee = $priceTTCDeee;
                           }
                        }
                        else {
                           $priceTTC = ($price * $check[1]);
                           $totalht = $priceTTC;
                           $total_taxe = 0;
                           $price_display = $priceTTC;
                           $tutu[0] = 0;
                           $priceTTCDeee = $check[7] * $check[1];
                           $totalhtDeee = $check[7] * $check[1];
                        }

                        if($p['countries_product_tax_active']=="no") {
                           $total_taxe = 0;
                        }
                        
                        $_price[] = $price_display;

                        if($p['countries_product_tax_active'] == "yes") $products_tax = $tutu[0]; else $products_tax = "0";
                        
                        // Isoler les articles soumis à la remise des coupons
                         if(!in_array($row['products_id'], $_SESSION['getPromoId'])) {
                            if(isset($_SESSION['coupon_items'])) {
                                 if($_SESSION['coupon_items']!=="") {
                                    $productsId = explode("|",$_SESSION['coupon_items']);
                                    if(in_array($row['products_id'], $productsId)) {
                                       $couponTTC[] = $priceTTC;
                                       $couponHT[] = $totalht;
                                    }
                                    else {
                                       $couponTTC[] = 0;
                                       $couponHT[] = 0;
                                    }
                                 }
                                 else {
                                    $couponTTC[] = $priceTTC;
                                    $couponHT[] = $totalht;
                                 }
                            }
                            else {
                                   $couponTTC[] = 0;
                                   $couponHT[] = 0;
                            }
                         }
                         else {
                            $couponTTC[] = 0;
                            $couponHT[] = 0;
                         }

                        // Poids
                        $p_ = $row['products_weight'];
                        if($row['products_download'] == "yes") {$p_=0;}
                        $poidsOptionsArray = explode('|',$check[8]);
                        $poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
                        $poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
                        $_SESSION['poids'] = sprintf("%0.2f",array_sum($poid));

                        // Frais d'emballage
                        $emballage[] = $check[1] * $row['products_sup_shipping'];
                        
                        // quantite
                        print "<td width='50' align='center'>".$check[1]."</td>";

                        // PU
                        $priceU = ($price==0)? "--" : sprintf("%0.2f",$price);
                        $priceU = ($taxePosition=="Tax included")? $price/(1+$check[5]/100) : $price;
                        print "<td align='center'>".sprintf("%0.2f",$priceU)."</td>";
                        
                        // Taxe
                        if($row['products_tax'] !=="0.00" AND $p['countries_product_tax'] !== $row['products_tax']) {
            				if($p['countries_product_tax'] == "0.00") { $pTax="0.00";} else { $pTax = $row['products_tax'];}
                        }
						else {
							$pTax = $p['countries_product_tax']; 
						}
                        
                        if($row['products_tax'] == "0.00") $taxeItemOri = $tutu[1]; else $taxeItemOri = $row['products_tax'];
                        if($row['products_taxable'] == "no") $taxeItemOri = 0;
                        $taxeItemOrigin[] = $taxeItemOri;

                        if($row['products_taxable'] == "no") {$pTax="0.00";}
                        if($taxePosition=="No tax") {$pTax="0.00";}
                        
                        // TYVA intracommunautaire
                        ########- 1
  						if(isset($_SESSION['account']) AND $tvaManuelValidation=="oui") {
                              $tvaValidationQueryZuu = mysql_query("SELECT users_pro_tva_confirm, users_pro_tva FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
                              $tvaValidationZuu = mysql_fetch_array($tvaValidationQueryZuu);
                              if($tvaValidationZuu['users_pro_tva']!=="" AND $tvaValidationZuu['users_pro_tva_confirm']=="yes") {
                                    $_SESSION['clientTVA'] = $tvaValidationZuu['users_pro_tva'];
                              }
                              else {
                                    if($tvaValidationZuu['users_pro_tva']!=="") {
                                        $_SESSION['clientTVA']="";
                                        if($tvaValidationZuu['users_pro_tva_confirm']=="??") $tvaMessage = NO_TVA." ".EN_ATTENTE_DE_VALIDATION;
                                    }
                              }
                        }
                        ########- 2
                        if(isset($_SESSION['account']) AND $tvaManuelValidation=="non") {
                              $tvaValidationQueryZuu = mysql_query("SELECT users_pro_tva_confirm, users_pro_tva FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
                              $tvaValidationZuu = mysql_fetch_array($tvaValidationQueryZuu);
                              if($tvaValidationZuu['users_pro_tva']!=="" AND $tvaValidationZuu['users_pro_tva_confirm']!=="no") {
                                    $_SESSION['clientTVA'] = $tvaValidationZuu['users_pro_tva'];
                              }
                        }

                        // Zone taxable avec tva intracomnunaitaire
                        $nc = array($iso, "NSA");
                        if(isset($_SESSION['clientTVA']) AND $_SESSION['clientTVA']!=="" AND !in_array($p['iso'],$nc)) {$pTax="0.00";}
                        if(isset($proNumber) AND $proNumber!=="" AND !in_array($p['iso'],$nc)) {$pTax="0.00";}
                        //if(isset($_SESSION['clientTVA']) AND !empty($_SESSION['clientTVA']) AND $p['iso'] !== $iso) {$pTax="0.00";}
                        
                        if($pTax=="0.00") $pTaxDisplay = "--"; else $pTaxDisplay = sprintf("%0.2f",$pTax)." %";
                        if($taxePosition!=="No tax") {print "<td width='50' align='center'>".$pTaxDisplay."</td>";}
                        
                        $taxeItem[]=$pTax;
                        $itemId[] = $row['products_id'];
                        
        				// Exoneration de la remise sur les cheques cadeaux + DEEE
                        if($row['products_ref'] == "GC100") {
                            if($taxePosition == "Tax included") {
                                $priceHtCadeau[] = DisplayProductPrice($iso,$row['products_tax'],$priceTTC);
                                $priceTTCCadeau[] = $priceTTC;
                                $priceHtDeee1[] = DisplayProductPrice($iso,$row['products_tax'],$priceTTCDeee);
                                $priceTTCDeee1[] = $priceTTCDeee;
                            }
                            else {
                                $priceHtCadeau[] = DisplayProductPrice($iso,$row['products_tax'],$totalht);
                                $priceTTCCadeau[] = $priceTTC;
                                $priceHtDeee1[] = DisplayProductPrice($iso,$row['products_tax'],$totalhtDeee);
                                $priceTTCDeee1[] = $priceTTCDeee;
                            }
                        }
                        else {
                            $priceHtCadeau[] = 0;
                            $priceTTCCadeau[] = 0;
                            $priceHtDeee1[] = $totalhtDeee;
                            $priceTTCDeee1[] = $priceTTCDeee;
                        }

                        // Prix total HT
                        if($taxePosition=="Tax included") $priceZ=$price_display/(1+$check[5]/100); else $priceZ=$price_display;
                        $priceDisplayedOnTable[] = $priceZ;
                        print "<td width='80' align='right'>".sprintf("%0.2f",$priceZ)."</td>";
                        
                        $totalTaxeArray[] = $total_taxe;
                        $totalHtArray[] = $totalht;

                        $totalTTCArray[] = $priceTTC;
                        
                        $totalTaxeFinal = array_sum($totalTaxeArray);
                        $_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxeFinal);

                        $totalTTCFinal = array_sum($totalTTCArray);
                        
                        // Délai d'expédition
                        //-------------------
                        ##print_r($_SESSION['list']);
                        ##print_r($check[6]);
                        
                        if(!empty($check[6])) {
                        	$_optZ = explode("|",$check[6]);
							## session update option price
							$lastArray = $_optZ[count($_optZ)-1];
							if(preg_match("#epz$#", $lastArray) AND is_numeric(substr($lastArray,0,-3))) unset($_optZ[count($_optZ)-1]);
							$_optZ = implode(" | ",$_optZ);
							$optQuery = mysql_query("SELECT products_options_stock_stock 
														FROM products_options_stock 
														WHERE products_options_stock_prod_id = '".$row['products_id']."' 
														AND products_options_stock_prod_name = '".$_optZ."'
													");
							if(mysql_num_rows($optQuery)>0) {
								$optResult = mysql_fetch_array($optQuery);
								$stockOption = $optResult['products_options_stock_stock'];
							}
						}
                        
                        if(isset($stockOption) AND count($stockOption)>0) {
	                        if($stockOption>0) {
	                        	$expDelay[] = $row['products_delay_1']."|".$row['products_delay_2'];
	                        }
	                        else {
								if($actRes=="oui") {
									$expDelay[] = $row['products_delay_1b']."|".$row['products_delay_2b'];
								}
								else {
									$expDelay[] = $row['products_delay_1a']."|".$row['products_delay_2a'];
								}
							}
						}
                        else {
	                        if($row['products_qt']>0) {
	                        	$expDelay[] = $row['products_delay_1']."|".$row['products_delay_2'];
	                        }
	                        else {
								if($actRes=="oui") {
									$expDelay[] = $row['products_delay_1b']."|".$row['products_delay_2b'];
								}
								else {
									$expDelay[] = $row['products_delay_1a']."|".$row['products_delay_2a'];
								}
							}
						}
						if($row['products_download'] == "yes") $expDelay[] = "0|0";

                        // actualiser variable de session list
                        $list2[] = $row['products_id']."+".
                                   $check[1]."+".
                                   $price."+".
                                   $check[3]."+".
                                   $row['products_name_'.$_SESSION['lang']]."+".
                                   $pTax."+".
                                   $check[6]."+".
                                   $check[7]."+".
                                   $check[8];
    	}
}
//--------------------------
// end loop between products
//--------------------------
print "</tr></table>";









                        $_SESSION['totalHtFinal'] = sprintf("%0.2f",array_sum($totalHtArray));








                        // Calculer deee
                        if(isset($deee)) {
                            $ecoRaxAmount = sprintf("%0.2f",array_sum($deee));
                            if($ecoRaxAmount>0) {$_SESSION['deee'] = $ecoRaxAmount;} else {$_SESSION['deee'] = sprintf("%0.2f",0);}
                        }
                        else {
                            $_SESSION['deee']=0;
                        }
                        // Calculer montant avant remise
                        if($remiseOnTax == "TTC") {
                            $totalPriceTTCCadeau = array_sum($priceTTCCadeau);
                            $totalPriceTTCDeee = array_sum($priceTTCDeee1);
                            $_SESSION['ff'] = $totalTTCFinal-$totalPriceTTCCadeau-$totalPriceTTCDeee;
                            $amountCoupon = array_sum($couponTTC);
                        }
                        else {
                            $totalPriceHtCadeau = array_sum($priceHtCadeau);
                            $totalPriceHtDeee = array_sum($priceHtDeee1);
                            $_SESSION['ff'] = $_SESSION['totalHtFinal']-$totalPriceHtCadeau-$totalPriceHtDeee;
                            $amountCoupon = array_sum($couponHT);
                        }
                        
// Calculer livraison
                        $ola = shipping_price($iso,$_country,$_SESSION['poids'],$activerPromoLivraison,$_SESSION['totalHtFinal'],$livraisonComprise,$_livraisonId);
                        $shipFree = $ola[3];

                        if($activerPromoLivraison == "oui" AND $_SESSION['ff']>=$livraisonComprise AND $shipFree=="yes") {
                            $_SESSION['shipPrice'] = sprintf("%0.2f",0);
                            $_SESSION['livraisonhors'] = sprintf("%0.2f",0);
                            $_SESSION['shipTax'] = sprintf("%0.2f",0);
                            $gratos = "yes";
                            $gratoPack = "yes";
                        }
                        else {
                            $_SESSION['shipPrice'] = $ola[0];
                            $_SESSION['livraisonhors'] = $ola[1];
                            $_SESSION['shipTax'] = $ola[2];
                            $gratos = "no";
                            $gratoPack = "no";
                        }

                        $_SESSION['users_shipping_price'] = sprintf("%0.2f",$_SESSION['livraisonhors']+$_SESSION['shipTax']);
                        $totht = $_SESSION['totalHtFinal']+$_SESSION['livraisonhors'];
                        $_SESSION['totalTax'] = $totalTaxeFinal;
                        $_SESSION['totalTax'] = sprintf("%0.2f",$_SESSION['totalTax']);
                        $totttc = $totht+$_SESSION['totalTax']+$_SESSION['shipTax'];
                        $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);

// Calculer frais d'emballage
$totalFraisEmballage = sprintf("%0.2f",array_sum($emballage));

if($totalFraisEmballage > 0 AND $gratoPack == "no" AND $gratos == "no") {
   if($taxePosition=="Tax included") {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",$_SESSION['priceEmballageTTC']*100/($ola[4]+100));
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",$_SESSION['totalEmballageHt'] * ($ola[4]/100));
   }
   if($taxePosition=="Plus tax") {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",$totalFraisEmballage + ($totalFraisEmballage* ($ola[4]/100)));
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",$totalFraisEmballage* ($ola[4]/100));
   }
   if($taxePosition=="No tax") {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",$totalFraisEmballage);
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",0);
   }
}
else {
        $_SESSION['priceEmballageTTC'] = sprintf("%0.2f",0);
        $_SESSION['totalEmballageHt'] = sprintf("%0.2f",0);
        $_SESSION['totalEmballageTva'] = sprintf("%0.2f",0);
}

// Calculer remise
                if($activerRemise == "oui" AND $_SESSION['ff']>=$remiseOrderMax) {
                	   $_SESSION['users_remise_note'] = strtolower(REMISE)." | Seuil <b>".$remiseOrderMax.$symbolDevise."</b> | (-".$remise.$remiseType.")";
                       if($remiseType == "%") {$_SESSION['montantRemise'] = $_SESSION['ff']*($remise/100);}
                       if($remiseType == $symbolDevise) {$_SESSION['montantRemise'] = $remise;}
                          $_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
                          $discount[] = $_SESSION['montantRemise'];
                }
                else {
                        $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
                        $_SESSION['montantRemise'] = sprintf("%0.2f",0);
                        $discount[] = 0;
                }

// Calculer remise sur commandes precedentes
          if($activerRemisePastOrder=="oui" AND isset($_SESSION['account']) AND !isset($_SESSION['accountRemise'])) {
               $accountRequest = mysql_query("SELECT users_products_ht, users_account_remise_app
                                                FROM users_orders
                                                WHERE users_password = '".$_SESSION['account']."'
                                                AND users_confirm = 'yes'
                                                AND users_payed = 'yes'
                                                AND users_nic NOT LIKE 'TERUG%'
                                                AND TO_DAYS(users_date_payed) >= TO_DAYS('".$dateActivationPdf."')
                                                ORDER BY users_date_payed
                                                ASC");
function extract_pastPdf() {
   GLOBAL $accountProp;
   if($accountProp['users_account_remise_note']!=="" AND $accountProp['users_account_remise_app']>0 AND preg_match("/\b".strtolower(REMISE_SUR_COMMANDES)."\b/i", $accountProp['users_account_remise_note'])) {
      if(preg_match( '!\(([^\)]+)\)!', $accountProp['users_account_remise_note'], $match))
      $toRemove = array(" ","-","%");
      $e = str_replace($toRemove,"",$match[1]);
   }
   else {
      $e = 1;
   }
   return $e;
}
               $accountRequestNum = mysql_num_rows($accountRequest);
               if($accountRequestNum>0) {
         	   	while($accountProp = mysql_fetch_array($accountRequest)) {
            			  $totalPurchase[] = $accountProp['users_products_ht'];
            			  $totalPurchaseRemiseUsed[] = $accountProp['users_account_remise_app']/(extract_pastPdf()/100);
      		      }
      		          $totalPurchaseRemiseUsed[0] = 0;
                      $TotalPurchaseSum = array_sum($totalPurchase);
                      $TotalPurchaseSumRemiseUsed = array_sum($totalPurchaseRemiseUsed);
                      $TotalPurchaseSumRemise = $TotalPurchaseSum*$remisePastOrder/100;
                      $pointRest = $TotalPurchaseSum-$TotalPurchaseSumRemiseUsed;
                      $remiseRest = $pointRest*$remisePastOrder/100;
            		}
            		else {
                      $remiseRest = 0;
                  }
      		   if(!isset($_SESSION['accountRemiseActive'])) $_SESSION['accountRemise'] = sprintf("%0.2f",$remiseRest);
          }

                if(isset($_SESSION['openAccount']) and isset($_SESSION['accountRemise']) AND $_SESSION['accountRemise'] > 0) {
	                    if($_SESSION['ff'] >= $_SESSION['accountRemise']) {
                            $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$_SESSION['accountRemise']);
                            $discount[] = $_SESSION['accountRemise'];
						 }
						 else {
                            $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",0);
                            $discount[] = 0;
						 }
                            $_SESSION['users_account_remise_note'] = strtolower(REMISE_SUR_COMMANDES)." | - <b>".$_SESSION['accountRemiseEffec'].$symbolDevise."</b> | (-".$remisePastOrder."%)";
              	}

// Calculer coupon de réduction
                if(isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon']==1) {
                    $query = mysql_query("SELECT code_promo_seuil, code_promo_reduction, code_promo_type, code_promo FROM code_promo WHERE code_promo = '".$_SESSION['coupon_name']."'");
                    $result = mysql_fetch_array($query);
                    $seuilPromoCode = $result['code_promo_seuil'];
	               		
	              if(isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon'] == 1 AND $_SESSION['ff'] >= $seuilPromoCode) {
                      $remiseCoupon = $result['code_promo_reduction'];
                      $remiseCouponType = $result['code_promo_type'];
                      if($remiseCouponType == "%") $_SESSION['montantRemise2'] = sprintf("%0.2f",$amountCoupon*($remiseCoupon/100));
                      if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = sprintf("%0.2f",$remiseCoupon);
                      if($amountCoupon==0) $_SESSION['montantRemise2'] = sprintf("%0.2f",0);
                      $discount[] = $_SESSION['montantRemise2'];
                      $_SESSION['users_coupon_note'] = "Coupon ".$result['code_promo']." | Seuil <b>".$result['code_promo_seuil']."".$symbolDevise."</b> | (-".$remiseCoupon."".$remiseCouponType.")"; 
	              }
	              else {
                      $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
                      $_SESSION['montantRemise2'] = sprintf("%0.2f",0);
                      $discount[] = 0;
	              }
                }

// Calculer cheque cadeau
                if(isset($_SESSION['activerCadeau']) AND $_SESSION['activerCadeau'] == 1) {
                	$query = mysql_query("SELECT gc_amount, gc_number FROM gc WHERE gc_number = '".$_SESSION['cadeau_number']."'") or die (mysql_error());
               		$result = mysql_fetch_array($query);
    				$remiseCadeau = $result['gc_amount'];
    				$_SESSION['montantRemise3'] = sprintf("%0.2f",$remiseCadeau);
    				$tutuGc = tax_price($_country,0);
				}
// Calcul total des remises
                $totalDiscount = array_sum($discount);
                
// Calcul taxes
	if($p['countries_product_tax_active'] == "yes") {
				$_priceNb = count($_price)-1;
            for($i=0; $i<=$_priceNb; $i++) {

    			 if($totalDiscount==0) {
                        if($taxePosition=="Tax included") {
                            $totalTaxe1[] =  $totalHtArray[$i]*$taxeItem[$i]/100;
                        }
                        else {
                            $totalTaxe1[] = $_price[$i]*$taxeItem[$i]/100;
                        }
                  }
                  else {
                    if($taxePosition=="Plus tax" OR $taxePosition=="No tax") {
                        if($remiseOnTax == "TTC") {
                            /*
                            $prixHtFinalTTC = $_price[$i]+($_price[$i]*($taxeItem[$i]/100));
                            print $totalTTCFinal;
                            $ratio = sprintf("%0.1f",$prixHtFinalTTC/$totalTTCFinal);
                            //print $ratio;
                            $totalTaxe1[] = (($totalTTCFinal-($totalDiscount))*$ratio)*($taxeItem[$i]/100);
                            print_r($totalTaxe1);
                            */
                            $prixHtFinalTTC = $_price[$i]+($_price[$i]*($taxeItem[$i]/100));
                            $pCent = sprintf("%0.2f",$_price[$i]/$_SESSION['totalHtFinal']*100);
                            $montantReductionProduct = $totalDiscount*$pCent/100;
                            $htTotalToPay = ($prixHtFinalTTC-$montantReductionProduct)/(1+$taxeItem[$i]/100);
                            $totalTaxe1[] = (($htTotalToPay))*($taxeItem[$i]/100);
                            $htTotalToPay1[] = $htTotalToPay;
                            /*
                            $s = $_price[$i];
                            print "HT final: ".$s;
                            $t = $s*($taxeItem[$i]/100);
                            print "<br>Taxe ".$taxeItem[$i]."%: ".$t;
                            $_tt = $s+$t;
                            print "<br>Total TTC avant remise: ".$_tt;
                            $_ttt = $_tt-$montantReductionProduct;
                            print "<br>Total aprés remise: ".$_ttt;
                            print "<br>---------------------<br>";
                            print $prixHtFinalTTC;
                            */
                        }
                        else {
                            $ratio = $_price[$i]/$_SESSION['totalHtFinal'];
                            $totalTaxe1[] = (($_SESSION['totalHtFinal']-$totalDiscount)*$ratio)*($taxeItem[$i]/100);
                        }
                    }
                    if($taxePosition=="Tax included") {
                        if($remiseOnTax == "TTC") {
                            /*
                            $prixHtFinal = $_price[$i]/(1+$taxeItem[$i]/100);
                            $ratio = sprintf("%0.1f",$prixHtFinal/$_SESSION['totalHtFinal']);
                            $totalTaxe1[] = (($totalTTCFinal-$totalDiscount)*$ratio)*($taxeItem[$i]/100);
                            $htTotalToPay[] = ($totalTTCFinal-$totalDiscount)/(1+$taxeItem[$i]/100);
                            */
                            $pCent = sprintf("%0.2f",$_price[$i]/$totalTTCFinal*100);
                            $montantReductionProduct = $totalDiscount*$pCent/100;
                            $prixHtFinal = ($_price[$i]-$montantReductionProduct)/(1+$taxeItemOrigin[$i]/100);
                            $htTotalToPay = ($totalTTCFinal-$totalDiscount)/(1+$taxeItem[$i]/100);
                            $totalTaxe1[] = $prixHtFinal*($taxeItem[$i]/100);
                            $htTotalToPay1[] = $prixHtFinal;
							/*
                            $s = ($_price[$i]-$montantReductionProduct)/(1+$taxeItem[$i]/100);
                            print "HT final: ".$s;
                            $t = $s*($taxeItem[$i]/100);
                            print "<br>Taxe ".$taxeItem[$i]."%: ".$t;
                            $_tt = $s+$t;
                            print "<br>Total: ".$_tt;
                            print "<br>".$totalTTCFinal;
                            print "<br>---------------------<br>";
                            */
                        }
                        else {
                           $prixHtFinal = $_price[$i]/(1+$taxeItem[$i]/100);
                           $ratio = $prixHtFinal/$_SESSION['totalHtFinal'];
                           $totalTaxe1[] = (($_SESSION['totalHtFinal']-$totalDiscount)*$ratio)*($taxeItem[$i]/100);
                    }
                  }
				}
					}
						$totalTaxe = array_sum($totalTaxe1);
	}
	else {
	   $totalTaxe = 0;
    }

                if(isset($htTotalToPay1)) {
                    $_SESSION['totalHtFinal'] = sprintf("%0.2f",array_sum($htTotalToPay1)); 
                    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalHtFinal']+$totalTaxe));
                }
                else {
                	$_SESSION['totalHtFinal'] = sprintf("%0.2f",($_SESSION['totalHtFinal']-$totalDiscount));
                    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalHtFinal']+$totalTaxe));
                }
                
				$_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxe);












/*-------------
AFFICHER TOTAUX
-------------*/
                print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
                print "<td>";
// Afficher mode de livraison
$whatShipFact = mysql_query("SELECT livraison_delay_1, livraison_delay_2, livraison_nom_".$_SESSION['lang']." 
								FROM ship_mode 
								WHERE livraison_id='".$_livraisonId."'") or die (mysql_error());
if(mysql_num_rows($whatShipFact) > 0) {
    $resultWhatShipFact = mysql_fetch_array($whatShipFact);
    $_shippingName = $resultWhatShipFact['livraison_nom_'.$_SESSION['lang']];
    $_shippingDelay1 = $resultWhatShipFact['livraison_delay_1'];
    $_shippingDelay2 = $resultWhatShipFact['livraison_delay_2'];
}
else {
    $_shippingName = 'Download';
    $_shippingDelay1 = 0;
    $_shippingDelay2 = 0;
}

// Afficher mode de livraison
print '<br>'.MODE_DE_LIVRAISON.': <b><span class="TABLE1 fontrouge" style="padding:3px;">'.$_shippingName.'</span></b>';

// Afficher livraison
            if($gratos=="yes") {
					$shipPriceDisplay = "<span class='fontrouge'>".strtolower(LIVRAISON_GRATUITE)." (<a href='".$_field."voorwaarden.php' target='_blank'>*</a>)</span>";
					$_SESSION['users_shipping_note'] = strtolower(LIVRAISON_GRATUITE)." | Seuil <b>".$livraisonComprise."".$symbolDevise."</b>";
				}
				else {
					//$shipPriceDisplay = "<b>".sprintf("%0.5f",$_SESSION['shipPrice'])."</b> $symbolDevise/gr HT";
					$shipPriceDisplay='';
				}
                print "<br><br>";
                //print TOTAL_POIDS.": <b>".$_SESSION['poids']."</b> gr<br>".$shipPriceDisplay;
                if($mlFact!=='') {
                  print "<div style='color:#00CC00'><i>".$mlFact."</i></div>";
                }
                if($shipPriceDisplay!=="") print "<br>".$shipPriceDisplay;
                print "<br><br>";
                print "</td>";
                print "</tr></table>";

                print "<table border='0' width='300' align='right' cellspacing='0' cellpadding='1'><tr>";
// Afficher prix HT avant remise
                if(isset($totalDiscount) AND $totalDiscount>0) {
                $totalPriceDisplayedOnTable = sprintf("%0.2f",array_sum($priceDisplayedOnTable));
                ($taxePosition=="No tax")? print "<td align='left'>".SOUS_TOTAL."</td>" : print "<td align='left'>".TOTAL." ".HT." ".AVANT_REMISE."</td>";
                print "<td align='right'>".$totalPriceDisplayedOnTable."</td>";
                print "</tr><tr>";
                }

// Afficher remise
                if(isset($_SESSION['montantRemise']) AND $_SESSION['montantRemise'] > 0) {
                    print "<td align='left'>".REMISE." (-$remise$remiseType)</td>";
                    print "<td align='right'>";
                    print "<span class='fontrouge'><b>".$_SESSION['montantRemise']."</b></span>";
                    print "</td>";
                    print "</tr><tr>";
                }
// Afficher remise sur commandes precedentes
                if(isset($_SESSION['accountRemiseEffec']) AND $_SESSION['accountRemiseEffec'] > 0) {
    				     print "<td align='left'>".REMISE_SUR_COMMANDES."</td>";
    				     print "<td align='right'>";
                    print "<span class='fontrouge'><b>".$_SESSION['accountRemiseEffec']."</b></span>";
                    print "</td>";
                    print "</tr><tr>";
              	}
// Afficher coupon
                if(isset($_SESSION['montantRemise2']) AND $_SESSION['montantRemise2'] > 0) {
	              if(isset($_SESSION['activerCoupon']) AND $_SESSION['activerCoupon'] == 1 AND $_SESSION['ff'] >= $seuilPromoCode) {
                    print "<td align='left'>";
                    print REMISE." (-".$remiseCoupon."".$remiseCouponType.")<br>";
                    print "<i>Coupon: ".$result['code_promo']."</i>";
                    print "</td>";
                    print "<td align='right'>";
                    print "<span class='fontrouge'><b>".$_SESSION['montantRemise2']."</b></span>";
                    print "</td>";
                    print "</tr><tr>";
	              }
                }
// Afficher prix HT final aprés remise
                ## $_totalHtFinal = sprintf("%0.2f",$_SESSION['totalHtFinal']-$totalDiscount);
                ##$_totalHtFinal = sprintf("%0.2f",$_SESSION['totalHtFinal']);
                ($taxePosition=="No tax")? print "<td align='left'><br>".TOTAL."</td>" : print "<td align='left'><br>".TOTAL." ".HT."</td>";
                print "<td align='right'><br><b>".$_SESSION['totalHtFinal']."</b></td>";
                print "</tr><tr>";

// Afficher taxe article
                if(!isset($totalTaxe1)) $totalTaxe1=0;
                print tax_details($taxeItem,$totalTaxe1);

// Afficher frais de livraison
                $_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['livraisonhors'];
                print "<td colspan='2' align='left'><img src='im/zzz.gif' width='1' height='3'></td>";
                print "</tr><tr>";
                print "<td align='left'>".LIVRAISON."</td>";
                print "<td align='right'>".$_SESSION['livraisonhors']."</td>";
                print "</tr><tr>";

// Afficher taxe livraison
                $_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['shipTax'];
                if($taxePosition!=="No tax") {
                    print "<td align='left'>".strtoupper($taxeName)." ".$p['countries_shipping_tax']."% ".strtolower(LIV)."</td>";
                    print "<td align='right'>".$_SESSION['shipTax']."</td>";
                    print "</tr><tr>";
                }
// Afficher frais d'emballage
if($_SESSION['priceEmballageTTC']>0) {
                $_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['totalEmballageHt'];
                print "<td colspan='2' align='left'><img src='im/zzz.gif' width='1' height='3'></td>";
                print "</tr><tr>";
                print "<td align='left'>".PAKING_COST."</td>";
                print "<td align='right'>".$_SESSION['totalEmballageHt']."</td>";
                print "</tr><tr>";

// Afficher taxe d'emballage
                $_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['totalEmballageTva'];
                if($taxePosition!=="No tax") {
                    print "<td align='left'>".strtoupper($taxeName)." ".$ola[4]."%</td>";
                    print "<td align='right'>".$_SESSION['totalEmballageTva']."</td>";
                    print "</tr><tr>";
                }
}

// Afficher contre remboursement
                if(isset($_SESSION['contre']) and $_SESSION['contre'] == 1) {
                      $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalToPayTTC']+$seuilContre));
                      print "<td colspan='2' align='left'><img src='im/zzz.gif' width='1' height='3'></td>";
                      print "</tr><tr>";
                      print "<td align='left'>".CONTRE_REMBOURSEMENT."</td>";  
                      print "<td align='right'>".sprintf("%0.2f",$seuilContre)."</td>";					                
                      print "</tr><tr>";
                      print "<td align='left' colspan='2'><hr width='100%'></td>";
                      print "</tr><tr>";
			    }
			    
// Afficher total taxe
                $totalTax1 = sprintf("%0.2f",$_SESSION['itemTax'] + $_SESSION['shipTax'] + $_SESSION['totalEmballageTva']);
                if($taxePosition!=="No tax") {
                    print "<td align='left' colspan='2'><hr width='100%'></td>";
                    print "</tr><tr>";
                    print "<td align='left'>".TOTAL." ".strtoupper($taxeName)."</td>";
                    print "<td align='right'>".$totalTax1."</td>";
                    print "</tr><tr>";
                }
                print "<td align='left' colspan='2'><hr width='100%'></td>";
                print "</tr><tr>";
                
// Afficher cheque cadeau
                if(isset($_SESSION['montantRemise3']) and $_SESSION['montantRemise3'] > 0) {
                    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalToPayTTC']-$_SESSION['montantRemise3']));
                    print "<td align='left'>";
                    print CHEQUE_CADEAU_MIN."<br><i>N&deg; ".$result['gc_number']."</i>";
                    print "</td>";
                    print "<td align='right'>";
                    print "<b><span class='fontrouge'>(";
                    print sprintf("%0.2f",$_SESSION['montantRemise3']);
                    print ")</span></b>";
                    print "</td>";
                    print "</tr><tr>";
                    print "<td align='left' colspan='2'><img src='im/zzz.gif' width='1' height='5'></td>";
                    print "</tr><tr>";
				}

// afficher total to pay
                print "<td align='left'>".MONTANT_A_PAYER."</td>";
                print "<td align='right'>";
                print "<b>".sprintf("%0.2f",$_SESSION['totalToPayTTC'])." ".$symbolDevise."</b>";
                // Afficher devise 2
                if($devise2Visible=="oui" AND $_SESSION['totalToPayTTC']>0) {
                    $currency = sprintf("%0.2f",$_SESSION['totalToPayTTC']*$tauxDevise2)."&nbsp;".$symbolDevise2;
                    print "<div title='".A_TITRE_INDICATF."' class='FontGris tiny' align='right'><i>[".$currency."]</i></div>";
                }
                print "</td>";
                print "</tr>";
                print "</table>";
                print "</td></tr></table>";


//---------------------------
// Afficher date de livraison

if($displayDelivery=="oui") {
	if($_shippingDelay1!=="100" AND $_shippingName!=="Download") {
		$delivDelay[0]=0;
		$delivDelay[1]=0;
		foreach($expDelay AS $items) {
			$delivDelay = explode("|", $items);
			$delivDelayDate1[] = $delivDelay[0];
			$delivDelayDate2[] = $delivDelay[1];
		}
		sort($delivDelayDate1);
		sort($delivDelayDate2);
		$delivDelay1 = end($delivDelayDate1);
		$delivDelay2 = end($delivDelayDate2);
		$shipDate1 = $delivDelay1+$_shippingDelay1;
		$shipDate2 = $delivDelay2+$_shippingDelay2;


		
		$date1 = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$shipDate1, date("Y")));
		$date2 = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")+$shipDate2, date("Y")));
		// Ajuster weekends
		$jourSemaine1 = date("l", mktime(0, 0, 0, date("m"), date("d")+$shipDate1, date("Y")));
		$jourSemaine2 = date("l", mktime(0, 0, 0, date("m"), date("d")+$shipDate2, date("Y")));
		if($jourSemaine1=="Saturday") $date1 = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$shipDate1+2, date("Y")));
		if($jourSemaine2=="Saturday") $date2 = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$shipDate2+2, date("Y")));
		if($jourSemaine1=="Sunday") $date1 = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$shipDate1+1, date("Y")));
		if($jourSemaine2=="Sunday") $date2 = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$shipDate2+1, date("Y")));
		// Chercher jour de la semaine livraison
		$joursem[1] = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
		$joursem[2] = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
		$joursem[3] = array('Zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zatedag');
		$jourSemaine1Final1 = $joursem[$_SESSION['lang']][date("w", strtotime($date1))];
		$jourSemaine1Final2 = $joursem[$_SESSION['lang']][date("w", strtotime($date2))];
		

		// Délai d'expédition
		if($delivDelay1==0 AND $delivDelay2>$delivDelay1) {
			$delivMax = $delivDelay2*24;
			$delivDisplay = SOUS." ".$delivMax."H.";
		}
		else {
			$delivDisplay = ENTRE." ".$delivDelay1." ".ET." ".$delivDelay2." ".JOURS_OUVRES;
		}
		
		// Délai de livraison
		if($shipDate2==$shipDate1) {
			$shipMax = $shipDate2*24;
			$shipDisplay = SOUS." ".$shipMax."H.";
		}
		else {
			$shipDisplay = ENTRE." ".$shipDate1." ".ET." ".$shipDate2." ".JOURS_OUVRES;
		}

		print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
		
		print "<table width='500' cellpadding='3' class='".$_style."' cellspacing='0' border='0' align='center'><tr>";
		print "<td>";
		print "<u><b>".INFOS_LIVRAISON."</b></u>:";
		print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
		print DELAI_EXPEDITION.": ".$delivDisplay;
		print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
		print DELAI_LIVRAISON.": ".$shipDisplay;
		print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
		// Date de livraison
		print DATE_LIVRAISON." (".ESTIMATION."):  ".ENTRE_LE.$jourSemaine1Final1." ".dateFr($date1, $_SESSION['lang'])." ".ET_LE.$jourSemaine1Final2." ".dateFr($date2, $_SESSION['lang']).".";
		print "</td>";
		print "</tr></table>";
	}
	if($_shippingName=="Download") {
		print "<div><img src='im/zzz.gif' width='1' height='5'></div>";
		print "<table width='500' cellpadding='3' class='".$_style."' cellspacing='0' border='0' align='center'><tr>";
		print "<td class='FontGris' style='font-style:italic'>";
		print "<u><b>".INFOS_LIVRAISON."</b></u>:";
		print "<div><img src='im/zzz.gif' width='1' height='3'></div>";
		print DATE_LIVRAISON.": ".EN_TELECHARGEMENT_A_LA_CONFIRMATION_DU_PAIEMENT;
		print "</td></tr></table>";
	}
}
// Fin date de livraison
//----------------------

if(isset($_SESSION['recup']) AND $_SESSION['recup']=="yes") {
	if(preg_match('#berekenen.php#', $_SESSION['ActiveUrl'])) print "<p align='center'><input type='submit' value='".SAVE_THIS_ORDER."' style='padding:2px'></p>";
// --------
// End form
	print "</form>";
}

if(isset($tvaMessage) AND $tvaMessage!=="") print "<p align='center' style='color:#CC0000; font-size:12px;'><b>".$tvaMessage."</b></p>";
if(isset($updateOrderMessage) AND $updateOrderMessage!=="") print "<p align='center'><br><span style='background:#FFFFFF; border:#CCCCCC 1px solid; color:#CC0000; font-size:12px; padding:10px;'><b>".$updateOrderMessage."</b></span><br><br></p>";

// Actualiser variable de session list
$tototo = implode(",",$list2);
$_SESSION['list'] = $tototo;
}
else {
    print "panier vide !";
}
}
?>