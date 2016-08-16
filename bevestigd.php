<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');
if(isset($_SESSION['recup'])) {header("Location: caddie.php"); exit;}

$dateNow = date("Y-m-d H:i:s");
$displayPayments = 0;


// naam
$adress2 = explode("|",$_SESSION['fact_adresse']);
$adreesName = $adress2[0];

if($_POST['mode']=="BL") {
// ********
// rembours
// ********
include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = CONTRE_REMBOURSEMENT;
$_SESSION['contre'] = "1";
$paymentMode = "BL"; 
$modeSelect = CONTRE_REMBOURSEMENT;

// registratie bestelling
if(isset($_POST['mode2']) and isset($_POST['statut']) and $_POST['mode2']=="BL" and $_POST['statut']=="confirm") {

	if(empty($_POST['tel'])) {
		$messageTel = VEUILLEZ_SAISIR;
	}
	else {
	$_SESSION['clientTelephone'] = $_POST['tel'];
	

				$query = mysql_query("SELECT users_password, users_nic
                                      FROM users_orders
                                      WHERE users_password = '".$_SESSION['clientPassword']."'
                                      AND users_nic = '".$_SESSION['clientNic']."'");
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
                      
                // eco taks
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
                      users_products_tax,users_products_tax_statut,users_payed,users_statut,users_comment,
                      users_remise,users_remise_coupon,users_remise_coupon_name,users_contre_remboursement,
                      users_lang,users_save_data_from_form,users_coupon_note,users_remise_note,
					       users_shipping_note,users_confirm,users_account_remise_note,users_account_remise_app,
                      users_facture_adresse,users_affiliate,users_affiliate_amount,
                      users_devis, users_gc, users_remise_gc,
                      users_multiple_tax, users_deee_ht, users_deee_tax,
                      users_sup_ttc, users_sup_ht, users_sup_tax,
                      users_ip, users_shipping)
                      VALUES
                      ('".$_SESSION['clientGender']."','".$_SESSION['clientFirstname']."','".$_SESSION['clientLastname']."','".$_SESSION['clientCompany']."',
      					  '".$_SESSION['clientStreetAddress']."','".$_SESSION['clientPostCode']."','".$_SESSION['clientCity']."',
      					  '".$_SESSION['clientSurburb']."','".$_SESSION['clientProvince']."','".$_SESSION['clientCountry']."','".$dateNow."',
      					  '".$_SESSION['clientEmail']."','".$_SESSION['clientTelephone']."','".$_SESSION['clientFax']."',
      					  '".$_SESSION['clientPassword']."','".$_SESSION['clientNic']."','".$paymentMode."','".$symbolDevise."',
      					  '".$_SESSION['list']."','".$_SESSION['poids']."',
      					  '".$_SESSION['shipPrice']."','".$_SESSION['totalToPayTTC']."','".$_SESSION['users_shipping_price']."',
      					  '".$_SESSION['livraisonhors']."','".$_SESSION['shipTax']."','".$_SESSION['totalHtFinal']."',
      					  '".$_SESSION['itemTax']."','".$_SESSION['taxStatut']."','no','no','".$_SESSION['clientComment']."',
      					  '".$couponC1."','".$couponC."','".$couponN."','".$seuilContre."',
      					  '".$_SESSION['lang']."','".$_SESSION['saveDataFromForm']."','".$users_coupon_note."','".$users_remise_note."',
      					  '".$users_shipping_note."','yes','".$users_account_remise_note."','".$accountRemiseEffecBd."',
                      '".$_SESSION['fact_adresse']."','".$usersAffNumber."','".$usersAffAmount."',
                      '".$_devis."', '".$_gc."', '".$gcAmount."',
                      '".$mTax."', '".$deeeHtFinal."', '".$deeeTaxFinal."',
                      '".$_SESSION['priceEmballageTTC']."', '".$_SESSION['totalEmballageHt']."', '".$_SESSION['totalEmballageTva']."',
                      '".$ipAddress."', '".$_SESSION['shippingId']."'
                      )");


                    // geschenkbon
					$splitUp = explode(",",$_SESSION['list']);
					foreach ($splitUp as $item) {
		         		$check = explode("+",$item);
		         		if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} 
		         	}
		            $arrGc = array_sum($gc);
		            if($arrGc> 0) {
                            // aanmaak klant nummer
                            $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                            $codeGc = '';
                            for( $i = 0; $i < 12 ; $i++ ) {
                                $codeGc .= substr($str1, rand(0, strlen($str1) - 1), 1);
                            }
                            // Datum +1 jaar
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
                            // Update database
                            mysql_query("UPDATE users_orders SET users_gc='".$codeGc."' WHERE users_nic = '".$_SESSION['clientNic']."'");
                    }


$query = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_SESSION['clientNic']."'") or die (mysql_error());
$row = mysql_fetch_array($query);

$rappelContre =  "1 - ".AFIN_D_IDENTIFIER_VOTRE_COMMANDE_MANDAT."\r\n";
$rappelContre .= "2 - ".POUR_TOUTE_MODIFICATION."";

if($paymentMode == "BL") {$messageRappel = $rappelContre;}

$_store = str_replace("&#146;","'",$store_company);
$messageToSend = $_store."\r\n".$address_street."\r\n".$address_cp." - ".$address_city."  ".$address_country."\r\n";
              if(!empty($address_autre)) {
                  $address_autre2 = str_replace("<br>","\r\n",$address_autre);
                  $messageToSend .= $address_autre2."\r\n";
              }
              if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."\r\n";
              if(!empty($fax)) $messageToSend .= FAX.": ".$fax."\r\n";
$messageToSend .= "URL: http://".$www2.$domaineFull."<br>--Email: ".$mailOrder."<br>";
$messageToSend .= DATE.": ".date("d-m-Y H:i:s")."\r\n\r\n";
$messageToSend .= $adreesName.",\r\n";
$messageToSend .= VOUS_VENEZ_DE_FAIRE_UNE_COMMANDE." ".CONTRE_REMBOURSEMENT.".\r\n";
$messageToSend .= VOTRE_COMMANDE_A_ETE_ENREGISTREE_MAIL."\r\n";
$messageToSend .= AUSSITOT_CONFIRMATION_DU_PAIEMENTA."\r\n";
$messageToSend .= POUR_SUIVRE_VOTRE_COMMANDEA."\r\n";
$messageToSend .= "-----------------------------------------------------------------------------------------------\r\n";
$messageToSend .= INTERFACE_DE_SUIVIT_CLIENT.": $urlAdminClient\r\n";
$messageToSend .= "NIC (".NICO."): ".$_SESSION['clientNic']."\r\n";
$messageToSend .= NUMERO_DE_CLIENT.": ".$_SESSION['clientPassword']."\r\n";
$messageToSend .= POUR_SUIVRE_VOTRE_COMMANDE_IDENTIFIEZ_VOUS."\r\n";
$messageToSend .= "-----------------------------------------------------------------------------------------------\r\n";
$messageToSend .= POUR_TOUTE_INFORMATION."\r\n";
$messageToSend .= "Bedrag: ".$_SESSION['totalToPayTTC']."\r\n";

$messageToSend .= LE_SERVICE_CLIENT."\r\n";
$messageToSend .= $mailInfo;
// onderwerp
      $to = $_SESSION['clientEmail'];
      $subject = " Uw bestelling - betaling bij levering - NIC#:".$_SESSION['clientNic']." - http://".$www2.$domaineFull;
      $from = $mailOrder;

      mail($to, $subject, rep_slash($messageToSend),
      "Return-Path: $from\r\n"
      ."From: $from\r\n"
      ."Reply-To: $from\r\n"
      ."MIME-Version: 1.0\r \n"
      ."Content-Type: text/html; charset='iso-8859-1'\r \n"
      ."X-Mailer: PHP/" . phpversion());
      
// mail naar de admin
      $scss = "Een nieuwe bestelling op http://".$www2.$domaineFull."\r\n";
      $scss .= "Datum: ".date("d-m-Y H:i:s")."\r\n";
      $scss .= "-------------------------------------------------\r\n";
      $scss .= "Naam en voornaam: ".$_SESSION['clientGender']." ".$_SESSION['clientLastname']."\r\n";
      $scss .= "E-mail: ".$_SESSION['clientEmail']."\r\n";
      $scss .= "Land: ".$_SESSION['clientCountry']."\r\n";
      $scss .= "-----\r\n";
      $scss .= "Betaalwijze: ".$modeSelect."\r\n";
      $scss .= "-----\r\n";
      $scss .= "Bedrag: ".$_SESSION['totalToPayTTC']."\r\n";
      $scss .= "NIC nummer: ".$_SESSION['clientNic']."\r\n";
      $scss .= "Klant nummer: ".$_SESSION['clientPassword']."\r\n";
      $scss .= "-----\r\n";
      $scss .= "Bekijk deze bestelling: http://".$www2.$domaineFull."/admin\r\n";
// onderwerp
      $toMe = $mailOrder;
      $subjectMe = "Er is een nieuwe bestelling - ".strtoupper($modeSelect)." - NIC:".$_SESSION['clientNic'];
      $fromMe = $mailOrder;
      
      mail($toMe, $subjectMe, rep_slash($scss),
      "Return-Path: $fromMe\r\n"
      ."From: $fromMe\r\n"
      ."Reply-To: $fromMe\r\n"
      ."X-Mailer: PHP/" . phpversion());

				$message33 = VOTRE_COMMANDE_EST_ENREGISTREE_ET;
				}
				else {
				$message33 = VOTRE_COMMANDE_EST_DEJA_ENREGISTREE;
				}
		}
}
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table height="100%" width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
<tr height="<?php print $cellTop;?>" valign="top" >

