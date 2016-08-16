<?php
include('configuratie/configuratie.php');
include('includes/plug.php');
include('includes/doctype.php');



if(!isset($_SESSION['account']) OR !isset($_SESSION['list'])) {header("Location: caddie.php"); exit;}
if(isset($_SESSION['recup'])) {header("Location: caddie.php"); exit;}
if($paymentsDesactive=="oui") {header("Location: caddie.php"); exit;}

include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = PAIEMENTS;
$displayPayments = 0;

 
if(isset($_GET['dl'])) $_POST['shipping']="";
$_SESSION['shippingId'] = $_POST['shipping'];
$whatShip = mysql_query("SELECT livraison_nom_".$_SESSION['lang']." FROM ship_mode WHERE livraison_id='".$_POST['shipping']."'") or die (mysql_error());
if(mysql_num_rows($whatShip) > 0) {
    $resultWhatShip = mysql_fetch_array($whatShip);
    $_SESSION['shippingName'] = $resultWhatShip['livraison_nom_'.$_SESSION['lang']];
}
else {
    $_SESSION['shippingName'] = 'Download';
}

function displayTableTop($nom) {
    if($nom == "Pay.nl")
    {
      print "<tr><td><u><b><span style='font-size:13px;'>".PAIEMENT." via ".$nom."</span></b></u><br>";
      print LE_PAIEMENT_PAR_CARTE_DE_CREDIT." ".$nom.".<br>";
    }
    else
    {
      print "<tr><td><u><b><span style='font-size:13px;'>".CARTE_DE_CREDIT." via ".$nom."</span></b></u><br>";
      print LE_PAIEMENT_PAR_CARTE_DE_CREDIT." ".$nom.".<br>";
    }
}
function displayTableBottom() {
    print "</td></tr>";
    print "<td valign='top' height='2'><hr width='100%'></td>";
    print "</tr>";
}
 
