<?php
include('configuratie/configuratie.php');
include('includes/plug.php');



if(!isset($_POST['toto11']) AND !isset($_POST['actionLiv'])) {header("location: payment.php?cond=0"); exit;}
if(isset($_SESSION['recup'])) {header("Location: caddie.php"); exit;}
include("includes/lang/lang_".$_SESSION['lang'].".php");
$title = MODE_DE_LIVRAISON;
$displayPayments = 0;
$messageEr="";


function removeAp($value) {
    $valueNew = str_replace("\'","&#146;",$value);
    $valueNew = str_replace("'","&#146;",$valueNew);
    return $valueNew;
}

if(!isset($_SESSION['devisNumero'])) {
if(!isset($_POST['clientGender']) AND !isset($_SESSION['clientGender'])) {header("Location: caddie.php");}
if(!isset($_POST['clientFirstname']) AND !isset($_SESSION['clientFirstname'])) {header("Location: caddie.php");}
if(!isset($_POST['clientLastname']) AND !isset($_SESSION['clientLastname'])) {header("Location: caddie.php");}
if(!isset($_POST['clientEmail']) AND !isset($_SESSION['clientEmail'])) {header("Location: caddie.php");}
if(!isset($_POST['clientTelephone']) AND !isset($_SESSION['clientTelephone'])) {header("Location: caddie.php");}
if(!isset($_POST['clientProvince'])) $_POST['clientProvince']="";
}

if(!isset($_SESSION['clientGender']) AND isset($_POST['clientGender'])) $_SESSION['clientGender'] = removeAp($_POST['clientGender']);
if(!isset($_SESSION['clientFirstname']) AND isset($_POST['clientFirstname'])) $_SESSION['clientFirstname'] = removeAp($_POST['clientFirstname']);
if(!isset($_SESSION['clientLastname']) AND isset($_POST['clientLastname'])) $_SESSION['clientLastname'] = removeAp($_POST['clientLastname']);
if(!isset($_SESSION['clientCompany']) AND isset($_POST['clientCompany'])) $_SESSION['clientCompany'] = removeAp($_POST['clientCompany']);
if(!isset($_SESSION['clientEmail']) AND isset($_POST['clientEmail'])) $_SESSION['clientEmail'] = removeAp($_POST['clientEmail']);
if(!isset($_SESSION['clientStreetAddress']) AND isset($_POST['clientStreetAddress'])) $_SESSION['clientStreetAddress'] = removeAp($_POST['clientStreetAddress']);
if(!isset($_SESSION['clientSurburb']) AND isset($_POST['clientSurburb'])) $_SESSION['clientSurburb'] = removeAp($_POST['clientSurburb']);
if(!isset($_SESSION['clientPostCode']) AND isset($_POST['clientPostCode'])) $_SESSION['clientPostCode'] = removeAp($_POST['clientPostCode']);
if(!isset($_SESSION['clientProvince']) AND isset($_POST['clientProvince'])) $_SESSION['clientProvince'] = removeAp($_POST['clientProvince']);
if(!isset($_SESSION['clientCountry']) AND isset($_POST['clientCountry'])) $_SESSION['clientCountry'] = removeAp($_POST['clientCountry']);
if(!isset($_SESSION['clientCity']) AND isset($_POST['clientCity'])) $_SESSION['clientCity'] = removeAp($_POST['clientCity']);
if(!isset($_SESSION['clientTelephone']) AND isset($_POST['clientTelephone'])) $_SESSION['clientTelephone'] = removeAp($_POST['clientTelephone']);
if(!isset($_SESSION['clientFax']) AND isset($_POST['clientFax'])) $_SESSION['clientFax'] = removeAp($_POST['clientFax']);
if(!isset($_SESSION['clientComment']) AND isset($_POST['clientComment'])) $_SESSION['clientComment'] = removeAp($_POST['clientComment']);
if(!isset($_SESSION['clientTVA']) AND isset($_POST['clientTVA'])) $_SESSION['clientTVA'] = removeAp($_POST['clientTVA']);

 
$tvaValidationQuery = mysql_query("SELECT users_pro_tva_confirm FROM users_pro WHERE users_pro_password='".$_SESSION['account']."'");
if(mysql_num_rows($tvaValidationQuery)==0) {
    $_SESSION['clientTVA'] = "";
}
else {
    $tvaValidation = mysql_fetch_array($tvaValidationQuery);
    if($tvaManuelValidation=="oui" AND $tvaValidation['users_pro_tva_confirm']!=='yes') $_SESSION['clientTVA'] = "";
    if($tvaValidation['users_pro_tva_confirm']=='no') $_SESSION['clientTVA'] = "";
}

 
if(!isset($_SESSION['fact_adresse']) OR empty($_SESSION['fact_adresse'])) {
      $_SESSION['fact_adresse'] = removeAp($_POST['clientFactComp'])."|".
      removeAp($_POST['clientFactCompany'])."|".
      removeAp($_POST['clientFactAddress'])."|".
      removeAp($_POST['clientFactSurburb'])."|".
      removeAp($_POST['clientFactCode'])."|".
      removeAp($_POST['clientFactVille'])."|".
      removeAp($_POST['clientFactPays'])."|".
      removeAp($_POST['clientTVA']);
}

 
$splitZ = explode(",",$_SESSION['list']);
foreach ($splitZ as $item) {
    $check = explode("+",$item);
    $requestShipDownload = mysql_query("SELECT products_download, products_tax, products_weight, products_taxable FROM products WHERE products_id = '".$check[0]."'");
    $resultShipDownload = mysql_fetch_array($requestShipDownload);
    if($resultShipDownload['products_download']=="yes") $down[]=1;
  
                      $tutu = tax_price($_SESSION['clientCountry'],$resultShipDownload['products_tax']);
                      if($resultShipDownload['products_taxable']=="yes") {
                           if($taxePosition=="Tax included") {
                              $priceTTC = $check[2] * $check[1];
                              $totalht = $priceTTC*100/($tutu[0]+100);
                           }
                           if($taxePosition=="Plus tax") {
                              $totalht = $check[2] * $check[1];
                           }
                           if($taxePosition=="No tax") {
                              $priceTTC = $check[2] * $check[1];
                              $totalht = $priceTTC;
                           }
                        }
                        else {
                           $priceTTC = ($check[2] * $check[1]);
                           $totalht = $priceTTC;
                        }
                        $totalHtArray[] = $totalht;
   
                        $p_ = $resultShipDownload['products_weight'];
                        if($resultShipDownload['products_download'] == "yes") {$p_=0;}
                        $poidsOptionsArray = explode('|',$check[8]);
                        $poidsOptions = sprintf("%0.2f",array_sum($poidsOptionsArray));
                        $poid[] = ($check[1]*$p_)+($check[1]*$poidsOptions);
}