<?php

if(isset($logo) AND $logo!=="noPath") {
    // logo
    print "<td align='left' valign='middle'>";
        $largeurLogo = getimagesize($logo);
        $logoWidth = $largeurLogo[0];
        $widthMaxLogo = 160;

        if($logoWidth>$widthMaxLogo) {
            $logoRezise = $largeurLogo[0]/$largeurLogo[1];
            $wwww=$widthMaxLogo;
            $hhhh=$widthMaxLogo/$logoRezise;
            $logoWidth = $wwww;
        }
        else {
            $wwww=0;
            $hhhh=0;
            $logoWidth = $largeurLogo[0];
        }
        print detectIm($logo,$wwww,$hhhh);
    print "</td>";
}
else {
    if(isset($logo2) AND $logo2!=="noPath") {
    // logo
    print "<td valign='middle' align='center'>";
       if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm($logo2,0,0)."</a>"; else print detectIm($logo2,0,0);
    print "</td>";
    }
}
?>
</tr>
<tr>
<td colspan="3" valign="top">

             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
              <tr height="32">
               <td><b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php?var=session_destroy" ><?php print maj(HOME);?></a> | <?php print maj(CONTRE_REMBOURSEMENT);?> |</b>
               </td>
              </tr>
             </table>


</td>
</tr>

