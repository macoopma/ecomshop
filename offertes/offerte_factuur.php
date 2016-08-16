<?php
session_start();

include('../configuratie/configuratie.php');

if(isset($_POST['userInterface1'])) $_SESSION['userInterface'] = $_POST['userInterface1'];
if(empty($_SESSION['userInterface'])) $_SESSION['userInterface'] = $colorInter;
if(isset($_GET['lang'])) $_SESSION['lang'] = $_GET['lang'];
if(isset($_POST['lang1'])) $_SESSION['lang'] = $_POST['lang1'];
if(!isset($_SESSION['lang']) OR empty($_SESSION['lang'])) $_SESSION['lang'] = $langue;
if(isset($_SESSION['coupon_name'])) unset($_SESSION['coupon_name']);
if(isset($_SESSION['accountRemiseEffec'])) unset($_SESSION['accountRemiseEffec']);
if(isset($_SESSION['montantRemise'])) unset($_SESSION['montantRemise']);
if(isset($_SESSION['montantRemise2'])) unset($_SESSION['montantRemise2']);
      
include("../includes/lang/lang_".$_SESSION['lang'].".php");



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

 
function addToBdd($paymentMode,$pass,$nic) {
        global $_SESSION;
        include('../configuratie/configuratie.php');
        $dateNow = date("Y-m-d H:i:s");
        $query = mysql_query("SELECT users_password, users_nic
                              FROM users_orders
                              WHERE users_password = '".$pass."'
                              AND users_devis = '".$_SESSION['devisNumero']."'");
        $rows = mysql_num_rows($query);

            if($rows == 0) {
	            if(!isset($_SESSION['devisNumero'])) {$_devis = "";} else {$_devis = $_SESSION['devisNumero'];}
	            if(!isset($_SESSION['accountRemiseEffec'])) {$accountRemiseEffecBd = "0.00";} else {$accountRemiseEffecBd = $_SESSION['accountRemiseEffec'];}
	            if(!isset($_SESSION['coupon_name'])) {$couponN = "";} else {$couponN = $_SESSION['coupon_name'];}
	            if(!isset($_SESSION['montantRemise2'])) {$couponC = "0.00";} else {$couponC = $_SESSION['montantRemise2'];}
	            if(!isset($_SESSION['montantRemise'])) {$couponC1 = "0.00";} else {$couponC1 = $_SESSION['montantRemise'];}
	            if(!isset($_SESSION['users_coupon_note'])) {$users_coupon_note = "";} else {$users_coupon_note = $_SESSION['users_coupon_note'];}
	            if(!isset($_SESSION['users_remise_note'])) {$users_remise_note = "";} else {$users_remise_note =$_SESSION['users_remise_note'];}
	            if(!isset($_SESSION['users_account_remise_note'])) {$users_account_remise_note = "";} else {$users_account_remise_note =$_SESSION['users_account_remise_note'];}
	            if(!isset($_SESSION['users_shipping_note'])) {$users_shipping_note = "";} else {$users_shipping_note =$_SESSION['users_shipping_note'];}
	            if(!isset($_SESSION['cadeau_number'])) {$_gc = "";} else {$_gc = $_SESSION['cadeau_number'];}
	            if(!isset($_SESSION['montantRemise3'])) {$gcAmount = "0.00";} else {$gcAmount = $_SESSION['montantRemise3'];}
	            if(!isset($_SESSION['shippingId'])) $shippingId=""; else $shippingId=$_SESSION['shippingId'];
	            if(isset($_SESSION['multipleTax'])) {$mTax = $_SESSION['multipleTax'];} else {$mTax = "";}
	            if($_SERVER['REMOTE_ADDR']) {$ipAddress=$_SERVER['REMOTE_ADDR'];} else {$ipAddress='';}
	            if(!isset($_SESSION['affiliateNumber'])) {
	               $usersAffNumber = "";
	               $usersAffAmount = 0;
	            }
	            else {
	               $usersAffNumber = $_SESSION['affiliateNumber'];
	               $usersAffAmount = $_SESSION['totalHtFinal']*($_SESSION['affiliateCom']/100);
	            }
                  
                 
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

          		    mysql_query("INSERT INTO users_orders
                                (users_gender,users_firstname,users_lastname,users_company,
                                users_address,users_zip,users_city,
                                users_surburb,users_province,users_country,users_date_added,
                                users_email,users_telephone,users_fax,
                                users_password,users_nic,users_payment,users_symbol_devise,
                                users_products,users_products_weight,
                                users_products_weight_price,users_total_to_pay,users_shipping_price,
                                users_ship_ht,users_ship_tax,users_products_ht,
                                users_products_tax,users_products_tax_statut,users_confirm,users_payed,users_statut,users_comment,
                                users_remise,users_remise_coupon,users_remise_coupon_name,
                                users_lang,users_save_data_from_form,users_coupon_note,users_remise_note,
                                users_shipping_note,users_account_remise_note,users_account_remise_app,
                                users_facture_adresse,users_affiliate,users_affiliate_amount,
                                users_devis, users_gc, users_remise_gc,
                                users_multiple_tax, users_deee_ht, users_deee_tax,
                                users_sup_ttc, users_sup_ht, users_sup_tax,
                                users_ip, users_shipping)
                              VALUES
                              (
                                '".$_SESSION['clientGender']."','".$_SESSION['clientFirstname']."','".$_SESSION['clientLastname']."','".$_SESSION['clientCompany']."',
                                '".$_SESSION['clientStreetAddress']."','".$_SESSION['clientPostCode']."','".$_SESSION['clientCity']."',
                                '".$_SESSION['clientSurburb']."','".$_SESSION['clientProvince']."','".$_SESSION['clientCountry']."','".$dateNow."',
                                '".$_SESSION['clientEmail']."','".$_SESSION['clientTelephone']."','".$_SESSION['clientFax']."',
                                '".$pass."','".$nic."','".$paymentMode."','".$symbolDevise."',
                                '".$_SESSION['list']."','".$_SESSION['poids']."',
                                '".$_SESSION['shipPrice']."','".$_SESSION['totalToPayTTC']."','".$_SESSION['users_shipping_price']."',
                                '".$_SESSION['livraisonhors']."','".$_SESSION['shipTax']."','".$_SESSION['totalHtFinal']."',
                                '".$_SESSION['itemTax']."','".$_SESSION['taxStatut']."','".$_SESSION['users_confirm']."','".$_SESSION['users_payed']."','no','".$_SESSION['clientComment']."',
                                '".$couponC1."','".$couponC."','".$couponN."',
                                '".$_SESSION['lang']."','".$_SESSION['saveDataFromForm']."','".$users_coupon_note."','".$users_remise_note."',
                                '".$users_shipping_note."','".$users_account_remise_note."','".$accountRemiseEffecBd."',
                                '".$_SESSION['fact_adresse']."','".$usersAffNumber."','".$usersAffAmount."',
                                '".$_devis."', '".$_gc."', '".$gcAmount."',
                                '".$mTax."', '".$deeeHtFinal."', '".$deeeTaxFinal."',
                                '".$_SESSION['priceEmballageTTC']."', '".$_SESSION['totalEmballageHt']."', '".$_SESSION['totalEmballageTva']."',
                                '".$ipAddress."', '".$shippingId."'
                                )");

                  	
								$splitUp = explode(",",$_SESSION['list']);
								foreach ($splitUp as $item) {
					         		$check = explode("+",$item);
					         		if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} 
					         	}
		            $arrGc = array_sum($gc);
		            if($arrGc> 0) {
                            
                                    $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                                    $codeGc = '';
                                    for( $i = 0; $i < 12 ; $i++ ) {
                                        $codeGc .= substr($str1, rand(0, strlen($str1) - 1), 1);
                                    }
                            
                            $nextYear  = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
		                    
		                    mysql_query("INSERT INTO gc
		                                (gc_number,
		                                gc_start,
		                                gc_end,
		                                gc_nic,
		                                gc_amount
		                                )
		                                VALUES
		                                ('".$codeGc."',
		                                '".$dateNow."',
		                                '".$nextYear."',
		                                '".$_SESSION['clientNic']."',
		                                '".$arrGc."'
		                                )");
                    		mysql_query("UPDATE users_orders SET users_gc='".$codeGc."' WHERE users_nic = '".$_SESSION['clientNic']."'");
                    }
             }
}

function tax_price($_pays,$productTax) {
        GLOBAL $iso,$taxePosition;
         $_originQueryTax = mysql_query("SELECT * FROM countries WHERE iso = '".$iso."'");
         $_originTotoTax = mysql_fetch_array($_originQueryTax);
         $_originTax = $_originTotoTax['countries_product_tax'];
         if($productTax !== "0.00" and $_originTax !== $productTax) {$_originTaxFinal = $productTax;} else {$_originTaxFinal = $_originTax;}
         
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_tax = $_b['countries_product_tax'];
		 if($productTax !== "0.00" and $_tax !== $productTax) {$_taxFinal = $productTax;} else {$_taxFinal = $_tax;}
         
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


function tax_details($taxeItem,$totalTaxe1) {
    Global $taxeName,$taxePosition;
    $exist =array();
    $a = "";
    
        $taxeItemNb = count($taxeItem);
        for($uu=0; $uu<=$taxeItemNb-1; $uu++) {
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

function shipping_price($originIso,$_pays,$_poids,$activerPromoLivraison,$totalHtFinal,$livraisonComprise,$livraisonId) {
    GLOBAL $_SESSION,$_GET;
	$hid = mysql_query("SELECT * FROM devis WHERE devis_number = '".$_GET['id']."'");
	$myhid = mysql_fetch_array($hid);
	$devisTva = $myhid['devis_tva'];

    if($_poids>0) {
         $_a = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_pays."'");
         $_b = mysql_fetch_array($_a);
         $_zone = $_b['countries_shipping'];
         $_tax = $_b['countries_shipping_tax'];
         $_iso = $_b['iso'];

         $_c = mysql_query("SELECT * FROM ship_price WHERE weight >= ".$_poids." AND livraison_id='".$livraisonId."' ORDER BY weight");
         while ($_d = mysql_fetch_array($_c)) {
                $_sp[] = $_d['weight'];
         }
         
         
         
         $_f = mysql_query("SELECT ".$_zone." FROM ship_price WHERE weight = '".$_sp[0]."' AND livraison_id='".$livraisonId."'");
         $_p = mysql_fetch_array($_f);

         
         if($_p[$_zone]==9999) {
            $totalWeight = sprintf("%0.2f",$_poids/1000);
            print "<p align='center'><b>";
            print TOTAL_POIDS." : ".$totalWeight." kg<br>";
            print DESTINATION." : ".$_pays."<br><br>";
            
            print EXPED_IMPOSSIBLE;
            print "</b></p>";
            exit;
         }

         $query = mysql_query("SELECT free_shipping_zone FROM admin");
         $zoneZ = mysql_fetch_array($query);
         if(preg_match ("/\b$_zone\b/i", $zoneZ['free_shipping_zone'])) {$gratos="yes"; $gratosPack="yes";} else {$gratos="no"; $gratosPack="no";}

         $shipPrice = $_p[$_zone]/$_poids;

         if($_b['iso']=="RM") $shipPrice=0;
         if($activerPromoLivraison == "oui" and $totalHtFinal>=$livraisonComprise and $gratos=="yes") $shipPrice = sprintf("%0.2f",0);
         $livraisonhors = sprintf("%0.2f",$shipPrice*$_poids);
         
         if($_b['countries_shipping_tax_active'] == "yes") {$shipTax = sprintf("%0.2f",$livraisonhors*($_tax/100));} else {$shipTax = sprintf("%0.2f",0);}
         if($_b['countries_packing_tax_active'] == "yes") {$packTax = $_b['countries_packing_tax'];} else {$packTax = sprintf("%0.2f",0);}
         
         if(!empty($devisTva) AND $_iso !== $originIso) {
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







if(isset($_POST['action']) AND $_POST['action']=="add") {
	
	$devisQuery2 = mysql_query("SELECT users_id FROM users_orders WHERE users_devis = '".$_GET['id']."'");
	$devisQueryNum2 = mysql_num_rows($devisQuery2);
	if($devisQueryNum2 == 0) {
		
		$devisQuery = mysql_query("SELECT * FROM devis WHERE devis_number = '".$_GET['id']."'");
		$devisQueryNum20 = mysql_num_rows($devisQuery);
		$thisDevis = mysql_fetch_array($devisQuery);
		
		
		if(!empty($thisDevis['devis_client'])) $_SESSION['account'] = $thisDevis['devis_client'];
		if(isset($_GET['id'])) $_SESSION['devisNumero'] = $_GET['id'];
		$_SESSION['clientGender'] = "M";
		$_SESSION['clientFirstname'] = $thisDevis['devis_firstname'];
		$_SESSION['clientLastname'] = $thisDevis['devis_lastname'];
		$_SESSION['clientCompany'] = $thisDevis['devis_company'];
		$_SESSION['clientStreetAddress'] = $thisDevis['devis_address'];
		$_SESSION['clientPostCode'] = $thisDevis['devis_cp'];
		$_SESSION['clientCity'] = $thisDevis['devis_city'];
		$_SESSION['clientSurburb'] = "";
		$_SESSION['clientProvince'] = "";
		$_SESSION['clientCountry'] = $thisDevis['devis_country'];
		$_SESSION['clientEmail'] = $thisDevis['devis_email'];
		$_SESSION['clientTelephone'] = $thisDevis['devis_tel'];
		$_SESSION['clientFax'] = $thisDevis['devis_fax'];
		$_SESSION['list'] = $_SESSION['list2'];
		if($taxePosition == "Tax included") $_SESSION['taxStatut'] = "TTC";
		if($taxePosition == "Plus tax") $_SESSION['taxStatut'] = "HT";
		if($taxePosition == "No tax") $_SESSION['taxStatut'] = "";
		$_SESSION['clientComment'] = $thisDevis['devis_comment'];
		$_SESSION['saveDataFromForm'] = "";
		$_SESSION['fact_adresse'] = $thisDevis['devis_lastname']." ".$thisDevis['devis_firstname']."|".$thisDevis['devis_company']."|".$thisDevis['devis_address']."||".$thisDevis['devis_city']."|".$thisDevis['devis_cp']."|".$thisDevis['devis_country']."|".$thisDevis['devis_tva'];
		if($_POST['paiement']=='cc') {
			$_SESSION['users_confirm']='yes';
			$_SESSION['users_payed']='yes';
		}
		else {
			$_SESSION['users_confirm']='no';
			$_SESSION['users_payed']='no';
		}
		$_SESSION['shippingId'] = $thisDevis['devis_shipping'];
		
		
		$str1 = 'ABCDEFGHIJKLMNPQRJKLMNPQRSTUVWXYZ123456789';
		$str2 = '123456789123456789';
		
		if(isset($_SESSION['account'])) {
			$_SESSION['clientPassword'] = $_SESSION['account'];
		}
		else {
			if(!isset($_SESSION['clientPassword'])) {
				$_SESSION['clientPassword'] = '';
				$_SESSION['clientPassword'] = generate_account();
			}
		}
		
		$_SESSION['clientNic'] = '';
		$_SESSION['clientNic'] = generate_nic();
		
		addToBdd($_POST['paiement'],$_SESSION['clientPassword'],$_SESSION['clientNic']);
		$mess = "<p style='color:#CC0000' align='center'><b>*** De offerte werd succesvol toegevoegd als bestelling NIC N&deg; ".$_SESSION['clientNic']." ***</b></p>";
	}
	else {
		$mess = "<p style='color:#CC0000' align='center'><b>*** De offerte werd reeds toegevoegd als bestelling ***</b></p>";
	}
}

if(!isset($_SESSION['getPromo'])) {
   $promoCountAll = getPromo();
   $_SESSION['getPromo'] = $promoCountAll[0];
   $_SESSION['getPromoId'] = $promoCountAll[1];
}


if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['country']) AND !empty($_GET['country'])) {
	$title = "Devis ".$_GET['country'];
	if(isset($deee)) unset($deee);
	$deee = array();
	$pays = mysql_query("SELECT * FROM countries WHERE countries_name = '".$_GET['country']."'");
	$p = mysql_fetch_array($pays);
	$hid = mysql_query("SELECT * FROM devis WHERE devis_number = '".$_GET['id']."'");
	$myhid = mysql_fetch_array($hid);
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
<META NAME="description" CONTENT="<?php print $description;?>">
<meta name="keywords" content="<?php print $keywords;?>">
<meta name="revisit-after" content="15 days">
<title>Uitvoeren offerte naar bestelling </title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if(!empty($_SESSION['userInterface'])) {
   print "<link rel='stylesheet' href='../css/".$_SESSION['userInterface'].".css' type='text/css'>";
}
else {
   if($activerCouleurPerso=="oui") {
      print "<link rel='stylesheet' href='../css/perso.css' type='text/css'>";
   }
else {
   print "<link rel='stylesheet' href='../css/".$colorInter.".css' type='text/css'>";
}
}
?>

</head>

<body leftmargin="0" topmargin="30" marginwidth="10" marginheight="10">
<?php
if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['country']) AND !empty($_GET['country'])) {
print "<p align='center'><b>N&deg; ".$_GET['id']."</b></p>";
print "<p align='center'><b>BESTEMMING >> ".strtoupper($_GET['country'])."</b></p>";
print "<table width='500' cellpadding='5' cellspacing='0' border='0' class='TABLE1' align='center'><tr><td>";
print "<table border='0' width='100%' align='center' cellspacing='0' cellpadding='0'>";
print "<tr height='25'>";
print "<td><b>Ref/".ARTICLES."</b></td>";
print "<td width='50' align='center'><b>".QTE."</b></td>";
print "<td width='50' align='center'><b>".PRIX_UNITAIRE."</b></td>";
print "<td width='50' align='center'><b>".TAXE."</b></td>";
print "<td width='80' align='right'><b>".PRIX." ".strtolower(TOTAL)."</b></td>";

if(!empty($myhid['devis_products_new'])) {
    $_SESSION['list2'] = $myhid['devis_products_new'];
}
else {
    $_SESSION['list2'] = $myhid['devis_products'];
}

$split = explode(",",$_SESSION['list2']);


foreach ($split as $item) {
     
    $check = explode("+",$item);
    $query = mysql_query("SELECT p.products_sup_shipping, p.products_tax, p.products_deee, p.products_name_".$_SESSION['lang'].", p.products_id, p.categories_id,p.products_download,p.fournisseurs_id,p.products_desc_".$_SESSION['lang'].",p.products_price,p.products_weight,p.products_note_".$_SESSION['lang'].",p.products_ref,p.products_im,p.products_image,p.products_image2,p.products_image3,p.products_image4,p.products_option_note_".$_SESSION['lang'].",p.products_visible,p.products_taxable,p.products_tax,p.products_date_added,p.products_qt, s.specials_new_price, s.specials_last_day, s.specials_first_day, s.specials_visible
                          FROM products as p
                          LEFT JOIN specials as s
                          ON (p.products_id = s.products_id)
                          WHERE p.products_id = '".$check[0]."'");
    $row = mysql_fetch_array($query);
    if($check[1]!=="0") {
                         
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
								if($dateDebut <= $today  and $dateMax >= $today) {
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
                         
                        $tutu = tax_price($_GET['country'],$row['products_tax']);
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

                         
                        $p_ = $row['products_weight'];
                        if($row['products_download'] == "yes") {$p_=0;}
                        $poidsOptionsArray = explode('|',$check[8]);
                        $poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
                        $poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
                        $_SESSION['poids'] = sprintf("%0.2f",array_sum($poid));

                         
                        $emballage[] = $check[1] * $row['products_sup_shipping'];

                         
                        if($row['products_tax'] !=="0.00" and $p['countries_product_tax'] !== $row['products_tax']) {
            				  if($p['countries_product_tax'] == "0.00") { $pTax="0.00";} else { $pTax = $row['products_tax'];}
                        }
						      else {
							     $pTax = $p['countries_product_tax']; 
						      }
						if($row['products_taxable'] == "no") {$pTax="0.00";}
						if($taxePosition=="No tax") {$pTax="0.00";}
                        if(!empty($myhid['devis_tva']) AND $p['iso'] !== $iso) {$pTax="0.00";}
                        
                        $taxeItem[]=$pTax;
                        $itemId[] = $row['products_id'];

        				 
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
                        
                        $totalTaxeArray[] = $total_taxe;
                        $totalTaxeFinal = array_sum($totalTaxeArray);
                        $_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxeFinal);

                        $totalHtArray[] = $totalht;
                        $_SESSION['totalHtFinal'] = sprintf("%0.2f",array_sum($totalHtArray));

                        $totalTTCArray[] = $priceTTC;
                        $totalTTCFinal = array_sum($totalTTCArray);
                         
                        $liste[] = $row['products_id']."+".
                                   $check[1]."+".
                                   $price."+".
                                   $check[3]."+".
                                   $row['products_name_'.$_SESSION['lang']]."+".
                                   $pTax."+".
                                   $check[6]."+".
                                   $check[7]."+".
                                   $check[8];

                        print "</tr><tr>";
                        
							 
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
                         
                        print "<td>&bull;&nbsp;<b>".strtoupper($check[3])."</b><br><b>".$row['products_name_'.$_SESSION['lang']]."</b> ".$q;
                             
                            if($row['products_deee']>0) {
                                $deee[] = $check[1]*$row['products_deee'];
                                $openDeee = "<br><i>".DONT." <a href='javascript:void(0);' onClick=\"window.open('../includes/eco_taks.php','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=250,width=450,toolbar=no,scrollbars=yes,resizable=yes');\">";
                                print $openDeee."<span style='color:#00CC00'><b>Eco-part</b></a> </span>: ".$row['products_deee']." ".$symbolDevise."</i>";
                            }
                             
						     
                            print "<br><img src='im/zzz.gif' width='1' height='7'><br>";
						    print "</td>";
                         
                        print "<td width='50' align='center'>".$check[1]."</td>";
                         
                        print "<td align='center'>".$price."</td>";
                         
                        print "<td width='50' align='center'>".$pTax." %</td>";
                         
                        print "<td width='80' align='right'>".sprintf("%0.2f",$price_display)."</td>";

        if(isset($check[7])) {$ecoTaxFact[] = $check[7];} else {$ecoTaxFact[] = 0;}
    }
}


print "</tr></table>";



$ola = shipping_price($iso,$_GET['country'],$_SESSION['poids'],$activerPromoLivraison,$_SESSION['totalHtFinal'],$livraisonComprise,$myhid['devis_shipping']);
$_SESSION['shipPrice'] = $ola[0];
$_SESSION['livraisonhors'] = $ola[1];
$_SESSION['shipTax'] = $ola[2];
$gratos = $ola[3];

if($activerPromoLivraison == "oui" AND $_SESSION['totalHtFinal']>=$livraisonComprise AND $gratos=="yes") {
	$gratos = "yes";
	$gratoPack = "yes";
}
else {
	$gratos = "no";
	$gratoPack = "no";
}

$_SESSION['users_shipping_price'] = sprintf("%0.2f",$_SESSION['livraisonhors']+$_SESSION['shipTax']);
$totht = $_SESSION['totalHtFinal']+$_SESSION['livraisonhors'];
$_SESSION['totalTax'] = $totalTaxeFinal;
$_SESSION['totalTax'] = sprintf("%0.2f",$_SESSION['totalTax']);
$totttc = $totht+$_SESSION['totalTax']+$_SESSION['shipTax'];
$_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);

 
if(isset($deee)) {
    $ecoRaxAmount = sprintf("%0.2f",array_sum($deee));
    if($ecoRaxAmount>0) {$_SESSION['deee'] = $ecoRaxAmount;} else {$_SESSION['deee'] = sprintf("%0.2f",0);}
}
else {
    $_SESSION['deee'] = sprintf("%0.2f",0);
}


 
if($remiseOnTax == "TTC") {
    $totalPriceTTCCadeau = array_sum($priceTTCCadeau);
    $totalPriceTTCDeee = array_sum($priceTTCDeee1);
    $_SESSION['ff'] = $totalTTCFinal-$totalPriceTTCCadeau-$totalPriceTTCDeee;
}
else {
    $totalPriceHtCadeau = array_sum($priceHtCadeau);
    $totalPriceHtDeee = array_sum($priceHtDeee1);
    $_SESSION['ff'] = $_SESSION['totalHtFinal']-$totalPriceHtCadeau-$totalPriceHtDeee;
}

if($activerPromoLivraison == "oui" and $_SESSION['ff']>=$livraisonComprise and $gratos=="yes") {
	$shipPriceDisplay = LIVRAISON_GRATUITE;
}
else {
	$shipPriceDisplay = sprintf("%0.5f",$_SESSION['shipPrice'])." ".$symbolDevise."/gr HT";
}

 
$totalFraisEmballage = sprintf("%0.2f",array_sum($emballage));
if($totalFraisEmballage > 0 AND $gratoPack=="no" AND $gratos == "no") {
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

 
if($activerRemise == "oui" and $_SESSION['ff']>=$remiseOrderMax) {
	if($remiseType == "%") {$_SESSION['montantRemise'] = $_SESSION['ff']*($remise/100);}
	if($remiseType == $symbolDevise) {$_SESSION['montantRemise'] = $remise;}
	$_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
	$discount[] = $_SESSION['montantRemise'];
	$textRemise = REMISE." (-$remise$remiseType)";
}
else {
	$_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
	$_SESSION['montantRemise'] = sprintf("%0.2f",0);
	$discount[] = 0;
}

 
if($myhid['devis_remise_commande']>0) {
	if($_SESSION['ff'] >= $myhid['devis_remise_commande']) {
    	$discount[] = $myhid['devis_remise_commande'];
        $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",$myhid['devis_remise_commande']);						            
    }
    else {
    	$discount[] = 0;
        $_SESSION['accountRemiseEffec'] = sprintf("%0.2f",0);
    }
}

 
if(!empty($myhid['devis_remise_coupon'])) {
	$_SESSION['coupon_name'] = $myhid['devis_remise_coupon'];
    
	$query = mysql_query("SELECT * FROM code_promo WHERE code_promo = '".$_SESSION['coupon_name']."'");
	$result = mysql_fetch_array($query);
	$seuilPromoCode = $result['code_promo_seuil'];
 		
	if($_SESSION['ff'] > $result['code_promo_seuil']) {
		$remiseCoupon = $result['code_promo_reduction'];
		$remiseCouponType = $result['code_promo_type'];
	    if($remiseCouponType == "%") $_SESSION['montantRemise2'] = $_SESSION['ff']*($remiseCoupon/100);
	    if($remiseCouponType == $symbolDevise) $_SESSION['montantRemise2'] = $remiseCoupon;
        $_SESSION['montantRemise2'] = sprintf("%0.2f",$_SESSION['montantRemise2']);
        $discount[] = $_SESSION['montantRemise2'];                        
        $textCoupon1 = REMISE." (-".$remiseCoupon."".$remiseCouponType.")";
        $textCoupon2 = $textCoupon1." | Coupon: ".$result['code_promo'];
        $display_coupon = 1;
	}
	else {
	    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",$totttc);
	    $_SESSION['montantRemise2'] = sprintf("%0.2f",0);
	    $discount[] = 0;
	}
}

 
$totalDiscount = array_sum($discount);

 
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
            if($taxePosition=="Plus tax" or $taxePosition=="No tax") {
                if($remiseOnTax == "TTC") {
                    
                    $prixHtFinalTTC = $_price[$i]+($_price[$i]*($taxeItem[$i]/100));
                    $pCent = sprintf("%0.2f",$_price[$i]/$_SESSION['totalHtFinal']*100);
                    $montantReductionProduct = $totalDiscount*$pCent/100;
                    $htTotalToPay = ($prixHtFinalTTC-$montantReductionProduct)/(1+$taxeItem[$i]/100);
                    $totalTaxe1[] = (($htTotalToPay))*($taxeItem[$i]/100);
                    $htTotalToPay1[] = $htTotalToPay;
                }
                else {
                    $ratio = sprintf("%0.4f",$_price[$i]/$_SESSION['totalHtFinal']);
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
                    $prixHtFinal = ($_price[$i]-$montantReductionProduct)/(1+$taxeItem[$i]/100);
                    $htTotalToPay = ($totalTTCFinal-$totalDiscount)/(1+$taxeItem[$i]/100);
                    $totalTaxe1[] = (($prixHtFinal)*1)*($taxeItem[$i]/100);
                    $htTotalToPay1[] = $prixHtFinal;
                }
                else {
                   $prixHtFinal = $_price[$i]/(1+$taxeItem[$i]/100);
                   $ratio = sprintf("%0.1f",$prixHtFinal/$_SESSION['totalHtFinal']);
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
    $_SESSION['totalToPayTTC'] = sprintf("%0.2f",($_SESSION['totalHtFinal']-$totalDiscount+$totalTaxe));
}

$_SESSION['itemTax'] = sprintf("%0.2f",$totalTaxe);















                print "<table border='0' width='100%' cellspacing='0' cellpadding='0'><tr>";
                print "<td>";

 
                ## Afficher mode de livraison
            	$requestModeA = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id = '".$myhid['devis_shipping']."'") or die (mysql_error());
				if(mysql_num_rows($requestModeA) > 0) {
					$requestModeResultA = mysql_fetch_array($requestModeA);
					$livName = $requestModeResultA['livraison_nom_'.$_SESSION['lang']];
					print "<br><div style='font-size:13px'><b>".$livName."</b></div>";
            	}
                print "<div style='color:#CCCCCC'>".TOTAL_POIDS.": <b>".$_SESSION['poids']."</b> gr. [".$shipPriceDisplay."]</div>";
                print "<br><br>";
                print "</td>";
                print "</tr></table>";
                
 
                print "<table border='0' width='300' align='right' cellspacing='0' cellpadding='1'><tr>";
                print "<td align='left'>".TOTAL." ".HT."</td>";
                print "<td align='right'><b>".$_SESSION['totalHtFinal']."</b></td>";
                print "</tr><tr>";
                  
 
                if($activerRemise == "oui" and $_SESSION['ff']>=$remiseOrderMax) {
					if($remiseType == "%") {$_SESSION['montantRemise'] = $_SESSION['totalHtFinal']*($remise/100);}
					if($remiseType == $symbolDevise) {$_SESSION['montantRemise'] = $remise;}
					
					$_SESSION['montantRemise'] = sprintf("%0.2f",$_SESSION['montantRemise']);
					$discount[] = $_SESSION['montantRemise'];
					print "<td align='left'>".REMISE." (-$remise$remiseType)</td>";
					print "<td align='right'><span class='fontrouge'><b>-".$_SESSION['montantRemise']."</b></span></td></tr><tr>";
					print "</tr><tr>";
                }
                

 
                if(isset($_SESSION['accountRemiseEffec']) AND $_SESSION['accountRemiseEffec']>0) {
					print "<td align='left'>".REMISE_SUR_COMMANDES."</td>";
					print "<td align='right'><span class='fontrouge'><b>-".$_SESSION['accountRemiseEffec']."</b></span></td></tr><tr>";
              	}

 
                if(isset($display_coupon) AND $display_coupon == 1) {
                	print "<td align='left'>".REMISE."<br><i>Coupon: ".$textCoupon2."</i></td>";
                	print "<td align='right'><span class='fontrouge'><b>-".$_SESSION['montantRemise2']."</b></span></td></tr><tr>";
                	print "</tr><tr>";
                }

 
                $totalDiscount = array_sum($discount);

 
               if(!isset($totalTaxe1)) $totalTaxe1=0;
                 
                print tax_details($taxeItem,$totalTaxe1);

 
				$_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['livraisonhors'];
                print "<td colspan='2' align='left'><img src='im/zzz.gif' width='1' height='3'></td>";
                print "</tr><tr>";
                print "<td align='left'>".LIVRAISON."</td>";
                print "<td align='right'>".$_SESSION['livraisonhors']."</td>";
                print "</tr><tr>";

 
				$_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['shipTax'];
                print "<td align='left'>".strtoupper($taxeName)." ".$p['countries_shipping_tax']."%</td>";
                print "<td align='right'>".$_SESSION['shipTax']."</td>";
                print "</tr><tr>";

 
				$_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['totalEmballageHt'];
                print "<td colspan='2' align='left'><img src='im/zzz.gif' width='1' height='3'></td>";
                print "</tr><tr>";
                print "<td align='left'>".PAKING_COST."</td>";
                print "<td align='right'>".$_SESSION['totalEmballageHt']."</td>";
                print "</tr><tr>";

 
				$_SESSION['totalToPayTTC'] = $_SESSION['totalToPayTTC'] + $_SESSION['totalEmballageTva'];
                print "<td align='left'>".strtoupper($taxeName)." ".$p['countries_shipping_tax']."%</td>";
                print "<td align='right'>".$_SESSION['totalEmballageTva']."</td>";
                print "</tr><tr>";
                
                print "<td align='left' colspan='2'><hr width='100%'></td>";
                print "</tr><tr>";

 
				$totalTax1 = sprintf("%0.2f",$_SESSION['itemTax'] + $_SESSION['shipTax']);
		        print "<td align='left'>".TOTAL." ".strtoupper($taxeName).":</td>";
		        print "<td align='right'>".$totalTax1."</td>";
		        print "</tr><tr>";
                print "<td align='left' colspan='2'><hr width='100%'></td>";
                print "</tr><tr>";
                print "<td align='left' colspan='2'><img src='im/zzz.gif' width='1' height='5'></td>";
                print "</tr><tr>";
 
                print "<td align='left'>".MONTANT_A_PAYER.":</td>";
                print "<td align='right'><b>".sprintf("%0.2f",$_SESSION['totalToPayTTC'])." ".$symbolDevise."</b></td>";
                print "</tr>";
                print "</table>";
                
                print "</td></tr></table>";
                
 
$tototo = implode(",",$liste);
$_SESSION['list2'] = $tototo;

 

print "<p align='center'><b>".AJOUTER_COMMANDE_A_LA_BDD."</b></p>";

print "<table border='0' cellpadding='5' cellspacing='0' align='center'>";
print "<tr>";
print "<td>";
print "<form action='offerte_factuur.php?country=".$_GET['country']."&id=".$_GET['id']."' method='POST'>";
		print PAIEMENT.": ";
		print "<select name='paiement'>";
		print "<option value='vi'>".VIREMENT_BANCAIRE."</option>";
		print "<option value='cc'>".CARTE_DE_CREDIT."</option>";
		print "<option value='wu'>Western Union</option>";
		print "<option value='pp'>Paypal</option>";
		print "<option value='mb'>MoneyBookers</option>";
		print "</select>";
print "</td>";
print "</tr>";
print "<tr>";
print "<td cospan='2' align='center'>";
print "<input type='hidden' name='action' value='add'>";
print "<input type='Submit' value='".AJOUTER_COMMANDE."'>";
print "</td>";
print "</form>";
print "</tr>";
print "</table>";
if(isset($mess)) print $mess; 
}
?>
  </body>
  </html>