$_totalht = sprintf("%0.2f",array_sum($totalHtArray));
$_poidZ = sprintf("%0.2f",array_sum($poid));
 
if(isset($down) AND array_sum($down)==count($splitZ) AND $_poidZ==0) {
   header("Location: selecteer_betaling.php?dl=1");
   exit;
}

 
include('includes/doctype.php');
?>
<html>

<head>
<?php include('includes/hoofding.php');?>
<script type="text/javascript">
function formShip() {
<!--
var radioType = document.getElementsByName("shipping");
var checked = false;
for(var cpt = 0 ; (cpt < radioType.length) && !checked ; cpt++) {
checked = checked || radioType[cpt].checked;
}
if(!checked) {
alert("Selecteer een betaalwijze");
return false;
}
else {
return true;
}
}
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
 
include('includes/geen_script.php');
 
include('includes/recup_bericht.php');
?>

<table width="<?php print $_SESSION['storeWidthUser'];?>" height='100%' align="center" border="0" cellpadding="<?php print $cellpad;?>" cellspacing="0" class="TABLEBackgroundBoutiqueCentre">
<tr>
<td width="1" class="borderLeft"></td>
<td valign="top">

<table width="100%" height='100%' border="0" cellpadding="0" cellspacing="0" class="backGroundTop">
<tr height="<?php print $cellTop;?>" valign="top">
<?php
 
if(isset($logo) AND $logo!=="noPath") {
  
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
    
    print "<td valign='middle' align='center'>";
       if($urlLogo2!=="") print "<a href='http://www.".$urlLogo2."'>".detectIm($logo2,0,0)."</a>"; else print detectIm($logo2,0,0);
    print "</td>";
    }
}
?>
</tr>
<tr>
        <td colspan="3" height="32" valign="top">

             <table width="99%" align="center" border="0" cellspacing="0" cellpadding="5" class="TABLEMenuPathTopPageMenuTabOff">
              <tr height="32">
               <td>
               <b><img src="im/accueil.gif" align="TEXTTOP">&nbsp;<a href="cataloog.php" ><?php print maj(HOME);?></a> | <?php print MODELIVRI;?> |</b>
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
 
    display_payment_process(2,"");
