<?php
include('configuratie/configuratie.php');
$_GET['var'] = "session_destroy";
include('includes/plug.php');
include('includes/doctype.php');


include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = PAIEMENTS;
$displayPayments = 0;

function tax_priceZ($_pays) {
	$_a = mysql_query("SELECT countries_product_tax, countries_shipping_tax FROM countries WHERE countries_name = '".$_pays."'") or die (mysql_error());
	$_b = mysql_fetch_array($_a);
	$_tax = $_b['countries_product_tax'];
	$_tax2 = $_b['countries_shipping_tax'];
	$montant_taxe_prod = sprintf("%0.2f",$_tax);
	$montant_taxe_ship = sprintf("%0.2f",$_tax2);
	return array($montant_taxe_prod, $montant_taxe_ship);
}

 
function removeAp($value) {
    $valueNew = str_replace("\'","&#146;",$value);
    $valueNew = str_replace("'","&#146;",$valueNew);
    return $valueNew;
}
 

if(isset($_SESSION['contre'])) unset($_SESSION['contre']);

if($taxePosition == "Tax included") $_SESSION['taxStatut'] = "TTC";
if($taxePosition == "Plus tax") $_SESSION['taxStatut'] = "HT";
if($taxePosition == "No tax") $_SESSION['taxStatut'] = "";


if(isset($_POST['nic'])) $_GET['nic']=$_POST['nic'];
if(isset($_POST['clientNic'])) {
	$_GET['nic']=$_POST['clientNic'];
	$_SESSION['clientNic'] = $_POST['clientNic'];
}
if(isset($_POST['totalToPayTTC'])) {
	$_SESSION['totalToPayTTC'] = $_POST['totalToPayTTC'];
}
if(isset($_POST['clientEmail'])) {
	$_SESSION['clientEmail'] = $_POST['clientEmail'];
}