<tr valign="top">
<td colspan="3">


    <table width="80%" border="0" cellspacing="0" cellpadding="3" class="TABLEPageCentreProducts" align="center" height="100%">
    <tr>
    <td valign="top">



            <table  border="0" cellspacing="0" cellpadding="3" align="center" >
            <tr>
            <td valign="top" align="left">

                    <table width="500" align="center" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td>
                    <?php
                     
                    display_payment_process(4,"");
                    ?>
                    </td>
                    </tr>
                    </table>

<?php
// factuur
include('includes/plug.inc.php');
print invoice_display($_SESSION['clientCountry'],"TABLE1","",$_SESSION['shippingId']);

         /* BETALING BIJ LEVERING */
                print "<br>";

            print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE1'>";
            print "<tr><td>";
            print "<u><b>".PAIEMENT_AVEC_CO."</b></u><br>";
            print VOUS_AVEZ_CHOISIA." <b>".$_SESSION['clientEmail']."</b>.
                  <br>".VOUS_AVEZ_CHOISI2A;

            if(!isset($message33)) {
			print "<table  width='100%' border='0' cellpadding='0' cellspacing='5'><tr>
                    <form action='".$_SERVER['PHP_SELF']."' method='post'>
                    <td>
                    ".TELEPHONE_DE_CONTACT.":
                    <input type='hidden' name='mode' value='BL'>
                    <input type='hidden' name='statut' value='confirm'>
                    <input type='hidden' name='mode2' value='BL'>
                    <input type='input' name='tel' value=''><br><br>					                
                    <input type='submit' value='".CONFIRMER_MODE_DE_PAIEMENT."' name='submit'>
                    </td>
                    </form>
                    </tr>
                    </table><br>";
	            if(isset($messageTel)) {
	                print "<br><br><div align='center'><b>".$messageTel."</b></div><br><br>";
	            }
            }
            else {
			print "<br><br><div align='center'><b>".$message33."</b></div>";
			print "<p align='center'><img src='im/fleche_menu2.gif' align='absmiddle' border='0'>&nbsp;<a href='cataloog.php?var=session_destroy'>".RETOUR_BOUTIQUE."</a></p>";
			}
            print "</td></tr></table>";