function addToBdd($pass,$nic,$mode) {
        global $_SESSION;
        include('configuratie/configuratie.php');
        $dateNow = date("Y-m-d H:i:s");
        $paymentMode = $mode;
        $query = mysql_query("SELECT users_password, users_nic
                              FROM users_orders
                              WHERE users_password = '".$pass."'
                              AND users_nic = '".$nic."'");
        $rows = mysql_num_rows($query);

	if($rows == 0) {
        $_devis = (!isset($_SESSION['devisNumero']))? "" : $_SESSION['devisNumero'];
        $accountRemiseEffecBd = (!isset($_SESSION['accountRemiseEffec']))? "0.00" : $_SESSION['accountRemiseEffec'];
        $couponN = (!isset($_SESSION['coupon_name']))? "" : $_SESSION['coupon_name'];
        $couponC = (!isset($_SESSION['montantRemise2']))? "0.00" : $_SESSION['montantRemise2'];
        $couponC1 = (!isset($_SESSION['montantRemise']))? "0.00" : $_SESSION['montantRemise'];
        $users_coupon_note = (!isset($_SESSION['users_coupon_note']))? "" : $_SESSION['users_coupon_note'];
        $users_remise_note = (!isset($_SESSION['users_remise_note']))? "" : $_SESSION['users_remise_note'];
        $users_account_remise_note = (!isset($_SESSION['users_account_remise_note']))? "" : $_SESSION['users_account_remise_note'];
        $users_shipping_note = (!isset($_SESSION['users_shipping_note']))? "" : $_SESSION['users_shipping_note'];
        $_gc = (!isset($_SESSION['cadeau_number']))? "" : $_SESSION['cadeau_number'];
        $gcAmount = (!isset($_SESSION['montantRemise3']))? "0.00" : $_SESSION['montantRemise3'];
        $mTax = (isset($_SESSION['multipleTax']))? $_SESSION['multipleTax'] : "";
        $ipAddress = ($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : '';
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
                    users_products_tax,users_products_tax_statut,users_payed,users_statut,users_comment,
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
                    '".$_SESSION['itemTax']."','".$_SESSION['taxStatut']."','no','no','".$_SESSION['clientComment']."',
                    '".$couponC1."','".$couponC."','".$couponN."',
                    '".$_SESSION['lang']."','".$_SESSION['saveDataFromForm']."','".$users_coupon_note."','".$users_remise_note."',
                    '".$users_shipping_note."','".$users_account_remise_note."','".$accountRemiseEffecBd."',
                    '".$_SESSION['fact_adresse']."','".$usersAffNumber."','".$usersAffAmount."',
                    '".$_devis."', '".$_gc."', '".$gcAmount."',
                    '".$mTax."', '".$deeeHtFinal."', '".$deeeTaxFinal."',
                    '".$_SESSION['priceEmballageTTC']."', '".$_SESSION['totalEmballageHt']."', '".$_SESSION['totalEmballageTva']."',
                    '".$ipAddress."', '".$_SESSION['shippingId']."'
                    )") or die (mysql_error());
                     
					$splitUp = explode(",",$_SESSION['list']);
					foreach ($splitUp as $item) {
		         		$check = explode("+",$item);
		         		if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} 
		         	}
		            $arrGc = array_sum($gc);
		            if($arrGc> 0) {
                         
                        $str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                        $codeGc = '';
                        for($i = 0; $i < 12 ; $i++) {
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
	else {

	    mysql_query("UPDATE users_orders SET users_payment='cc' WHERE users_nic = '".$nic."' AND users_password = '".$pass."'");
	}
}
 

if(isset($_SESSION['contre'])) unset($_SESSION['contre']);

if($taxePosition == "Tax included") $_SESSION['taxStatut'] = "TTC";
if($taxePosition == "Plus tax") $_SESSION['taxStatut'] = "HT";
if($taxePosition == "No tax") $_SESSION['taxStatut'] = "";

 
if(isset($_POST['action']) and $_POST['action'] == "atos") {
	// Ajouter to database
	addToBdd($_SESSION['clientPassword'],$_POST['nic'],"eu");
	$goto = "1euro/cgi/paiement.php";
	header("Location: $goto");
	exit;
}

// ------------
// Paysite-Cash
// ------------
if(isset($_POST['action']) and $_POST['action'] == "paysitecash") {
	// Ajouter to database
	addToBdd($_SESSION['clientPassword'],$_SESSION['clientNic'],"cc");
	if($_SESSION['lang']==1) $languePaySite = "fr"; 
	if($_SESSION['lang']==2) $languePaySite = "us";
	if($_SESSION['lang']==3) $languePaySite = "es";
	if($paySiteCashTest=="oui") $test="&test=1"; else $test="";
	if($paySiteCashVal=="oui")  $val="&wait=1"; else $val="";
	$goto = "https://billing.paysite-cash.biz/?site=".$paySiteCashSite."&montant=".$_SESSION['totalToPayTTC']."&devise=".$paysiteCashDevise."&lang=".$languePaySite."&divers=".$_SESSION['clientNic']."&email=".$_SESSION['clientEmail'].$test.$val;
	header("Location: $goto");
}


// ----------------------
// EuroWebPayment payment
// ----------------------
if(isset($_POST['action']) and $_POST['action'] == "EuroWebPayment") {
	// Ajouter to database
	addToBdd($_SESSION['clientPassword'],$_SESSION['clientNic'],"cc");
	if($_SESSION['lang']== "1") {
	   $goto = "http://www.eurowebpayment.com/fr/cb/?amount=".$_SESSION['totalToPayTTC']."&EWP_KEY=".$myKey."&nic=".$_SESSION['clientNic']."&ref_vendeur=".$store_company." order";
	}
	else {
	   $goto = "http://www.eurowebpayment.com/en/cb/?amount=".$_SESSION['totalToPayTTC']."&EWP_KEY=".$myKey."&nic=".$_SESSION['clientNic']."&ref_vendeur=".$store_company." order";
	}
	header("Location: $goto");
}
// -----------------
// 2checkout payment
// -----------------
if(isset($_POST['action']) and $_POST['action'] == "2checkout") {

function change($subtotal,$defaultDevise) {
	$fp = fsockopen("www.2checkout.com", 80, $errmsg, $errno) or die("$errno:$errmsg");
	fputs($fp, "GET /cgi-bin/rk_buyers/rates.2c HTTP/1.0\r\n");
	fputs($fp, "User-Agent: Mozilla\r\n");
	fputs($fp, "Host: www.2checkout.com\r\n\r\n");
	$file = "";
	$toto = "";
	while (!feof($fp)) {
	    $file.= fgets($fp,1024);
	}
	fclose($fp);
	$file = preg_replace("/^(.*)\r\n\r\n/s", "", $file);
	$file = str_replace(" <br>", "|", $file);
    $num = explode("|",$file);
    $numNb = count($num)-1;
	for($i=0; $i<=$numNb; $i++) {
    	if(preg_match ("/\b".$defaultDevise."\b/i", $num[$i])) $toto = $num[$i];
    }
    $rates = explode(" ",$toto);
    $taux = $rates[1];
    $quote = $rates[2];
    $subtotalConvert = round($subtotal*1/$taux,4);
    return array($subtotalConvert,$quote);
}
	// Add to database
	addToBdd($_SESSION['clientPassword'],$_SESSION['clientNic'],"cc");
	// connect to 2co server
	if($storeId>200000) {
		$urlCo = "https://www.2checkout.com/2co/buyer/purchase";
		$goto = $urlCo."?sid=".$storeId."&total=".$_SESSION['totalToPayTTC']."&cart_order_id=".$_SESSION['clientNic']."&email=".$_SESSION['clientEmail'];
	}
	else {
		$ola = change($_SESSION['totalToPayTTC'],$coDefaultDevise);
		$aPayer = sprintf("%0.2f",$ola[0]);
		$urlCo = "https://www.2checkout.com/2co/buyer/purchase";
		$goto = $urlCo."?sid=".$storeId."&total=".$aPayer."&cart_order_id=".$_SESSION['clientNic']."&email=".$_SESSION['clientEmail']."&quote=".$ola[1];
	}
	header("Location: $goto");
}

/*-------------
----COUPON-----
-------------*/
if(isset($_SESSION['activerCoupon']) and $_SESSION['activerCoupon'] == "1") {
   $query = mysql_query("SELECT * FROM code_promo");
   $result = mysql_fetch_array($query);
}


$str1 = 'ABCDEFGHIJKLMNPQRSTUVWXYZWXYZ123456789';
$str2 = '123456789123456789';
// Generation Compte client
if(isset($_SESSION['account'])) {
	$_SESSION['clientPassword'] = $_SESSION['account'];
}
else {
	if(!isset($_SESSION['clientPassword'])) {
		$_SESSION['clientPassword'] = '';
		$_SESSION['clientPassword'] = generate_account();
	}
}
// Generation NIC
$_SESSION['clientNic'] = '';
$_SESSION['clientNic'] = generate_nic();

// ---------------------
// SAVE DATAS FROM FORM 
//----------------------
    
if(isset($_POST['save_info']) AND $_POST['save_info'] == "yes") {
// generate password
    $str1a = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
    $passFormToCustomer1 = "";
    $passFormToCustomer = "";
    for($i=0; $i<6; $i++) {
        $passFormToCustomer1 .= substr($str1a, rand(0, strlen($str1a) - 1), 1);
    }
    $_SESSION['saveDataFromForm'] = $passFormToCustomer1;
}
else {
    $_SESSION['saveDataFromForm'] = "no";
}
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
<script type="text/javascript">
<!--
function check_form() {
  var error = 0;
  var error_message = "";
  var mode = document.form1.mode.value;

  if(document.form1.elements['mode'].type != "hidden") {
    if(mode == 'select') {
      error_message = error_message + "<?php print PAYALERT;?>";
      error = 1;
    }
  }

  if(error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
function check_form_kandp() {
  var error2 = 0;
  var error_message2 = "";
  var tel = document.form1kandp.TEL.value;

  if(document.form1kandp.elements['TEL'].type != "hidden") {
    if(tel == '') {
      error_message2 = error_message2 + "<?php print TELEPHONE;?> Klik And Pay <?php print CHAMPS_VIDE;?>";
      error2 = 1;
    }
  }

  if(error2 == 1) {
    alert(error_message2);
    return false;
  } else {
    return true;
  }
}
// -->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
<tr height="<?php print $cellTop;?>" valign="top" >

<?php
 
if(isset($logo) AND $logo!=="noPath") {
    // Afficher logo
    print "<td align='left' valign='middle'>";
        $largeurLogo = getimagesize($logo);
        $logoWidth = $largeurLogo[0];
        $widthMaxLogo = 160;
        // If logo width > 160
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
    	// Afficher bandeau
    	print "<td valign='middle' align='center'>";
       	if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm($logo2,0,0)."</a>"; else print detectIm($logo2,0,0);
    	print "</td>";
    }
}
?>
</tr>
<tr>
    <td colspan="3">

             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
              <tr height="32">
               <td><b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print maj(PAIEMENTS);?> |</b>
               </td>
              </tr>
             </table>
    </td>
</tr>

<tr valign="top">
<td colspan="3">


    <br>
    <table align="center" height="100%" border="0" cellpadding="0" cellspacing="5" class="TABLEPageCentreProducts">
    <tr>
    <td valign="top">



            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top" align="left">

<table width="500" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<?php
// DISPAY PAYMENT PROCESS
    display_payment_process(3,"");
?>
</td>
</tr>
</table>


<?php
//-----------------
// Afficher facture
//-----------------
include('includes/plug.inc.php');
print invoice_display($_SESSION['clientCountry'],"TABLE1","",$_POST['shipping']);

// IP address
if($_SERVER['REMOTE_ADDR']) {$ipAddress=$_SERVER['REMOTE_ADDR'];} else {$ipAddress='';}
if($ipAddress!=="") {
    print "<p align='center'>";
    print "<img src='im/zzz.gif' width='1' height='7'><br>";
    print "<span class='TABLEPaymentProcessSelected' style='padding:3px;'>".IPADRESS." : <b>".$ipAddress."</b></span>";
    print "<br><img src='im/zzz.gif' width='1' height='7'>";
    print "</p>";
}

//----------------------------
// Paiement par carte bancaire
//----------------------------
if($paypalPayment == "oui") $paypalDisplay = " ".ETOUM." Paypal"; else $paypalDisplay = "";
print "<div align='center'>";
print round_top('yes',"500","raised2");
print "<table width='500' border='0' cellspacing='0' cellpadding='5' align='center' c/lass='TABLEPaymentProcessSelected'>";
print "<tr><td align='center' style='font-size:15px;'>";
print "<b>".PAIEMENT_CARTE.$paypalDisplay."</b><b>&nbsp;(1)</b>";
print "</td></tr></table>";
print round_bottom('yes');
print "</div>";
print "<br>";

            print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE1'>";
            print "<tr><td>";
				
        // PayPal
        if($paypalPayment == "oui") {
					print "<u><b><span style='font-size:13px;'>".PAIEMENT." via Paypal</span></b></u><br>";
					print PAYPAL_EST_UN_COMPTE_BANCAIRE."<br>";
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table  width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='betaling_met_paypal.php' method='POST'>";
					print "<td>";
					print "<input type='hidden' name='amount' value='".$_SESSION['totalToPayTTC']."'>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/paypal.gif' title='".PAIEMENT." via Paypal' name='submit'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					print "</td></tr>";
					print "<td valign='top' height='2'><hr width='100%'></td>";
					print "</tr>";
				}

        // Pay.nl
				if($paynlPayment == "yes") {
					displayTableTop("Pay.nl");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='paynl/paynl_payment.php' method='post'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/betaal-logos/paynl.gif'>";
					print "<input type='hidden' name='action' value='paynl'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}

				// MoneyBookers
				if($mbPayment == "oui") {
					print "<tr><td><u><b><span style='font-size:13px;'>".PAIEMENT_SECURISE."</span></b></u><br>";
					print "<table width='100%' border='0' cellpadding='10' cellspacing='0'><tr><td>";
					if($mbSecretWord!=="") {
						print "<u><b>".PAIEMENT_CARTE."</b></u>";
						print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
						// Paiement CC
						print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
						print "<form action='moneybookers/payment.php' method='post'>";
						print "<td>";
						print "<input style='BACKGROUND:none; border:0px' type='image' style='background:none' src='im/moneybookers.gif'>";
						print "<input type='hidden' name='action' value='moneybookers'>";
						print "<input type='hidden' name='pay' value='cc'>";
						print "</td>";
						print "</form>";
						print "</tr>";
						print "</table>";
					}
					// Paiement email
					print "<u><b>".PAIEMENT_EMAIL."</b></u>";
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='moneybookers/payment.php' method='post'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' style='background:none' src='im/moneybookers_wallet.gif'>";
					print "<input type='hidden' name='action' value='moneybookers'>";
					print "<input type='hidden' name='pay' value='email'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					print "</td></tr></table>";
					displayTableBottom();
				}

				// Ogone
				if($ogonePayment == "oui") {
					displayTableTop("Ogone");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='ogone/ogone_payment.php' method='post'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/logo_ogone.gif'>";
					print "<input type='hidden' name='action' value='ogone'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}

				// BluePaid
				if($bluepaid == "oui") {
					displayTableTop("BluePaid");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='bluePaid/bluepaid_payment.php' method='post'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/logo_bluepaid.gif'>";
					print "<input type='hidden' name='action' value='bluepaid'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}

				// KlikAndPay
				if($klikandpayActive == "oui") {
					displayTableTop("Klik&Pay");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='kandp/kandp_payment.php' method='post'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='http://www.klikandpay.com/bandeaux/logoblcmy.gif'>";
					print "<input type='hidden' name='action' value='kandp'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}
				
				// POSTFINANCE
				if($pfPayment=="oui") {
					displayTableTop("Postfinance");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\"><tr>";
					print "<form action=\"postfinance/pf_payment.php\" method=\"post\">";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type=\"image\" src=\"im/postfinance1.gif\">";
					print "<input type=\"hidden\" name=\"action\" value=\"Postfinance\">";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}
				
				// Paysitecash
				if($paySiteCash == "oui") {
					displayTableTop("Paysite-Cash");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
					print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
					print "<td>";
					print "<input type='hidden' name='action' value='paysitecash'>";
					print "<input type='hidden' name='toto11' value='yes'>";
					print "<input type='hidden' name='shipping' value='".$_POST['shipping']."'>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='http://www.paysite-cash.biz/images/button-paysite-cash-pay-fr.gif' border='0' name='submit' alt='Paiement s�curis� Paysite Cash, rapide et s�curis�'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}
          
				// EurowebPayment
				if($EuroWebPayment == "oui") {
					displayTableTop("EuroWebPayment");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'>";
					print "<tr>";
					print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
					print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
					print "<input type='hidden' name='clientPassword' value='".$_SESSION['clientPassword']."'>";
					print "<input type='hidden' name='action' value='EuroWebPayment'>";
					print "<input type='hidden' name='toto11' value='yes'>";
					print "<input type='hidden' name='shipping' value='".$_POST['shipping']."'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/betaal-logos/eurowebpayment.gif'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}
				
				// 2CO
				if($co == "oui") {
					displayTableTop("2CheckOut");
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table width='100%' border='0' cellpadding='5' cellspacing='0'>";
					print "<tr>";
					print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
					print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
					print "<input type='hidden' name='clientPassword' value='".$_SESSION['clientPassword']."'>";
					print "<input type='hidden' name='action' value='2checkout'>";
					print "<input type='hidden' name='toto11' value='yes'>";
					print "<input type='hidden' name='shipping' value='".$_POST['shipping']."'>";
					print "<td>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/betaal-logos/2co.gif'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					displayTableBottom();
				}


				// Liaison SSL
				if($liaisonssl == "oui") {
					print "<tr><td>";
					print "<u><b><span style='font-size:13px;'>".CARTE_DE_CREDIT." via <a href='https://www.liaison-ssl.com' target='_blank'>Liaison-SSL</a></span></b></u><br>";
					print PERMET."<br>";
					print "<br>".CLIQUEZ_SUR_LE_LOGO."<br>";
					print "<table border='0' cellpadding='5' cellspacing='0'>";
					print "<tr>";
					print "<form action='liaison_ssl_payment.php' method='post'>";
					print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
					print "<input type='hidden' name='clientPassword' value='".$_SESSION['clientPassword']."'>";
					print "<input type='hidden' name='mode' value='ssl'>";
					print "<td colspan='2'>";
					print "<input style='BACKGROUND:none; border:0px' type='image' src='im/betaal-logos/liaisonssl.gif' name='action' value='".PAIEMENT." via Liaison-SSL'>";
					print "</td>";
					print "</form>";
					print "</tr>";
					print "</table>";
					print "</td></tr>";
					print "<td valign='top' height='2'><hr width='100%'></td>";
					print "</tr>";
				}
            print "</table>";
            
//------------
// Financement
//------------
			if($euroPayment == "oui") {
				print "<br>";
				print "<div align='center'>";
				print round_top('yes',"500","raised2");
				print "<table width='500' border='0' cellspacing='0' cellpadding='5' align='center' c/lass='TABLEPaymentProcessSelected'>";
				print "<tr><td align='center' style='font-size:15px;'>";
				print "<b>".FINANCEMENT."</b>";
				print "</td></tr></table>";
				print round_bottom('yes');
				print "</div>";
				print "<br>";
			
				print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE1'>";
			    print "<tr><td>";
				// 1euro.com
					if($_SESSION['totalToPayTTC'] > 60 AND $_SESSION['totalToPayTTC'] < 8000) {
						print "<div style='font-size:13px;'><b><a href='https://www.1euro.com' target='_blank'><u>1EURO.COM</u></a> finance vos achats sur ".$store_name." !</b></div>";
						print "<div><br>En partenariat avec 1euro.com du groupe Cofidis, $store_name vous permet de <b>financer imm�diatement</b> vos achats en <b>3x</b>, <b>5x</b>, <b>10x</b>, <b>20x</b> ou en <b>petites mensualit�s</b>.</div>";
						
						if($id_partenaire!=="") {
						print "<br><img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a title='Financement 1Euro.com' alt='Financement 1Euro.com' href='javascript:calculette('https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".$_SESSION['totalToPayTTC']."')'><b>Cliquez ici pour simuler le financement de votre paiement avec 1euro.com.</b></a><br><br>";
						}
						print "".CLIQUEZ_SUR_LE_LOGO."<br>";
						print "<table border='0' align='left'><tr>";
						print "<form action='".$_SERVER['PHP_SELF']."' method='post'>";
						print "<td>";
						print "<input style='BACKGROUND:none; border:0px' type='image' src='im/1euro.gif' value='Paiement via 1Euro.com'>";
						print "<input type='hidden' name='nic' value='".$_SESSION['clientNic']."'>";
						print "<input type='hidden' name='action' value='atos'>";
						print "<input type='hidden' name='toto11' value='yes'>";
						print "<input type='hidden' name='shipping' value='".$_POST['shipping']."'>";
						print "</td>";
						print "</form>";
						print "</tr>";
						print "</table>";
					}
					else {
						print "<div align='center'><img src='im/betaal-logos/1euro_g.gif' title='1euro.com' alt='1euro.com' align='absmiddle'></div><br>";
						print "<div align='center'><b>".VOTRE_COMMANDE_EST_DE." <span class='FontColorTotalPrice'>".$_SESSION['totalToPayTTC']." ".$devise."s</span></b></div>";
						print "<div align='center'><img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<b>".FIANCEMENT_A_PARTIR_DE." 60.00 ".$devise."s</b>.</div>";
					}
			    print "</td></tr>";
			    print "</table>";
			}
//------------------
// Paiements manuels
//------------------
if($activerCheq == "oui" OR $activerTraite == "oui" OR $activerMandat == "oui" OR $activerVirement == "oui" OR $activerWestern == "oui" OR $activerContre == "oui") {
	print "<br>";
	print "<div align='center'>";
	print round_top('yes',"500","raised2");
	print "<table width='500' border='0' cellspacing='0' cellpadding='5' align='center' c/lass='TABLEPaymentProcessSelected'>";
	print "<tr><td align='center' style='font-size:15px;'>";
	print "<b>".PAIEMENT_AUTRE."</b><b>&nbsp;(2)</b>";
	print "</td></tr></table>";
	print round_bottom('yes');
	print "</div>";
	print "<br>";

		print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE1'>";
		print "<tr><td>";
		// Virement mandat ou cheque etc...
		print "<tr>";
		print "<td valign='top'>";
		// print "<span style='font-size:11px;'>";
		print "<b>";
		if($activerCheq == "oui") print - CHEQUE_BANCAIRE;
		if($activerTraite == "oui") print " - ".TRAITE_BANCAIRE;
		if($activerMandat == "oui") print " - ".MANDAT_POSTAL;
		if($activerVirement == "oui") print " - ".VIREMENT_BANCAIRE;
		if($activerWestern == "oui") print " - Western Union";
		if($activerContre == "oui") print " - ".CONTRE_REMBOURSEMENT;
		
		print "</b>";
		print "</span>";
		print "<br><br>";
		print LE_MONTANT_ENVOYE_DOIT_ETRE." ".maj($devise).".<br>";
		print LE_MONTANT_ENVOYE_DOIT_ETRE2." <b>".$_SESSION['totalToPayTTC']." ".$symbolDevise."</b> (".$devise.")";
		print ".";
		
		print "<table border='0' cellpadding='0' cellspacing='5'><tr>";
		
		print "<form action='bevestigd.php' method='post' name='form1' onsubmit='return check_form()'>";
		print "<td>".MODE_DE_PAIEMENT."<b>&nbsp;(2)</b>&nbsp;</td><td>";
		print "<select name='mode'>";
		print "<option value='select'>".SELECTIONNER_MODE_DE_PAIEMENT."</option>";
		
		// cheque
		if($activerCheq == "oui") print "<option value='ch'>".CHEQUE_BANCAIRE."</option>";
		// Traite bancaire
		if($activerTraite == "oui") print "<option value='tb'>".TRAITE_BANCAIRE."</option>";
		// mandat postal
		if($activerMandat == "oui") print "<option value='ma'>".MANDAT_POSTAL."</option>";
		// bank overschrijving
		if($activerVirement == "oui") print "<option value='BO'>".VIREMENT_BANCAIRE."</option>";
		// Western Union
		if($activerWestern == "oui") print "<option value='wu'>Western Union</option>";
		// contre remboursement
		//$contriesIn = array('Germany', 'Belgium', 'Netherlands');
		//if($activerContre == "oui" AND in_array($_SESSION['clientCountry'], $contriesIn)) {
		if($activerContre == "oui") {
			$seuilContreRemboursement = sprintf("%0.2f",$seuilContre);
			print "<option value='BL'>".CONTRE_REMBOURSEMENT." | +".$seuilContreRemboursement." ".$symbolDevise."</option>";
		}
		
		print "</select>";
		print "</td></tr><tr>";
		print "<td colspan='2'>";
		print "<input type='submit' name='action' value='".CONTINUER."'>";
		print "</td>";
		print "</form>";
		print "</tr></table>";
		print "</td>";
		print "</tr>";
		print "</table>";
}

             print "<br>";
             print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center'>";
             print "<tr>";
             print "<td>";
             print UNE_FOIS_VOTRE_PAIEMENT_CONFIRME;
             print "</td>";
             print "</tr></table>";
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

<?php include("includes/footer.php");?>

<br>
<table width="99%" align="center" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td align='center' valign='bottom'>Creatie: <a href="http://www.webhouse.be" target="_blank"><b>Webhouse</b></a></td>
	</tr>
</table>

</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