// Paysite-Cash
if(isset($_POST['action']) AND $_POST['action'] == "paysitecash") {
      // Ajouter to database
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
if(isset($_POST['action']) and $_POST['action'] == "EuroWebPayment") {
	// Ajouter to database
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
if(isset($_POST['action']) and $_POST['action'] == "2checkout") {

	function change($subtotal,$defaultDevise) {
			$fp = fsockopen("www.2checkout.com", 80, $errmsg, $errno, 30) or die("$errno:$errmsg");
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
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<script type="text/javascript">
<!--
function check_form() {
  var error = 0;
  var error_message = "";
  var mode = document.form1.mode.value;

  if(document.form1.elements['mode'].type != "hidden") {
    if(mode == 'select') {
      error_message = error_message + "Selectionner un mode de paiement.";
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

<table width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

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
    <td colspan="3" >

             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
              <tr height="32">
               <td><b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print maj(PAIEMENT_DIRECT);?> |</b>
               </td>
              </tr>
             </table>
</td>
</tr>

<tr valign="top">
<td colspan="3">


    <table width="100%" border="0" cellpadding="0" cellspacing="5">
    <tr>
    <td valign="top">



            <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" height="100%">
              <tr>
                <td valign="top" align="left">

<?php
//------------
// Requete SQL
//------------
$query1000 = mysql_query("SELECT * FROM users_orders WHERE users_nic='".$_GET['nic']."'");
if(mysql_num_rows($query1000)>0 AND $paymentsDesactive=="non") {
$row = mysql_fetch_array($query1000);
// General datas
$_SESSION['totalToPayTTC'] = $row['users_total_to_pay'];
$_SESSION['clientNic'] = $row['users_nic'];
$_SESSION['clientPassword'] = $row['users_password'];
$_SESSION['clientEmail'] = $row['users_email'];
$_SESSION['account'] = $row['users_password'];
$_SESSION['clientCountry'] = $row['users_country'];
// moneybookers datas
$_SESSION['clientGender'] = $row['users_gender'];
$_SESSION['clientStreetAddress'] = $row['users_address'];
$_SESSION['clientSurburb'] = $row['users_surburb'];
$_SESSION['clientPostCode'] = $row['users_zip'];
$_SESSION['clientCity'] = $row['users_city'];
$_SESSION['clientProvince'] = $row['users_province'];
$commandeTraitee = $row['pp_statut'];
$_SESSION['fact_adresse'] = $row['users_facture_adresse'];

$factName = explode("|",$row['users_facture_adresse']);
$factNameZ = explode(" ",$factName[0]);
$_SESSION['clientFirstname'] = $factNameZ[1];
$_SESSION['clientLastname'] = $factNameZ[0];


 



print "<br>";
print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE1'>";
print "<tr>";
print "<td align='center'><b>Ref/".ARTICLES."</b></td>";
print "<td width='50' align='center'><b>Qt</b></td>";
print "<td width='50' align='center'><b>".PRIX_UNITAIRE."</b></td>";
print "<td width='80' align='right'><b>".PRIX." ".strtolower(TOTAL)."</b></td>";

$split = explode(",",$row['users_products']);
foreach ($split as $item) {
$check = explode("+",$item);

if($check[1]!=="0") {
// Options
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
print "</tr><tr>";

print "<td>&bull;&nbsp;<b>".maj($check[3])."</b><br>".$check[4].$q."<br><img src='im/zzz.gif' width='1' height='3'><br></td>";
 
if(isset($check[7])) {$ecoTaxFact[] = $check[7]*$check[1];} else {$ecoTaxFact[] = 0;}
 
print "<td width='50' align='center'>".$check[1]."</td>";
 
print "<td width='50' align='center'>".$check[2]."</td>";
 
$priceTTC = ($check[2] * $check[1]);
print "<td width='80' align='right'>".sprintf("%0.2f",$priceTTC)."</td>";
}
}
print "</tr><tr><td colspan='5'>";
$la = 120;
$yoyo = tax_priceZ($row['users_country']);

print "<hr width='95%'>";
print "<table border='0' align='center' width='100%' style='padding:5px' align='right' cellspacing='0' cellpadding='2'><tr>";
print "<td align='right'>".SOUS_TOTAL."</td>";
print "<td align='right' width='".$la."'><b>".$row['users_products_ht']."</b></td>";
print "</tr><tr>";
print "<td align='right'>".$taxeName."</td>";


                print "<td align='right' width='".$la."'>";
                $explodMultiple = explode("|",$row['users_multiple_tax']);
                $explodMultipleNum = count($explodMultiple);
                    foreach ($explodMultiple as $item) {
                        if($item == "0.00>0.00") {
                            $itemDisplay = "";
                        }
                        else {
                            if($explodMultipleNum > 1) {$br = "<br>";} else {$br = "";}
                            $itemDisplay = str_replace(">", "%: ", $item).$br;
                        }
                        print $itemDisplay;
                    }
                print "</td>";

 
print "</tr><tr>";
print "<td align='right'>".LIVRAISON."</td>";
print "<td align='right' width='".$la."'>".$row['users_ship_ht']."</td>";
print "</tr><tr>";
print "<td align='right'>".$taxeName." ".$yoyo[1]."% ".strtolower(LIV)."</td>";
print "<td align='right' width='".$la."'>".$row['users_ship_tax']."</td>";

 
if($row['users_sup_ttc'] > 0) {
                print "</tr><tr>";
                print "<td align='right'>".PAKING_COST."</td>";
                print "<td align='right'>".$row['users_sup_ht']."</td>";
                print "</tr><tr>";
                print "<td align='right'>".$taxeName." ".$taxe."%</td>";
                print "<td align='right'>".$row['users_sup_tax']."</td>";
}


print "</tr><tr>";



if($row['users_account_remise_app'] > 0) {
	print "<td align='right'>".POINTS_FIDELITE."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_account_remise_app']."</span></td>";
	print "</tr><tr>";
}
if($row['users_remise']>0) {
	print "<td align='right'>".REMISE."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_remise']."</span></td>";
	print "</tr><tr>";
}
if($row['users_remise_coupon']>0) {
	print "<td align='right'>".COUPON_CODE."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_remise_coupon']."</span></td>";
	print "</tr><tr>";
}
if($row['users_remise_gc']>0) {
	print "<td align='right'>".CHEQUE_CADEAU_MIN."</td>";
	print "<td align='right' width='".$la."'><span class='fontrouge'>-".$row['users_remise_gc']."</span></td>";
	print "</tr><tr>";
}
if($row['users_contre_remboursement']>0) {
	print "<td align='right'>".CONTRE_REMBOURSEMENT."</td>";
	print "<td align='right' width='".$la."'>".$row['users_contre_remboursement']."</td>";
	print "</tr><tr>";
}

print "<td align='right' class='large'><b>".strtoupper(TOTAL)."</b>:</td>"    ;
print "<td align='right'class='large' width='".$la."'><b>".$row['users_symbol_devise']." ".$row['users_total_to_pay']."</b></td>";
print "</tr>";
print "</table>";
print "</td>";
print "</tr></table>";
print "<br>";

























function displayTableTop($nom) {
    print "<tr><td><u><b>".CARTE_DE_CREDIT." | ".$nom."</b></u><br>";
    print LE_PAIEMENT_PAR_CARTE_DE_CREDIT." ".$nom.".<br>";
}
function displayTableBottom() {
    print "</td></tr>";
    print "<td valign='top' height='2'><hr width='100%'></td>";
    print "</tr>";
}

            print "<table width='500' border='0' cellspacing='3' cellpadding='0' align='center' class='TABLE1'>";
            print "<tr><td>";

         // PayPal
         if($paypalPayment == "oui") {
            print "<u><b>".PAIEMENT." via Paypal</b></u>&nbsp;*<br>";
            print PAYPAL_EST_UN_COMPTE_BANCAIRE."<br>";
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
            print "<table  width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
            print "<form action='betaling_met_paypal.php?add=0' method='post'>";
            print "<td>";
            print "<input type='hidden' name='amount' value='".$_SESSION['totalToPayTTC']."'>";
            print "<input style='BACKGROUND: none; border:0px' type='image' src='im/paypal.gif' alt='".PAIEMENT." via Paypal' title='".PAIEMENT." via Paypal' name='submit' value='".PAIEMENT." via Paypal'>";
            print "</td>";
            print "</form>";
            print "</tr>";
            print "</table>";
            
            print "</td></tr>";
            print "<td valign='top' height='2'><hr width='100%'></td>";
            print "</tr>";
         }

         // MoneyBookers
         if($mbPayment=="oui") {
            if($commandeTraitee=="") {
                if($mbSecretWord!=="") {
                      print "<tr><td><u><b>".CARTE_DE_CREDIT." | MoneyBookers</b></u>&nbsp;*<br>";
                      print LE_PAIEMENT_PAR_CARTE_DE_CREDIT." MoneyBookers.<br>";
                      print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
                      print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
                      print "<form action='moneybookers/payment.php?add=0' method='post'>";
                      print "<td>";
                      print "<input type='image' src='im/moneybookers.gif' value='".PAIEMENT." via MoneyBookers'>";
                      print "<input type='hidden' name='action' value='moneybookers'>";
                      print "<input type='hidden' name='pay' value='cc'>";
                      print "</td>";
                      print "</form>";
                      print "</tr>";
                      print "</table>";
                      print "</td></tr>";
                }
                // Paiement email
                print "<tr><td><u><b>".PAIEMENT_EMAIL."</b></u>";
                print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
                print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
                print "<form action='moneybookers/payment.php?add=0' method='post'>";
                print "<td>";
                print "<input type='image' style='background:none' src='im/moneybookers_wallet.gif'>";
                print "<input type='hidden' name='action' value='moneybookers'>";
                print "<input type='hidden' name='pay' value='email'>";
                print "</td>";
                print "</form>";
                print "</tr>";
                print "</table>";
                displayTableBottom();
             }
             else {
               displayTableTop("MoneyBookers");
               print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
               print "<td class='PromoFontColorNumber'>";
               print CETTE_COMMANDE." (Ref: <b>".$_SESSION['clientNic']."</b>) ".A_DEJA_ETE_TRAITEE;
               print "</td>";
               print "</tr>";
               print "</table>";
               displayTableBottom();
             }
         }
         
// 1euro.com
         if($euroPayment == "oui") {
              if($_SESSION['totalToPayTTC'] > 60 AND $_SESSION['totalToPayTTC'] < 8000) {
                  print "<tr><td><a href='https://www.1euro.com' target='_blank'><u><b>1EURO.COM</b></u></a><br>";
                  print "<b>Financez vos achats sur ".$store_name." !</b><br>
                          En partenariat avec 1euro.com du groupe Cofidis, $store_name vous permet de <b>financer immédiatement</b> vos achats en <b>3x</b>, <b>5x</b>, <b>10x</b>, <b>20x</b> ou en <b>petites mensualités</b>.<br>";
          
                  if($id_partenaire!=="" AND $displayGraphics == "oui") {
                     print "<br><img src='im/fleche_menu.gif' align='absmiddle'>&nbsp;<a alt='Financement 1Euro.com' title='Financement 1Euro.com' href='javascript:calculette(\"https://www.1euro.com/1euro/calculetteTEG.do?idPartenaire=".$id_partenaire."&montant=".$_SESSION['totalToPayTTC']."\")'><b>Cliquez ici pour simuler le financement de votre paiement avec 1euro.com.</b></a><br><br>";
                  }
          
                  print "<i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
                  print "<table  border='0' align='left'><tr>";
                  print "<form action='1euro/cgi/paiement.php?add=0' method='post'>";
                  print "<td>";
                  print "<input type='image' src='im/1euro.gif' value='Paiement via 1Euro.com'>";
                  print "<input type='hidden' name='nic' value='".$_SESSION['clientNic']."'>";
                  print "<input type='hidden' name='action' value='atos'>";
                  print "</td>";
                  print "</form>";
                  print "</tr>";
                  print "</table>";
                  displayTableBottom();
              }
         }

             // Ogone
             if($ogonePayment == "oui") {
             displayTableTop("Ogone");
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
             print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
             print "<form action='ogone/ogone_payment.php?add=0' method='post'>";
             print "<td>";
             print "<input type='image' src='im/logo_ogone.gif' value='".PAIEMENT." via Ogone'>";
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
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
             print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
             print "<form action='bluePaid/bluepaid_payment.php?add=0' method='post'>";
             print "<td>";
             print "<input type='image' src='im/logo_bluepaid.gif' value='".PAIEMENT." via BluePaid'>";
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
             print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
             print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
             print "<form action='kandp/kandp_payment.php?add=0' method='post'>";
             print "<td>";
             print "<input type='image' src='http://www.klikandpay.com/bandeaux/logoblcmy.gif'>";
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
			    print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
				print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
				print "<form action='postfinance/pf_payment.php?add=0' method='post'>";
				print "<td>";
				print "<input type='image' src='im/postfinance1.gif'>";
				print "<input type='hidden' name='action' value='Postfinance'>";
				print "</td>";
				print "</form>";
				print "</tr>";
				print "</table>";
			    displayTableBottom();
			}

             // Paysitecash
             if($paySiteCash == "oui") {
             displayTableTop("Paysite-Cash");
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
              print "<table width='100%' border='0' cellpadding='5' cellspacing='0'><tr>";
              print "<form action='".$_SERVER['PHP_SELF']."?add=0' method='post'>";
              print "<td>";
              print "<input type='hidden' name='action' value='paysitecash'>";
              print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
              print "<input type='hidden' name='totalToPayTTC' value='".$_SESSION['totalToPayTTC']."'>";
              print "<input type='hidden' name='clientEmail' value='".$_SESSION['clientEmail']."'>";
              print '<input type="image" src="http://www.paysite-cash.biz/images/button-paysite-cash-pay-fr.gif" border="0" name="submit" alt="Paiement sécurisé Paysite Cash, rapide et sécurisé">';
              print "</td>";
              print "</form>";
              print "</tr>";
              print "</table>";
          displayTableBottom();
          }
          
          // EurowebPayment
          if($EuroWebPayment == "oui") {
          displayTableTop("EuroWebPayment");
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
            print "<table width='100%' border='0' cellpadding='5' cellspacing='0'>";
            print "<tr>";
            print "<form action='".$_SERVER['PHP_SELF']."?add=0' method='post'>";
            print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
            print "<input type='hidden' name='clientPassword' value='".$_SESSION['clientPassword']."'>";
            print "<input type='hidden' name='totalToPayTTC' value='".$_SESSION['totalToPayTTC']."'>";
            print "<input type='hidden' name='action' value='EuroWebPayment'>";
            print "<td>";
            print "<input type='image' src='im/betaal-logos/eurowebpayment.gif' value='".PAIEMENT." via EuroWebPayment'>";
            print "</td>";
            print "</form>";
            print "</tr>";
            print "</table>";
           displayTableBottom();
           }

         // 2CO
         if($co == "oui") {
         displayTableTop("2CheckOut");
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
          print "<table width='100%' border='0' cellpadding='5' cellspacing='0'>";
          print "<tr>";
          print "<form action='".$_SERVER['PHP_SELF']."?add=0' method='post'>";
          print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
          print "<input type='hidden' name='clientPassword' value='".$_SESSION['clientPassword']."'>";
          print "<input type='hidden' name='totalToPayTTC' value='".$_SESSION['totalToPayTTC']."'>";
          print "<input type='hidden' name='clientEmail' value='".$_SESSION['clientEmail']."'>";
          print "<input type='hidden' name='action' value='2checkout'>";
          print "<td>";
          print "<input type='image' src='im/betaal-logos/2co.gif' value='".PAIEMENT." via 2checkout'>";
          print "</td>";
          print "</form>";
          print "</tr>";
          print "</table>";
          displayTableBottom();
          }


         // Liaison SSL
         if($liaisonssl == "oui") {
            print "<tr><td>";
            print "<u><b>".CARTE_DE_CREDIT." via <a href='https://www.liaison-ssl.com' target='_blank'>Liaison-SSL</a></b></u>&nbsp;*<br>";
            print PERMET."<br>";
            print "<br><i>".CLIQUEZ_SUR_LE_LOGO."</i><br>";
            print "<table border='0' cellpadding='5' cellspacing='0'>";
            print "<tr>";
            print "<form action='liaison_ssl_payment.php?add=0' method='post'>";
            print "<input type='hidden' name='clientNic' value='".$_SESSION['clientNic']."'>";
            print "<input type='hidden' name='clientPassword' value='".$_SESSION['clientPassword']."'>";
            print "<input type='hidden' name='mode' value='ssl'>";
            print "<td colspan='2'>";
            print "<input type='image' src='im/betaal-logos/liaisonssl.gif' name='action' value='".PAIEMENT." via Liaison-SSL'>";
            print "</td>";
            print "</form>";
            print "</tr>";
            print "</table>";
                 
            print "</td></tr>";
            print "<td valign='top' height='2'><hr width='100%'></td>";
            print "</tr>";
          }

            print "</table>";
}
else {
	$displayMessage = ($paymentsDesactive=="oui")? "Paiement désactivé." : "Aucune commande a été trouvé!";
	print "<br><img src='im/zzz.gif' width='1' height='30'><br><p align='center' class='titre'><b>".$displayMessage."</b></p>";
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

<?php include("includes/footer.php");?>

</td>
<td width="1" class="borderLeft"></td>
</tr></table>
</body>
</html>