?>
</td>
</tr>
</table>


<?php
if(isset($_SESSION['devisNumero']) AND $_SESSION['devisNumero']!=="") {
		$requestShipDevis = mysql_query("SELECT devis_shipping FROM devis WHERE devis_number='".$_SESSION['devisNumero']."'") or die (mysql_error());
		if(mysql_num_rows($requestShipDevis)>0) {
			$resultShipDevis = mysql_fetch_array($requestShipDevis);
			$_POST['shipping'] = $resultShipDevis['devis_shipping'];
		}
		else {
			print 'Mode de livraion non validé.';
			exit;
		}
}
$seuil = (isset($_SESSION['devisNumero']))? 0 : $minimumOrder;

if(!isset($_SESSION['list']) OR $_SESSION['list'] == "" OR empty($_SESSION['list']) OR $_SESSION['tot_art']=="0" OR $_SESSION['totalTTC'] < $seuil ) {
   print "<table border='0' width='350' align='center' cellspacing='0' cellpadding='0'>";
   print "<tr>";
   print "<td class='titre' align='center'>";
             if(isset($_SESSION['totalTTC']) AND $_SESSION['totalTTC'] < $minimumOrder) {
                print "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".COMMANDE_MINIMUM.": ".sprintf("%0.2f",$minimumOrder)." ".$symbolDevise."</p>";
             }
             else {
                print "<p class='styleAlert'><img src='im/note.gif' align='absmiddle'>&nbsp;".VOUS_N_AVEZ_PAS_D_ARTICLES_DANS_VOTRE_CADDIE."</p>";
             }
   print "</td>";
   print "</tr>";
   print "</table>";
}
else {
    
 
    $requestShipCountry = mysql_query("SELECT countries_id FROM countries WHERE countries_name = '".$_SESSION['clientCountry']."'");
    $oountryIdResult = mysql_fetch_array($requestShipCountry);
    $oountryId = $oountryIdResult['countries_id'];
 
	$requestpays = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes'") or die (mysql_error());
	$requestpaysNum = mysql_num_rows($requestpays);
	if($_poidZ>=999999999) {
		$requestWeight = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max='999999999'") or die (mysql_error());
		$requestWeightNum = mysql_num_rows($requestWeight);
	}
	else {
		$requestWeight = mysql_query("SELECT livraison_id FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max >= '".$_poidZ."'") or die (mysql_error());
		$requestWeightNum = mysql_num_rows($requestWeight);
	}
	$messageEr.= ($requestpaysNum==0)? AUCUNE_LIVRAISON_DANS_CE_PAYS."<br>" : "";
	$messageEr.= ($requestpaysNum > 0 AND $requestWeightNum==0)? POIDS_TOTAL_DEPASSE_NOS_CAPACITES : "";


 
	if($_poidZ>=999999999) {
		$requestShipSet = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max ='999999999' ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
	}
	else {
		$requestShipSet = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." FROM ship_mode WHERE livraison_country LIKE '%|".$oountryId."|%' AND livraison_active='yes' AND livraison_max >= '".$_poidZ."' ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
	}
	$requestShipSetNum = mysql_num_rows($requestShipSet);
	
    if($requestShipSetNum > 0) {

		if(!isset($_POST['shipping'])) {
			while($resultShipSet = mysql_fetch_array($requestShipSet)) {
		    	$_shipping[] = $resultShipSet['livraison_id'];
			}
			$_POST['shipping'] = $_shipping[0];
		}
 
	if($requestShipSetNum > 1) {
      print "<table border='0' width='500' align='center' cellspacing='0' cellpadding='5' class='TABLE1'><tr><td>";
      print "<p align='center'><b>".VEUILLEZ_CHOISIR_MODE_DE_LIVRAISON."</b></p>";
 
      $requestShipZ = mysql_query("SELECT livraison_id, livraison_nom_".$_SESSION['lang'].", livraison_image, livraison_note_".$_SESSION['lang']." 
                                    FROM ship_mode 
                                    WHERE livraison_country LIKE '%|".$oountryId."|%' 
                                    AND livraison_active='yes' 
                                    AND livraison_max >= '".$_poidZ."' 
                                    ORDER BY livraison_nom_".$_SESSION['lang']."") or die (mysql_error());
      print "<form method='POST' action='selecteer_verzending.php' name='formZ' onsubmit='return formShip()'>";
      print "<input type='hidden' name='actionLiv' value='liv'>";
      print "<table border='0' align='center' cellspacing='0' cellpadding='5'>";
      while($resultShip = mysql_fetch_array($requestShipZ)) {
        $tarifRequestZ = mysql_query("SELECT id FROM ship_price WHERE livraison_id = '".$resultShip['livraison_id']."'");
        if(mysql_num_rows($tarifRequestZ)>0) {
             print "<tr>";
             print "<td width='1'>";
             if(!isset($_POST['shipping'])) $_POST['shipping']=$resultShip['livraison_id'];
             $selLiv = (isset($_POST['shipping']) AND $_POST['shipping']==$resultShip['livraison_id'])? "checked" : "";
             $state = (isset($_SESSION['devisNumero']) AND $_SESSION['devisNumero']!=="")? "disabled" : "";
             print "<input type='radio' ".$selLiv." ".$state." name='shipping' value='".$resultShip['livraison_id']."' style='BACKGROUND:none; border:none' onclick='submit();'>";
             print "</td>";
             print "<td>";
             print $resultShip['livraison_nom_'.$_SESSION['lang']];
             print "</td>";
             print "<td align='left' width='120'>";
               if($resultShip['livraison_image']!=="") {
                        ## Resize image
                        $sizeImShip = @getimagesize($resultShip['livraison_image']);
                        if($sizeImShip) {
                            $widthShipIm = ($sizeImShip[0]>100)? "width='100'" : "";
                        }
                        else {
                            $widthShipIm = "";
                        }
                  print "<img src='".$resultShip['livraison_image']."' ".$widthShipIm." title='".$resultShip['livraison_nom_'.$_SESSION['lang']]."' alt='".$resultShip['livraison_nom_'.$_SESSION['lang']]."'>";
               }
               else {
                  print "&nbsp;";
               }
             print "</td>";
             print "<td align='left'>";
                $openLeg = "<a href='javascript:void(0);' onClick=\"window.open('leverings_wijze.php?id=".$resultShip['livraison_id']."','_blank','menubar=no,location=no,directories=no,status=no,copyhistory=no,height=300,width=400,toolbar=no,scrollbars=no,resizable=yes');\">";
                if($resultShip['livraison_note_'.$_SESSION['lang']]!=="") print $openLeg."<img src='im/info_plus.gif' border='0' title='".PLUS_INFOS."' align='absmiddle'></a>"; else print "&nbsp;";
                ## NO Javascript
                print "<noscript>";
                $openUseSearch22 = "<a href='#' class='tooltip'><b><span class='darkBackground' style='padding:2px;'>&nbsp;?&nbsp;</span></b><em style='width: 375px; left:30px'>".$resultShip['livraison_note_'.$_SESSION['lang']]."</em></a>";
                if($resultShip['livraison_note_'.$_SESSION['lang']]!=="") print "&nbsp;".$openUseSearch22; else print "&nbsp;";
                print "</noscript>";
             print "</td>";
             print "</tr>";
        }
      }
      print "</table>";
       
      print "<noscript>";
      print "<br><img src='im/zzz.gif' width='1' height='1'><br>";
      print "<div align='center'><input type='submit' value='".SELECTIONNER_CE_MODE_LIVRAISON."'></div>";
      print "</noscript>";
      print "</form>";
      print "</td></tr></table>";
      print "<br>";
    }

 
 
include('includes/plug.inc.php');
print invoice_display($_SESSION['clientCountry'],"TABLE1","",$_POST['shipping']);
      print "<form method='POST' action='selecteer_betaling.php'>";
      	print "<input type='hidden' name='shipping' value='".$_POST['shipping']."'>";
      	 
      	print "<p align='center'><input type='image' src='im/lang".$_SESSION['lang']."/payercommande.gif' value='".PAIEMENT_DE_CETTE_COMMANDE."' style='BACKGROUND:none; border:0px'></p>";
      print "</form>";
    }
    else {
    	print (isset($messageEr) AND $messageEr!=="")? "<p align='center'>".$messageEr."</p>" : "";
        print "<table border='0' cellpadding='0' cellspacing='0' align='center'><tr><td class='styleAlert' style='padding:10px'><img src='im/note.gif' align='absmiddle'>&nbsp;".PARAMS_LIVRAISON_NON_DEFINIS."</td></tr></table>";
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
<tr>
<td colspan="2" valign="bottom">

<table width="99%" align="center" border="0" cellspacing="0" cellpadding="2">
	<tr>
	<td><br><br></td>
	</tr>
</table>

</td>
</tr>
</table>

</td>
<td width="1" class="borderLeft"></td>
</tr></table>

</body>
</html>