?>
</td>
</tr>
</table>

<div><img src="im/zzz.gif" width="1" height="15"></div>
</td>
</tr>
</table>

</td>
</tr>
<tr>
</tr>
</table>


</td>
<td width="1" class="borderLeft"></td>
</tr></table>
</body>
</html>

<?php
}
else {
// **************
// einde rembours
// **************

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = "Confirmation paiement";
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height='100%' class="backGroundTop">
<?php

if($header1Display=='oui') {
   include('includes/tabel_top1.php');
}
else {
   print "<tr valign='top'>";
}
?>
<td colspan="3" height="32" valign='top'>

      <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
      <tr height="32">
      <td><b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php?var=session_destroy"><?php print strtoupper(HOME);?></a> | <?php print CONFIRMATION_DE_PAIEMENT;?> |</b></td>
      </tr>
      </table>


</td>
</tr>

<tr valign="top">
<td colspan="3">

    <br>
    <table align="center" border="0" cellpadding="0" cellspacing="5">
        <tr>
          <td valign="top">


    <table align="center" height="100%" border="0" cellpadding="0" cellspacing="5" class="TABLEPageCentreProducts">
        <tr>
        <td valign="top" align="center">

            <table width="500" align="center" border="0" cellpadding="0" cellspacing="0">
            <tr>
            <td>
            <?php
             
            display_payment_process(4,"");
            ?>
            </td>
            </tr>
            </table>

<?php
                if($_POST['mode']=="BO") {$paymentMode = "BO"; $modeSelect = VIREMENT_BANCAIRE;}
                if($_POST['mode']=="ma") {$paymentMode = "ma"; $modeSelect = MANDAT_POSTAL;}
                if($_POST['mode']=="ch") {$paymentMode = "ch"; $modeSelect = CHEQUE_BANCAIRE;}
                if($_POST['mode']=="tb") {$paymentMode = "tb"; $modeSelect = TRAITE_BANCAIRE;}
                if($_POST['mode']=="BL") {$paymentMode = "BL"; $modeSelect = CONTRE_REMBOURSEMENT;}
                if($_POST['mode']=="wu") {$paymentMode = "wu"; $modeSelect = "Western Union";}
                if($_POST['mode']=="ss") {$paymentMode = "ss"; $modeSelect = "Liaison-SSL";}
                if($_POST['mode']=="eu") {$paymentMode = "eu"; $modeSelect = "1euro.com";}
                
                // controle indien reeds geregistreerd
                $query = mysql_query("SELECT users_password, users_nic
                                      FROM users_orders
                                      WHERE users_password = '".$_SESSION['clientPassword']."'
                                      AND users_nic = '".$_SESSION['clientNic']."'");
                $rows = mysql_num_rows($query);
                if($rows == 0) {
                // automatische bevestiging
                if($autoConfirm=="oui") {
                  $automaticConfirm = array("BO","ma","ch","tb","wu","ss","ts");
                  if(in_array($paymentMode, $automaticConfirm)) $userConfirm = 'yes'; else $userConfirm = 'no';
                }
                else {
                  $userConfirm = 'no';
                }
                // naar database
                if(!isset($_SESSION['devisNumero'])) {$_devis = "";} else {$_devis = $_SESSION['devisNumero'];}
                if(!isset($_SESSION['coupon_name'])) { $_SESSION['coupon_name'] = "";}
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
                      
                // eco taks
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
                      users_products_tax,users_products_tax_statut,users_payed,users_statut,users_comment,
                      users_remise,users_remise_coupon,users_remise_coupon_name,
                      users_lang,users_save_data_from_form,users_coupon_note,users_remise_note,
					       users_shipping_note,users_account_remise_note,users_account_remise_app,
                      users_facture_adresse,users_affiliate,users_affiliate_amount,
                      users_devis, users_gc, users_remise_gc, users_confirm,
                      users_multiple_tax, users_deee_ht, users_deee_tax,
                      users_sup_ttc, users_sup_ht, users_sup_tax,
                      users_ip, users_shipping)
                      VALUES
                      ('".$_SESSION['clientGender']."','".$_SESSION['clientFirstname']."','".$_SESSION['clientLastname']."','".$_SESSION['clientCompany']."',
      					  '".$_SESSION['clientStreetAddress']."','".$_SESSION['clientPostCode']."','".$_SESSION['clientCity']."',
      					  '".$_SESSION['clientSurburb']."','".$_SESSION['clientProvince']."','".$_SESSION['clientCountry']."','".$dateNow."',
      					  '".$_SESSION['clientEmail']."','".$_SESSION['clientTelephone']."','".$_SESSION['clientFax']."',
      					  '".$_SESSION['clientPassword']."','".$_SESSION['clientNic']."','".$paymentMode."','".$symbolDevise."',
      					  '".$_SESSION['list']."','".$_SESSION['poids']."',
      					  '".$_SESSION['shipPrice']."','".$_SESSION['totalToPayTTC']."','".$_SESSION['users_shipping_price']."',
      					  '".$_SESSION['livraisonhors']."','".$_SESSION['shipTax']."','".$_SESSION['totalHtFinal']."',
      					  '".$_SESSION['itemTax']."','".$_SESSION['taxStatut']."','no','no','".$_SESSION['clientComment']."',
      					  '".$couponC1."','".$couponC."','".$couponN."',
      					  '".$_SESSION['lang']."','".$_SESSION['saveDataFromForm']."','".$users_coupon_note."','".$users_remise_note."',
      					  '".$users_shipping_note."','".$users_account_remise_note."','".$accountRemiseEffecBd."',
                      '".$_SESSION['fact_adresse']."','".$usersAffNumber."','".$usersAffAmount."',
                      '".$_devis."', '".$_gc."', '".$gcAmount."','".$userConfirm."',
                      '".$mTax."', '".$deeeHtFinal."', '".$deeeTaxFinal."',
                      '".$_SESSION['priceEmballageTTC']."', '".$_SESSION['totalEmballageHt']."', '".$_SESSION['totalEmballageTva']."',
                      '".$ipAddress."', '".$_SESSION['shippingId']."'
                      )");
                    

		          // geschenkbon
                  $splitUp = explode(",",$_SESSION['list']);
                  foreach ($splitUp as $item) {
                      $check = explode("+",$item);
                      if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Controle geschenkbon
                  }
		          $arrGc = array_sum($gc);
		          if($arrGc> 0) {
                            // klant nummer aan maken
                            $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                            $codeGc = '';
                            for( $i = 0; $i < 12 ; $i++ ) {
                                $codeGc .= substr($str1, rand(0, strlen($str1) - 1), 1);
                            }
                            // Datum + 1 jaat
                            $nextYear  = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")+1));
                            // geschenkbon
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
                            // Update database
                            mysql_query("UPDATE users_orders SET users_gc='".$codeGc."' WHERE users_nic = '".$_SESSION['clientNic']."'");
                    }

$query = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_SESSION['clientNic']."'") or die (mysql_error());
$row = mysql_fetch_array($query);

if($paymentMode == "ch") $messageRappel = "- ".AFIN_D_IDENTIFIER_VOTRE_COMMANDE."<br>".POUR_TOUTE_MODIFICATION;
if($paymentMode == "BO") $messageRappel = "- ".OUBLIEZ_PAS_DE_DEMANDER."\r\n".FAITES_NOUS_PARVENIR_PAR_EMAIL."<br>".POUR_TOUTE_MODIFICATION;
if($paymentMode == "ma") $messageRappel = "- ".AFIN_D_IDENTIFIER_VOTRE_COMMANDE_MANDAT."<br>".POUR_TOUTE_MODIFICATION;
if($paymentMode == "BL") $messageRappel = "- ".AFIN_D_IDENTIFIER_VOTRE_COMMANDE_MANDAT."<br>".POUR_TOUTE_MODIFICATION;
if($paymentMode == "wu") $messageRappel = "- ".AFIN_D_IDENTIFIER_VOTRE_COMMANDE_WESTERN."<br>".POUR_TOUTE_MODIFICATION;
if($paymentMode == "ss") $messageRappel = "";
if($paymentMode == "ts") $messageRappel = "";
if($paymentMode == "tb") $messageRappel = "- ".AFIN_D_IDENTIFIER_VOTRE_COMMANDE_TRAITE."<br>".POUR_TOUTE_MODIFICATION;
if($paymentMode == "eu") $messageRappel = "";

// e-mail naar de klant
$_store = str_replace("&#146;","'",$store_company);

$messageToSend = $_store."<font face=arial><br>".$address_street."<br>".$address_cp." ".$address_country." ".$address_city."<br>";
if(!empty($address_autre)) {
	$address_autre2 = str_replace("<br>","\r\n",$address_autre);
	$messageToSend .= $address_autre2."\r\n";
}
if(!empty($tel)) $messageToSend .= TELEPHONE.": ".$tel."<br>";
if(!empty($fax)) $messageToSend .= FAX.": ".$fax."<br>";
$messageToSend .= "URL: http://".$www2.$domaineFull."<br>E-mail: ".$mailOrder."<br>";
$messageToSend .= DATE.": ".date("d-m-Y H:i:s")."<br><br>------------------<br>";
$messageToSend .= $adreesName."<br>";
$messageToSend .= VOTRE_COMMANDE_A_ETE_ENREGISTREE_MAIL."<br>";
$messageToSend .= VOUS_VENEZ_DE_FAIRE_UNE_COMMANDE." ".strtolower($modeSelect).".<br>";
$messageToSend .= AUSSITOT_CONFIRMATION_DU_PAIEMENT."<br>";
$messageToSend .= POUR_SUIVRE_VOTRE_COMMANDE."<br>";
$messageToSend .= "<br><br>";
$messageToSend .= RAPPEL.":<br>";
$messageToSend .= $messageRappel."<br>";
$messageToSend .= "<br>";
$messageToSend .= INTERFACE_DE_SUIVIT_CLIENT.": <a href='$urlAdminClient'>$urlAdminClient</a><br>";
$messageToSend .= NICEMAIL.": ".$_SESSION['clientNic']."<br>";
$messageToSend .= NUMERO_DE_CLIENT.": ".$_SESSION['clientPassword']."<br>";
$messageToSend .= ADRESSE_EMAIL.": ".$_SESSION['clientEmail']."<br>";
$messageToSend .= POUR_SUIVRE_VOTRE_COMMANDE_IDENTIFIEZ_VOUS."<br>";
$messageToSend .= "<br>";
$messageToSend .= POUR_TOUTE_INFORMATION.".<br>";
$messageToSend .= LE_SERVICE_CLIENT."<br>";
$messageToSend .= $mailOrder;

$to = $_SESSION['clientEmail'];
// mail onderwerp wanneer een klant een nieuwe bestelling doet
$subject = "".COMMANDEMAIL." ".$_SESSION['clientNic']." - http://".$www2.$domaineFull;

$from = $mailOrder;

mail($to, $subject, rep_slash($messageToSend),
"Return-Path: $from\r\n"
."From: $from\r\n"
."Reply-To: $from\r\n"
     ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
."X-Mailer: PHP/" . phpversion());

// e-mail naar de administrator
$scss = "<font face=arial>Een nieuwe bestelling op <a href=\"http://".$www2.$domaineFull."\">http://".$www2.$domaineFull."</a><br>";
$scss .= "Datum: ".date("d-m-Y H:i:s")."<br>";
$scss .= "<br>";
$scss .= "Naam: ".$_SESSION['clientGender']." ".$_SESSION['clientLastname']."<br>";
$scss .= "E-mail: ".$_SESSION['clientEmail']."<br>";
$scss .= "Land: ".$_SESSION['clientCountry']."<br>";
$scss .= "<br>";
$scss .= "Betaalwijze: ".$modeSelect."<br>";
$scss .= "Bedrag: ".$_SESSION['totalToPayTTC']."<br>";
$scss .= "NIC: ".$_SESSION['clientNic']."<br>";
$scss .= "Klant nummer: ".$_SESSION['clientPassword']."<br>";
$scss .= "<br>";
$scss .= "Bekijk deze bestelling: <a href=\"http://".$www2.$domaineFull."/admin\">Administrator</a>";

$toMe = $mailPerso;
$subjectMe = "Er is een nieuwe bestelling met nummer NIC:".$_SESSION['clientNic'];
$fromMe = $mailOrder;

mail($toMe, $subjectMe, rep_slash($scss),
"Return-Path: $fromMe\r\n"
."From: $fromMe\r\n"
."Reply-To: $fromMe\r\n"
."Reply-To: $from\r\n"
     ."MIME-Version: 1.0\r \n"
     ."Content-Type: text/html; charset='iso-8859-1'\r \n"
."X-Mailer: PHP/" . phpversion());

            // bestelling afgewerkt
            print "<table border='0' cellpadding='0' cellspacing='0' width='80%'><tr><td>";
            print "<img src='im/i05.gif' align='absmiddle'>&nbsp;".UN_EMAIL_A_ETE_ENVOYE1." ".$_SESSION['clientEmail']."<br><br>";
            print $adreesName.",<br>";
            print UN_EMAIL_A_ETE_ENVOYE2." <b>".$modeSelect."</b>.<br>";
            
            if($paymentMode == "BL") {
               print VOTRE_COMMANDE_A_ETE_ENREGISTREE_CONTRE;
            }
            else {
               print VOTRE_COMMANDE_A_ETE_ENREGISTREE;
            }
               print "<p><a href='cataloog.php?var=session_destroy'><img src='im/fleche_menu2.gif' align='absmiddle' border='0'>&nbsp;".RETOUR_BOUTIQUE."</a></p>";
               print "</td></tr></table>";
}
else {
    print "<table border='0' cellpadding='0' cellspacing='0' width='80%'><tr><td>";
    print $adreesName.",<br>";
    print VOTRE_COMMANDE_EST_DEJA_ENREGISTREE."<br>";
    print "<br><a href='cataloog.php?var=session_destroy'><img src='im/fleche_menu2.gif' align='absmiddle' border='0'>&nbsp;".RETOUR_BOUTIQUE."</a><br><br>";
    print "</td></tr></table>";
}
?>
<div><img src="im/zzz.gif" width="1" height="5"></div>
                </td>
              </tr>
            </table>
            
            
         
          </td>
        </tr>
      </table>
      
</td>
</tr>
</table>


</td>
<td width="1" class="borderLeft"></td>
</tr>
</table>
</body>
</html>
<?php }?>
