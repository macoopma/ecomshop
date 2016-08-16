<?php
include('../configuratie/configuratie.php');
$dir="../";
include('../includes/plug.php');
if(isset($_SESSION['recup'])) {header("Location: ../caddie.php"); exit;}


include("../includes/lang/lang_".$_SESSION['lang'].".php");
$title = "MoneyBookers";
$displayPayments = 0;

function addToBdd($pass,$nic) {
        global $_SESSION;
        include('../configuratie/configuratie.php');
        $dateNow = date("Y-m-d H:i:s");
        $paymentMode = "mb";
        $query = mysql_query("SELECT users_password, users_nic
                              FROM users_orders
                              WHERE users_password = '".$pass."'
                              AND users_nic = '".$nic."'");
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
                                )");
                                
                     
								$splitUp = explode(",",$_SESSION['list']);
								foreach ($splitUp as $item) {
					         		$check = explode("+",$item);
                              if($check[3]=="GC100") {$gc[]=$check[2];} else {$gc[]=0;} // Contrôle cheque cadeau dans la commande
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
             else {

                  mysql_query("UPDATE users_orders SET users_payment='mb' WHERE users_nic = '".$nic."' AND users_password = '".$pass."'");
            }
}

if(!isset($_GET['add'])) addToBdd($_SESSION['clientPassword'],$_SESSION['clientNic']);
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
<title><?php print $title." | ".$store_name;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if(!empty($_SESSION['userInterface'])) {
   print "<link rel=\"stylesheet\" href=\"../css/".$_SESSION['userInterface'].".css\" type=\"text/css\">";
}
else {
   if($activerCouleurPerso=="oui") {
      print "<link rel=\"stylesheet\" href=\"../css/perso.css\" type=\"text/css\">";
   }
   else {
      print "<link rel=\"stylesheet\" href=\"../css/".$colorInter.".css\" type=\"text/css\">";
   }
}
if(isset($backgroundImageHeader) AND $backgroundImageHeader!=="noPath") {
print '<style type="text/css">.backGroundTop {background-color: #none; background-image: url(../im/'.$backgroundImageHeader.'); background-repeat: no-repeat; background-position: right top}</style>';
}
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="<?php print $_SESSION['storeWidthUser'];?>" align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre"><tr>
<td width="1" class="borderLeft"></td><td valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
<tr height="<?php print $cellTop;?>" valign="top" >

<?php


if(isset($logo) AND $logo!=="noPath") {

    print "<td align='left' valign='middle'>";
        $largeurLogo = getimagesize("../".$logo);
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
        print detectIm("../".$logo,$wwww,$hhhh);
    print "</td>";
}
else {
    if(isset($logo2) AND $logo2!=="noPath") {

    print "<td valign='middle' align='center'>";
       if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm("../".$logo2,0,0)."</a>"; else print detectIm("../".$logo2,0,0);
    print "</td>";
    }
}
?>
</tr>
       <tr>
    <td colspan="3" >
    
             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEBottomPage">
              <tr height="32">
               <td><b><img src="../im/accueil.gif" align="TEXTTOP">&nbsp;<a href="../cataloog.php" ><?php print maj(HOME);?></a> | MONEYBOOKERS |</b>
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



            <table width="100%" border="0" cellspacing="0" cellpadding="3" class="TABLEPageCentreProducts-sample" align="center" height="100%">
              <tr>
                <td valign="top" align="left">

<table width="500" align="center" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
<?php

if(!isset($_GET['add'])) {
    display_payment_process(4,"../");
}
?>
</td>
</tr></table>

<?php
 
if(!isset($_GET['add'])) {
include('../includes/plug.inc.php');
invoice_display($_SESSION['clientCountry'],"TABLE1","../",$_SESSION['shippingId']);
}



print "<br>";

 
            print "<table width=\"500\" border=\"0\" cellspacing=\"3\" cellpadding=\"0\" align=\"center\" class=\"TABLE1\">";
            print "<tr><td>";
            print "<u><b>".PAIEMENT_CARTE."</b></u><br><br>";
            print VOUS_AVEZ_CHOISI." <b>".$_SESSION['clientEmail']."</b>.<br>".VOUS_AVEZ_CHOISI2."<br><br>";

?>
<table  width="100%" border="0" cellpadding="0" cellspacing="5"><tr><td>

<?php
 
switch($_SESSION['lang']) {
   case 1:
      $langid = "FR";
   break;
   case 2:
      $langid = "EN";
   break;
   case 3:
      $langid = "EN";
   break;
   default:
      $langid = "FR";
}


$__gender = ($_SESSION['clientGender']=="M")? "Mr" : "Mrs";
$return_url = "http://".$www2.$domaineFull."/moneybookers/ok.php?id=".$_SESSION['clientNic'];
$cancel_url = "http://".$www2.$domaineFull."/cataloog.php";
$status_url = "http://".$www2.$domaineFull."/moneybookers/response.php";
$_store_name = strip_tags($store_name);
 
$requestCountryZ = mysql_query("SELECT iso FROM countries WHERE countries_name = '".$_SESSION['clientCountry']."'");
$countriesZ = mysql_fetch_array($requestCountryZ);
$iso3 = $countriesZ['iso'];
 

 
print '<form action="https://www.moneybookers.com/app/payment.pl" method="post">';
print '<input type="hidden" name="pay_to_email" value="'.$mbEmail.'">';
print '<input type="hidden" name="status_url" value="'.$status_url.'">';
print '<input type="hidden" name="pay_from_email" value="'.$_SESSION['clientEmail'].'">';
print '<input type="hidden" name="language" value="'.$langid.'">';
print '<input type="hidden" name="amount" value="'.$_SESSION['totalToPayTTC'].'">';
print '<input type="hidden" name="transaction_id" value="'.$_SESSION['clientNic'].'">';
print '<input type="hidden" name="return_url" value="'.$return_url.'">';
print '<input type="hidden" name="cancel_url" value="'.$cancel_url.'">';
print '<input type="hidden" name="currency" value="'.$mbDevise.'">';
print '<input type="hidden" name="title" value="'.$__gender.'">';
print '<input type="hidden" name="firstname" value="'.$_SESSION['clientFirstname'].'">';
print '<input type="hidden" name="lastname" value="'.$_SESSION['clientLastname'].'">';
print '<input type="hidden" name="address" value="'.$_SESSION['clientStreetAddress'].'">';
print '<input type="hidden" name="address2" value="'.$_SESSION['clientSurburb'].'">';
print '<input type="hidden" name="postal_code" value="'.$_SESSION['clientPostCode'].'">';
print '<input type="hidden" name="city" value="'.$_SESSION['clientCity'].'">';
print '<input type="hidden" name="state" value="'.$_SESSION['clientProvince'].'">';
print '<INPUT TYPE="hidden" NAME="country" VALUE="'.$iso3.'">';
print '<INPUT TYPE="hidden" NAME="merchant_fields" VALUE="referring_platform">';
print '<INPUT TYPE="hidden" NAME="referring_platform" VALUE="Ecomshop">';
print '<input type="hidden" name="recipient_description" value="'.$_store_name.'">';

if(isset($_POST['pay']) AND $_POST['pay']=='cc') {
   print '<input type="hidden" name="hide_login" value="1">';
   print '<input type="hidden" name="payment_methods" value="ACC">';
   $extLogo = "";
}
if(isset($_POST['pay']) AND $_POST['pay']=='email') $extLogo = "_wallet";
 
print "<input type='image' src='../im/moneybookers".$extLogo.".gif'>";
 
if(isset($logo) AND $logo!=="noPath") {
print '<input type="hidden" name="logo_url" value="http://'.$www2.$domaineFull.'/'.$logo.'">';
}
print '</form>';
 
?>
</td>
</tr>
</table>

<?php
  print "</td></tr></table>";
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
<?php include("../includes/footer.php");?>

</td>
<td width="1" class="borderLeft"></td>
</tr></table>
</body>
</html>
